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
    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get room details if an ID is provided
    if (isset($_GET['id'])) {
        $roomID = $_GET['id'];
        $stmt = $pdo->prepare('SELECT * FROM Room WHERE roomID = ?');
        $stmt->execute([$roomID]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$room) {
            echo "Room not found.";
            exit;
        }
    } else {
        echo "No room ID provided.";
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
    <title>Update Room</title>
</head>
<body>
    <h1>Update Room Information</h1>
    <form action="updateRoomRecord.php" method="post">
        <input type="hidden" name="roomID" value="<?= htmlspecialchars($room['roomID']) ?>">

        <label for="hotelID">Hotel ID:</label>
        <input type="number" name="hotelID" id="hotelID" value="<?= htmlspecialchars($room['hotelID']) ?>" required>

        <label for="roomNumber">Room Number:</label>
        <input type="number" name="roomNumber" id="roomNumber" value="<?= htmlspecialchars($room['roomNumber']) ?>" required>

        <label for="roomTypeID">Room Type ID:</label>
        <input type="number" name="roomTypeID" id="roomTypeID" value="<?= htmlspecialchars($room['roomTypeID']) ?>" required>

        <label for="status">Status:</label>
        <input type="text" name="status" id="status" value="<?= htmlspecialchars($room['status']) ?>" required>

        <button type="submit">Update Room</button>
    </form>
    <a href="viewRooms.php">Back to Room List</a>
</body>
</html>