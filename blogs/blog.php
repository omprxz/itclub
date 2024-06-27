<?php
session_start();
if (isset($_GET['blogid'])) {
  require('../action/conn.php');
  $id=$_GET['blogid'];
  
  if(isset($_SESSION['loggedin'])){
    $admin_id=$_SESSION['admin_id'];
    $admin_level = mysqli_fetch_assoc(mysqli_query($mysqli,"select admin_level from adminCreds where admin_id = $admin_id"))['admin_level'];
    
    $checkAdminId = mysqli_fetch_assoc(mysqli_query($mysqli,"select adminId from blogs where id = $id"))['adminId'];
    if($checkAdminId==$admin_id || $admin_level >= 6){
      $detSql = "select * from blogs where id = $id";
      $hasFullAccess = true;
    }else{
      $detSql = "select * from blogs where id = '$id' AND visibility = 'public' AND approved = 1";
      $hasFullAccess = false;
    }
  }else{
  $detSql = "select * from blogs where id = '$id' AND visibility = 'public' AND approved = 1";
  $hasFullAccess = false;
  }
  $EdetSql = mysqli_query($mysqli,$detSql);
  if(mysqli_num_rows($EdetSql)>0){
    $blogAvailable=true;
    //views
    $views=mysqli_query($mysqli,"update blogs set views = views + 1 where id = $id");
    
    $blogDets = mysqli_fetch_assoc($EdetSql);
    $authorSql = "select admin_name,admin_designation,admin_profilepic,admin_username from adminCreds where admin_id = '".$blogDets['adminId']."'";
    $EauthorSql = mysqli_query($mysqli,$authorSql);
    $EauthorSql = mysqli_fetch_assoc($EauthorSql);
    
    $authorId = $blogDets['adminId'];
    $authorProfile = $EauthorSql['admin_profilepic'];
    $authorName = $EauthorSql['admin_name'];
    $authorUsername = $EauthorSql['admin_username'];
    $authorDesig = $EauthorSql['admin_designation'];
    $title = htmlspecialchars_decode($blogDets['title']);
    $published = date_format(date_create($blogDets['publishTime']), 'd M, Y');
    $pubTime=$blogDets['publishTime'];
    $content = htmlspecialchars_decode($blogDets['content']);
    $likes=$blogDets['likes'];
    $visibility = $blogDets['visibility'];
    $thumbnail = $blogDets['thumbnail'];
    $approved = $blogDets['approved'];
    $tags = explode(',',$blogDets['tags']);
    if($blogDets['metaDesc'] != ""){
      $metaDesc = $blogDets['metaDesc'];
    }else{
      $metaDesc="IT Club Blogs";
    }
    $pageTitle=$title;
  }else{
    $blogAvailable=false;
    $pageTitle='Blog unavailable or private!';
  }
}
?>
<html>
<head>
  <meta name="viewport" content="width=device-width" >
  <meta property="og:title" content="<?php echo $pageTitle;?>">
  <meta property="og:description" content="<?php echo $metaDesc; ?>">
  <meta property="og:image" content="https://itclub.000.pe/blogs/thumbnails/<?php echo $thumbnail; ?>">
  <meta property="og:url" content="https://itclub.000.pe/blogs/blog.php?blogid=<?php echo $id; ?>">
  <meta name="description" content="<?php echo $metaDesc; ?>" >
  <meta name="keywords" content="<?php foreach($tags as $tag) {echo $tag.", ";} ?>" >
  <title><?php echo $pageTitle;?></title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="components/blog.css" type="text/css" media="all" />
