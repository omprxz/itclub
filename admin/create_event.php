<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}
require '../action/conn.php';
$admin_id=$_SESSION["admin_id"];
$query = "SELECT admin_level FROM adminCreds WHERE admin_id = $admin_id";
$result = $mysqli->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    $admin_level = $row['admin_level'];
    $result->free_result();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if($admin_level >= 3){
    $event_title = $mysqli->real_escape_string($_POST["event_title"]);
    if(isset($_POST['event_description'])){
    $event_description = $mysqli->real_escape_string($_POST["event_description"]);}else{
      $event_description=NULL;
    }
    if(isset($_POST['event_date'])){
    $event_date = $_POST["event_date"];}else {
      $event_date=NULL;
    }
    if(isset($_POST['event_gphotoslink'])){
    $event_gphotoslink = $mysqli->real_escape_string($_POST["event_gphotoslink"]);}else {
      $event_gphotoslink='';
    } 
if(isset($_POST['event_ytlink'])){
    $event_ytlink = $mysqli->real_escape_string($_POST["event_ytlink"]);
}else {
    $event_ytlink = '';
}

    // Check if an image file was uploaded
    if (isset($_FILES["event_image"]) && $_FILES["event_image"]["error"] == UPLOAD_ERR_OK) {
        $targetDirectory = "../img/events/";
        $targetFile = $targetDirectory . basename($_FILES["event_image"]["name"]);

        // Check if the file is an image (using MIME type)
        $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/svg', 'image/webp', 'image/png', 'image/gif'];
        $uploadedMimeType = mime_content_type($_FILES["event_image"]["tmp_name"]);

        if (in_array($uploadedMimeType, $allowedMimeTypes) && $_FILES["event_image"]["size"] <= 2 * 1024 * 1024) {
            // File is an image and doesn't exceed 2MB

            if (move_uploaded_file($_FILES["event_image"]["tmp_name"], $targetFile)) {
                // File was uploaded successfully
                $imageURL = basename($_FILES["event_image"]["name"]);
                $imageURL = $mysqli->real_escape_string($imageURL);

              
            } else {
                echo "Error uploading the image.";
            }
        } else {
            echo "Invalid file type or file size exceeds 2MB. Only JPEG, PNG, or GIF images up to 2MB are allowed.";
        }
    } else {
      //no image selected
        $imageURL='';
    }
  // Insert the image URL into the database using mysqli_query
   $sql = "INSERT INTO events (event_title, event_description, event_date, event_imgurl, event_gphotoslink, query_admin_id,event_ytlink) VALUES ('$event_title', '$event_description', '$event_date', '$imageURL', '$event_gphotoslink','$admin_id','$event_ytlink')";


                if (mysqli_query($mysqli, $sql)) {
                    echo "Event created successfully!";
                } else {
                    echo "Error in creating event: " . mysqli_error($mysqli);
                 }
}else{
   echo "<p style='color:red;font-size:16px;margin:10px;'>The event cannot be created because you're not allowed to create events.</p>";
}
    $mysqli->close();
}
?>
<html>
<head>
  <meta name="viewport" content="width=device-width">
    <title>Create Event</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      .event_descriptionDiv{
        height: 150px;
      }
    </style>
</head>
<body>
   <?php include 'header.php'; ?>
  <br/>
    <h2 class="text-center fw-bold my-2">Create Event
    <?php
if($admin_level < 3){
  echo "<p style='color:red;font-size:16px;'>You are currently not allowed to create events.</p>";
}
?>
    </h2>
<div class="container">
    <form method="post" enctype="multipart/form-data">
        <label for="event_title">Event Title:</label>
        <input type="text" name="event_title" placeholder="Title" class="form-control mb-3" required>

<div style="text-align: center; margin:10px;" class="mb-3">
            <button type="button" class="format-options btn btn-secondary" onclick="document.execCommand('bold', false, null)"><b>B</b></button>
            <button type="button" class="format-options btn btn-secondary" onclick="document.execCommand('underline', false, null)"><u>U</u></button>
            <button type="button" class="format-options btn btn-secondary" onclick="document.execCommand('italic', false, null)"><i>I</i></button>
        </div>
        <label for="event_description">Event Description:</label>
        <div placeholder="Description" class="form-control event_descriptionDiv mb-2" contenteditable></div>
        <input type="text" hidden name="event_description" class="event_description">

        <label for="event_date">Event Date:</label>
        <input value="now()" type="date" name="event_date" class="form-control mb-2" id="event_date">

        <label for="event_image">Event Image (Max Limit: 2MB):</label>
        <input type="file" name="event_image" class="mb-3" accept="image/*">

        <label for="event_gphotoslink">Google Photos Link: <br> (if multiple then seperate by commas)</label>
        <input type="text" name="event_gphotoslink" class="form-control mb-3" placeholder="Google photos">

<label for="event_ytlink">YouTube Link: <br />(if multiple then seperate by commas)</label>
<input type="text" name="event_ytlink" class="form-control mb-3" placeholder="YouTube Link">

        <center><input type="submit" value="Create Event" class="btn btn-primary my-3">
</center>
    </form>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      $('.event_descriptionDiv').on('input',function(){
        $('.event_description').val($('.event_descriptionDiv').html());
      })
$('.format-options').click(function(){
$(this).toggleClass('btn-primary')
$(this).toggleClass('btn-secondary')
})

document.getElementById('event_date').valueAsDate = new Date();
    </script>
</body>
</html>

