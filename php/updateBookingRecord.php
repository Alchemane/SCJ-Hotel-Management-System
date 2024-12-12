<?php // ensure page is protected by login session from admin
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: admin/login.php');
    exit;
}

require_once __DIR__ . '/../components/config.php';
include '../components/NavBar.php'; // add navbar to this page

// Database Connection
try {
    

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $bookingID = $_POST['bookingID'];
        $guestID = $_POST['guestID'];
        $roomID = $_POST['roomID'];
        $checkInDate = $_POST['checkInDate'];
        $checkOutDate = $_POST['checkOutDate'];
        $bookingStatus = $_POST['bookingStatus'];

        // Update booking in the database
        $stmt = $pdo->prepare('UPDATE Booking SET guestID = ?, roomID = ?, checkInDate = ?, checkOutDate = ?, bookingStatus = ? WHERE bookingID = ?');
        $stmt->execute([$guestID, $roomID, $checkInDate, $checkOutDate, $bookingStatus, $bookingID]);

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