<?php
// ensure page is protected by login session from admin
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: php/admin/login.php');
    exit;
}

include 'components/NavBar.php'; // add navbar to this page

$config_path = 'components/config.php';
if (!file_exists($config_path)) {
    die("Config file not found at: $config_path");
}
require_once $config_path; // import database

try {
    // Connect to the database
    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("
        UPDATE Room
        SET status = 'available'
        WHERE roomID IN (
            SELECT roomID
            FROM Booking
            WHERE checkOutDate < :today
        )
    ");
    $stmt->execute([':today' => date('Y-m-d')]);

    // Fetch booking data for each hotel
    $stmt = $pdo->query("
        SELECT h.hotelName,
               COUNT(r.roomID) AS totalRooms,
               SUM(CASE WHEN r.status = 'booked' THEN 1 ELSE 0 END) AS bookedRooms
        FROM Hotel h
        LEFT JOIN Room r ON h.hotelID = r.hotelID
        GROUP BY h.hotelID
        ORDER BY h.hotelName
    ");
    $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalHotels = 0;
    $totalRooms = 0;
    $totalBookedRooms = 0;

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Hotel Management Dashboard</title>
</head>
<body>
    <h1 class="center-text">Welcome to SCJ Hotel Management System</h1>
    <p class="center-text">Select a section from the navigation bar above to begin managing the hotel data.</p>

    <div class="form-container">
        <h2 class="center-text">Hotel Booking Efficiency</h2>
        <table>
            <thead>
                <tr>
                    <th>Hotel Name</th>
                    <th>Total Rooms</th>
                    <th>Booked Rooms</th>
                    <th>Booking Efficiency</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hotels as $hotel): ?>
                    <?php
                    $totalHotels++;
                    $totalRooms += $hotel['totalRooms'];
                    $totalBookedRooms += $hotel['bookedRooms'];
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($hotel['hotelName']) ?></td>
                        <td><?= htmlspecialchars($hotel['totalRooms']) ?></td>
                        <td><?= htmlspecialchars($hotel['bookedRooms']) ?></td>
                        <td>
                            <?= $hotel['totalRooms'] > 0
                                ? number_format(($hotel['bookedRooms'] / $hotel['totalRooms']) * 100, 2) . '%'
                                : 'N/A'
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <tr>
                    <th>Total Hotels</th>
                    <th><?= $totalRooms ?></th>
                    <th><?= $totalBookedRooms ?></th>
                    <th>
                        <?= $totalRooms > 0
                            ? number_format(($totalBookedRooms / $totalRooms) * 100, 2) . '%'
                            : 'N/A'
                        ?>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>