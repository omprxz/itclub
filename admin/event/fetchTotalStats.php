<?php
include_once('../../action/checklogin.php');
$admin_id = $_SESSION['admin_id'];
require '../../action/conn.php';

$response = array("status" => "failed");

$stats = mysqli_query($mysqli, "select count(*) as events from events");
if($stats){
$stats = mysqli_fetch_assoc($stats);
$eventsCount = $stats['events'];
if ($eventsCount == '') {
  $eventsCount = 0;
}

$response["status"]="success";
$response["eventsCount"] = $eventsCount;
}
echo(json_encode($response));
exit();

?>