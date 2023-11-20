<?php
require('../../action/conn.php');
date_default_timezone_set('Asia/Kolkata');
$current_time=date("Y-m-d H:i:s");

$sql="select id,visibility,publishTime from blogs where publishTime <= '$current_time' AND visibility = 'schedule'";
$eSql= mysqli_query($mysqli,$sql);
if(mysqli_num_rows($eSql)>0){
  while($row=mysqli_fetch_assoc($eSql)){
    $id=$row['id'];
    $sql2="update blogs set visibility = 'public',publishTime = '$current_time' where id = $id";
    if($eSql2 = mysqli_query($mysqli,$sql2)){
      echo('Published.');
    }else{
      echo("Cannot update visibility.");
    }
  }
}else {
  echo("Error: ".mysqli_error($mysqli));
}


?>