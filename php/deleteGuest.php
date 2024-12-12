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
    

    if (isset($_GET['guestID'])) {
        $guestID = $_GET['guestID'];
    
        // Delete the guest from the database
        $stmt = $pdo->prepare("DELETE FROM Guest WHERE guestID = :guestID");
        $stmt->execute([':guestID' => $guestID]);
    
        header('Location: viewGuests.php?message=Guest Deleted Successfully');
    } else {
        echo "No guest ID provided.";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>