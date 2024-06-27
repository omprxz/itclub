<?php
session_start();
function setSessionUrl(){
  $_SESSION['login_redirect'] = $_SERVER['REQUEST_URI'];
}
if (!isset($_SESSION['loggedin'])) {
    setSessionUrl();
    header('Location: /admin/login.php');
    exit();
}

?>