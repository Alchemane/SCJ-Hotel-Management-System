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
    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $hotelID = $_POST['hotelID'];
        $roomNumber = $_POST['roomNumber'];
        $roomTypeID = $_POST['roomTypeID'];
        $status = $_POST['status'];

        // Insert new room into the database
        $stmt = $pdo->prepare('INSERT INTO Room (hotelID, roomNumber, roomTypeID, status) VALUES (?, ?, ?, ?)');
        $stmt->execute([$hotelID, $roomNumber, $roomTypeID, $status]);

        // Redirect to the viewRooms.php page
        header('Location: viewRooms.php');
        exit;
    } else {
        echo "Invalid request.";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>