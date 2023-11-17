<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: ../');
  exit();
}

$err = 0;
$response = array();

$admin_id = $_SESSION["admin_id"];
require('../../action/conn.php');


$st = "select admin_level from adminCreds where admin_id = '$admin_id'";
$result = mysqli_query($mysqli, $st);
if ($result) {
  $row = mysqli_fetch_assoc($result);
  $admin_level = $row["admin_level"];
  mysqli_free_result($result);
}
if($admin_level >= 7){
  $approved = 1;
}else{
  $approved = 0;
}


function sanitizeInput($input, $conn) {
  if (is_array($input)) {
    foreach ($input as $key => $value) {
      $input[$key] = sanitizeInput($value, $conn);
    }
  } else {
    $input = trim($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    $input = mysqli_real_escape_string($conn, $input);
  }
  return $input;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $title = sanitizeInput($_POST['title'], $mysqli);
  $content = sanitizeInput($_POST['content'], $mysqli);
  $tags = sanitizeInput($_POST['tags'], $mysqli);
  date_default_timezone_set('Asia/Kolkata');
  $created_time = date('Y-m-d H:i:s');

    $visibility = sanitizeInput($_POST['visibility'], $mysqli);


  if ($visibility == 'schedule') {
    if ($_POST['sTime']) {
      $userDatetime = $_POST['sTime'];
    } else {
      $userDatetime = 'now';
    }
    $userTimezone = 'Asia/Kolkata';
    $userDateTimeObj = new DateTime($userDatetime, new DateTimeZone($userTimezone));
    $userDateTimeObj->setTimezone(new DateTimeZone($userTimezone));
    $convertedDateTime = $userDateTimeObj->format('Y-m-d H:i:s');
    $publishTime = $convertedDateTime;
  } else {
    $currentTime = new DateTime('now', new DateTimeZone('UTC'));
    $currentTime->setTimezone(new DateTimeZone('Asia/Kolkata'));
    $datetime = $currentTime->format('Y-m-d H:i:s');
    $publishTime = $datetime;
  }

  //THUMBNAIL STARTS HERE
  if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
    $thumbnail = $_FILES['thumbnail'];
    $file_name = $thumbnail['name'];
    $file_size = $thumbnail['size'];
    $file_tmp = $thumbnail['tmp_name'];
    $file_type = $thumbnail['type'];

    $max_file_size = 1 * 1024 * 1024;
    if ($file_size > $max_file_size) {
      $err = 1;
      $response['status']='failed';
  $response['result']='Image file too large. (Max size 1 MB)';
  $response=json_encode($response);
  echo($response);
      exit();
    }

    $allowed_types = ['image/jpeg',
      'image/png',
      'image/gif',
      'image/webp',
      'image/jpg'];
    if (!in_array($file_type, $allowed_types)) {
      $err = 1;
       $response['status']='failed';
  $response['result']='Invalid file type. Please upload a JPG, JPEG, WEBP, PNG, or GIF image.';
  $response=json_encode($response);
  echo($response);
      exit();
    }

    $upload_dir = '../../blogs/thumbnails/';

    $upload_file = $upload_dir . $file_name;
    $extension = pathinfo($upload_file, PATHINFO_EXTENSION);
    $base_name = pathinfo($upload_file, PATHINFO_FILENAME);
    $counter = 1;
    while (file_exists($upload_file)) {
      $upload_file = $upload_dir . $base_name . '_' . $counter . '.' . $extension;
      $counter++;
    }

    if (move_uploaded_file($file_tmp, $upload_file)) {
      $thumbnail = basename($upload_file);
    } else {
      $err = 1;
       $response['status']='failed';
  $response['result']='Error uploading the thumbnail.';
  $response=json_encode($response);
  echo($response);
    }
  } else {
    $thumbnail = "thumbnail.png";
  }
  //THUMBNAIL ENDS HERE

  $url = strtolower(str_replace(' ', '-', $title));

  $url = uniqid($url."_");


  if ($err == 0) {
    $sql = "insert into blogs (title,content,thumbnail,tags,visibility,publishTime,url,adminId,createdTime,approved) values('$title','$content','$thumbnail','$tags','$visibility','$publishTime','$url',$admin_id,'$created_time',$approved)";
    if (mysqli_query($mysqli, $sql)) {
       $response['status']='success';
      
      if($approved==1){
       if($visibility=='public'){
        $response['result']='Blog published. Thanks.';
       }elseif ($visibility=='schedule') {
        $response['result']='Blog has been scheduled. Thanks.';
       }else{
         $response['result']='Blog drafted. Thanks.';
       }
      }elseif($approved==0){
       if($visibility=='public'){
        $response['result']='Blog will be published after review. Thanks.';
       }elseif ($visibility=='schedule') {
        $response['result']='Blog has been scheduled and will be published after review. Thanks.';
       }else{
         $response['result']='Blog drafted. Thanks.';
       }
      }
  $response=json_encode($response);
  echo($response);
    } else {
       $response['status']='failed';
  $response['result']='Error while creating blog.';
  $response=json_encode($response);
  echo($response);
    }

  } else {
     $response['status']='failed';
  $response['result']='Something went wromg.';
  $response=json_encode($response);
  echo($response);
    }
} else {
   $response['status']='failed';
  $response['result']='Method not allowed.';
  $response=json_encode($response);
  echo($response);
}

?>