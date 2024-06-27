<?php
include '../../action/conn.php';

if(isset($_GET['blogId']) && isset($_GET['featVal'])){
    $blogId = $_GET['blogId'];
    $featVal = $_GET['featVal'];

    if(is_numeric($featVal) && $featVal >= -99999 && $featVal <= 99999){
        // Sanitize inputs to prevent SQL injection
        $blogId = mysqli_real_escape_string($mysqli, $blogId);
        $featVal = mysqli_real_escape_string($mysqli, $featVal);

        // Update the 'featured' column in the 'blogs' table
        $query = "UPDATE blogs SET featured = $featVal WHERE id = $blogId";

        if(mysqli_query($mysqli, $query)){
            $response = array('status' => 'success', 'message' => 'Featured value updated successfully', 'updatedTo' => $featVal);
            echo json_encode($response);
            exit;
        } else {
            $response = array('status' => 'error', 'message' => 'Error updating featured value');
            echo json_encode($response);
            exit;
        }

    } else {
        $response = array('status' => 'error', 'message' => 'Value must be between -99999 and 99999');
        echo json_encode($response);
        exit;
    }
} else {
    $response = array('status' => 'error', 'message' => 'Invalid parameters');
    echo json_encode($response);
    exit;
}
?>