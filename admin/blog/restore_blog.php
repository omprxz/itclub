<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: ../login.php');
  exit();
}
$admin_id = $_SESSION['admin_id'];
require '../../action/conn.php';


if (isset($_POST['del_id'])) {
  $del_id = $_POST['del_id'];

  // Fetch adminId from blogs table for the given blog_id
  $fetchAdminIdQuery = "SELECT adminId FROM blogsTrash WHERE delId = $del_id";
  $resultFetchAdminId = mysqli_query($mysqli, $fetchAdminIdQuery);

  if ($resultFetchAdminId) {
    $row = mysqli_fetch_assoc($resultFetchAdminId);
    $fetchedAdminId = $row['adminId'];

    // Check if fetched adminId matches with session admin_id
    if ($fetchedAdminId == $admin_id) {
      // Copy data to blogsTrash table
      $RestoreQuery = "INSERT INTO blogs (
    id,
    title,
    content,
    thumbnail,
    tags,
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
  FROM blogsTrash
  WHERE delId = $del_id AND adminId = $admin_id";
      $resultRestore = mysqli_query($mysqli, $RestoreQuery);

      if ($resultRestore) {
        // Delete row from blogs table
        $deleteBlogQuery = "DELETE FROM blogsTrash WHERE delId = $del_id AND adminId = $admin_id";
        $resultDeleteBlog = mysqli_query($mysqli, $deleteBlogQuery);

        if ($resultDeleteBlog) {
          $response['status'] = 'success';
          $response['result'] = 'Blog restored successfully';
        } else {
          $response['status'] = 'failed';
          $response['result'] = 'Failed to restore blog';
        }
      } else {
        $response['status'] = 'failed';
        $response['result'] = 'Failed to restore blog';
      }
    } else {
      $response['status'] = 'failed';
      $response['result'] = 'You do not have permission to restore this blog.'.mysqli_error($mysqli);
    }
  } else {
    $response['status'] = 'failed';
    $response['result'] = 'Error during authentication';
  }
} else {
  $response['status'] = 'failed';
  $response['result'] = 'Delete ID not provided';
}

echo json_encode($response);
exit();

?>