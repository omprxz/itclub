<?php
include_once('../action/checklogin.php');
include_once('../action/conn.php');
include_once('../libs/ImgCompressor/ImgCompressor.class.php');
$response = array('status' => 'success', 'result' => 'Updated');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $admin_id = $_SESSION["admin_id"];
  $previousProfilepic = $_POST['previousProfilepic'];

  $defaultProfilepic = 'profilepic.png';

  // Handle profile picture upload
  if (isset($_FILES['profilepic'])) {
    $file = $_FILES['profilepic'];

    // File details
    $fileName = filter_var($file['name'], FILTER_SANITIZE_STRING);
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    // File extension
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Allowed file types and maximum file size (1 MB)
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'webp', 'gif');
    $maxFileSize = 1000 * 1024; // 1 MB

    if (in_array($fileExt, $allowedExtensions)) {
      if ($fileSize <= $maxFileSize) {
        $filenameWithoutExtension = pathinfo($fileName, PATHINFO_FILENAME);

//blocked keywords
if(strpos($filenameWithoutExtension,"Snapchat" != false)){
          $filenameWithoutExtension = str_replace("Snapchat", "sc", $filenameWithoutExtension);
        }

        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $uniqueFileName = $filenameWithoutExtension.'_'.time().'.'.$extension;
        $fileDestination = 'images/admins/' . $uniqueFileName;

        // Move uploaded file to destination
        if (move_uploaded_file($fileTmpName, $fileDestination)) {
          $setting = array(
            'directory' => 'images/admins',
            'file_type' => array(
              'image/jpg',
              'image/jpeg',
              'image/png',
              'image/gif'
            )
          );
          $ImgCompressor = new ImgCompressor($setting);
          $compImg = $ImgCompressor->run($fileDestination, $fileExt, 7);
          if($compImg['status']=='success'){
          // Update profile pic in the database
          $updatePicQuery = "UPDATE adminCreds SET admin_profilepic = '".$compImg['data']['compressed']['name']."' WHERE admin_id = $admin_id";
          if (!mysqli_query($mysqli, $updatePicQuery)) {
            $response['status'] = 'failed';
            $response['result'] = 'Error updating profile picture: ' . mysqli_error($mysqli);
            echo json_encode($response);
            $mysqli->close();
            exit;
          }
          }elseif($compImg['status']=='error'){
          $response['status'] = 'failed';
          $response['result'] = $compImg['message'];
          echo json_encode($response);
          $mysqli->close();
          exit;
          }else {
          $response['status'] = 'failed';
          $response['result'] = 'Error uploading file';
          echo json_encode($response);
          $mysqli->close();
          exit;
          }
        } else {
          $response['status'] = 'failed';
          $response['result'] = 'Error uploading file';
          echo json_encode($response);
          $mysqli->close();
          exit;
        }
      } else {
        $response['status'] = 'failed';
        $response['result'] = 'File size exceeds maximum limit (1 MB)';
        echo json_encode($response);
        $mysqli->close();
        exit;
      }
    } else {
      $response['status'] = 'failed';
      $response['result'] = 'Invalid file type. Supported types: jpg, jpeg, png, gif';
      echo json_encode($response);
      $mysqli->close();
      exit;
    }
  }

  // Handle email update
  if (isset($_POST['email'])) {
    $newEmail = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    // Check if the new email already exists
    $checkEmailQuery = "SELECT admin_id FROM adminCreds WHERE admin_email = '$newEmail' AND admin_id != $admin_id";
    $checkEmailResult = mysqli_query($mysqli, $checkEmailQuery);
    if ($checkEmailResult && mysqli_num_rows($checkEmailResult) > 0) {
      $response['status'] = 'failed';
      $response['result'] = 'Email already exists';
      echo json_encode($response);
      $mysqli->close();
      exit;
    }
    $updateEmailQuery = "UPDATE adminCreds SET admin_email = '$newEmail' WHERE admin_id = $admin_id";
    if (!mysqli_query($mysqli, $updateEmailQuery)) {
      $response['status'] = 'failed';
      $response['result'] = 'Error updating email: ' . mysqli_error($mysqli);
      echo json_encode($response);
      $mysqli->close();
      exit;
    }
  }

  // Handle username update
  if (isset($_POST['username'])) {
    $newUsername = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    // Check if the new username already exists
    $checkUsernameQuery = "SELECT admin_id FROM adminCreds WHERE admin_username = '$newUsername' AND admin_id != $admin_id";
    $checkUsernameResult = mysqli_query($mysqli, $checkUsernameQuery);
    if ($checkUsernameResult && mysqli_num_rows($checkUsernameResult) > 0) {
      $response['status'] = 'failed';
      $response['result'] = 'Username already exists';
      echo json_encode($response);
      $mysqli->close();
      exit;
    }
    $updateUsernameQuery = "UPDATE adminCreds SET admin_username = '$newUsername' WHERE admin_id = $admin_id";
    if (!mysqli_query($mysqli, $updateUsernameQuery)) {
      $response['status'] = 'failed';
      $response['result'] = 'Error updating username: ' . mysqli_error($mysqli);
      echo json_encode($response);
      $mysqli->close();
      exit;
    }
  }

  // Handle name update
  if (isset($_POST['name'])) {
    $newName = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $updateNameQuery = "UPDATE adminCreds SET admin_name = '$newName' WHERE admin_id = $admin_id";
    if (!mysqli_query($mysqli, $updateNameQuery)) {
      $response['status'] = 'failed';
      $response['result'] = 'Error updating name: ' . mysqli_error($mysqli);
      echo json_encode($response);
      $mysqli->close();
      exit;
    }
  }

  // Handle designation update
  if (isset($_POST['designation'])) {
    $newDesignation = filter_var($_POST['designation'], FILTER_SANITIZE_STRING);
    $updateDesignationQuery = "UPDATE adminCreds SET admin_designation = '$newDesignation' WHERE admin_id = $admin_id";
    if (!mysqli_query($mysqli, $updateDesignationQuery)) {
      $response['status'] = 'failed';
      $response['result'] = 'Error updating designation: ' . mysqli_error($mysqli);
      echo json_encode($response);
      $mysqli->close();
      exit;
    }
  }

  // Handle bio update
  if (isset($_POST['bio'])) {
    $newBio = filter_var($_POST['bio'], FILTER_SANITIZE_STRING);
    $updateBioQuery = "UPDATE adminCreds SET admin_bio = '$newBio' WHERE admin_id = $admin_id";
    if (!mysqli_query($mysqli, $updateBioQuery)) {
      $response['status'] = 'failed';
      $response['result'] = 'Error updating bio: ' . mysqli_error($mysqli);
      echo json_encode($response);
      $mysqli->close();
      exit;
    }
  }

} else {
  $response['status'] = 'failed';
  $response['result'] = 'Invalid request method';
  echo json_encode($response);
  $mysqli->close();
  exit;
}

echo json_encode($response);

$mysqli->close();
exit;
?>