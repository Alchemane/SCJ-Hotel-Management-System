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
    <title>Create New Room</title>
</head>
<body>
    <div class="form-container">
        <h2>Add New Room</h2>
        <form action="createNewRoomRecord.php" method="post">
            <label for="hotelID">Hotel ID:</label>
            <input type="number" id="hotelID" name="hotelID" required>

            <label for="roomNumber">Room Number:</label>
            <input type="number" id="roomNumber" name="roomNumber" required>

            <label for="roomTypeID">Room Type ID:</label>
            <input type="number" id="roomTypeID" name="roomTypeID" required>

            <label for="status">Status:</label>
            <input type="text" id="status" name="status" required>

            <button type="submit" class="form-button">Add Room</button>
        </form>
    </div>
</body>
</html>