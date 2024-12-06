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

    $stmt = $pdo->query('SELECT * FROM Guest');
    $guests = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>View Guests</title>
</head>
<body>
    <div class="form-container">
        <h2 class="center-text">Guests List</h2>
        <div class="center-text" style="margin-bottom: 20px;">
            <a href="createGuestPage.php" class="form-button half-width-button">Add New Guest</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Guest ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($guests as $guest): ?>
                    <tr>
                        <td><?= htmlspecialchars($guest['guestID']) ?></td>
                        <td><?= htmlspecialchars($guest['firstName']) ?></td>
                        <td><?= htmlspecialchars($guest['lastName']) ?></td>
                        <td><?= htmlspecialchars($guest['email']) ?></td>
                        <td><?= htmlspecialchars($guest['phoneNo']) ?></td>
                        <td><?= htmlspecialchars($guest['address']) ?></td>
                        <td>
                            <a href="updateGuestPage.php?guestID=<?= $guest['guestID'] ?>">Update</a>
                            <a href="deleteGuest.php?guestID=<?= $guest['guestID'] ?>" onclick="return confirm('Are you sure you want to delete this guest?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>