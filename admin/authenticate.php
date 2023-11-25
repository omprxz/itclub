<?php

// Start or resume the session
session_start();

// Check if the user is already logged in
if (isset($_SESSION['loggedin'])) {
  header('Location: index.php'); // Redirect if logged in
  exit();
}
  require('../action/conn.php');
  if (isset($_POST['login'])) {
    // Retrieve posted email and password (validate and sanitize as needed)
    $userid = $_POST['userid'];
    $password = $_POST['password'];

    // Check credentials against the database using mysqli_query
    $sql = "SELECT * FROM adminCreds WHERE admin_email = '$userid' OR admin_username = '$userid'";
    $result = mysqli_query($mysqli, $sql);

    if ($result) {
      if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $hashedPassword = $row['admin_pass'];

        if ($password==$hashedPassword) {
          // Authentication successful
          $_SESSION['loggedin'] = true;
          $_SESSION['admin_id'] = $row['admin_id'];
          $_SESSION['admin_level'] = $row['admin_level'];
          $_SESSION['admin_name'] = $row['admin_name'];
          $_SESSION['admin_username'] = $row['admin_username'];
          header('Location: index.php');
        } else {
          // Authentication failed
          header('Location: login.php?error=invalid_credentials');
        }
      } else {
        // Email not found
        header('Location: login.php?error=invalid_credentials');
      }

      mysqli_free_result($result);
    } else {
      // Query execution failed
      header('Location: login.php?error=database_error');
    }

    mysqli_close($mysqli);
    header('Location: login.php');
  } elseif (isset($_POST['signup'])) {
      // Retrieve posted registration data (validate and sanitize as needed)
      $admin_email = $_POST['email'];
      $admin_name = $_POST['name'];
      $admin_username = $_POST['username'];
      $admin_password = $_POST['password']; // Hash the password
      
      // Check if the email is already registered
      $checkUnameSQL = "SELECT * FROM adminCreds WHERE admin_username = '$admin_username'";
      $UnameResult = mysqli_query($mysqli, $checkUnameSQL);
      
      if (mysqli_num_rows($UnameResult) == 0) {
      // Check if the email is already registered
      $checkEmailSQL = "SELECT * FROM adminCreds WHERE admin_email = '$admin_email'";
      $emailResult = mysqli_query($mysqli, $checkEmailSQL);

      if (mysqli_num_rows($emailResult) == 0) {
        $insertSQL = "INSERT INTO adminCreds (admin_email,admin_username, admin_name, admin_pass) VALUES ('$admin_email','$admin_username', '$admin_name', '$admin_password')";

        if (mysqli_query($mysqli, $insertSQL)) {
          // Registration successful; redirect to login page
          header('Location: login.php?success=registration success');
        } else {
          // Registration failed; handle error
          header('Location: signup.php?error=database_error');
        }
      } else {
        // Email is already registered; show an error
         header("Location: signup.php?error=email already registered&name=$admin_name&username=$admin_username&email=$admin_email");
      }
      }else{
         header("Location: signup.php?error=username not available&name=$admin_name&username=$admin_username&email=$admin_email");
      }

      mysqli_close($mysqli);
    }else{
      header('Location: login.php');
    }

?>