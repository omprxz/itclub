<?php
 $mysqli = new mysqli("sql308.infinityfree.com", "if0_35270932", "BJa1wWqdnjn0yJ", "if0_35270932_itclub");

 if ($mysqli->connect_error) {
     die("Connection failed: " . $mysqli->connect_error);
 }
?>