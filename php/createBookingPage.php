<?php // ensure page is protected by login session from admin
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: admin/login.php');
    exit;
}

include '../components/NavBar.php'; // add navbar to this page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Create New Booking</title>
</head>
<body>
    <div class="form-container">
        <h2>Add New Booking</h2>
        <form action="createNewBookingRecord.php" method="post">
            <label for="guestID">Guest ID:</label>
            <input type="number" id="guestID" name="guestID" required>

            <label for="roomID">Room ID:</label>
            <input type="number" id="roomID" name="roomID" required>

            <label for="checkInDate">Check-In Date:</label>
            <input type="text" id="checkInDate" name="checkInDate" placeholder="YYYY-MM-DD" required>

            <label for="checkOutDate">Check-Out Date:</label>
            <input type="text" id="checkOutDate" name="checkOutDate" placeholder="YYYY-MM-DD" required>

            <label for="bookingStatus">Booking Status:</label>
            <input type="text" id="bookingStatus" name="bookingStatus" required>

            <button type="submit" class="form-button">Add Booking</button>
        </form>
    </div>
</body>
</html>