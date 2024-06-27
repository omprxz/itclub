<?php
include_once('../../action/checklogin.php');

$admin_id = $_SESSION['admin_id'];
require '../../action/conn.php';

$response = array();

// Fetch specific columns from blogs
$fetchBlogs = mysqli_query($mysqli, "SELECT id, title, thumbnail, createdTime, visibility, approved, adminId, likes, views FROM blogs WHERE adminId = $admin_id");
$blogs = array();

if ($fetchBlogs && mysqli_num_rows($fetchBlogs) > 0) {
    while ($row = mysqli_fetch_assoc($fetchBlogs)) {
        $blogs[] = $row;
    }
    $response['status'] = 'success';
    $response['result'] = $blogs;
} elseif ($fetchBlogs && mysqli_num_rows($fetchBlogs) === 0) {
    $response['status'] = 'empty';
    $response['result'] = 'You don\'t have any blogs.';
} else {
    $response['status'] = 'error';
    $response['result'] = 'Error fetching blogs';
}

echo json_encode($response);
exit();
?>
