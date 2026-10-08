<?php
/**
 * Fastender Pre-Order Platform
 * File Konfigurasi Database (PDO)
 * config/database.php
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'fastender_db');
define('DB_PORT', 3306);

function getDbConnection() {
    static $pdo = null;
    
    if ($pdo !== null) {
        return $pdo;
    }

    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        // Jika database belum diimport atau MySQL belum aktif,
        // kembalikan null agar sistem fallback ke data JSON/file storage tanpa crash 500 error
        return null;
    }
}
