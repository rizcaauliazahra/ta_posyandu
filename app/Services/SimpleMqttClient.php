<?php

namespace App\Services;

use RuntimeException;

class SimpleMqttClient
{
    private $socket = null;
    private int $lastPingAt = 0;

    // Simpan parameter koneksi untuk reconnect
    private string $host = '';
    private int $port = 1883;
    private string $clientId = '';
    private ?string $username = null;
    private ?string $password = null;
    private int $keepAlive = 30;

    public function connect(
        string $host,
        int $port,
        string $clientId,
        ?string $username = null,
        ?string $password = null,
        int $keepAlive = 30
    ): void {
        // Simpan untuk reconnect
        $this->host = $host;
        $this->port = $port;
        $this->clientId = $clientId;
        $this->username = $username;
        $this->password = $password;
        $this->keepAlive = $keepAlive;

        $this->socket = @stream_socket_client("tcp://{$host}:{$port}", $errno, $errstr, 10);

        if (! $this->socket) {
            throw new RuntimeException("Tidak bisa konek MQTT broker {$host}:{$port}. {$errstr}");
        }

        stream_set_timeout($this->socket, 5);
        $this->lastPingAt = time();

        $flags = 0x02;
        $payload = $this->string($clientId);

        if ($username !== null && $username !== '') {
            $flags |= 0x80;
            if ($password !== null && $password !== '') {
                $flags |= 0x40;
            }
        }

        $variableHeader = $this->string('MQTT').chr(4).chr($flags).pack('n', $keepAlive);

        if ($username !== null && $username !== '') {
            $payload .= $this->string($username);
            if ($password !== null && $password !== '') {
                $payload .= $this->string($password);
            }
        }

        $this->writeRaw(chr(0x10).$this->remainingLength(strlen($variableHeader.$payload)).$variableHeader.$payload);

        $packet = $this->readPacket();
        if ($packet['type'] !== 0x20 || strlen($packet['payload']) < 2 || ord($packet['payload'][1]) !== 0) {
            throw new RuntimeException('MQTT CONNACK gagal diterima.');
        }
    }

    /**
     * Reconnect menggunakan parameter yang sudah disimpan.
     */
    public function reconnect(): void
    {
        $this->closeSocket();
        $this->connect(
            $this->host,
            $this->port,
            $this->clientId . '-' . time(),
            $this->username,
            $this->password,
            $this->keepAlive
        );
    }

    /**
     * Cek apakah socket masih terhubung.
     */
    public function isConnected(): bool
    {
        if ($this->socket === null) {
            return false;
        }

        if (feof($this->socket)) {
            return false;
        }

        return true;
    }

    public function subscribe(string $topic): void
    {
        $packetId = random_int(1, 65535);
        $payload = pack('n', $packetId).$this->string($topic).chr(0);
        $this->writeRaw(chr(0x82).$this->remainingLength(strlen($payload)).$payload);

        $packet = $this->readPacket();
        if ($packet['type'] !== 0x90) {
            throw new RuntimeException('MQTT SUBACK gagal diterima.');
        }
    }

    public function publish(string $topic, string $message): void
    {
        $payload = $this->string($topic).$message;
        $this->writeRaw(chr(0x30).$this->remainingLength(strlen($payload)).$payload);
    }

    /**
     * Publish dengan proteksi — return false jika gagal, tidak throw exception.
     */
    public function safePublish(string $topic, string $message): bool
    {
        try {
            if (! $this->isConnected()) {
                return false;
            }
            $this->publish($topic, $message);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function readMessage(): ?array
    {
        $packet = $this->readPacket();
        if ($packet === null) {
            $this->pingIfNeeded();
            return null;
        }

        if (($packet['type'] & 0xF0) !== 0x30) {
            return null;
        }

        $payload = $packet['payload'];
        $topicLength = unpack('n', substr($payload, 0, 2))[1];
        $topic = substr($payload, 2, $topicLength);
        $message = substr($payload, 2 + $topicLength);

        return ['topic' => $topic, 'message' => $message];
    }

    public function disconnect(): void
    {
        if ($this->socket) {
            @fwrite($this->socket, chr(0xE0).chr(0));
            $this->closeSocket();
        }
    }

    private function closeSocket(): void
    {
        if ($this->socket) {
            @fclose($this->socket);
            $this->socket = null;
        }
    }

    private function readPacket(): ?array
    {
        $type = $this->readBytes(1, true);
        if ($type === null) {
            return null;
        }

        $multiplier = 1;
        $value = 0;

        do {
            $encodedByte = ord($this->readBytes(1));
            $value += ($encodedByte & 127) * $multiplier;
            $multiplier *= 128;
        } while (($encodedByte & 128) !== 0);

        return [
            'type' => ord($type),
            'payload' => $value > 0 ? $this->readBytes($value) : '',
        ];
    }

    private function readBytes(int $length, bool $allowTimeout = false): ?string
    {
        $data = '';
        while (strlen($data) < $length) {
            $chunk = @fread($this->socket, $length - strlen($data));
            if ($chunk === false || $chunk === '') {
                $meta = stream_get_meta_data($this->socket);
                if ($allowTimeout && ($meta['timed_out'] ?? false)) {
                    return null;
                }
                throw new RuntimeException('Koneksi MQTT terputus.');
            }
            $data .= $chunk;
        }

        return $data;
    }

    /**
     * Write data ke socket. Throw exception jika gagal.
     */
    private function writeRaw(string $data): void
    {
        $written = @fwrite($this->socket, $data);

        if ($written === false || $written < strlen($data)) {
            throw new RuntimeException('Koneksi MQTT publisher terputus saat mengirim data.');
        }
    }

    private function pingIfNeeded(): void
    {
        if (time() - $this->lastPingAt < 20) {
            return;
        }

        try {
            $this->writeRaw(chr(0xC0).chr(0));
            $this->lastPingAt = time();
        } catch (\Throwable $e) {
            // Ping gagal, biarkan reconnect logic yang handle
        }
    }

    private function string(string $value): string
    {
        return pack('n', strlen($value)).$value;
    }

    private function remainingLength(int $length): string
    {
        $encoded = '';
        do {
            $digit = $length % 128;
            $length = intdiv($length, 128);
            if ($length > 0) {
                $digit |= 128;
            }
            $encoded .= chr($digit);
        } while ($length > 0);

        return $encoded;
    }
}
