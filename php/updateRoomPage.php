<?php // ensure page is protected by login session from admin
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

// Connect to the database
$pdo = new PDO('sqlite:' . DB_PATH);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['roomID'])) {
    $roomID = $_GET['roomID'];

    // Fetch the room's details from the database
    $stmt = $pdo->prepare('SELECT * FROM Room WHERE roomID = ?');
    $stmt->execute([$roomID]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($room) {
        // Pre-fill variables for the form
        $hotelID = htmlspecialchars($room['hotelID']);
        $roomNumber = htmlspecialchars($room['roomNumber']);
        $roomTypeID = htmlspecialchars($room['roomTypeID']);
        $status = htmlspecialchars($room['status']);
        $pricePerNight = htmlspecialchars($room['pricePerNight']);
    } else {
        echo "Room not found.";
        exit;
    }
} else {
    echo "No room ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Room</title>
</head>
<body>
    <div class="form-container">
        <h2 class="center-text">Update Room</h2>
        <form action="updateRoomRecord.php" method="POST">
            <input type="hidden" name="roomID" value="<?= $roomID ?>">

            <label for="hotelID">Hotel:</label>
            <select name="hotelID" id="hotelID" required>
                <?php
                // Fetch all hotels for the dropdown
                $hotelsStmt = $pdo->query('SELECT hotelID, hotelName FROM Hotel ORDER BY hotelName');
                while ($hotel = $hotelsStmt->fetch(PDO::FETCH_ASSOC)) {
                    $selected = $hotel['hotelID'] == $hotelID ? 'selected' : '';
                    echo "<option value=\"{$hotel['hotelID']}\" $selected>{$hotel['hotelName']}</option>";
                }
                ?>
            </select><br>

            <label for="roomNumber">Room Number:</label>
            <input type="number" id="roomNumber" name="roomNumber" value="<?= $roomNumber ?>" required><br>

            <label for="roomTypeID">Room Type:</label>
            <select name="roomTypeID" id="roomTypeID" required>
                <?php
                // Fetch all room types for the dropdown
                $roomTypesStmt = $pdo->query('SELECT roomTypeID, roomTypeName FROM RoomType ORDER BY roomTypeName');
                while ($roomType = $roomTypesStmt->fetch(PDO::FETCH_ASSOC)) {
                    $selected = $roomType['roomTypeID'] == $roomTypeID ? 'selected' : '';
                    echo "<option value=\"{$roomType['roomTypeID']}\" $selected>{$roomType['roomTypeName']}</option>";
                }
                ?>
            </select><br>

            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="available" <?= $status === 'available' ? 'selected' : '' ?>>Available</option>
                <option value="booked" <?= $status === 'booked' ? 'selected' : '' ?>>Booked</option>
                <option value="maintenance" <?= $status === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
            </select><br>

            <label for="pricePerNight">Price Per Night:</label>
            <input type="number" id="pricePerNight" name="pricePerNight" value="<?= $pricePerNight ?>" step="0.01" required><br>

            <div class="button-container">
                <form action="viewRooms.php">
                    <button type="submit" class="form-button cancel-button">Cancel</button>
                </form>
                <button type="submit" class="form-button">Update Room</button>
            </div>
        </form>
    </div>
</body>
</html>
