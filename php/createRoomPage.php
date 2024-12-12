<?php // ensure page is protected by login session from admin
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: admin/login.php');
    exit;
}

include '../components/NavBar.php'; // add navbar to this page
require_once __DIR__ . '/../components/config.php';

try {
    // Database connection
    

    // Fetch all hotels
    $hotelsStmt = $pdo->query("SELECT hotelID, hotelName FROM Hotel");
    $hotels = $hotelsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all room types
    $roomTypesStmt = $pdo->query("SELECT roomTypeID, roomTypeName FROM RoomType");
    $roomTypes = $roomTypesStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Add New Room</title>
</head>
<body>
    <div class="form-container">
        <h1 class="center-text">Add New Room</h1>
        <form action="createRoomRecord.php" method="post">
            <label for="hotelID">Hotel:</label>
            <select name="hotelID" id="hotelID" required>
                <option value="" disabled selected>Select a Hotel</option>
                <?php foreach ($hotels as $hotel): ?>
                    <option value="<?= htmlspecialchars($hotel['hotelID']) ?>">
                        <?= htmlspecialchars($hotel['hotelName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="roomNumber">Room Number:</label>
            <input type="number" name="roomNumber" id="roomNumber" required>

            <label for="roomTypeID">Room Type:</label>
            <select name="roomTypeID" id="roomTypeID" required>
                <option value="" disabled selected>Select a Room Type</option>
                <?php foreach ($roomTypes as $roomType): ?>
                    <option value="<?= htmlspecialchars($roomType['roomTypeID']) ?>">
                        <?= htmlspecialchars($roomType['roomTypeName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="status">Status:</label>
                <select name="status" id="status" required>
                    <option value="" disabled selected>Select a Status</option>
                    <option value="Available">Available</option>
                    <option value="Booked">Booked</option>
                    <option value="Maintenance">Maintenance</option>
                </select>

            <label for="pricePerNight">Price Per Night:</label>
                <input type="number" id="pricePerNight" name="pricePerNight" step="0.01" required>

            <button type="submit">Add Room</button>
        </form>
    </div>
</body>
</html>