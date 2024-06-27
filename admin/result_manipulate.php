<?php
include_once('../action/checklogin.php');
if (isset($_POST['passBtn'])) {
    require '../action/conn.php';
    $admin_id=$_SESSION["admin_id"];
$query = "SELECT admin_level FROM adminCreds WHERE admin_id = $admin_id";
$result = $mysqli->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    $admin_level = $row['admin_level'];
    $result->free_result();
}

  if($admin_level >= 6){
    $email = $_POST['email'];
    $resultType = $_POST['resultType'];

    // Build the SQL query
    $sql = "UPDATE joinrequests SET $resultType = 1 WHERE email = '$email'";

    // Execute the query
    if (mysqli_query($mysqli, $sql)) {
        echo "Passed";
    } else {
        echo "Error:" . mysqli_error($mysqli);
    }


}else{
  echo("You are not allowed to do this.");
}
    // Close the database connection
    mysqli_close($mysqli);
}else{
    echo 'Something went wrong';
}
?>
