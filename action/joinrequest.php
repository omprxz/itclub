<?php
if(isset($_GET["email"])){
date_default_timezone_set('Asia/Kolkata');

require('conn.php');
// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
// Set the expiry date and time
$keyToFetch = 'joinForm_expiry';
$result = $mysqli->query("SELECT value1 FROM extra WHERE key1 = '$keyToFetch'");
$row = $result->fetch_assoc();
$expiryDate = strtotime($row['value1']);

//$expiryDate = strtotime("2023-12-01 19:26:00"); // October 17, 2023, at 11:59:59 PM
// Get the current date and time
$currentDate = time();
if ($currentDate > $expiryDate) {
    echo "Submission date expired";
} else {

// Function to sanitize input data
function sanitizeInput($input) {
    global $mysqli;
    return $mysqli->real_escape_string($input);
}

// Get form data
$fullname = sanitizeInput($_GET['fullname']);
$rollno = sanitizeInput($_GET['rollno']);
$email = sanitizeInput($_GET['email']);
$contact = sanitizeInput($_GET['contact']);
$whatsapp = sanitizeInput($_GET['whatsapp']);
$session = sanitizeInput($_GET['session']);
$branch = sanitizeInput($_GET['branch']);
$semester = sanitizeInput($_GET['semester']);
$choice1 = sanitizeInput($_GET['choice1']);


// Check for duplicate email
$duplicateEmailCheck = "SELECT email FROM joinrequests WHERE email = '$email'";
$duplicateEmailResult = $mysqli->query($duplicateEmailCheck);

if ($duplicateEmailResult->num_rows > 0) {
    echo "Email already used!";
} else {
    $duplicateRollCheck = "SELECT rollno FROM joinrequests WHERE rollno = '$rollno'";
    $duplicateRollResult = $mysqli->query($duplicateRollCheck);
    
    if ($duplicateRollResult->num_rows > 0) {
        echo "Roll no. already exists!";
    } else {  
// Insert the data into the database
if(isset($_GET['choice2'])){
    $choice2 = sanitizeInput($_GET['choice2']);
$sql = "INSERT INTO joinrequests (fullname, rollno, email, contact, whatsapp, session, branch, semester, choice1, choice2)
        VALUES ('$fullname','$rollno', '$email', '$contact', '$whatsapp', '$session', '$branch', '$semester', '$choice1', '$choice2')";
}else{
$sql = "INSERT INTO joinrequests (fullname, rollno, email, contact, whatsapp, session, branch, semester, choice1)
        VALUES ('$fullname','$rollno', '$email', '$contact', '$whatsapp', '$session', '$branch', '$semester', '$choice1')";

}
if ($mysqli->query($sql) === TRUE) {
    echo "Applied! we will contact you soon.";
} else {
    echo $mysqli->error;
}

}
// Close the database connection
$mysqli->close();
}
}}else{
    echo "Something went wrong";
}
?>
