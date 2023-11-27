<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: ../login.php');
  exit();
}
$admin_id = $_SESSION['admin_id'];
require '../../action/conn.php';

$response = array("status" => "failed");

$stats = mysqli_query($mysqli, "select count(*) as blogs, sum(likes) as likes, sum(views) as views from blogs where adminId = $admin_id");
if($stats){
$stats = mysqli_fetch_assoc($stats);
$blogsCount = $stats['blogs'];
$viewsCount = $stats['views'];
$likesCount = $stats['likes'];
if ($blogsCount == '') {
  $blogsCount = 0;
}
if ($viewsCount == '') {
  $viewsCount = 0;
}
if ($likesCount == '') {
  $likesCount = 0;
}

$response["status"]="success";
$response["blogsCount"] = $blogsCount;
$response["likesCount"] = $likesCount;
$response["viewsCount"] = $viewsCount;
}
echo(json_encode($response));
exit();

?>