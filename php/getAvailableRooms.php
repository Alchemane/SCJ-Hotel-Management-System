<?php
require_once '../components/config.php';

if (isset($_GET['hotelID']) && !empty($_GET['hotelID'])) {
    $hotelID = $_GET['hotelID'];

    try {
        // Fetch available rooms for the given hotel
        $stmt = $pdo->prepare("SELECT roomID, roomNumber FROM Room WHERE hotelID = :hotelID AND status = 'available'");
        $stmt->execute([':hotelID' => $hotelID]);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the data as JSON
        echo json_encode($rooms);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error fetching rooms: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid or missing hotel ID']);
}
