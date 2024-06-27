<?php
include_once('../../action/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blogId = $_POST['blogId'];
    $status = $_POST['status'];

    $response = array();

    try {
         $updateQuery = "UPDATE blogs SET approved = ".($status === 'approve' ? 1 : -1)." WHERE id = $blogId";
         mysqli_query($mysqli, $updateQuery);

        $response['status'] = 'success';
        $response['result'] = $status==='approve'? 'Approved' : 'Rejected';
    } catch (Exception $e) {
        $response['status'] = 'failed';
        $response['result'] = 'Error: ' . $e->getMessage();
    }

    echo json_encode($response);
    exit;
} else {
    http_response_code(405);
    $response['status'] = 'failed';
    $response['result'] = 'Invalid request method!';
    echo json_encode($response);
    exit;
}
?>