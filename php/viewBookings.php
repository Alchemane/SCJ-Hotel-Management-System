<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: php/admin/login.php');
    exit;
}

include '../components/NavBar.php';

$config_path = '../components/config.php';
if (!file_exists($config_path)) {
    die("Config file not found at: $config_path");
}
require_once $config_path;

// Connect to the database
try {
    

    $stmt = $pdo->query("
        SELECT Booking.bookingID, 
            Guest.firstName || ' ' || Guest.lastName AS guestName,
            Room.roomNumber, 
            Booking.checkInDate, 
            Booking.checkOutDate, 
            Booking.bookingStatus
        FROM Booking
        INNER JOIN Guest ON Booking.guestID = Guest.guestID
        INNER JOIN Room ON Booking.roomID = Room.roomID
        ORDER BY Booking.checkInDate
    ");
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>View Bookings</title>
</head>
<body>
    <div class="form-container">
        <h2 class="center-text">Bookings List</h2>
        <div class="center-text" style="margin-bottom: 20px;">
            <a href="createBookingPage.php" class="form-button half-width-button">Add New Booking</a>
        </div>
        <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Guest</th>
                <th>Room</th>
                <th>Check-In Date</th>
                <th>Check-Out Date</th>
                <th>Booking Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= htmlspecialchars($booking['bookingID']) ?></td>
                    <td><?= htmlspecialchars($booking['guestName']) ?></td>
                    <td><?= htmlspecialchars($booking['roomNumber']) ?></td>
                    <td><?= htmlspecialchars($booking['checkInDate']) ?></td>
                    <td><?= htmlspecialchars($booking['checkOutDate']) ?></td>
                    <td><?= htmlspecialchars($booking['bookingStatus']) ?></td>
                    <td>
                        <a href="updateBookingPage.php?bookingID=<?= $booking['bookingID'] ?>">Update</a>
                        <a href="deleteBooking.php?bookingID=<?= $booking['bookingID'] ?>" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>
</body>
</html>
