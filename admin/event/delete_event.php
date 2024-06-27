<?php
include_once('../../action/checklogin.php');

$response = array();
$admin_id = $_SESSION['admin_id'];
require '../../action/conn.php';

$admin_level = mysqli_query($mysqli,"select admin_level from adminCreds where admin_id = $admin_id");
if($admin_level){
  $admin_level=mysqli_fetch_assoc($admin_level)['admin_level'];
}else{
  $response['status']='failed';
  $response['result']='Can\'t authenticate!';
  echo(json_encode($response));
  exit;
}

if (isset($_POST['eventid'])) {
  if($admin_level>=3){
  $event_id = $_POST['eventid'];
  $copyToTrash = mysqli_query($mysqli,"insert into eventsTrash select * from events where event_id = $event_id");
  if($copyToTrash){
  // Delete row from events table
  $deleteEventQuery = "DELETE FROM events WHERE event_id = $event_id";
  $resultDeleteEvent = mysqli_query($mysqli, $deleteEventQuery);

  if ($resultDeleteEvent) {
    $response['status'] = 'success';
    $response['result'] = 'Event deleted successfully';
echo json_encode($response);
exit();
  } else {
    $response['status'] = 'failed';
    $response['result'] = 'Failed to delete event';
echo json_encode($response);
exit();
  }
  }else{
    $response['status'] = 'failed';
    $response['result'] = 'Can\'t move to trash.';
echo json_encode($response);
exit();
  }
  }else{
     $response['status'] = 'failed';
    $response['result'] = 'You are not authorized to delete notices.';
echo json_encode($response);
exit();
  }
} else {
  $response['status'] = 'failed';
  $response['result'] = 'Event ID not provided';
echo json_encode($response);
exit();
  
}

echo json_encode($response);
exit();
?>