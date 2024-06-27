<?php
include_once 'conn.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $feedback = $_POST['feedback'];
    $ipAddress = $_SERVER['REMOTE_ADDR'];

    if (empty($name) || empty($email) || empty($feedback)) {
        $response['status'] = 'error';
        $response['message'] = 'Please fill in all fields.';
        echo(json_encode($response));
        exit();
    } else {
        $query = "INSERT INTO askAiFeedback (name, email, feedback, ip_address) VALUES ('$name', '$email', '$feedback', '$ipAddress')";
        $result = mysqli_query($mysqli, $query);

        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Feedback submitted successfully.';
            echo(json_encode($response));
        exit();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error submitting feedback.';
            echo(json_encode($response));
        exit();
        }
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method.';
    echo(json_encode($response));
        exit();
}
?>