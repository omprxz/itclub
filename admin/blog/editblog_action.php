<?php
include_once('../../action/checklogin.php');
$err = 0;
$response = array();
require('../../action/conn.php');
include_once('../../libs/ImgCompressor/');
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
  if($admId == $admin_id || $admin_level >= 3){
  
  $title = sanitizeInput($_POST['title'], $mysqli);
  $metaDesc = sanitizeInput($_POST['metaDesc'],$mysqli);
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

    $upload_dir = '../../blogs/thumbnails';

    $extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $base_name = pathinfo($file_name, PATHINFO_FILENAME);
    $upload_file = $upload_dir .'/'. $base_name . '_' . time() . '.' . $extension;

    if (move_uploaded_file($file_tmp, $upload_file)) {
       $setting = array(
            'directory' => $upload_dir,
            'file_type' => array(
              'image/jpg',
              'image/jpeg',
              'image/png',
              'image/gif'
            )
          );
      $ImgCompressor = new ImgCompressor($setting);
      $compImg = $ImgCompressor->run($upload_file, $extension, 4);
      if($compImg['status']=='success'){
      $thumbnail = basename($upload_file);
      }elseif($compImg['status']=='error'){
        $err=1;
          $response['status'] = 'failed';
          $response['result'] = $compImg['message'];
          echo json_encode($response);
          $mysqli->close();
          exit;
      }else{
        $err=1;
          $response['status'] = 'failed';
          $response['result'] = 'Error uploading file';
          echo json_encode($response);
          $mysqli->close();
          exit;
          }
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
    metaDesc = '$metaDesc',
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