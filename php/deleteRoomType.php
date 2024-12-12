<?php // ensure page is protected by login session from admin
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: admin/login.php');
    exit;
}

include '../components/NavBar.php'; // add navbar to this page

// Database Connection
$config_path = '../components/config.php';
if (!file_exists($config_path)) {
    die("Config file not found at: $config_path");
}
require_once $config_path;

// Connect to the database
try {
    

    if (isset($_GET['roomTypeID'])) {
        $roomTypeID = $_GET['roomTypeID'];

        // Delete room type from the database
        $stmt = $pdo->prepare('DELETE FROM RoomType WHERE roomTypeID = ?');
        $stmt->execute([$roomTypeID]);

        // Redirect to the viewRoomTypes.php page
        header('Location: viewRoomTypes.php');
        exit;
    } else {
        echo "No room type ID provided.";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>