<?php
include_once('../../action/checklogin.php');
$admin_id = $_SESSION['admin_id'];
$postPerPage = 5;
require '../../action/conn.php';

$admin_level = mysqli_fetch_assoc(mysqli_query($mysqli,"select admin_level from adminCreds where admin_id = $admin_id"))['admin_level'];

if(isset($_GET['page'])){
   $currentPage = $_GET['page'];
}else{
   $currentPage = 1;
}
$showFirstPage="";
if($currentPage<1){
  $currentPage=1;
  $showFirstPage = "Showing first page.";
}

$stats = mysqli_query($mysqli, "select count(*) as blogs, sum(likes) as likes, sum(views) as views from blogs where adminId = $admin_id");
$stats = mysqli_fetch_assoc($stats);
$blogsCount = $stats['blogs'];
$viewsCount = $stats['views'];
$likesCount = $stats['likes'];
if ($blogsCount == '') {
  $blogsCount = 0;
}
if ($viewsCount == '') {
  $viewsCount = 0;
}
if ($likesCount == '') {
  $likesCount = 0;
}

// Fetch specific columns from blogs
$offset = $postPerPage*($currentPage-1);
$blogsSql = mysqli_query($mysqli, "SELECT id, title, thumbnail, createdTime, visibility, approved, adminId, likes, views FROM blogs WHERE adminId = $admin_id order by createdTime desc limit $postPerPage offset $offset");
?>
<html>
<head>
  <meta name="viewport" content="width=device-width" />
<title>Blog Admin Page</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Mukta:wght@100;200;300;400;500;600;700;00;900&display=swap"
rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
<style>
  * {
    font-family: Mukta;
  }
  .dropstart .dropdown-toggle::before {
    display: none;
  }
  .statsLikes span,.statsViews span {
    line-height: 1.4;
    font-size: 15px;
  }
  .thumbnailImg{
    max-width: 100px;
    height: 100%;
  }
</style>
</head>
<body>

<?php include "header.php"; ?>

<section class="main">
<div class="d-flex flex-column">
<div class="stats d-flex justify-content-center gap-4 bg-dark-subtle p-2 m-3 rounded ">
<div
class="statsBlogs d-flex flex-column justify-content-center align-items-center bg-black text-white p-2 rounded shadow-sm">
<span>Total blogs</span>
<span id="blogsCount"><?php echo $blogsCount; ?></span>
</div>
<div
class="statsViews d-flex flex-column justify-content-center align-items-center bg-black text-white p-2 rounded shadow-sm">
<span>Total views</span>
<span id="viewsCount"><?php echo $viewsCount; ?></span>
</div>
<div
class="statsLikes d-flex flex-column justify-content-center align-items-center bg-black text-white p-2 rounded shadow-sm">
<span>Total Likes</span>
<span id="likesCount"><?php echo $likesCount; ?></span>
</div>

</div>
<div class="creatBlog d-flex flex-wrap justify-content-center gap-3 row-gap-1">
  <a class="btn btn-outline-danger rounded d-flex align-items-center my-2" href="trashed_blog.php">
    <i class="fas fa-trash-alt"></i>&nbsp; Trashed Blogs
  </a>
  <a class="btn btn-outline-primary rounded d-flex align-items-center my-2" href="create_blog.php">
    <i class="fas fa-plus"></i>&nbsp; Create New Blog
  </a>
  <?php
  if($admin_level >=7){
  ?>
   <a class="btn btn-outline-primary rounded d-flex align-items-center my-2 mt-1" href="approve_blogs.php">
    <i class="fas fa-list-alt"></i>&nbsp; Approve Blogs
  </a>
  <?php
  }
  ?>
