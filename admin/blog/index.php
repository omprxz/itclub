<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: /admin/');
  exit();
}
$admin_id = $_SESSION['admin_id'];
require('../../action/conn.php');
$st = "select admin_level from adminCreds where admin_id = '$admin_id'";
$result = mysqli_query($mysqli, $st);

if ($result) {
  $row = mysqli_fetch_assoc($result);
  $admin_level = $row['admin_level'];
  mysqli_free_result($result);
} else {
  echo "Error: " . mysqli_error($connection);
}
if ($admin_level >= 7) {
  $disOrnot = "";
  $disHint = "";
} else {
  $disOrnot = "disabled";
  $disHint = "(Limited Access)";
}
?>
<html>
<head>
  <meta name="viewport" content="width=device-width" />

<title>Write a blog</title>
<link rel="stylesheet" href="css/create_blog.css" />
<link rel="stylesheet" href="/richtexteditor/rte_theme_default.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

</head>
<body>

<div class="container">
<h1 style="text-align:center;text-decoration:underline;">Write a blog &nbsp;<i class="fas fa-pencil"></i></h1>

<form id="blogForm" class="blogForm" method="post" enctype="multipart/form-data">
<div class="col">
  
<div class="inputs float-inputs input-title">
<input type="text" class="float-input title" name="title" id="title" placeholder="" required />
<label class="float-label" for="title">Post title</label>
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
<img class="thumbnail-prev" id="thumbnail-prev" src="uu" onerror="this.onerror=null; this.src='../../blogs/thumbnails/thumbnail.png';" alt="">
</label>
<label for="thumbnail" class="thumbnail-label">Thumbnail Preview</label>
</div>
<div class="inputs input-visStatus">
<div class="inputs input-visibility">
<label class="visibility-label" for="visibility">Visibility </label>
<select name="visibility" id="visibility" class="visibility" required>
<option value="private">Private</option>
<option value="schedule" <?php echo $disOrnot; ?>>Schedule <?php echo $disHint; ?></option>
<option value="public" <?php echo $disOrnot; ?>>Public <?php echo $disHint; ?></option>
</select>
</div>
<div class="inputs input-sTime">
<label class="sTime-label" for="sTime">Schedule </label>
<input id="sTime" name="sTime" type="datetime-local" class="sTime" />
</div>
</div>
</div>

</div>

<div class="input input-actions">
<div class="uploadStatusDiv">
<progress class="uploadStatus" min="0" max="100" value="0"></progress>
</div>
<div class="inputs input-submit">
<button class="createBlog" type="button">Create Blog</button>
</div>
</div>

</div>


</form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/eruda.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="/richtexteditor/rte.js"></script>
<script type="text/javascript" src='/richtexteditor/plugins/all_plugins.js'></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/create_blog.js"></script>
<script src="js/rte_cust.js"></script>
</body>
</html>