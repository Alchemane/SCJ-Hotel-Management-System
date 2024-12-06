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

    // Get guest details if an ID is provided
    if (isset($_GET['id'])) {
        $guestID = $_GET['id'];
        $stmt = $pdo->prepare('SELECT * FROM Guest WHERE guestID = ?');
        $stmt->execute([$guestID]);
        $guest = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$guest) {
            echo "Guest not found.";
            exit;
        }
    } else {
        echo "No guest ID provided.";
        exit;
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Update Guest</title>
</head>
<body>
    <h1>Update Guest Information</h1>
    <form action="updateGuestRecord.php" method="post">
        <input type="hidden" name="guestID" value="<?= htmlspecialchars($guest['guestID']) ?>">

        <label for="firstName">First Name:</label>
        <input type="text" name="firstName" id="firstName" value="<?= htmlspecialchars($guest['firstName']) ?>" required>

        <label for="lastName">Last Name:</label>
        <input type="text" name="lastName" id="lastName" value="<?= htmlspecialchars($guest['lastName']) ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($guest['email']) ?>" required>

        <label for="phoneNo">Phone Number:</label>
        <input type="text" name="phoneNo" id="phoneNo" value="<?= htmlspecialchars($guest['phoneNo']) ?>" required>

        <label for="address">Address:</label>
        <input type="text" name="address" id="address" value="<?= htmlspecialchars($guest['address']) ?>">

        <button type="submit">Update Guest</button>
    </form>
    <a href="viewGuests.php">Back to Guest List</a>
</body>
</html>