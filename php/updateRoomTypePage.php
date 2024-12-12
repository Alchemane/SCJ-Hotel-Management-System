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
    

    // Get room type details if an ID is provided
    if (isset($_GET['roomTypeID'])) {
        $roomTypeID = $_GET['roomTypeID'];
        $stmt = $pdo->prepare('SELECT * FROM RoomType WHERE roomTypeID = ?');
        $stmt->execute([$roomTypeID]);
        $roomType = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$roomType) {
            echo "Room Type not found.";
            exit;
        }
    } else {
        echo "No room type ID provided.";
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
    <title>Update Room Type</title>
</head>
<body>
    <div class="form-container">
        <h2 class="center-text">Update Room Type Information</h2>
        <form action="updateRoomTypeRecord.php" method="post">
            <input type="hidden" name="roomTypeID" value="<?= htmlspecialchars($roomType['roomTypeID']) ?>">

            <label for="roomTypeName">Room Type Name:</label>
            <input type="text" name="roomTypeName" id="roomTypeName" value="<?= htmlspecialchars($roomType['roomTypeName']) ?>" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="4"><?= htmlspecialchars($roomType['description']) ?></textarea>

            <button type="submit">Update Room Type</button>
        </form>
        <a href="viewRoomTypes.php">Back to Room Type List</a>
    </div>
</body>
</html>