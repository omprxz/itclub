<?php
require_once '../action/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $name = filter_var($_GET['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'invalid_email';
        exit();
    }

    $stmt = $mysqli->prepare("SELECT email FROM subscriptions WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo 'duplicate';
        exit();
    }

    $stmt = $mysqli->prepare("INSERT INTO subscriptions (name, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $email);
    $stmt->execute();

    if ($stmt->affected_rows === 1) {
        echo 'success';
    } else {
        echo 'failure';
    }

    $stmt->close();
    $mysqli->close();
} else {
    echo 'invalid_request';
}
?>