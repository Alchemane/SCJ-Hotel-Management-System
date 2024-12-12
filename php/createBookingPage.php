<?php
// ensure page is protected by login session from admin
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: admin/login.php');
    exit;
}

include '../components/NavBar.php'; // add navbar to this page

$config_path = '../components/config.php';
if (!file_exists($config_path)) {
    die("Config file not found at: $config_path");
}
require_once $config_path;
$errorMessage = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Add New Booking</title>
</head>
<body>
    <div class="form-container">
        <h2 class="center-text">Add New Booking</h2>

        <?php if ($errorMessage): ?>
            <div class="error-message">
                <?= $errorMessage ?>
            </div>
        <?php endif; ?>


        <form action="createBookingRecord.php" method="POST">
            <label for="guestID">Guest:</label>
            <select id="guestID" name="guestID" required>
                <?php
                try {
                    // Fetch all guests
                    $stmt = $pdo->query("SELECT guestID, firstName || ' ' || lastName AS fullName FROM Guest");
                    $guests = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (empty($guests)) {
                        echo '<option disabled>No guests found</option>';
                    } else {
                        foreach ($guests as $guest) {
                            echo '<option value="' . htmlspecialchars($guest['guestID']) . '">' . htmlspecialchars($guest['fullName']) . '</option>';
                        }
                    }
                } catch (PDOException $e) {
                    echo '<option disabled>Error loading guests: ' . htmlspecialchars($e->getMessage()) . '</option>';
                }
                ?>
            </select>

            <label for="hotelID">Hotel:</label>
            <select id="hotelID" name="hotelID" required>
                <option value="" disabled selected>Select a hotel</option>
                <?php
                try {
                    // Fetch all hotels
                    $stmt = $pdo->query("SELECT hotelID, hotelName FROM Hotel");
                    $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($hotels as $hotel) {
                        echo '<option value="' . htmlspecialchars($hotel['hotelID']) . '">' . htmlspecialchars($hotel['hotelName']) . '</option>';
                    }
                } catch (PDOException $e) {
                    echo '<option disabled>Error loading hotels: ' . htmlspecialchars($e->getMessage()) . '</option>';
                }
                ?>
            </select>

            <label for="roomID">Room:</label>
            <select id="roomID" name="roomID" required>
                <?php
                try {
                    // Fetch available rooms
                    $stmt = $pdo->query("SELECT roomID, roomNumber FROM Room WHERE status = 'available'");
                    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (empty($rooms)) {
                        echo '<option disabled>No rooms available</option>';
                    } else {
                        foreach ($rooms as $room) {
                            echo '<option value="' . htmlspecialchars($room['roomID']) . '">Room ' . htmlspecialchars($room['roomNumber']) . '</option>';
                        }
                    }
                } catch (PDOException $e) {
                    echo '<option disabled>Error loading rooms: ' . htmlspecialchars($e->getMessage()) . '</option>';
                }
                ?>
            </select>

            <label for="checkInDate">Check-In Date:</label>
            <input type="date" id="checkInDate" name="checkInDate" required>

            <label for="checkOutDate">Check-Out Date:</label>
            <input type="date" id="checkOutDate" name="checkOutDate" required>

            <label for="bookingStatus">Booking Status:</label>
            <select id="bookingStatus" name="bookingStatus" required>
                <option value="confirmed">Confirmed</option>
                <option value="pending">Pending</option>
                <option value="canceled">Canceled</option>
            </select>

            <button type="submit" class="form-button">Add Booking</button>
        </form>
    </div>

    <script src="../js/scripts.js"></script>
</body>
</html>
