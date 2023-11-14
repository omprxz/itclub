<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page or any other appropriate page
header('Location: login.php?error=Logged out');
?>