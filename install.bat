@echo off
title GIS Fasilitas Umum - Automatic Setup
cls
echo ========================================================
echo   GIS Fasilitas Umum Tahunan - Instalasi Otomatis
echo ========================================================
echo.

:: Check PHP
php -v >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] PHP tidak ditemukan. Pastikan PHP sudah terinstall dan masuk ke PATH.
    pause
    exit /b
)

:: Check Composer
call composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Composer tidak ditemukan. Install Composer terlebih dahulu.
    pause
    exit /b
)

echo [1/6] Mengcopy konfigurasi environment...
if not exist .env (
    copy .env.example .env
    echo     .env berhasil dibuat.
) else (
    echo     .env sudah ada, melewati langkah ini.
)
echo.

echo [2/6] Menginstall library PHP (Composer)...
call composer install
echo.

echo [3/6] Generate Application Key...
call php artisan key:generate
echo.

echo [4/6] Konfigurasi Database...
echo     Pastikan Anda sudah membuat database kosong (contoh: gis_tahunan) di MySQL.
echo     Jika belum, buka phpMyAdmin atau HeidiSQL dan buat database sekarang.
echo.
set /p DBNAME="Masukkan nama database yang sudah dibuat (default: gis_tahunan): "
if "%DBNAME%"=="" set DBNAME=gis_tahunan

:: Update .env database name using PowerShell replacement because batch is hard
powershell -Command "(Get-Content .env) -replace 'DB_DATABASE=.*', 'DB_DATABASE=%DBNAME%' | Set-Content .env"
echo     Database diset ke: %DBNAME%
echo.

echo [5/6] Migrasi dan Seeding Database...
echo     Melakukan reset database dan mengisi data awal...
call php artisan migrate:fresh --seed
if %errorlevel% neq 0 (
    echo.
    echo [WARNING] Migrasi gagal. Pastikan database '%DBNAME%' sudah dibuat dan config DB_USERNAME/PASSWORD di .env benar.
    echo Silakan edit file .env secara manual lalu jalankan 'php artisan migrate:fresh --seed' nanti.
) else (
    echo     Database berhasil disetup!
)
echo.

echo [6/6] Selesai! Menjalankan aplikasi...
echo Aplikasi akan berjalan di http://localhost:8000
echo Login Admin Default: admin@admin.com / admin123
echo.
call php artisan serve
pause
