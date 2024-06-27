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

if(isset($_GET['noticeid'])){
  
  $id=$_GET['noticeid'];
  $sql="select * from notices where notice_id = '$id'";
  $eSql=mysqli_query($mysqli,$sql);
  if(mysqli_num_rows($eSql)>0){
    $rowData=mysqli_fetch_assoc($eSql);
    $rowData['notice_content']=htmlspecialchars_decode($rowData['notice_content']);
    
if($admin_level < 3){
  $response['status']='failed';
  $response['result']='You are not authorized to edit this notice.';
  $response=json_encode($response);
  echo($response);
  exit();
}else{
  $rowData['status']='success';
  $noticedata=json_encode($rowData);
  echo($noticedata);
}
    
  }else {
     $response['status']='failed';
     $response['result']='No notice found with this notice id.';
     $response=json_encode($response);
     echo($response);
     exit();
  }
  
  
}
?>