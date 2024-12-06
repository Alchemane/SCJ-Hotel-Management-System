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
    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $roomTypeName = $_POST['roomTypeName'];
        $description = $_POST['description'];
        $pricePerNight = $_POST['pricePerNight'];

        // Insert new room type into the database
        $stmt = $pdo->prepare('INSERT INTO RoomType (roomTypeName, description, pricePerNight) VALUES (?, ?, ?)');
        $stmt->execute([$roomTypeName, $description, $pricePerNight]);

        // Redirect to the viewRoomTypes.php page
        header('Location: viewRoomTypes.php');
        exit;
    } else {
        echo "Invalid request.";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>