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
        $guestID = $_POST['guestID'];
        $roomID = $_POST['roomID'];
        $checkInDate = $_POST['checkInDate'];
        $checkOutDate = $_POST['checkOutDate'];
        $bookingStatus = $_POST['bookingStatus'];

        // Insert new booking into the database
        $stmt = $pdo->prepare('INSERT INTO Booking (guestID, roomID, checkInDate, checkOutDate, bookingStatus) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$guestID, $roomID, $checkInDate, $checkOutDate, $bookingStatus]);

        // Redirect to the viewBookings.php page
        header('Location: viewBookings.php');
        exit;
    } else {
        echo "Invalid request.";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>