<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: php/admin/login.php');
    exit;
}

include '../components/NavBar.php';

$config_path = '../components/config.php';
if (!file_exists($config_path)) {
    die("Config file not found at: $config_path");
}
require_once $config_path;

// Connect to the database
try {
    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query('SELECT * FROM Room');
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>View Rooms</title>
</head>
<body>
    <div class="form-container">
        <h2 class="center-text">Rooms List</h2>
        <div class="center-text" style="margin-bottom: 20px;">
            <a href="createRoomPage.php" class="form-button half-width-button">Add New Room</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Room ID</th>
                    <th>Hotel ID</th>
                    <th>Room Number</th>
                    <th>Room Type ID</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rooms as $room): ?>
                    <tr>
                        <td><?= htmlspecialchars($room['roomID']) ?></td>
                        <td><?= htmlspecialchars($room['hotelID']) ?></td>
                        <td><?= htmlspecialchars($room['roomNumber']) ?></td>
                        <td><?= htmlspecialchars($room['roomTypeID']) ?></td>
                        <td><?= htmlspecialchars($room['status']) ?></td>
                        <td>
                            <a href="updateRoomPage.php?roomID=<?= $room['roomID'] ?>">Update</a>
                            <a href="deleteRoom.php?roomID=<?= $room['roomID'] ?>" onclick="return confirm('Are you sure you want to delete this room?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
