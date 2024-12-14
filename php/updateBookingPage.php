<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: admin/login.php');
    exit;
}

include '../components/NavBar.php';

$config_path = '../components/config.php';
if (!file_exists($config_path)) {
    die("Config file not found at: $config_path");
}
require_once $config_path;

if (isset($_GET['id'])) {
    $bookingID = $_GET['id'];

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/scripts.js"></script>
    <title>Update Booking</title>
</head>
<body>
    <div class="form-container">
        <h2 class="center-text">Update Booking</h2>
        <form action="updateBookingRecord.php" method="POST">
            <input type="hidden" name="bookingID" value="<?= htmlspecialchars($booking['bookingID']) ?>">

            <label for="guestID">Guest:</label>
            <select id="guestID" name="guestID" required>
                <?php
                $stmt = $pdo->query("SELECT guestID, firstName || ' ' || lastName AS fullName FROM Guest");
                $guests = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($guests as $guest) {
                    $selected = ($guest['guestID'] == $booking['guestID']) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($guest['guestID']) . '" ' . $selected . '>' . htmlspecialchars($guest['fullName']) . '</option>';
                }
                ?>
            </select>

            <label for="hotelID">Hotel:</label>
            <select id="hotelID" name="hotelID" required>
                <option value="" disabled>Select a hotel</option>
                <?php
                $stmt = $pdo->query("SELECT hotelID, hotelName FROM Hotel");
                $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($hotels as $hotel) {
                    $selected = ($hotel['hotelID'] == $booking['hotelID']) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($hotel['hotelID']) . '" ' . $selected . '>' . htmlspecialchars($hotel['hotelName']) . '</option>';
                }
                ?>
            </select>

            <label for="roomID">Room:</label>
            <select id="roomID" name="roomID" required>
                <option value="<?= htmlspecialchars($booking['roomID']) ?>" selected>Select a room</option>
            </select>

            <label for="checkInDate">Check-In Date:</label>
            <input type="date" id="checkInDate" name="checkInDate" value="<?= htmlspecialchars($booking['checkInDate']) ?>" required>

            <label for="checkOutDate">Check-Out Date:</label>
            <input type="date" id="checkOutDate" name="checkOutDate" value="<?= htmlspecialchars($booking['checkOutDate']) ?>" required>

            <label for="bookingStatus">Booking Status:</label>
            <select id="bookingStatus" name="bookingStatus" required>
                <option value="confirmed" <?= ($booking['bookingStatus'] == 'confirmed') ? 'selected' : '' ?>>Confirmed</option>
                <option value="pending" <?= ($booking['bookingStatus'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                <option value="canceled" <?= ($booking['bookingStatus'] == 'canceled') ? 'selected' : '' ?>>Canceled</option>
            </select>

            <div class="button-container">
                <form action="viewBookings.php">
                    <button type="submit" class="form-button cancel-button">Cancel</button>
                </form>
                <button type="submit" class="form-button">Update Booking</button>
            </div>
        </form>
    </div>
</body>
</html>
