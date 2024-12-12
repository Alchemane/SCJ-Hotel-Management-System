<?php
require_once '../components/config.php';

if (isset($_GET['hotelID'])) {
    $hotelID = $_GET['hotelID'];

    try {
        $stmt = $pdo->prepare("SELECT roomID, roomNumber FROM Room WHERE hotelID = ? AND status = 'available'");
        $stmt->execute([$hotelID]);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rooms);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error fetching rooms: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No hotel ID provided.']);
}
?>