<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: php/admin/login.php');
    exit;
}

include 'components/NavBar.php';

try {
    $pdo = new PDO('sqlite:../database/hotel_management.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare('SELECT * FROM Hotel WHERE hotelID = ?');
    $stmt->execute([$_GET['hotelID']]);
    $hotel = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$hotel) {
        echo "Hotel not found.";
        exit;
    }
} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
    exit;
}
?>

<div class="form-container">
    <h2>Update Hotel</h2>
    <form action="updateHotelRecord.php" method="post">
        <input type="hidden" name="hotelID" value="<?= htmlspecialchars($hotel['hotelID']) ?>">

        <label for="hotelName">Hotel Name:</label>
        <input type="text" id="hotelName" name="hotelName" value="<?= htmlspecialchars($hotel['hotelName']) ?>" required>

        <label for="hotelAddress">Address:</label>
        <input type="text" id="hotelAddress" name="hotelAddress" value="<?= htmlspecialchars($hotel['hotelAddress']) ?>" required>

        <label for="city">City:</label>
        <input type="text" id="city" name="city" value="<?= htmlspecialchars($hotel['city']) ?>" required>

        <label for="postcode">Postcode:</label>
        <input type="text" id="postcode" name="postcode" value="<?= htmlspecialchars($hotel['postcode']) ?>" required>

        <label for="hotel_telNo">Telephone Number:</label>
        <input type="text" id="hotel_telNo" name="hotel_telNo" value="<?= htmlspecialchars($hotel['hotel_telNo']) ?>" required>

        <button type="submit" class="form-button">Update Hotel</button>
    </form>
</div>