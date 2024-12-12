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
    

    $stmt = $pdo->query('SELECT * FROM RoomType');
    $roomTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>View Room Types</title>
</head>
<body>
    <div class="form-container">
        <h2 class="center-text">Room Types List</h2>
        <div class="center-text" style="margin-bottom: 20px;">
            <a href="createRoomTypePage.php" class="form-button half-width-button">Add New Room Type</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Room Type ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roomTypes as $roomType): ?>
                    <tr>
                        <td><?= htmlspecialchars($roomType['roomTypeID']) ?></td>
                        <td><?= htmlspecialchars($roomType['roomTypeName']) ?></td>
                        <td><?= htmlspecialchars($roomType['description']) ?></td>
                        <td>
                            <a href="updateRoomTypePage.php?roomTypeID=<?= $roomType['roomTypeID'] ?>">Update</a>
                            <a href="deleteRoomType.php?roomTypeID=<?= $roomType['roomTypeID'] ?>" onclick="return confirm('Are you sure you want to delete this room type?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
