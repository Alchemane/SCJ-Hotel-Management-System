<?php // ensure page is protected by login session from admin
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: php/admin/login.php');
    exit;
}

include 'components/NavBar.php'; // add navbar to this page
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
</body>
</html>