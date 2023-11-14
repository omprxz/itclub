<?php
if (isset($_POST['passBtn'])) {
    require '../action/conn.php';

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

    // Close the database connection
    mysqli_close($mysqli);
}else{
    echo 'Something went wrong';
}
?>
