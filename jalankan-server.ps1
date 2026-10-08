# FastTender - Script Menjalankan Server Lokal PHP Native
Write-Host "===================================================" -ForegroundColor Cyan
Write-Host "    MEMULAI SERVER LOKAL FASTTENDER (PHP NATIVE)" -ForegroundColor Cyan
Write-Host "===================================================" -ForegroundColor Cyan

$phpPath = $null

if (Get-Command php -ErrorAction SilentlyContinue) {
    $phpPath = "php"
} elseif (Test-Path "D:\KULIAH\SEMESTER 3\DESAIN DAN PEMROGRAMAN WEB\php_server\php.exe") {
    $phpPath = "D:\KULIAH\SEMESTER 3\DESAIN DAN PEMROGRAMAN WEB\php_server\php.exe"
} elseif (Test-Path "C:\xampp\php\php.exe") {
    $phpPath = "C:\xampp\php\php.exe"
} elseif (Test-Path "D:\xampp\php\php.exe") {
    $phpPath = "D:\xampp\php\php.exe"
}

if (-not $phpPath) {
    Write-Host "[ERROR] PHP tidak ditemukan di sistem Anda!" -ForegroundColor Red
    Write-Host "Pastikan PHP terpasang atau gunakan XAMPP." -ForegroundColor Yellow
    exit 1
}

Write-Host "[OK] Menggunakan PHP: $phpPath" -ForegroundColor Green
Write-Host "[INFO] Membuka URL: http://localhost:8000" -ForegroundColor Cyan
Write-Host "[TIPS] Tekan CTRL + C di terminal ini untuk mematikan server." -ForegroundColor Yellow
Write-Host "===================================================" -ForegroundColor Cyan

Start-Process "http://localhost:8000"
& $phpPath -S localhost:8000