</head>
<body>
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
      <a href="author.php?author=<?php echo $authorUsername; ?>" class="author-name"><?php echo $authorName; ?></a>
      <p class="author-designation"><?php echo $authorDesig; ?></p>
    </div>
  </div>
  <div class="blog-audio" data-status="initial">
    <span class="icon" data-status="notloaded">
      <i class="fas fa-play audio-play"></i>
      <img class="audio-load" src="compimgs/loader1.svg" alt="@">
      <i class="fas fa-pause audio-pause"></i>
    </span>
    <span class="audio-label">Listen to this blog</span>
    <div class="audio-player-div">
        <div class="audio-player" id="audioPlayer">
          <input type="range" class="audio-player-slider" id="audioPlayerSlider" value="0" max="100" step="0.1">
          <select style="padding:5px;" class="audio-player-playback">
            <option value="0.25">0.25x</option>
            <option value="0.5">0.5x</option>
            <option value="0.75">0.75x</option>
            <option value="1" selected>1x</option>
            <option value="1.25">1.25x</option>
            <option value="1.5">1.5x</option>
            <option value="1.75">1.75x</option>
            <option value="2">2x</option>
          </select>
        </div>
        <div class="audio-player-durations">
            <span class="audio-player-curTime">00:00</span>
            <span style="margin:auto 10px;"> | </span>
            <span class="audio-player-totTime">00:00</span>
        </div>
    </div>
  </div>
  <div class="blog-translate">
    <select class="blog-languages">
  <option selected disabled>Translate blog</option>
<option value="English">English</option>
<option value="Spanish">Spanish</option>
<option value="Chinese">Chinese</option>
<option value="Hindi">Hindi</option>
<option value="Arabic">Arabic</option>
<option value="Bengali">Bengali</option>
<option value="Portuguese">Portuguese</option>
<option value="Russian">Russian</option>
<option value="Urdu">Urdu</option>
<option value="Indonesian">Indonesian</option>
<option value="German">German</option>
<option value="French">French</option>
<option value="Japanese">Japanese</option>
<option value="Marathi">Marathi</option>
<option value="Telugu">Telugu</option>
<option value="Turkish">Turkish</option>
<option value="Tamil">Tamil</option>
<option value="Vietnamese">Vietnamese</option>
<option value="Urdu">Urdu</option>
<option value="Gujarati">Gujarati</option>
<option value="Kannada">Kannada</option>
   </select>
    <div class="translate-loader">
      <img class="translate-loaderGif" src="compimgs/loader2.gif" >
      <span class="translate-loaderText">Translating...</span>
    </div>
  </div>
  <?php
  if($hasFullAccess==true){
  if($visibility != 'public' || $approved <= 0 || isset($_GET['preview'])){
    $visibIcon = "fas fa-lock";
    $approvedIcon = "fa-check-circle";
    $approvedStatus = "Approved";

    $formattedDateHA = date('M d, Y h:m:s', strtotime($pubTime));

    if ($visibility == 'public') {
        $visibIcon = "fas fa-globe";
        $pubTime=$formattedDateHA;
    } else if ($visibility == 'private') {
        $visibIcon = "fas fa-lock";
        $pubTime="";
    } else if ($visibility == 'schedule') {
        $visibIcon = "far fa-calendar-alt";
        $pubTime=$formattedDateHA;
    }
  
    if($approved == 0){
        $approvedIcon = "fas fa-hourglass textYellow";
        $approvedStatus = "Approval Pending";
    }
    else if ($approved < 0) {
        $approvedIcon = "fas fa-times-circle textRed";
        $approvedStatus = "Rejected";
    } else if($approved > 0) {
        $approvedIcon = "fas fa-check-circle textGreen";
        $approvedStatus = "Approved";
    }
  
  ?>
  <div class="hasFA">
      <span class="textRed">Admin View Only! <i class="fas fa-hand-point-down"></i></span>
      <p class="visib">
        <i class="<?php echo $visibIcon; ?>"></i>&nbsp; <?php echo $visibility; ?>
      </p>
      <p style="margin-bottom:0;">
        <?php echo $pubTime; ?>
      </p>
      <p class="appr">
        <i class="<?php echo $approvedIcon; ?>"></i>&nbsp; <?php echo $approvedStatus; ?>
      </p>
      <a href="../admin/blog/edit_blog.php?blogid=<?php echo $id; ?>" class="blogEdit">Edit This Blog &nbsp;<i class="fas fa-edit"></i></a>
  </div>
  <?php 
    }
  }
  ?>
  <div class="blog-content">
    <?php echo $content; ?>
  </div>
  <div class="blog-like">
  <button class="confetti-button like"><i class="far fa-heart"></i> <span class="likecount"><?php echo $likes;?></span></button>
