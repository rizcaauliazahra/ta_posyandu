<?php

namespace App\Console\Commands;

use App\Services\SimpleMqttClient;
use Illuminate\Console\Command;
use Throwable;

class SerialMqttBridgeCommand extends Command
{
    protected $signature = 'serial:mqtt {port=COM5} {--baud=115200}';

    protected $description = 'Baca Serial Monitor ESP32 lalu publish data berat/tinggi ke Mosquitto MQTT.';

    private ?SimpleMqttClient $mqtt = null;

    public function handle(): int
    {
        $port = strtoupper($this->argument('port'));
        $baud = (int) $this->option('baud');
        $topic = config('services.mqtt.topic');

        $this->info("Serial bridge {$port} {$baud} baud -> MQTT {$topic}");
        $this->warn('Tutup Serial Monitor Arduino IDE dulu, karena COM port tidak bisa dipakai bersamaan.');

        $this->info('Bridge aktif. Menunggu baris RAW dari ESP32...');

        // Loop utama — otomatis reconnect serial jika ESP32 restart
        while (true) {
            try {
                $this->runBridge($port, $baud, $topic);
            } catch (Throwable $e) {
                $this->error('Bridge error: ' . $e->getMessage());
            }

            // ESP32 kemungkinan brownout/restart, tunggu lalu coba lagi
            $this->warn('Serial terputus. Menunggu 3 detik lalu reconnect...');
            sleep(3);
        }

        return self::SUCCESS;
    }

    private function runBridge(string $port, int $baud, string $topic): void
    {
        // Konfigurasi COM port
        if (PHP_OS_FAMILY === 'Windows') {
            exec("mode {$port}: BAUD={$baud} PARITY=N DATA=8 STOP=1");
        }

        $serialPath = PHP_OS_FAMILY === 'Windows' ? '\\\\.\\'.$port : $port;
        $serial = @fopen($serialPath, 'r');

        if (! $serial) {
            $this->error("Tidak bisa membuka {$port}. Pastikan port benar dan Serial Monitor Arduino sudah ditutup.");
            sleep(5);
            return;
        }

        stream_set_blocking($serial, true);
        stream_set_timeout($serial, 10);

        $this->info("Serial {$port} terbuka. Membaca data...");

        // Koneksi MQTT persisten — satu koneksi untuk semua publish
        $this->connectMqtt();

        while (true) {
            $line = @fgets($serial);

            // Serial timeout atau terputus
            if ($line === false) {
                $meta = @stream_get_meta_data($serial);

                // Timeout biasa (ESP32 belum kirim data), lanjut tunggu
                if ($meta && ($meta['timed_out'] ?? false)) {
                    // Kirim MQTT ping agar koneksi tidak mati
                    $this->ensureMqttAlive();
                    continue;
                }

                // Serial benar-benar terputus (brownout / kabel lepas)
                $this->warn('Serial stream terputus.');
                @fclose($serial);
                return;
            }

            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $this->line($line);

            // Parse baris RAW dari ESP32
            if (! preg_match('/Berat\s*:\s*([0-9]+(?:\.[0-9]+)?)\s*kg.*Tinggi\s*:\s*([0-9]+(?:\.[0-9]+)?)\s*cm/i', $line, $matches)) {
                continue;
            }

            $weight = (float) $matches[1];
            $height = (float) $matches[2];

            if ($weight <= 0 || $height <= 0) {
                continue;
            }

            $payload = json_encode([
                'child_id' => 1,
                'weight' => $weight,
                'height' => $height,
                'measurement_date' => now()->toDateString(),
                'measurement_time' => now()->format('H:i:s'),
            ]);

            // Publish via koneksi persisten
            $this->publishWithRetry($topic, $payload);
        }
    }

    /**
     * Buat koneksi MQTT baru (atau reconnect).
     */
    private function connectMqtt(): void
    {
        try {
            if ($this->mqtt !== null) {
                $this->mqtt->disconnect();
            }
        } catch (Throwable $e) {
            // Abaikan error saat disconnect
        }

        $this->mqtt = new SimpleMqttClient();

        try {
            $this->mqtt->connect(
                config('services.mqtt.host'),
                config('services.mqtt.port'),
                'serial-bridge-' . getmypid() . '-' . time(),
                config('services.mqtt.username'),
                config('services.mqtt.password'),
                30,
            );
            $this->info('MQTT terhubung ke ' . config('services.mqtt.host'));
        } catch (Throwable $e) {
            $this->error('MQTT connect gagal: ' . $e->getMessage());
            $this->mqtt = null;
        }
    }

    /**
     * Pastikan MQTT masih hidup, reconnect jika perlu.
     */
    private function ensureMqttAlive(): void
    {
        if ($this->mqtt === null || ! $this->mqtt->isConnected()) {
            $this->warn('MQTT tidak aktif, mencoba reconnect...');
            $this->connectMqtt();
        }
    }

    /**
     * Publish data ke MQTT, reconnect otomatis jika gagal.
     */
    private function publishWithRetry(string $topic, string $payload): void
    {
        // Pastikan koneksi MQTT aktif
        $this->ensureMqttAlive();

        if ($this->mqtt === null) {
            $this->error("Publish MQTT gagal: tidak ada koneksi. Data: {$payload}");
            return;
        }

        // Coba publish
        if ($this->mqtt->safePublish($topic, $payload)) {
            $this->info("Publish MQTT: {$payload}");
            return;
        }

        // Gagal, coba reconnect sekali lagi
        $this->warn('Publish gagal, reconnect MQTT...');
        $this->connectMqtt();

        if ($this->mqtt === null) {
            $this->error("Publish MQTT gagal setelah reconnect. Data: {$payload}");
            return;
        }

        if ($this->mqtt->safePublish($topic, $payload)) {
            $this->info("Publish MQTT (retry): {$payload}");
        } else {
            $this->error("Publish MQTT tetap gagal. Data: {$payload}");
        }
    }
}
