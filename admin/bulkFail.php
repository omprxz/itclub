<?php
include_once('../action/checklogin.php');

if(isset($_POST['bulkbtn'])){
require '../action/conn.php';
 $admin_id=$_SESSION["admin_id"];
$query = "SELECT admin_level FROM adminCreds WHERE admin_id = $admin_id";
$result = $mysqli->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    $admin_level = $row['admin_level'];
    $result->free_result();
}
if($admin_level >=6 ){
$col=$_POST['bulkbtn'];
$sql="UPDATE joinrequests SET $col = -1 WHERE $col != 1";
if($qsql=mysqli_query($mysqli,$sql)){
    echo "$col Rest all failed";
}else{
    echo ' Error in Failing';
}
}else{
  echo("You are not allowed to do this.");
}
}else{
    echo ' Something went wrong';
}
?>