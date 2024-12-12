<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: php/admin/login.php');
    exit;
}

$config_path = '../components/config.php';
if (!file_exists($config_path)) {
    die("Config file not found at: $config_path");
}
require_once $config_path;

// Connect to the database
try {
    $stmt = $pdo->prepare('INSERT INTO Hotel (hotelName, hotelAddress, city, postcode, hotel_telNo) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$_POST['hotelName'], $_POST['hotelAddress'], $_POST['city'], $_POST['postcode'], $_POST['hotel_telNo']]);

    header('Location: viewHotels.php');
    exit;
} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
}
?>