@echo off
echo ========================================================
echo Menjalankan Server Laravel untuk diakses oleh ESP32...
echo Pastikan ESP32 terhubung ke WiFi yang sama dengan laptop.
echo IP Komputer Anda saat ini: 10.60.216.108
echo ========================================================
php artisan serve --host=0.0.0.0 --port=8000
pause
