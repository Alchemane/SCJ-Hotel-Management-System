<?php
// Ensure the admin is logged in
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: admin/login.php');
    exit;
}

$config_path = '../components/config.php';
if (!file_exists($config_path)) {
    die("Config file not found at: $config_path");
}
require_once $config_path;

// Connect to the database
try {
    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $hotelID = $_POST['hotelID'];
        $hotelName = $_POST['hotelName'];
        $hotelAddress = $_POST['hotelAddress'];
        $city = $_POST['city'];
        $postcode = $_POST['postcode'];
        $hotel_telNo = $_POST['hotel_telNo'];

        // Validate input
        if (empty($hotelID) || empty($hotelName) || empty($hotelAddress) || empty($city) || empty($postcode) || empty($hotel_telNo)) {
            echo "Error: All fields are required.";
            exit;
        }

        // Update the hotel record
        $stmt = $pdo->prepare("
            UPDATE Hotel
            SET hotelName = :hotelName,
                hotelAddress = :hotelAddress,
                city = :city,
                postcode = :postcode,
                hotel_telNo = :hotel_telNo
            WHERE hotelID = :hotelID
        ");
        $stmt->execute([
            ':hotelName' => $hotelName,
            ':hotelAddress' => $hotelAddress,
            ':city' => $city,
            ':postcode' => $postcode,
            ':hotel_telNo' => $hotel_telNo,
            ':hotelID' => $hotelID,
        ]);

        header('Location: viewHotels.php?message=Hotel Updated Successfully');
        exit;
    } else {
        echo "Invalid request.";
        exit;
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>