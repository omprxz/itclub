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

if(isset($_GET['eventid'])){
  
  $id=$_GET['eventid'];
  $sql="select * from events where event_id = '$id'";
  $eSql=mysqli_query($mysqli,$sql);
  if(mysqli_num_rows($eSql)>0){
    $rowData=mysqli_fetch_assoc($eSql);
    $rowData['event_description']=htmlspecialchars_decode($rowData['event_description']);
    
if($admin_level < 3){
  $response['status']='failed';
  $response['result']='You are not authorized to edit this event.';
  $response=json_encode($response);
  echo($response);
  exit();
}else{
  $rowData['status']='success';
  $eventdata=json_encode($rowData);
  echo($eventdata);
}
    
  }else {
     $response['status']='failed';
     $response['result']='No event found with this event id.';
     $response=json_encode($response);
     echo($response);
     exit();
  }
  
  
}
?>