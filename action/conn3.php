<?php
 $mysqli = new mysqli("localhost", "root", "", "itclub");

 if ($mysqli->connect_error) {
     die("Connection failed: " . $mysqli->connect_error);
 }
?>