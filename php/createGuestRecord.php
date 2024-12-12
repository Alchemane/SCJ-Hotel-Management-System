<?php // ensure page is protected by login session from admin
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: admin/login.php');
    exit;
}

include '../components/NavBar.php'; // add navbar to this page

$config_path = '../components/config.php';
if (!file_exists($config_path)) {
    die("Config file not found at: $config_path");
}
require_once $config_path;

// Connect to the database
try {
    

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $phoneNo = $_POST['phoneNo'];
        $address = $_POST['address'];

        // Insert new guest into the database
        $stmt = $pdo->prepare('INSERT INTO Guest (firstName, lastName, email, phoneNo, address) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$firstName, $lastName, $email, $phoneNo, $address]);

        // Redirect to the viewGuests.php page
        header('Location: viewGuests.php');
        exit;
    } else {
        echo "Invalid request.";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>