</div>
<div class="blogsDiv d-flex flex-column my-2">
<h2 class="text-center fw-bold" style="font-family: arial;">My Blogs</h2>
<?php
if($showFirstPage!=""){
  echo "<div class='text-center text-danger'>$showFirstPage</div>";
}
if(mysqli_num_rows($blogsSql)>0){
?>
<div class="blogCards d-flex flex-column my-2 p-1 gap-2">
<?php
while($blogs = mysqli_fetch_assoc($blogsSql)){
  
    $visibIcon = "fas fa-lock";
    $approvedIcon = "fa-check-circle";

    $formattedDate = date('M d, Y', strtotime($blogs['createdTime']));

    if ($blogs['visibility'] == 'public') {
        $visibIcon = "fas fa-globe";
    } else if ($blogs['visibility'] == 'private') {
        $visibIcon = "fas fa-lock";
    } else if ($blogs['visibility'] == 'schedule') {
        $visibIcon = "far fa-calendar-alt";
    }

    if($blogs['approved'] == 0){
        $approvedIcon = "fas fa-times-circle text-danger";
    }
    else if ($blogs['approved'] < 0) {
        $approvedIcon = "fas fa-hourglass text-warning";
    } else {
        $approvedIcon = "fas fa-check-circle text-success";
    }
  
  ?>
 <div class="blogCard mx-3 border border-2 border-dark-subtle rounded blogNo<?php echo $blogs['id'];?>">
   <div class="firstRow d-flex align-items-center p-2">
     <div class="first">
          <img src="../../blogs/thumbnails/<?php echo $blogs['thumbnail'];?>" class="rounded thumbnailImg" alt="<?php echo substr($blogs['title'],0, 20);?>">
          </div>
     <div class="second flex-grow-1 p-1 mx-2">
          <span style="line-height:1.1;" class="blogTitle fs-6">
          <a href="edit_blog.php?blogid=<?php echo $blogs['id'];?>" class="text-decoration-none text-dark text-break"><?php echo substr($blogs['title'],0, 50);?></a>
          </span>
      </div>
     <div class="third d-flex flex-column justify-content-evenly align-items-center text-secondary">
          <div class="dropdown dropstart open">
          <button class="btn dropdown-toggle text-secondary actionDropdown" type="button" id="triggerId"
          data-bs-toggle="dropdown" aria-haspopup="true">
          <i class="fas fa-ellipsis-v"></i>
          </button>
          <div class="dropdown-menu" aria-labelledby="triggerId">
          <a class="dropdown-item blogPreview" href="../../blogs/blog.php?blogid=<?php echo $blogs['id'];?>&preview=show">
          <i class="fas fa-eye text-secondary"></i>&nbsp; Preview
          </a>
          <button class="dropdown-item blogShare" data-blogid="<?php echo $blogs['id'];?>">
          <i class="fas fa-share-alt text-secondary"></i>&nbsp; Share
          </button>
          </div>
          </div>
          <button class="text-secondary btn deleteBlog" data-blogid="<?php echo $blogs['id'];?>">
          <i class="fas fa-trash-alt"></i>
          </button>
          <a class="text-secondary btn editBlog" href="edit_blog.php?blogid=<?php echo $blogs['id'];?>">
          <i class="fas fa-edit"></i>
          </a>
          </div>
   </div>
   <div class="secondRow p-2 d-flex justify-content-between align-items-center border-top text-secondary blogMeta">
          <div><?php echo $formattedDate;?></div> 
          <div class="cardStats d-flex align-items-center gap-3">
          <span class="cardApproved">
          <i class="<?php echo $approvedIcon;?>"></i>
          </span>
          <span class="cardVisibilty">
          <i class="<?php echo $visibIcon;?>"></i>
          </span>
          <span class="cardViews">
          <i class="far fa-eye"></i> <?php echo $blogs['views'];?>
          </span>
          <span class="cardLikes">
          <i class="far fa-thumbs-up"></i> <?php echo $blogs['likes'];?>
          </span>
          </div>
          
   </div>
          
   </div>
<?php
}
?>
</div>
<?php
  
  $totalPostByAdmin = mysqli_fetch_assoc(mysqli_query($mysqli,"select count(*) as totalPost from blogs where adminId = $admin_id"))['totalPost'];
  if($totalPostByAdmin > $postPerPage){
    
    $totalPage = ceil($totalPostByAdmin/$postPerPage);
    if($currentPage == 1){
      $prevDisabledOrNot = 'disabled';
    }else{
      $prevDisabledOrNot = '';
    }
    
    if($currentPage == $totalPage){
      $nextDisabledOrNot = 'disabled';
    }else{
      $nextDisabledOrNot = '';
    }
    ?>
    <nav class="mt-4 mb-2" aria-label="Page navigation example">
      <ul class="pagination justify-content-center">
        <li class="page-item <?php echo $prevDisabledOrNot; ?>">
          <a class="page-link" href="?page=<?php echo $currentPage-1; ?>" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
       </li>
       <?php
       
       for ($i = 1; $i <= $totalPage ; $i++) {
         if($currentPage==$i){
           $activeOrNot='active';
         }else{
           $activeOrNot='';
         }
          echo "<li class='page-item $activeOrNot'><a class='page-link' href='?page=$i'>$i</a></li>";
       }
       
       ?>
       <li class="page-item <?php echo $nextDisabledOrNot; ?>">
          <a class="page-link" href="?page=<?php echo $currentPage+1; ?>" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
       </li>
      </ul>
</nav>
 <?php   
  }

}
else if (mysqli_num_rows($blogsSql) == 0) {
  if($currentPage==1){
    echo '<p class="noblogs text-center text-black">You don\'t have any blogs.</p>';
  }else{
    echo '<p class="noblogs text-center text-black">You\'ve reached beyond the last blog page.</p>';
  }
}
else{
  echo '<p class="errblogs text-center text-danger">Something Went Wrong!</p>';
}
?>
</div>

</div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let admin_id = '<?php echo $admin_id; ?>';  
</script>

<script src="js/index.js"></script>
</body>
</html>