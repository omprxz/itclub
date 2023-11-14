<?php
if(isset($_POST['bulkbtn'])){
require 'conn.php';
$col=$_POST['bulkbtn'];
$sql="UPDATE joinrequests SET $col = -1 WHERE $col != 1";
if($qsql=mysqli_query($mysqli,$sql)){
    echo "$col Rest all failed";
}else{
    echo ' Error in Failing';
}
}else{
    echo ' Something went wrong';
}
?>