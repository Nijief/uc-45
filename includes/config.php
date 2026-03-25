<?php
session_start();

// Настройки базы данных
define('DB_HOST', 'localhost');
define('DB_NAME', 'uc45_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Настройки сайта - ИЗМЕНИ ЭТУ СТРОКУ для локальной разработки
// Для локальной разработки:
define('SITE_URL', 'http://localhost/Test');  // Если сайт в корне htdocs
// ИЛИ (если папка называется, например, uc45):
// define('SITE_URL', 'http://localhost/uc45');

define('ADMIN_EMAIL', 'nik.chubeyko@bk.ru');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>