<?php
// ensure page is protected by login session from admin
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
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $hotelID = $_POST['hotelID'] ?? null; // needed for cross referencing available rooms to hotels

        $guestID = $_POST['guestID'] ?? null;
        $roomID = $_POST['roomID'] ?? null;
        $checkInDate = $_POST['checkInDate'] ?? null;
        $checkOutDate = $_POST['checkOutDate'] ?? null;
        $bookingStatus = $_POST['bookingStatus'] ?? 'confirmed';

        // Basic validation
        if (!$hotelID || !$guestID || !$roomID || !$checkInDate || !$checkOutDate) {
            echo "All fields are required.";
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM Room WHERE roomID = :roomID AND hotelID = :hotelID");
        $stmt->execute([':roomID' => $roomID, ':hotelID' => $hotelID]);

        // Validate booking status
        $allowedStatuses = ['confirmed', 'pending', 'canceled'];
        if (!in_array($bookingStatus, $allowedStatuses)) {
            echo "Invalid booking status.";
            exit;
        }

        // Check for overlapping bookings
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM Booking
            WHERE roomID = :roomID
            AND (
                (checkInDate BETWEEN :checkIn AND :checkOut)
                OR (checkOutDate BETWEEN :checkIn AND :checkOut)
                OR (:checkIn BETWEEN checkInDate AND checkOutDate)
                OR (:checkOut BETWEEN checkInDate AND checkOutDate)
            )
        ");
        $stmt->execute([
            ':roomID' => $roomID,
            ':checkIn' => $checkInDate,
            ':checkOut' => $checkOutDate,
        ]);
        
        if ($stmt->fetchColumn() > 0) {
            $errorMessage = "The selected room is already booked for the specified dates.";
            header("Location: createBookingPage.php?error=" . urlencode($errorMessage));
            exit;
        }

        if (strtotime($checkOutDate) <= strtotime($checkInDate)) {
            $errorMessage = "The check-out date must be after the check-in date.";
            header("Location: createBookingPage.php?error=" . urlencode($errorMessage));
            exit;
        }
        
        if (strtotime($checkInDate) < strtotime(date('Y-m-d'))) {
            $errorMessage = "The check-in date cannot be in the past.";
            header("Location: createBookingPage.php?error=" . urlencode($errorMessage));
            exit;
        }

        // Insert the booking
        $stmt = $pdo->prepare("
            INSERT INTO Booking (guestID, roomID, checkInDate, checkOutDate, bookingStatus)
            VALUES (:guestID, :roomID, :checkIn, :checkOut, :bookingStatus)
        ");
        $stmt->execute([
            ':guestID' => $guestID,
            ':roomID' => $roomID,
            ':checkIn' => $checkInDate,
            ':checkOut' => $checkOutDate,
            ':bookingStatus' => $bookingStatus,
        ]);
        // update room status to booked
        $stmt = $pdo->prepare("
        UPDATE Room
        SET status = 'booked'
        WHERE roomID = :roomID
        ");
        $stmt->execute([':roomID' => $roomID]);

        header('Location: viewBookings.php?message=Booking Added Successfully');
    } else {
        echo "Invalid request.";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>