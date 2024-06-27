<?php
date_default_timezone_set('Asia/Kolkata');

include_once('../action/checklogin.php');
require_once '../action/conn.php';
$admin_id=$_SESSION["admin_id"];
$query = "SELECT admin_level FROM adminCreds WHERE admin_id = $admin_id";
$result = $mysqli->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    $admin_level = $row['admin_level'];
    $result->free_result();
}

$response = array(
    'status' => '',
    'message' => ''
);
if($admin_level >= 7){
if(isset($_POST['admin_id']) && isset($_POST['new_admin_level']) && $_POST['action'] == 'changeLevel') {
    $admin_id = mysqli_real_escape_string($mysqli, $_POST['admin_id']);
    $new_admin_level = mysqli_real_escape_string($mysqli, $_POST['new_admin_level']);

    $query = "UPDATE adminCreds SET admin_level = '$new_admin_level' WHERE admin_ID = '$admin_id'";
    $result = mysqli_query($mysqli, $query);

    if($result) {
        $response['status'] = 'success';
        $response['message'] = 'Admin level updated successfully!';
        echo json_encode($response);
        exit;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error updating admin level: ' . mysqli_error($mysqli);
        echo json_encode($response);
        exit;
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid action or Admin ID or new admin level not provided.';
    echo json_encode($response);
    exit;
}
}else{
  $response['status'] = 'error';
    $response['message'] = 'This action is not permitted on your account.';
    echo json_encode($response);
    exit;
}

?>