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
    

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $roomTypeID = $_POST['roomTypeID'];
        $roomTypeName = $_POST['roomTypeName'];
        $description = $_POST['description'];

        // Update room type in the database
        $stmt = $pdo->prepare('UPDATE RoomType SET roomTypeName = ?, description = ? WHERE roomTypeID = ?');
        $stmt->execute([$roomTypeName, $description, $roomTypeID]);

        // Redirect to the viewRoomTypes.php page
        header('Location: viewRoomTypes.php');
        exit;
    } else {
        echo "Invalid request.";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>