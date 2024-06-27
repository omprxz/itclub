<?php
include_once('../../action/checklogin.php');

$response = array();
$admin_id = $_SESSION['admin_id'];
require '../../action/conn.php';

if (isset($_POST['blogid'])) {
  $blog_id = $_POST['blogid'];

  // Fetch adminId from blogs table for the given blog_id
  $fetchAdminIdQuery = "SELECT adminId FROM blogs WHERE id = $blog_id";
  $resultFetchAdminId = mysqli_query($mysqli, $fetchAdminIdQuery);

  if ($resultFetchAdminId) {
    $row = mysqli_fetch_assoc($resultFetchAdminId);
    $fetchedAdminId = $row['adminId'];

    // Check if fetched adminId matches with session admin_id
    if ($fetchedAdminId == $admin_id) {
      // Copy data to blogsTrash table
      $copyToTrashQuery = "INSERT INTO blogsTrash (
    id,
    title,
    content,
    thumbnail,
    tags,
    metaDesc,
    visibility,
    approved,
    publishTime,
    createdTime,
    lastUpdate,
    url,
    adminId,
    views,
    likes,
    featured
  ) SELECT
    id,
    title,
    content,
    thumbnail,
    tags,
    metaDesc,
    visibility,
    approved,
    publishTime,
    createdTime,
    lastUpdate,
    url,
    adminId,
    views,
    likes,
    featured
  FROM blogs
  WHERE id = $blog_id AND adminId = $admin_id";
      $resultCopyToTrash = mysqli_query($mysqli, $copyToTrashQuery);

      if ($resultCopyToTrash) {
        // Delete row from blogs table
        $deleteBlogQuery = "DELETE FROM blogs WHERE id = $blog_id AND adminId = $admin_id";
        $resultDeleteBlog = mysqli_query($mysqli, $deleteBlogQuery);

        if ($resultDeleteBlog) {
          $response['status'] = 'success';
          $response['result'] = 'Blog deleted successfully';
        } else {
          $response['status'] = 'failed';
          $response['result'] = 'Failed to delete blog';
        }
      } else {
        $response['status'] = 'failed';
        $response['result'] = 'Failed to move blog to trash';
      }
    } else {
      $response['status'] = 'failed';
      $response['result'] = 'You do not have permission to delete this blog.';
    }
  } else {
    $response['status'] = 'failed';
    $response['result'] = 'Error during authentication.';
  }
} else {
  $response['status'] = 'failed';
  $response['result'] = 'Blog ID not provided';
}

echo json_encode($response);
exit();
?>