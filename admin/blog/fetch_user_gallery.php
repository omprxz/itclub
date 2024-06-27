<?php
include_once('../../action/checklogin.php');
require('../../action/conn.php');
$admin_id = $_SESSION["admin_id"];

$sql = "SELECT content FROM blogs WHERE adminId = $admin_id";
$result = $mysqli->query($sql);

$imageLinks = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
       preg_match_all('/src="([^";]+)"/i', html_entity_decode($row['content']), $matches);
        $imageLinks[] =$matches[1];
    }
}
$linkList=array();
for ($i = 0; $i < count($imageLinks); $i++) {
   if(count($imageLinks[$i])>0){
     $hasImage=$imageLinks[$i];
     for($j=0;$j < count($hasImage) ; $j++){
       array_push($linkList,$hasImage[$j]);
     }
   }
}

echo json_encode($linkList);

$mysqli->close();

?>