<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: ../');
  exit();
}
require('../../action/conn.php');
 
$admin_id = $_SESSION["admin_id"];
$response = array();

$st = "select admin_level from adminCreds where admin_id = '$admin_id'";
$result = mysqli_query($mysqli, $st);

if ($result) {
  $row = mysqli_fetch_assoc($result);
  $admin_level = $row['admin_level'];
  mysqli_free_result($result);
} else {
  $response['status']='failed';
  $response['result']='Can\'t fetch admin level.';
  $response=json_encode($response);
  echo($response);
  exit();
}

if(isset($_GET['id'])){
  
  $id=$_GET['id'];
  $sql="select * from blogs where id = '$id'";
  $eSql=mysqli_query($mysqli,$sql);
  if(mysqli_num_rows($eSql)>0){
    $rowData=mysqli_fetch_assoc($eSql);
    
if($rowData['adminId'] != $admin_id && $admin_level < 7){
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