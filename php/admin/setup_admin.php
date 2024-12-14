<?php
// this script is run once, and called directly to add admins to the database.
$config_path = '../../components/config.php';
if (!file_exists($config_path)) {
    die("Config file not found at: $config_path");
}
require_once $config_path;

$pdo = new PDO('sqlite:' . DB_PATH);
// Check if the `admin_users` table exists
$stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='admin_users'");
$tableExists = $stmt->fetch();

if (!$tableExists) {
    die("Error: The table `admin_users` does not exist in the database.");
}

// Admin credentials to insert into the database
$username = 'admin';
$password = ''; // password has been cleared as has been hashed and inserted into the database

// Check if admin user already exists
$stmt = $pdo->prepare("SELECT COUNT(*) FROM admin_users WHERE username = ?");
$stmt->execute([$username]);
$userExists = $stmt->fetchColumn();

if ($userExists) {
    die("Error: Admin user already exists.");
}

// Hash the password using the PASSWORD_BCRYPT algorithm
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare('INSERT INTO admin_users (username, password) VALUES (?, ?)');
    $stmt->execute([$username, $hashedPassword]);
    echo "Admin user added successfully!";
} catch (PDOException $e) {
    error_log("Failed to insert admin user: " . $e->getMessage(), 3, __DIR__ . '/../logs/error_log.txt');
    die("An error occurred while adding the admin user. Please contact support.");
}
?>