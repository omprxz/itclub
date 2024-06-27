<?php

 $mysqli = new mysqli("sql308.infinityfree.com", "if0_35270932", "BJa1wWqdnjn0yJ", "if0_35270932_itclub");

 if ($mysqli->connect_error) {
     die("Connection failed: " . $mysqli->connect_error);
 }

/*
$countFile = 'count.txt';
$count = file_get_contents($countFile);
$newCount = $count + 1;
file_put_contents($countFile, $newCount);
*/
$uniqueID = uniqid();

$query = "INSERT INTO test1 (id) VALUES ('$uniqueID')";
$result = $mysqli->query($query);

if ($result) {
    echo "Unique ID '$uniqueID' inserted successfully!";
} else {
    echo "Error: " . $mysqli->error;
}
?>