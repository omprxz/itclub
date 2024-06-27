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
      $updateQuery = 'UPDATE events SET event_title = "'.$title.'" WHERE event_id = '.$id;
      if (!mysqli_query($mysqli, $updateQuery)) {
        $response['status'] = 'failed';
        $response['result'] = 'Error updating title: ' . mysqli_error($mysqli);
        echo json_encode($response);
        $mysqli->close();
        exit;
      }
    }

    if (isset($_POST['date'])) {
      $date = htmlspecialchars($_POST['date']);
      $updateQuery = 'UPDATE events SET event_date = "'.$date.'" WHERE event_id = '.$id;
      if (!mysqli_query($mysqli, $updateQuery)) {
        $response['status'] = 'failed';
        $response['result'] = 'Error updating date: ' . mysqli_error($mysqli);
        echo json_encode($response);
        $mysqli->close();
        exit;
      }
    }

    if (isset($_POST['gphotos'])) {
      $gphotos = htmlspecialchars($_POST['gphotos']);
      $updateQuery = 'UPDATE events SET event_gphotoslink = "'.$gphotos.'" WHERE event_id = '.$id;
      if (!mysqli_query($mysqli, $updateQuery)) {
        $response['status'] = 'failed';
        $response['result'] = 'Error updating Google photos link: ' . mysqli_error($mysqli);
        echo json_encode($response);
        $mysqli->close();
        exit;
      }
    }

    if (isset($_POST['yt'])) {
      $yt = htmlspecialchars($_POST['yt']);
      $updateQuery = 'UPDATE events SET event_ytlink = "'.$yt.'" WHERE event_id = '.$id;
      if (!mysqli_query($mysqli, $updateQuery)) {
        $response['status'] = 'failed';
        $response['result'] = 'Error updating YouTube link: ' . mysqli_error($mysqli);
        echo json_encode($response);
        $mysqli->close();
        exit;
      }
    }

    if (isset($_POST['event_description'])) {
      $event_desc = htmlspecialchars($_POST['event_description']);
      $updateQuery = 'UPDATE events SET event_description= "'.$event_desc.'" WHERE event_id = '.$id;
      if (!mysqli_query($mysqli, $updateQuery)) {
        $response['status'] = 'failed';
        $response['result'] = 'Error updating Description: ' . mysqli_error($mysqli);
        echo json_encode($response);
        $mysqli->close();
        exit;
      }
    }

    if (isset($_FILES["event_image"]) && $_FILES["event_image"]["error"] == UPLOAD_ERR_OK) {
      $targetDirectory = "../../img/events/";
      $targetFile = $targetDirectory . pathinfo($_FILES["event_image"]["name"],PATHINFO_FILENAME).'_'.time().'.'.pathinfo($_FILES["event_image"]["name"],PATHINFO_EXTENSION);

      $allowedMimeTypes = ['image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif'];
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
          $extension = pathinfo($targetFile, PATHINFO_EXTENSION);
          $compImg = $ImgCompressor->run($targetFile, $extension, 5);
          if ($compImg['status'] == 'success') {
            $imageURL = basename($targetFile);
            $imageURL = $mysqli->real_escape_string($imageURL);

            $updateQuery = 'UPDATE events SET event_imgurl = "'.$imageURL.'" WHERE event_id = '.$id;
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
    $response['result'] = 'Event updated successfully';
    echo json_encode($response);
    $mysqli->close();
    exit;

  } else {
    echo json_encode(array('status' => 'failed', 'result' => 'You are not allowed to create events.'));
  }
  $mysqli->close();
} else {
  echo json_encode(array('status' => 'failed', 'result' => 'Method not allowed.'));
}
?>