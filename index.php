<?php // ensure page is protected by login session from admin
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: php/admin/login.php');
    exit;
}

include 'components/NavBar.php'; // add navbar to this page

require_once __DIR__ . '/components/config.php';
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
    <h1 class="center-text">SCJ Hotels</h1>
    <h2 class="center-text">Management System</h2>
    <p class="center-text">Select a section from the navigation bar above to begin managing the hotel data.</p>

    <div class="form-container">
        <div class="center-text">
        <h2 class="center-text">Hotel Reports</h2>
            <?php
            $stmt = $pdo->query("SELECT h.hotelID, h.hotelName,
                COUNT(*) AS total_rooms,
                COUNT(CASE WHEN r.status = 'booked' THEN 1 END) AS booked_rooms
            FROM Hotel h
            INNER JOIN Room r ON h.hotelID = r.hotelID
            GROUP BY h.hotelID, h.hotelName");

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $hotelID = $row['hotelID'];
                $hotelName = $row['hotelName'];
                $totalRooms = $row['total_rooms'];
                $bookedRooms = $row['booked_rooms'];

                $bookedPercentage = ($bookedRooms / $totalRooms) * 100;

                echo "<h3>Hotel: $hotelName</h3>";
                echo "<p>Total Rooms: $totalRooms</p>";
                echo "<p>Booked Rooms: $bookedRooms</p>";
                echo "<p>Booking Efficiency: " . round($bookedPercentage, 2) . "%</p>";
            }

            $totalRooms = 0;
            $totalBookedRooms = 0;
            $stmt = $pdo->query("SELECT COUNT(*) AS total_rooms,
                                    COUNT(CASE WHEN r.status = 'booked' THEN 1 END) AS booked_rooms
                                FROM Room r");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $totalRooms += $row['total_rooms'];
            $totalBookedRooms += $row['booked_rooms'];

            // Calculate the franchise-wide booking efficiency
            $franchiseEfficiency = ($totalBookedRooms / $totalRooms) * 100;

            echo "<h3>Total Hotel Efficiency</h3>";
            echo "<p>Total Rooms: $totalRooms</p>";
            echo "<p>Total Booked Rooms: $totalBookedRooms</p>";
            echo "<p>Company Booking Efficiency: " . round($franchiseEfficiency, 2) . "%</p>";
            ?>
        </div>
    </div>
</body>
</html>