<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: login.php');
  exit();
}
$err = 0;
$response = array();
require('../../action/conn.php');
$admin_id = $_SESSION["admin_id"];
$query = "SELECT admin_level FROM adminCreds WHERE admin_id = $admin_id";

$result = $mysqli->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $admin_level = $row['admin_level'];
    $result->free_result();
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
  $id=$_POST['id'];
  
  $admId = mysqli_query($mysqli,"select adminId from blogs where id = $id");
  if(mysqli_num_rows($admId)>0){
    $admId=mysqli_fetch_assoc($admId)['adminId'];
  }else{
    $response['status']='failed';
  $response['result']='Blog not available.';
  $response=json_encode($response);
  echo($response);
      exit();
  }
  if($admId == $admin_id || $admin_level >= 6){
  
  $title = sanitizeInput($_POST['title'], $mysqli);
  $content = sanitizeInput($_POST['content'], $mysqli);
  $tags = sanitizeInput($_POST['tags'], $mysqli);
  
  $pubTime=$_POST['pubTime'];
  $sameThumbnail=$_POST['sameThumbnail'];
  $thumbnailUrl=$_POST['thumbnailUrl'];
  $titleFetched=$_POST['titleFetched'];
  $uniqid=substr($titleFetched,-13);

    $visibility = sanitizeInput($_POST['visibility'], $mysqli);

  if ($visibility == 'schedule') {
    if ($_POST['sTime']) {
      $userDatetime = $_POST['sTime'];
    } else {
      $userDatetime = $pubTime;
    }
    $userTimezone = 'Asia/Kolkata';
    $userDateTimeObj = new DateTime($userDatetime, new DateTimeZone($userTimezone));
    $userDateTimeObj->setTimezone(new DateTimeZone($userTimezone));
    $convertedDateTime = $userDateTimeObj->format('Y-m-d H:i:s');
    $publishTime = $convertedDateTime;
  } else {
    $publishTime = $pubTime;
  }

  //THUMBNAIL STARTS HERE
  if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK && $sameThumbnail == 'no') {
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
    $thumbnail = $thumbnailUrl;
  }
  //THUMBNAIL ENDS HERE

  $url = strtolower(str_replace(' ', '-', $title));

  $url .= '_'.$uniqid;


  if ($err == 0) {
    $sql = "UPDATE blogs SET 
    title = '$title',
    content = '$content',
    thumbnail = '$thumbnail',
    tags = '$tags',
    visibility = '$visibility',
    publishTime = '$publishTime',
    url = '$url'
    WHERE id = $id";
    if (mysqli_query($mysqli, $sql)) {
       $response['status']='success';
       
       if ($visibility=='schedule') {
        $response['result']='Blog updated. Thanks.';
       }else{
         $response['result']='Blog updated. Thanks.';
       }
  $response=json_encode($response);
  echo($response);
    } else {
       $response['status']='failed';
  $response['result']='Error while creating blog.'.mysqli_error($mysqli);
  $response=json_encode($response);
  echo($response);
    }

  } else {
     $response['status']='failed';
  $response['result']='Something went wromg.';
  $response=json_encode($response);
  echo($response);
    }
}else{
   $response['status']='failed';
  $response['result']='You are not authorized to edit this blog.';
  $response=json_encode($response);
  echo($response);
  exit();
}
}else {
   $response['status']='failed';
  $response['result']='Method not allowed.';
  $response=json_encode($response);
  echo($response);
}

?>