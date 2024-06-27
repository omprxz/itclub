<?php
include_once('../action/checklogin.php');
require('../action/conn.php');
$admin_id=$_SESSION["admin_id"];
$response = array();

if(isset($_POST['remove'])){
  $remove="update adminCreds set admin_profilepic = 'profilepic.png' where admin_id = $admin_id";
  if (mysqli_query($mysqli,$remove)) {
    $response['status']='success';
    $response['result']='Profile pic removed';
    echo(json_encode($response));
    exit();
  }else{
    $response['status']='failed';
    $response['result']='Something went wrong!';
    echo(json_encode($response));
    exit();
  }
  
}else{
  echo('Method not allowed');
}

?>