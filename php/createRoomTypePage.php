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
    <title>Create New Room Type</title>
</head>
<body>
    <div class="form-container">
        <h2>Add New Room Type</h2>
        <form action="createNewRoomTypeRecord.php" method="post">
            <label for="roomTypeName">Room Type Name:</label>
            <input type="text" id="roomTypeName" name="roomTypeName" required>

            <label for="description">Description:</label>
            <input type="text" id="description" name="description">

            <label for="pricePerNight">Price Per Night:</label>
            <input type="number" id="pricePerNight" name="pricePerNight" step="0.01" required>

            <button type="submit" class="form-button">Add Room Type</button>
        </form>
    </div>
</body>
</html>