<?php
include_once('../../action/checklogin.php');

require '../../action/conn.php';
include_once('../../libs/ImgCompressor/ImgCompressor.class.php');
$admin_id = $_SESSION["admin_id"];
$query = "SELECT admin_level FROM adminCreds WHERE admin_id = $admin_id";
$result = $mysqli->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    $admin_level = $row['admin_level'];
    $result->free_result();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 if ($admin_level >= 3) {
       $id = $_POST['id'];
       
  if (isset($_POST['title'])) {
    $title = htmlspecialchars($_POST['title']);
    $updateQuery = 'UPDATE notices SET notice_title = "'.$title.'" WHERE notice_id = '.$id;
    if (!mysqli_query($mysqli, $updateQuery)) {
      $response['status'] = 'failed';
      $response['result'] = 'Error updating title: ' . mysqli_error($mysqli);
      echo json_encode($response);
      $mysqli->close();
      exit;
    }
  }
        
  if (isset($_POST['notice_description'])) {
    $notice_desc = htmlspecialchars($_POST['notice_description']);
    $updateQuery = 'UPDATE notices SET notice_content= "'.$notice_desc.'" WHERE notice_id = '.$id;
    if (!mysqli_query($mysqli, $updateQuery)) {
      $response['status'] = 'failed';
      $response['result'] = 'Error updating Description: ' . mysqli_error($mysqli);
      echo json_encode($response);
      $mysqli->close();
      exit;
    }
  }
        
  if (isset($_FILES["notice_image"]) && $_FILES["notice_image"]["error"] == UPLOAD_ERR_OK) {
            $targetDirectory = "../../img/notices/";
            $targetFile = $targetDirectory . pathinfo($_FILES["notice_image"]["name"],PATHINFO_FILENAME).'_'.time().'.'.pathinfo($_FILES["notice_image"]["name"],PATHINFO_EXTENSION);


            $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $uploadedMimeType = mime_content_type($_FILES["notice_image"]["tmp_name"]);

            if (in_array($uploadedMimeType, $allowedMimeTypes) && $_FILES["notice_image"]["size"] <= 2 * 1024 * 1024) {
                if (move_uploaded_file($_FILES["notice_image"]["tmp_name"], $targetFile)) {
                  
                  
                    $setting = array(
            'directory' => '../../img/notices',
            'file_type' => array(
              'image/jpg',
              'image/jpeg',
              'image/png',
              'image/gif'
            )
          );
          $ImgCompressor = new ImgCompressor($setting);
          $extension = pathinfo($targetFile, PATHINFO_EXTENSION);
          $compImg = $ImgCompressor->run($targetFile, $extension, 5);
          if ($compImg['status'] == 'success') {
            
            $imageURL = $compImg['data']['compressed']['name'];
                    $imageURL = $mysqli->real_escape_string($imageURL);
                    
                    $updateQuery = 'UPDATE notices SET notice_imgurl = "'.$imageURL.'" WHERE notice_id = '.$id;
    if (!mysqli_query($mysqli, $updateQuery)) {
      $response['status'] = 'failed';
      $response['result'] = 'Error updating Image: ' . mysqli_error($mysqli);
      echo json_encode($response);
      $mysqli->close();
      exit;
    }
            
          } elseif ($compImg['status'] == 'error') {
            $response['status'] = 'failed';
            $response['result'] = $compImg['message'];
            echo json_encode($response);
            $mysqli->close();
            exit;
          } else {
            $response['status'] = 'failed';
            $response['result'] = 'Error uploading file';
            echo json_encode($response);
            $mysqli->close();
            exit;
          }
                } else {
                    echo json_encode(array('status' => 'failed', 'result' => 'Error uploading the image.'));
                    exit();
                }
            } else {
                echo json_encode(array('status' => 'failed', 'result' => 'Invalid file type or file size exceeds 2MB. Only JPG, JPEG, PNG, or GIF images up to 2MB are allowed.'));
                exit();
            }
        }
  
      $response['status'] = 'success';
      $response['result'] = 'Notice updated successfully';
      echo json_encode($response);
      $mysqli->close();
      exit;

    } else {
        echo json_encode(array('status' => 'failed', 'result' => 'You are not allowed to create notices.'));
    }
    $mysqli->close();
} else {
    echo json_encode(array('status' => 'failed', 'result' => 'Method not allowed.'));
}
?>