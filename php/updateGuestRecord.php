<?php // ensure page is protected by login session from admin
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: admin/login.php');
    exit;
}

require_once __DIR__ . '/../components/config.php';
include '../components/NavBar.php'; // add navbar to this page

// Database Connection
try {
    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $guestID = $_POST['guestID'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $phoneNo = $_POST['phoneNo'];
        $address = $_POST['address'];

        // Update guest in the database
        $stmt = $pdo->prepare('UPDATE Guest SET firstName = ?, lastName = ?, email = ?, phoneNo = ?, address = ? WHERE guestID = ?');
        $stmt->execute([$firstName, $lastName, $email, $phoneNo, $address, $guestID]);

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