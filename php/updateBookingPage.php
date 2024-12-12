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
    

    // Get booking details if an ID is provided
    if (isset($_GET['bookingID'])) {
        $bookingID = $_GET['bookingID'];
        $stmt = $pdo->prepare('SELECT * FROM Booking WHERE bookingID = ?');
        $stmt->execute([$bookingID]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            echo "Booking not found.";
            exit;
        }
    } else {
        echo "No booking ID provided.";
        exit;
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Update Booking</title>
</head>
<body>
    <div class="form-container">
        <h2 class="center-text">Update Booking Information</h2>
        <form action="updateBookingRecord.php" method="post">
            <input type="hidden" name="bookingID" value="<?= htmlspecialchars($booking['bookingID']) ?>">

            <label for="guestID">Guest ID:</label>
            <input type="number" name="guestID" id="guestID" value="<?= htmlspecialchars($booking['guestID']) ?>" required>

            <label for="roomID">Room ID:</label>
            <input type="number" name="roomID" id="roomID" value="<?= htmlspecialchars($booking['roomID']) ?>" required>

            <label for="checkInDate">Check-In Date:</label>
            <input type="date" name="checkInDate" id="checkInDate" value="<?= htmlspecialchars($booking['checkInDate']) ?>" required>

            <label for="checkOutDate">Check-Out Date:</label>
            <input type="date" name="checkOutDate" id="checkOutDate" value="<?= htmlspecialchars($booking['checkOutDate']) ?>" required>

            <label for="bookingStatus">Booking Status:</label>
            <input type="text" name="bookingStatus" id="bookingStatus" value="<?= htmlspecialchars($booking['bookingStatus']) ?>" required>

            <button type="submit">Update Booking</button>
        </form>
        <a href="viewBookings.php">Back to Booking List</a>
    </div>
</body>
</html>