<?php
include_once('../../action/checklogin.php');

require '../../action/conn.php';
$response = array();
$admin_id = $_SESSION['admin_id'];

$admin_level = mysqli_query($mysqli,"select admin_level from adminCreds where admin_id = $admin_id");
if($admin_level){
  $admin_level=mysqli_fetch_assoc($admin_level)['admin_level'];
}else{
  $response['status']='failed';
  $response['result']='Can\'t authenticate!';
  echo(json_encode($response));
  exit;
}

if (isset($_POST['noticeid'])) {
  if($admin_level>=3){
  $notice_id = $_POST['noticeid'];
  $copyToTrash = mysqli_query($mysqli,"insert into noticesTrash select * from notices where notice_id = $notice_id");
  if($copyToTrash){
  // Delete row from notices table
  $deleteNoticeQuery = "DELETE FROM notices WHERE notice_id = $notice_id";
  $resultDeleteNotice = mysqli_query($mysqli, $deleteNoticeQuery);

  if ($resultDeleteNotice) {
    $response['status'] = 'success';
    $response['result'] = 'Notice deleted successfully';
echo json_encode($response);
exit();
  } else {
    $response['status'] = 'failed';
    $response['result'] = 'Failed to delete notice';
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
  $response['result'] = 'Notice ID not provided';
echo json_encode($response);
exit();
}

echo json_encode($response);
exit();
?>