<?php
echo(777);
if(isset($_POST['action'])){
  require('../action/conn.php');
  $action = $_POST['action'];
  $id = $_POST['id'];
  if($action == 'like'){
    $likeSql = mysqli_query($mysqli,"update blogs set likes = likes + 1 where id = '$id'");
  }elseif ($action == 'unlike') {
    $unlikeSql = mysqli_query($mysqli,"update blogs set likes = likes - 1 where id = '$id'");
  }
}else{
  echo('What\'sup buddy, Go Home.');
}

?>