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
        $event_title = $mysqli->real_escape_string($_POST["event_title"]);
        $event_description = isset($_POST['event_description']) ? $mysqli->real_escape_string($_POST["event_description"]) : null;
        $event_date = isset($_POST['event_date']) ? $_POST["event_date"] : null;
        $event_gphotoslink = isset($_POST['event_gphotoslink']) ? $mysqli->real_escape_string($_POST["event_gphotoslink"]) : '';
        $event_ytlink = isset($_POST['event_ytlink']) ? $mysqli->real_escape_string($_POST["event_ytlink"]) : '';
        
        if (isset($_FILES["event_image"]) && $_FILES["event_image"]["error"] == UPLOAD_ERR_OK) {
            $targetDirectory = "../../img/events/";
            $targetFile = $targetDirectory . pathinfo($_FILES["event_image"]["name"],PATHINFO_FILENAME).'_'.time().'.'.pathinfo($_FILES["event_image"]["name"],PATHINFO_EXTENSION);

            $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $uploadedMimeType = mime_content_type($_FILES["event_image"]["tmp_name"]);

            if (in_array($uploadedMimeType, $allowedMimeTypes) && $_FILES["event_image"]["size"] <= 2 * 1024 * 1024) {
                if (move_uploaded_file($_FILES["event_image"]["tmp_name"], $targetFile)) {
                  
     $setting = array(
            'directory' => '../../img/events',
            'file_type' => array(
              'image/jpg',
              'image/jpeg',
              'image/png',
              'image/gif'
            )
          );
      $ImgCompressor = new ImgCompressor($setting);
      $extension=pathinfo($targetFile, PATHINFO_EXTENSION);
      $compImg = $ImgCompressor->run($targetFile, $extension, 5);
      if($compImg['status']=='success'){
        
        $imageURL = $compImg['data']['compressed']['name'];
                    $imageURL = $mysqli->real_escape_string($imageURL);
        
      }elseif($compImg['status']=='error'){
          $response['status'] = 'failed';
          $response['result'] = $compImg['message'];
          echo json_encode($response);
          $mysqli->close();
          exit;
      }else{
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
        } else {
            $imageURL = '';
        }

        $sql = "INSERT INTO events (event_title, event_description, event_date, event_imgurl, event_gphotoslink, query_admin_id, event_ytlink) VALUES ('$event_title', '$event_description', '$event_date', '$imageURL', '$event_gphotoslink', '$admin_id', '$event_ytlink')";

        if (mysqli_query($mysqli, $sql)) {
          $event_id = mysqli_insert_id($mysqli);
            echo json_encode(array('status' => 'success', 'result' => 'Event created successfully!', 'event_id'=> $event_id));
        } else {
            echo json_encode(array('status' => 'failed', 'result' => 'Error in creating event: ' . mysqli_error($mysqli)));
        }
    } else {
        echo json_encode(array('status' => 'failed', 'result' => 'You are not allowed to create events.'));
    }
    $mysqli->close();
} else {
    echo json_encode(array('status' => 'failed', 'result' => 'Method not allowed.'));
}
?>