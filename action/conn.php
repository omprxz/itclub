<?php
$host = $_SERVER['HTTP_HOST'];
    $mysqli = new mysqli("localhost", "root", "", "itclub");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// CRONS >>>
//require('cron.php');
require('vars.php');
//<<< CRONS
?>