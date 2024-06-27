<?php
include('conn.php');
$response = [];
$userIP = $_SERVER['REMOTE_ADDR'];

$action = $_POST['action'];
$prompt = mysqli_real_escape_string($mysqli, $_POST['prompt']);

if($action=="newchat"){
  $createChat = mysqli_query($mysqli, "insert into askAiChats (chat_ip) values ('$userIP')");
  if($createChat){
    $chatid = mysqli_insert_id($mysqli);
    $insertMsg = mysqli_query($mysqli, "insert into askAiMsgs (msg_prompt, msg_ip, chat_id) values ('$prompt',  '$userIP','$chatid')");
    if($insertMsg){
       $response['status'] = 'success';
  $response['chatid'] = $chatid;
  echo(json_encode($response));
  exit();
    }else{
       $response['status'] = 'error';
  $response['message'] = 'Error saving chat';
  echo(json_encode($response));
  exit();
    }
  }else{
    $response['status'] = 'error';
  $response['message'] = 'Error creating chat';
  echo(json_encode($response));
  exit();
  }
  
}elseif($action=="oldchat"){
  $chatid = $_POST['chatid'];
  $insertMsg = mysqli_query($mysqli, "insert into askAiMsgs (msg_prompt, msg_ip, chat_id) values ('$prompt', '$userIP', '$chatid')");
   if($insertMsg){
       $response['status'] = 'success';
  $response['chatid'] = $chatid;
  echo(json_encode($response));
  exit();
    }else{
       $response['status'] = 'error';
  $response['message'] = 'Error saving chat';
  echo(json_encode($response));
  exit();
    }
}else{
  $response['status'] = 'error';
  $response['message'] = 'Invalid action';
  echo(json_encode($response));
  exit();
}

?>