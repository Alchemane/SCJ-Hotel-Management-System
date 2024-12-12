<?php // ensure page is protected by login session from admin
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: admin/login.php');
    exit;
}

include '../components/NavBar.php'; // add navbar to this page

require_once __DIR__ . '/../components/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $hotelID = $_POST['hotelID'] ?? null;
        $roomNumber = $_POST['roomNumber'] ?? null;
        $roomTypeID = $_POST['roomTypeID'] ?? null;
        $status = strtolower($_POST['status'] ?? 'available');
        $pricePerNight = $_POST['pricePerNight'] ?? null;

        $allowedStatuses = ['maintenance', 'booked', 'available'];
        if (!in_array($status, $allowedStatuses)) {
            die("Invalid room status.");
        }

        if (empty($hotelID) || empty($roomNumber) || empty($roomTypeID) || empty($status) || empty($pricePerNight)) {
            die("Error: All fields are required.");
        }

        $stmt = $pdo->prepare("INSERT INTO Room (hotelID, roomNumber, roomTypeID, status, pricePerNight) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$hotelID, $roomNumber, $roomTypeID, $status, $pricePerNight]);

        echo "Room added successfully!";
        header("Location: viewRooms.php");
        exit;
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    die("Invalid request method.");
}
?>