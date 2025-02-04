<?php
include_once('../../action/checklogin.php');
require('../../action/conn.php');
$admin_id = $_SESSION["admin_id"];
$query = "SELECT admin_level FROM adminCreds WHERE admin_id = $admin_id";
$result = $mysqli->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    $admin_level = $row['admin_level'];
    $result->free_result();
}
?>
<html>
<head>
  <meta name="viewport" content="width=device-width" />
<title>Edit blog</title>
<link rel="stylesheet" href="css/edit_blog.css" />
<link rel="stylesheet" href="/libs/richtexteditor/rte_theme_default.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

</head>
<body>

   <?php include 'header.php'; ?>

<div class="container" style="margin:15px;">
<h1 style="text-align:center;text-decoration:underline;">Edit blog &nbsp;<i class="fas fa-pencil"></i>
<?php
if($admin_level < 2){
  echo "<p style='color:red;font-size:16px;'>You are currently not allowed to create or edit blogs.</p>";
}
?>
</h1>

<form id="blogForm" class="blogForm" method="post" enctype="multipart/form-data">
<div class="col">

<div class="inputs float-inputs input-title">
<input type="text" class="float-input title" name="title" value="<?php echo $rowData['title'];?>" id="title" placeholder=" " required />
<label class="float-label" for="title">Blog title</label>
</div>

<div class="row">

<div class="inputs input-content">
<h3 style="margin-bottom:5px;text-align:center;">Blog</h3>
<div id="content" class="content"></div>
</div>

<div class="col">
<div class="input-tagList">
<div class="tagList">
</div>
<div class="inputs input-tag">
<input type="text" class="inpTag" placeholder="Add tags (seperated by commas)" />
<button class="addTag" type="button"><i class="fas fa-plus"></i></button>
</div>
</div>
<div class="inputs input-thumbnail">
<label for="thumbnail" class="thumbnail-label">Select Thumbnail (Max 1 MB)</label>
<input type="file" name="thumbnail" id="thumbnail" class="thumbnail" accept="image/*" />
<label for="thumbnail" style="text-align:center;">
<img class="thumbnail-prev" id="thumbnail-prev" src="" onerror="this.onerror=null; this.src='../../blogs/thumbnails/thumbnail.png';" alt="">
</label>
<label for="thumbnail" class="thumbnail-label">Thumbnail Preview</label>
</div>
<div class="inputs input-visStatus">
<div class="inputs input-visibility">
<label class="visibility-label" for="visibility">Visibility </label>
<select name="visibility" id="visibility" class="visibility" required>
<option value="private">Private</option>
<option class="scheduleOpt" value="schedule">Schedule</option>
<option value="public">Public</option>
</select>
</div>
<div class="inputs input-sTime">
<label class="sTime-label" for="sTime">Schedule </label>
<input id="sTime" name="sTime" type="datetime-local" class="sTime" />
</div>

<div class="inputs input-meta-desc">
  <label for="metaDesc">Meta Description (For better SEO Max 500 Chars): </label>
  <textarea name="metaDesc" id="metaDesc" class="metaDesc"></textarea>
</div>
</div>
</div>

</div>

<div class="input input-actions">
<div class="uploadStatusDiv">
<progress class="uploadStatus" min="0" max="100" value="0"></progress>
</div>
<div class="inputs input-submit">
<button class="updateBlog" type="button">Update Blog</button>
</div>
</div>

</div>


</form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="/libs/richtexteditor/rte.js"></script>
<script type="text/javascript" src='/libs/richtexteditor/plugins/all_plugins.js'></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/edit_blog.js"></script>
<script src="js/rte_cust.js"></script>
</body>
</html>