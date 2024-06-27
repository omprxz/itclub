<?php
include_once('../../action/checklogin.php');
require('../../action/conn.php');
 
$admin_id = $_SESSION["admin_id"];
$query = "SELECT admin_level FROM adminCreds WHERE admin_id = $admin_id";

$result = $mysqli->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $admin_level = $row['admin_level'];
    $result->free_result();
}

$response = array();

if(isset($_GET['blogid'])){
  
  $id=$_GET['blogid'];
  $sql="select * from blogs where id = '$id'";
  $eSql=mysqli_query($mysqli,$sql);
  if(mysqli_num_rows($eSql)>0){
    $rowData=mysqli_fetch_assoc($eSql);
    
if($rowData['adminId'] != $admin_id && $admin_level < 6){
  $response['status']='failed';
  $response['result']='You are not authorized to edit this blog.';
  $response=json_encode($response);
  echo($response);
  exit();
}else{
  $rowData['status']='success';
  $blogdata=json_encode($rowData);
  echo($blogdata);
}
    
  }else {
     $response['status']='failed';
     $response['result']='No blog found with this blog id.';
     $response=json_encode($response);
     echo($response);
     exit();
  }
  
  
}
?>