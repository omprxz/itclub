<?php
if(isset($_GET['subid'])){
  require("../../action/conn.php");
  $subid=$_GET['subid'];
  $getSubid = mysqli_query($mysqli, "select * from subscriptions where id = $subid");
  if(mysqli_num_rows($getSubid)>0){
    while($row = mysqli_fetch_assoc($getSubid)){
      $subDet[] = $row;
    }
    if($subDet[0]['subscribed']==1){
    $unSub = mysqli_query($mysqli, "update subscriptions set subscribed = 0 where id = $subid");
    if($unSub){
      echo("Thank you ".$subDet[0]['name'].".<br>");
      echo("Unsubscribed successfully.");
    }
    }else{
      echo("Already Unsubscribed!");
    }
  }else{
    echo("Subscriber ID Invalid.");
  }
}else{
  echo("Subscriber ID not mentioned.");
}

?>