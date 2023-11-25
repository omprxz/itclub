<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}

require('../action/conn.php');
$admin_id=$_SESSION["admin_id"];
$query = "SELECT admin_level FROM adminCreds WHERE admin_id = $admin_id";
$result = $mysqli->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    $admin_level = $row['admin_level'];
    $result->free_result();
}

if (isset($_POST['createNotice'])) {
  if($admin_level >= 3){
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    $title = $mysqli->real_escape_string($title);
    $content = $mysqli->real_escape_string($content);
    

    // Check if a file was uploaded
    if (isset($_FILES['imgurl']) && $_FILES['imgurl']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../img/notices/';
        $uploadFile = $uploadDir . basename($_FILES['imgurl']['name']);
        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Check if the uploaded file is an image
        if (getimagesize($_FILES['imgurl']['tmp_name'])) {
            // Check if the file type is allowed (e.g., you can add more types if needed)
            if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif','webp','svg'])) {
                if (move_uploaded_file($_FILES['imgurl']['tmp_name'], $uploadFile)) {
                    echo "Image uploaded successfully.";
                    $imgurl = $uploadFile; // Store the image URL in $imgurl
                    $imgurl = basename($_FILES['imgurl']['name']);
                } else {
                    echo "Error uploading the image.";
                }
            } else {
                echo "Invalid image file type. Allowed types are jpg, jpeg, png, and gif.";
            }
        } else {
            echo "The uploaded file is not an image.";
        }
    $sql = "INSERT INTO notices (notice_title, notice_content, notice_imgurl,query_admin_id) VALUES ('$title', '$content', '$imgurl','$admin_id')";


    } else {
    $sql = "INSERT INTO notices (notice_title, notice_content,query_admin_id) VALUES ('$title', '$content','$admin_id')";
        
    }


    if ($mysqli->query($sql) === true) {
        echo "Notice created successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
  }else{
    echo "<p style='color:red;font-size:16px;margin:10px;'>The notice cannot be created because you're not allowed to create notices.</p>";
  }
}

$mysqli->close();
?>

<html>
<head> 
<meta name="viewport" content="width=device-width">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
    <title>Create Notice</title>
    <style>
        .noticeForm{
          display: flex;
          flex-direction: column;
          align-items: center;
        }
        .title{
            height: 35px;
            width: clamp(200px,90%,500px);
            border-radius: 3px;
            border: 1px solid black;
            outline: 0;
        }
        .content{
            border: 1px solid black;
            border-radius: 3px;
            height: 250px;
            width: 90%;
            border-radius: 5px;
            margin-top: 4px;
            padding: 5px;
            overflow: scroll;
            color: black;
            outline: 0;
        }
        .format-options{
            padding: 5px 10px;
        }
        .imgDrop{
            margin: 15px 20px;
            width: 90%;
            border: 1px solid black;
            padding: 10px;
            border-radius: 5px;
            background-color: aquamarine;
            overflow: hidden;
        }
        .imgDrop label{
            font-weight: 600;
        }
        .imgDrop .innerBorder{
            border: 1px dashed black;
            padding: 30px 0 30px 90px;
            border-radius: 5px;
            background-color: rgb(1, 206, 206);
        }
        .submit{
            margin-top: 5px;
            height: 40px;
            padding: 2px 15px;
            border-radius: 7px;
            border: 1px solid #143e48;
            background-color: aqua;
        }
        .activeBtn{
            background-color: aqua;
        }
    </style>
</head>
<body>
   <?php include 'header.php'; ?>
  <br/>
    <h2 style="text-align:center;margin-bottom:15px;">Create an Notice
     <?php
if($admin_level < 3){
  echo "<p style='color:red;font-size:16px;'>You are currently not allowed to create notices.</p>";
}
?>
    </h2>
    <form method="POST" class="noticeForm" action="" enctype="multipart/form-data">
        <label for="title" style="font-weight: bold;">Title:</label>
        <input type="text" class="title" name="title" required> <br/>
        <label for="content" style="font-weight: bold;">Content:</label>
        <div style="text-align: center;">
            <button type="button" class="format-options" onclick="document.execCommand('bold', false, null)"><b>B</b></button>
            <button type="button" class="format-options" onclick="document.execCommand('underline', false, null)"><u>U</u></button>
            <button type="button" class="format-options" onclick="document.execCommand('italic', false, null)"><i>I</i></button>
        </div>
        
        <div class="content" contenteditable="true"></div>
        <div class="imgDrop">
            <div class="innerBorder">
                <input name="imgurl" id="imgurl" type="file"> <br>
                <label for="imgurl">or Drag and Drop image</label>
            </div>
        </div>
        <input type="text" class="contentMain" name="content" hidden >
        <input class="submit" type="submit" name="createNotice" value="Create Notice">
    </form>

<div class="write">

</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
   // $('.content').focus()
    $('.content').on('input',function(){
        $('.contentMain').val($('.content').html())
    })
    $('.getval').click(function(){
        $('.write').text($('.content').html())
    })
    $('.format-options').click(function(){
        $(this).toggleClass('activeBtn')
    })

</script>
    

</body>
</html>
