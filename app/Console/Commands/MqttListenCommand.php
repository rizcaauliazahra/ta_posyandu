<?php

namespace App\Console\Commands;

use App\Models\Child;
use App\Repositories\MeasurementRepository;
use App\Services\SimpleMqttClient;
use Illuminate\Console\Command;
use Throwable;

class MqttListenCommand extends Command
{
    protected $signature = 'mqtt:listen {--once : Berhenti setelah menerima satu pesan} {--max-seconds=0 : Berhenti otomatis setelah beberapa detik, 0 berarti terus berjalan}';

    protected $description = 'Subscribe data measurement ESP32 dari Mosquitto MQTT dan simpan ke database.';

    public function handle(MeasurementRepository $repository): int
    {
        $host = config('services.mqtt.host');
        $port = config('services.mqtt.port');
        $topic = config('services.mqtt.topic');
        $startedAt = time();
        $maxSeconds = (int) $this->option('max-seconds');

        $this->info("Menghubungkan ke MQTT {$host}:{$port}, topic {$topic}");

        // Loop utama dengan auto-reconnect
        while (true) {
            // Cek max-seconds
            if ($maxSeconds > 0 && time() - $startedAt >= $maxSeconds) {
                $this->info('Subscriber berhenti karena max-seconds tercapai.');
                return self::SUCCESS;
            }

            $mqtt = null;

            try {
                $mqtt = new SimpleMqttClient();
                $mqtt->connect(
                    $host,
                    $port,
                    config('services.mqtt.client_id') . '-' . getmypid() . '-' . time(),
                    config('services.mqtt.username'),
                    config('services.mqtt.password'),
                );
                $mqtt->subscribe($topic);
                $this->info('Subscriber aktif. Menunggu data ESP32...');
                $this->warn('Kalau berhenti di sini, itu normal: artinya Laravel sudah tersambung dan sedang menunggu publish MQTT.');

                // Inner loop: baca pesan terus sampai koneksi putus
                while (true) {
                    // Cek max-seconds
                    if ($maxSeconds > 0 && time() - $startedAt >= $maxSeconds) {
                        $mqtt->disconnect();
                        $this->info('Subscriber berhenti karena max-seconds tercapai.');
                        return self::SUCCESS;
                    }

                    $message = $mqtt->readMessage();
                    if (! $message) {
                        continue;
                    }

                    try {
                        $payload = json_decode($message['message'], true, flags: JSON_THROW_ON_ERROR);

                        $child = Child::find($payload['child_id'] ?? null) ?? Child::query()->oldest()->firstOrFail();
                        $measurement = $repository->createForChild($child, [
                            'weight' => $payload['weight'] ?? null,
                            'height' => $payload['height'] ?? null,
                            'measurement_date' => $payload['measurement_date'] ?? now()->toDateString(),
                            'measurement_time' => $payload['measurement_time'] ?? now()->format('H:i:s'),
                        ]);

                        $this->line("Tersimpan: {$child->name} {$measurement->weight}kg {$measurement->height}cm {$measurement->measurement_date->format('Y-m-d')}");

                        if ($this->option('once')) {
                            $mqtt->disconnect();
                            return self::SUCCESS;
                        }
                    } catch (Throwable $exception) {
                        $this->error('Error proses pesan: ' . $exception->getMessage());
                        if ($this->option('once')) {
                            return self::FAILURE;
                        }
                    }
                }
            } catch (Throwable $exception) {
                $this->error('Koneksi MQTT terputus: ' . $exception->getMessage());

                if ($this->option('once')) {
                    return self::FAILURE;
                }

                // Coba disconnect bersih
                if ($mqtt !== null) {
                    try {
                        $mqtt->disconnect();
                    } catch (Throwable $e) {
                        // Abaikan
                    }
                }

                // Tunggu sebelum reconnect
                $this->warn('Mencoba reconnect dalam 5 detik...');
                sleep(5);
            }
        }
    }
}
