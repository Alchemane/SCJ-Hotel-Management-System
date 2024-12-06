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

    $stmt = $pdo->query('SELECT * FROM Hotel');
    $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>View Hotels</title>
</head>
<body>
    <div class="form-container">
        <h2 class="center-text">Hotels List</h2>
        <div class="center-text" style="margin-bottom: 20px;">
            <a href="createHotelPage.php" class="form-button half-width-button">Add New Hotel</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Hotel ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>Postcode</th>
                    <th>Telephone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hotels as $hotel): ?>
                    <tr>
                        <td><?= htmlspecialchars($hotel['hotelID']) ?></td>
                        <td><?= htmlspecialchars($hotel['hotelName']) ?></td>
                        <td><?= htmlspecialchars($hotel['hotelAddress']) ?></td>
                        <td><?= htmlspecialchars($hotel['city']) ?></td>
                        <td><?= htmlspecialchars($hotel['postcode']) ?></td>
                        <td><?= htmlspecialchars($hotel['hotel_telNo']) ?></td>
                        <td>
                            <a href="updateHotelPage.php?hotelID=<?= $hotel['hotelID'] ?>">Update</a>
                            <a href="deleteHotel.php?hotelID=<?= $hotel['hotelID'] ?>" onclick="return confirm('Are you sure you want to delete this hotel?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