</div>
	<div class="blog-share a2a_kit a2a_kit_size_32 a2a_default_style">
      <a class="a2a_dd"></a>
      <a class="a2a_button_copy_link"></a>
      <a class="a2a_button_linkedin"></a>
      <a class="a2a_button_whatsapp"></a>
      <a class="a2a_button_facebook"></a>
      <a class="a2a_button_x"></a>
	</div>
	<div class="blog-tags">
	  <?php
	  $tag=0;
	  if($tags[0]!==''){
	  while($tag < count($tags)){
	    echo("<li><a href='search.php?query=".urlencode($tags[$tag])."'>".$tags[$tag]."</a></li>");
	    $tag++;
	  }
	  }
	  ?>
	</div>
	<div class="blog-suggestions-div">
	  <div class="blog-suggestion-controls">
	    <button class="suggest-related active ripple">Related Blogs</button>
	    <button class="suggest-byauthor">By This Author</button>
	    <button class="suggest-popular">Popular Blogs</button>
	  </div>
	  <div class="blog-suggestions">
	    <div class="related-blogs active"></div>
	    <div class="byauthor-blogs"></div>
	    <div class="popular-blogs"></div>
	  </div>
	</div>
	
  <div class="newsletter-div">
      <h2 class="heading-news">Newsletter</h2>
      <p class="news-desc">
        Get ahead of the game with exclusive access to our compelling technology blogs, inspiring case studies, and the latest industry updates. Join us today and stay ahead of the curve!
      </p>
      <div class="news-inputs">
        <i class="far fa-user"></i>
        <input tnews-email class="news-name" name="name" placeholder="Your name" />
      </div>
      <div class="news-inputs">
        <i class="far fa-envelope"></i>
        <input type="email" class="news-email" name="email" placeholder="Email address" />
      </div>
      <div class="news-msg"></div>
      <div class="news-btn-div">
        <button class="news-btn">Subscribe ➠</button>
      </div>
    </div>
	<?php } ?>
</div>

<?php include 'footer.html'; ?>


<!--<script src="../eruda.js"></script>-->

<script type="importmap">
    {
      "imports": {
        "@google/generative-ai": "https://esm.run/@google/generative-ai"
      }
    }
  </script>
<script type="module">
    import { GoogleGenerativeAI } from "@google/generative-ai";
      const API_KEY = "AIzaSyDUeSSKD2IarvZElf-Dv8nHfxoKJPNk80w";
      const genAI = new GoogleGenerativeAI(API_KEY);
    $(document).ready(function(){
       $('.blog-languages').on('change', async function(){
    let targetLang = $('.blog-languages').val()
    let toTranslate = `Transform this html code content to ${targetLang} language but don't change html markdowns and urls and other things . i just wanted to change the language of this content.
    Content - 
    ${blogContent}`;
   
    if (toTranslate) {
    $('.translate-loader').fadeIn(200, function () {
        $(this).css("display", "flex");
    });
    $('.blog-languages').hide();

    try {
        const model = genAI.getGenerativeModel({ model: "gemini-pro" });
        const result = await model.generateContent(toTranslate);
        const response = await result.response;
        const translated = response.text();
        Toast.fire({
          icon: 'success',
          title: 'Blog translated.'
        })
        $('.translate-loader').hide();
        $('.blog-languages').show();
        $('.blog-content').html(translated);
    } catch (error) {
       Toast.fire({
          icon: 'error',
          title: 'Error while translating!'
        })
        console.error("Error during translation:", error);
        $('.translate-loader').hide();
        $('.blog-languages').show();
    }
}
    
});
    })
   </script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="components/blog.js"></script>
<script async src="https://static.addtoany.com/menu/page.js"></script>
</body>
</html>