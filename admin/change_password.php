<?php
include_once('../action/checklogin.php');
require('../action/conn.php');
$admin_id=$_SESSION["admin_id"];
$response = array();
if(isset($_POST['currentPass'])){
$admin_currentPass=$_POST['currentPass'];
$admin_newPass=$_POST['newPass'];

$checkPass = mysqli_query($mysqli,"select * from adminCreds where admin_id = $admin_id AND admin_pass = '$admin_currentPass'");
if($checkPass){
  if(mysqli_num_rows($checkPass)>0){
    $changePass = mysqli_query($mysqli,"update adminCreds set admin_pass = '$admin_newPass' where admin_id = $admin_id");
    if($changePass){
      $response['status']='success';
  $response['result']='Password changed.';
  echo(json_encode($response));
  exit();
    }else{
      $response['status']='failed';
  $response['result']='Something went wrong!';
  echo(json_encode($response));
  exit();
    }
  }else{
    $response['status']='failed';
  $response['result']='Wrong password!';
  echo(json_encode($response));
  exit();
  }
}else{
  $response['status']='failed';
  $response['result']='Something went wrong!';
  echo(json_encode($response));
  exit();
}
}else{
  $response['status']='failed';
  $response['result']='Method not allowed!';
  echo(json_encode($response));
  exit();
}

?>