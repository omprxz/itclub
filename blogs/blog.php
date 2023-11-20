<?php
if (isset($_GET['blogid'])) {
  require('../action/conn.php');
  $id=$_GET['blogid'];
  //views
  $views=mysqli_query($mysqli,"update blogs set views = views + 1 where id = $id");
  
  
  $detSql = "select * from blogs where id = '$id' AND visibility = 'public' AND approved = 1";
  $EdetSql = mysqli_query($mysqli,$detSql);
  if(mysqli_num_rows($EdetSql)>0){
    $blogAvailable=true;
    $blogDets = mysqli_fetch_assoc($EdetSql);
    $authorSql = "select admin_name,admin_designation,admin_profilepic from adminCreds where admin_id = '".$blogDets['adminId']."'";
    $EauthorSql = mysqli_query($mysqli,$authorSql);
    $EauthorSql = mysqli_fetch_assoc($EauthorSql);
    
    $authorId = $blogDets['adminId'];
    $authorProfile = $EauthorSql['admin_profilepic'];
    $authorName = $EauthorSql['admin_name'];
    $authorDesig = $EauthorSql['admin_designation'];
    $title = $blogDets['title'];
    $published = date_format(date_create($blogDets['publishTime']), 'd M, Y');
    $content = htmlspecialchars_decode($blogDets['content']);
    $likes=$blogDets['likes'];
    $tags = explode(',',$blogDets['tags']);
    $pageTitle=$title;
  }else{
    $blogAvailable=false;
    $pageTitle='Blog unavailable or private!';
  }
}
?>
<html>
<head>
  <meta name="viewport" content="width=device-width" />
  <title><?php echo $pageTitle;?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
  <link rel="stylesheet" href="components/blog.css" type="text/css" media="all" />
  <style>
    body{
      margin: 0;
    }
  </style>
</head>
<body>
  <script src="../eruda.js"></script>
<script>
  let blogid=<?php echo $id; ?>;
  let authorid=<?php echo $authorId; ?>;
</script>

<?php include 'header.html'; ?>

<div class="blog">
  <?php
  if(!$blogAvailable){ ?>
  <style>
.page_404{ padding:40px 0; background:#fff; font-family: 'Arvo', serif;
text-align: center;
}

.page_404  img{ width:100%;}

.four_zero_four_bg{
 
 background-image: url(https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif);
    height: 400px;
    background-position: center;
 }
 .four_zero_four_bg h1{
 font-size:80px;
 margin-bottom: 10px;
 }
  .four_zero_four_bg h3{
	          		 font-size:80px;
	          		 }
	          		 
	.link_404{			 
	color: #fff!important;
    padding: 10px 20px;
    background: #39ac31;
    margin: 20px 0;
    text-decoration: none;
    display: inline-block;}
	.contant_box_404{ margin-top:-50px;}
  </style>
 <section class="page_404">
	<div class="container">
		<div class="row">	
		<div class="col-sm-12 ">
		<div class="col-sm-10 col-sm-offset-1  text-center">
		<div class="four_zero_four_bg">
			<h1 class="text-center">Oops...</h1>
		</div>
		
		<div class="contant_box_404">
		<h3 class="h2">
		Look like you're lost
		</h3>
		
		<p>The blog you are looking for is either private or not avaible!</p>
		
		<a href="index.php" class="link_404">Explore other blogs</a>
	</div>
		</div>
		</div>
		</div>
	</div>
</section>
    <?php
  }else{
  ?>
  <div class="blog-title">
    <h1>
     <?php echo $title; ?>
    </h1>
  </div>
  <p class="blog-date">
    <?php echo $published; ?>
  </p>
  <div class="blog-author">
    <img src="../admin/images/admins/<?php echo $authorProfile; ?>" alt="<?php echo $authorName; ?>" class="author-profile">
    <div class="author-details">
      <p class="author-name"><?php echo $authorName; ?></p>
      <p class="author-designation"><?php echo $authorDesig; ?></p>
    </div>
  </div>
  <div class="blog-content">
    <?php echo $content; ?>
  </div>
  <div class="blog-like">
  <button class="confetti-button like"><i class="far fa-heart"></i> <span class="likestatus">Like</span> <span class="likecount"><?php echo $likes;?></span></button>
</div>
	<div class="blog-tags">
	  <?php
	  $tag=0;
	  while($tag < count($tags)){
	    echo("<li><a href='search.php?label=".$tags[$tag]."'>".$tags[$tag]."</a></li>");
	    $tag++;
	  }
	  ?>
	</div>
	<div class="blog-share a2a_kit a2a_kit_size_32 a2a_default_style">
      <a class="a2a_dd"></a>
      <a class="a2a_button_copy_link"></a>
      <a class="a2a_button_linkedin"></a>
      <a class="a2a_button_whatsapp"></a>
      <a class="a2a_button_facebook"></a>
      <a class="a2a_button_x"></a>
	</div>
	<div class="blog-suggestions-div">
	  <div class="blog-suggestion-controls">
	    <button class="suggest-related active ripple">Related Blogs</button>
	    <button class="suggest-byauthor">By This Author</button>
	    <button class="suggest-popular">Popular Blogs</button>
	  </div>
	  <div class="blog-suggestions">
	    <div class="related-blogs active">
	      
	    </div>
	    <div class="byauthor-blogs">
	      <div class="byauthor-blog">
	       <div class="byauthor-blogThumbnail-div">
	          <img src="images/camera.jpg" alt="" class="byauthor-blogThumbnail">
	        </div>
	        <div class="byauthor-blogDeails">
	          <p class="byauthor-blogTitle"><a href="#">By author I have this blog too</a></p>
	          <p class="byauthor-blogDate">26 Jul, 2020</p>
	        </div>
	      </div>
	    </div>
	    <div class="popular-blogs">
	      <div class="popular-blog">
	        <div class="popular-blogThumbnail-div">
	          <img src="images/camera.jpg" alt="" class="popular-blogThumbnail">
	        </div> 
	        <div class="popular-blogDeails">
	          <p class="popular-blogTitle"><a href="#">Popular I am popular blog must checkoua</a></p>
	          <p class="popular-blogDate">3 Sep, 2001</p>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
	<?php } ?>
</div>

<?php include 'footer.html'; ?>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="components/blog.js"></script>
<script async src="https://static.addtoany.com/menu/page.js"></script>
</body>
</html>