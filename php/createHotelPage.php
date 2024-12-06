<?php
// Ensure page is protected by login session from admin
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: php/admin/login.php');
    exit;
}

include 'components/NavBar.php'; // Include navbar
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Add New Hotel</title>
</head>
<body>
    <div class="form-container">
        <h2>Add New Hotel</h2>
        <form action="createHotelRecord.php" method="post">
            <label for="hotelName">Hotel Name:</label>
            <input type="text" id="hotelName" name="hotelName" required>

            <label for="hotelAddress">Address:</label>
            <input type="text" id="hotelAddress" name="hotelAddress" required>

            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>

            <label for="postcode">Postcode:</label>
            <input type="text" id="postcode" name="postcode" required>

            <label for="hotel_telNo">Telephone Number:</label>
            <input type="text" id="hotel_telNo" name="hotel_telNo" required>

            <button type="submit" class="form-button">Add Hotel</button>
        </form>
    </div>
</body>
</html>