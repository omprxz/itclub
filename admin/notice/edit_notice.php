<?php
include_once('../../action/checklogin.php');
require '../../action/conn.php';
$admin_id=$_SESSION["admin_id"];
?>
<html>
<head>
  <meta name="viewport" content="width=device-width">
    <title>Edit Notice</title>
<link rel="stylesheet" href="/libs/richtexteditor/rte_theme_default.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <style>
      .notice_descriptionDiv{
        height: 500px;
      }
      rte-powerby {
  visibility: hidden !important;
}
rte-plusbtn, rte-taglist {
  display: none !important;
}
.uploadStatusDiv{
  display: none;
}
.uploadStatus {
  height: 45px;
}
    </style>
</head>
<body>
   <?php include 'header.php'; ?>
  <br/>
    <h2 class="text-center fw-bold my-2">Edit Notice
    <?php
if($admin_level < 3){
  echo "<p style='color:red;font-size:16px;'>You are currently not allowed to edit notices.</p>";
}
?>
    </h2>
  <div class="container">
    <form method="post" enctype="multipart/form-data" id="noticeForm">
        <label for="notice_title">Notice Title:</label>
        <input type="text" name="notice_title" placeholder="Title" class="form-control mb-3 title" required>

        <label for="content">Notice Description:</label>
        <div class="notice_descriptionDiv mb-2 content" id="content"></div>

        <label for="notice_image">Notice Image (Max Limit: 2MB):</label>
        <input type="file" name="notice_image" class="mb-3 thumbnail" id="thumbnail" accept="image/*">
        <p class="text-center mb-1">Thumbnail Preview</p>
        <div class="d-flex justify-content-center mb-2">
        <img src="../../blogs/thumbnails/thumbnail.png" alt="Thumbnail" class="thumbnail-prev img-thumbnail">
        </div>
       
        <div class="progress my-2 mb-4 uploadStatusDiv">
  <div class="progress-bar uploadStatus" role="progressbar" style="width: 0;">0%</div>
</div>
      <div class="text-center">
        <input type="button" value="Update Notice" class="btn btn-primary updateNotice">
      </div>
    </form>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="/libs/richtexteditor/rte.js"></script>
<script type="text/javascript" src='/libs/richtexteditor/plugins/all_plugins.js'></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="components/edit_notice.js"></script>
    <script src="components/rte_cust.js"></script>
</body>
</html>
