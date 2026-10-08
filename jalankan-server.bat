@echo off
title Server Lokal FastTender Pre-Order
color 0b
echo ===================================================
echo     MEMULAI SERVER LOKAL FASTTENDER (PHP NATIVE)
echo ===================================================
echo.

set PHP_BIN=""

where php >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    set PHP_BIN=php
    goto START_SERVER
)

if exist "D:\KULIAH\SEMESTER 3\DESAIN DAN PEMROGRAMAN WEB\php_server\php.exe" (
    set PHP_BIN="D:\KULIAH\SEMESTER 3\DESAIN DAN PEMROGRAMAN WEB\php_server\php.exe"
    goto START_SERVER
)

if exist "C:\xampp\php\php.exe" (
    set PHP_BIN="C:\xampp\php\php.exe"
    goto START_SERVER
)

if exist "D:\xampp\php\php.exe" (
    set PHP_BIN="D:\xampp\php\php.exe"
    goto START_SERVER
)

echo [ERROR] PHP tidak ditemukan di sistem Anda!
echo Pastikan PHP sudah terinstal atau gunakan XAMPP.
pause
exit /b 1

:START_SERVER
echo [OK] PHP Ditemukan: %PHP_BIN%
echo [INFO] Membuka browser: http://localhost:8000
echo [INFO] Menjalankan server pada http://localhost:8000 ...
echo [TIPS] Tekan CTRL + C untuk menghentikan server.
echo ===================================================
echo.

start http://localhost:8000

%PHP_BIN% -S localhost:8000
