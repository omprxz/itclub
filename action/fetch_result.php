<?php
header('Content-Type: application/json');
if (isset($_GET['email'])) {
require 'conn.php';
$email=$_GET['email'];
$sql="select * from joinrequests where email = '$email'";
$qsql=mysqli_query($mysqli,$sql);
if(mysqli_num_rows($qsql)>0){
    $res = array('status'=>'success','response'=>mysqli_fetch_assoc($qsql));
    $json_res = json_encode($res);
    echo $json_res;
}else{
    $res = array('status'=>'error','response'=>'Students not found!');
    $json_res = json_encode($res);
    echo $json_res;
}
}else{
    echo "Something went wrong";
}
?>