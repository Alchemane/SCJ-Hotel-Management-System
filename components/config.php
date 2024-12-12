<?php
define('DB_PATH', realpath(__DIR__ . '/../database/hotel_management.db'));

try {
    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Enable foreign key constraints
    $pdo->exec("PRAGMA foreign_keys = ON;");
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>