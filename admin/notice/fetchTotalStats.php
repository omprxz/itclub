<?php
include_once('../../action/checklogin.php');
$admin_id = $_SESSION['admin_id'];
require '../../action/conn.php';

$response = array("status" => "failed");

$stats = mysqli_query($mysqli, "select count(*) as notices from notices");
if($stats){
$stats = mysqli_fetch_assoc($stats);
$noticesCount = $stats['notices'];
if ($noticesCount == '') {
  $noticesCount = 0;
}

$response["status"]="success";
$response["noticesCount"] = $noticesCount;
}
echo(json_encode($response));
exit();

?>