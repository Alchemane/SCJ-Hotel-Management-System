<?php
session_start();
$_SESSION = array();
session_destroy();
header("Location:/website/php/admin/login.php"); // redirect to login page
exit;
?>