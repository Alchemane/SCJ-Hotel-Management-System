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
    if (isset($_GET['hotelID'])) {
        $guestID = $_GET['hotelID'];
    
        // Delete the guest from the database
        $stmt = $pdo->prepare("DELETE FROM Hotel WHERE hotelID = :hotelID");
        $stmt->execute([':hotelID' => $hotelID]);
    
        header('Location: viewHotels.php?message=Hotel Deleted Successfully');
    } else {
        echo "No Hotel ID provided.";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>