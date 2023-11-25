<?php
if (isset($_GET['author'])) {
  require('../action/conn.php');
  $isAuthor = true;
  $approvedStatus=1;
  $author=$_GET['author'];
  $authorSql = mysqli_query($mysqli,"select admin_id,admin_name,admin_designation,admin_profilepic,admin_bio,admin_username from adminCreds where admin_username = '$author'");
  if(mysqli_num_rows($authorSql)>0){
    $authDets=mysqli_fetch_assoc($authorSql);
    $authorId=$authDets['admin_id'];
    $authorName=$authDets['admin_name'];
    $authorDesignation=$authDets['admin_designation'];
    $authorProfilepic=$authDets['admin_profilepic'];
    $authorBio=$authDets['admin_bio'];
    $authorUsername=$authDets['admin_username'];
    
    
    $authorStats = mysqli_fetch_assoc(mysqli_query($mysqli,"select count(id) as blogsCount,sum(views) as viewsCount,sum(likes) as likesCount from blogs where adminId = $authorId and visibility = 'public' and approved = $approvedStatus"));
    $blogsCount=$authorStats['blogsCount'];
    $viewsCount=$authorStats['viewsCount'];
    $likesCount=$authorStats['likesCount'];
    
    if($authorStats['blogsCount']>0){
      $isBlogs=true;
      $blogsSql=mysqli_query($mysqli,"select id,title,thumbnail,views,likes from blogs where adminId = $authorId and visibility = 'public' and approved = $approvedStatus order by publishTime desc");
    
    }else{
      $isBlogs=false;
    }
  }else{
    $isAuthor=false;
  }
}else{
  $isAuthor = false;
}

?>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php 
  if($isAuthor){
  echo $authorName." - Author";
  }else{
    echo("No author found");
  }
  ?></title>
  <link rel="stylesheet" href="components/author.css" type="text/css" media="all" />
  <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>
<body>
  
  <?php include 'header.html'; ?>
  
  <section class="main">
    <div class="author-div">
      <?php
      if($isAuthor){
      ?>
      <div class="author-profile">
        <img src="../admin/images/admins/<?php echo $authorProfilepic;?>" class="author-profile-img" alt="<?php echo $authorName;?>" />
      </div>
      <div class="author-meta">
        <p class="author-name"><?php echo $authorName;?></p>
        <p class="author-designation">
          <?php
          if($authorDesignation!=""){
            echo $authorDesignation;
          }else{
            echo("Author");
          }
          ?>
        </p>
        <p class="author-username">
          @<?php echo $authorUsername;?>
        </p>
        <p class="author-bio">
          <?php
          if($authorBio!=""){
            echo $authorBio;
          }else{
            echo("Passionate writer sharing stories that resonate.");
          }
          ?>
        </p>
      </div>
      <div class="author-stats">
        <div class="author-blogsCount">
          <p>Total Blogs</p>
          <p><?php
          if($blogsCount!=""){
            echo $blogsCount;
          }else{
            echo(0);
          }
          ?>
          </p>
        </div>
        <div class="author-viewsCount">
          <p>Total Views</p>
          <p>
            <?php
          if($viewsCount!=""){
            echo $viewsCount;
          }else{
            echo(0);
          }
          ?>
          </p>
        </div>
        <div class="author-likesCount">
          <p>Total Likes</p>
          <p>
            <?php
          if($likesCount!=""){
            echo $likesCount;
          }else{
            echo(0);
          }
          ?>
          </p>
        </div>
      </div>
      <div class="author-blogs">
        <h2 class="author-blogs-heading">Blogs</h2>
        <div class="author-blogs-cards">
          <?php
          if($isBlogs){
            while($b=mysqli_fetch_assoc($blogsSql)){
          ?>
          <div class="blogs-card">
            <div class="blogs-card-img">
              <img src="thumbnails/<?php echo $b['thumbnail'];?>" alt="" class="blog-img">
            </div>
            <div class="blogs-card-details">
              <a href="blog.php?blogid=<?php echo $b['id'];?>" class="blogs-card-title">
                <?php
                if(strlen($b['title'])>85){
                echo substr($b['title'],0,85)."...";
                }else{
                  echo($b['title']);
                }
                ?>
              </a>
              <p class="blogs-card-meta">
                <span class="blogs-card-views"><span class="views"><?php echo $b['views'];?></span> <i class="fas fa-eye"></i></span>
                <span class="meta-dot">·</span>
                <span class="blogs-card-likes"><span class="likes"><?php echo $b['likes'];?></span> <i class="fas fa-heart"></i></span>
              </p>
            </div>
          </div>
          <?php
            }
          }else{
            echo("<p style='text-align:center;color:#B9E0F2;'>No blogs found by this author.</p>");
          }
          ?>
        </div>
      </div>
      <?php
      }else{
        echo("<p style='text-align:center;color:#B9E0F2;margin:200px auto;'>No author found.</p>");
      }
      ?>
    </div>
  </section>
  
  <?php include 'footer.html'; ?>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="components/author.js"></script>
</body>
</html>