<?php
require ("../action/conn.php");
$featured=mysqli_query($mysqli,"select * from blogs where featured != 0 AND visibility = 'public' AND approved = 1 order by featured desc limit 3");
$recent=mysqli_query($mysqli,"select * from blogs where visibility = 'public' AND approved = 1 order by publishTime desc limit 7");
$popular=mysqli_query($mysqli,"select * from blogs where visibility = 'public' AND approved = 1 order by views desc limit 7");
?>

<html>
<head>
  <meta name="viewport" content="width=device-width">
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>Blogs - IT Club</title>
  <link rel="stylesheet" href="components/index.css" type="text/css" media="all" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
  
  <?php include('header.html') ?>
  
  <section class="main">
    <div class="featured-div blog-div">
      <h2 class="heading">Featured Articles</h2>
      <div class="featured-cards cards">
        <?php
        if(mysqli_num_rows($featured)>0){
        while($feat = mysqli_fetch_assoc($featured)){
          $author=mysqli_query($mysqli,"select admin_name,admin_profilepic,admin_username from adminCreds where admin_id =". $feat['adminId']);
          $author=mysqli_fetch_assoc($author);
          
          $pubTime = new DateTime($feat['publishTime']);
          $pubTime = $pubTime->format('d M, Y');
        ?>
        <div class="featured-card card">
          <div class="featured-img-div blog-img-div">
            <img src="thumbnails/<?php echo $feat['thumbnail']; ?>" alt="" class="featured-img blog-img">
           <!-- <div class="featured-views views">
              <span class="views-count"><?php echo $feat['views']; ?></span> <i class="fas fa-eye"></i>
            </div> -->
          </div>
          <div class="featured-details-div details-div">
            <div class="featured-title title">
              <a href="blog.php?blogid=<?php echo $feat['id']; ?>"><?php 
              if(strlen($feat['title'])>150){
                echo substr($feat['title'],0,150)."...";
              }else{
                echo $feat['title'];
              }
              ?></a>
            </div>
            
            <div class="featured-extrainfo extrainfo">
              <div class="featured-meta meta">
              <img src="../admin/images/admins/<?php echo $author['admin_profilepic']; ?>" alt="<?php echo $author['admin_name']; ?>" class="meta-profile">
              <div class="meta-details">
                <a href="author.php?author=<?php echo $author['admin_username']; ?>" class="meta-name"><?php echo $author['admin_name']; ?></a>
                <p class="meta-date"><?php echo $pubTime;?></p>
               </div>
            </div>
              <div class="featured-link link">
              <a href="blog.php?blogid=<?php echo $feat['id']; ?>">Read More »</a>
              </div>
            </div>
          </div>
        </div>
        <?php
        }
        }else{
          ?>
          <p class="noblog">No featured articles.</p>
          <?php
        }
        ?>
      </div>
    </div>
    
    <div class="recent-div blog-div">
      <h2 class="heading">Recent Articles</h2>
      <div class="recent-cards cards">
        <?php
        if(mysqli_num_rows($recent)>0){
        while($rec = mysqli_fetch_assoc($recent)){
          $author=mysqli_query($mysqli,"select admin_name,admin_profilepic,admin_username from adminCreds where admin_id =". $rec['adminId']);
          $author=mysqli_fetch_assoc($author);
          
          $pubTime = new DateTime($rec['publishTime']);
          $pubTime = $pubTime->format('d M, Y');
        ?>
        <div class="card recent-card">
          <div class="recent-img-div blog-img-div">
            <img src="thumbnails/<?php echo $rec['thumbnail']; ?>" alt="" class="recent-img blog-img">
           <!-- <div class="recent-views views">
              <span class="views-count"><?php echo $rec['views']; ?></span> <i class="fas fa-eye"></i>
            </div> -->
          </div>
          <div class="recent-details-div details-div">
            <div class="recent-title title">
              <a href="blog.php?blogid=<?php echo $rec['id']; ?>">
              <?php 
              if(strlen($rec['title'])>150){
                echo substr($rec['title'],0,150)."...";
              }else{
                echo $rec['title'];
              }
              ?>
              </a>
            </div>
            
            <div class="recent-extrainfo extrainfo">
              <div class="recent-meta meta">
              <img src="../admin/images/admins/<?php echo $author['admin_profilepic']; ?>" alt="<?php echo $author['admin_name']; ?>" class="meta-profile">
              <div class="meta-details">
                <a href="author.php?author=<?php echo $author['admin_username']; ?>" class="meta-name"><?php echo $author['admin_name']; ?></a>
                <p class="meta-date"><?php echo $pubTime;?></p>
               </div>
            </div>
              <div class="recent-link link">
              <a href="blog.php?blogid=<?php echo $rec['id']; ?>">Read More »</a>
              </div>
            </div>
          </div>
        </div>
        <div style="width:100%;height: 1px;border-bottom:1px solid #cfcfcf;opacity:0.3;"></div>
        <?php
        }
        }else{
          ?>
          <p class="noblog">No recent articles.</p>
          <?php
        }
        ?>
      </div>
      <div class="recent-more-div">
        <button class="recent-more-btn">Show More Posts ➠</button>
      </div>
    </div>
    
    <div class="popular-div">
    <h3 class="heading">Popular Blogs </h3>
    <div class="popularBlogs">
       <?php
        if(mysqli_num_rows($popular)>0){
        while($pop = mysqli_fetch_assoc($popular)){
          $author=mysqli_query($mysqli,"select admin_name,admin_profilepic,admin_username from adminCreds where admin_id =". $pop['adminId']);
          $author=mysqli_fetch_assoc($author);
          
          $pubTime = new DateTime($pop['publishTime']);
          $pubTime = $pubTime->format('d M, Y');
        ?>
      <div class="popularBlog">
      <div class="popular-img-div">
        <img src="thumbnails/<?php echo $pop['thumbnail']; ?>" class="popular-img" alt="">
      </div>
      <div class="popular-details">
        <a class="popular-title" href="blog.php?blogid=<?php echo $pop['id']; ?>">
          <?php 
              if(strlen($pop['title'])>45){
                echo substr($pop['title'],0,45)."...";
              }else{
                echo $pop['title'];
              }
              ?>
        </a>
        <div class="popular-meta">
          <p class="popular-date">
            <?php echo $pubTime;?>
          </p>
          ∙
          <p class="popular-author">
            <?php echo $author['admin_name']; ?>
          </p>
        </div>
      </div>
    </div>
    <?php
        }
        }else{
          ?>
          <p class="noblog">No popular articles.</p>
          <?php
        }
        ?>
    </div>
  </div>
    
    <section class="authorOfTheWeek">
            <h2 class="aOTW-title">
                AUTHOR OF THE WEEK
            </h2>
              
              <?php

$start_date = date('Y-m-d H:i:s', strtotime('last week Monday'));
$end_date = date('Y-m-d H:i:s', strtotime('last week Sunday'));
 
$query = "SELECT adminId, 
    COUNT(id) AS post_count, 
    SUM(views) AS total_views, 
    SUM(likes) AS total_likes,
    (COUNT(id) * 0.4) + (SUM(views) * 0.3) + (SUM(likes) * 0.3) AS score
    FROM blogs 
    WHERE approved = 1 AND visibility = 'public' AND publishTime BETWEEN '$start_date' AND '$end_date'
    GROUP BY adminId 
    ORDER BY score DESC 
    LIMIT 3";

$result = $mysqli->query($query);

if ($result->num_rows > 0) {
   echo '<div class="aOTW-cards">';
    $rank = 0;
    while ($row = $result->fetch_assoc()) {
        $rank++;
        $rankImg = '';
        if ($rank == 1) {
            $rankImg = '1st.png';
        } elseif ($rank == 2) {
            $rankImg = '2nd.png';
        } elseif ($rank == 3) {
            $rankImg = '3rd.png';
        }
        $adminId = $row["adminId"];
        $postCount = $row["post_count"];
        $totalViews = $row["total_views"];
        $totalLikes = $row["total_likes"];

        // Fetch admin details based on adminId
        $adminDetailsQuery = "SELECT admin_id, admin_name, admin_username, admin_profilepic FROM adminCreds WHERE admin_id = $adminId";
        $adminDetailsResult = $mysqli->query($adminDetailsQuery);

        if ($adminDetailsResult->num_rows > 0) {
            while ($adminRow = $adminDetailsResult->fetch_assoc()) {
                $adminName = $adminRow["admin_name"];
                $adminUsername = $adminRow["admin_username"];
                $adminProfilePic = $adminRow["admin_profilepic"];
                ?>
                
   <div class="aOTW-card">
         <img src="../admin/images/admins/<?php echo $adminProfilePic; ?>" alt="<?php echo $adminName; ?>" class="aOTW-img">
                  <div class="aOTW-details">
                      <a href="author.php?author=<?php echo $adminUsername; ?>" class="aOTW-name"><?php echo $adminName; ?></a>
                      <a href="author.php?author=<?php echo $adminUsername; ?>" class="aOTW-username">@<?php echo $adminUsername; ?></a>
                      <span class="aOTW-ratio">Ratio: <?php echo $totalLikes; ?></span>
                  </div>
                  <img src="../img/<?php echo $rankImg; ?>" alt="<?php echo $rank; ?>" class="aOTW-imgRank">
              </div>
                
<?php
            }
        } else {
            //echo "No admin details found for author: $adminUsername.<br>";
        }
    }
    echo('</div>');
} else {
    echo "<p style='color:white;text-align:center;'>No authors found for the past week.</br>Updates on each monday.</p>";
}
?>

        </section>
    
    <div class="browse-topic-div">
      <h2 class="heading-browsetopic">
        Browse Topics
      </h2>
      <?php
      $topics = json_decode(file_get_contents("components/topics.json"),true)['topics'];
      foreach ($topics as $topic){
        $topicCount = mysqli_query($mysqli,"select count(*) as topicCount from blogs where tags like '%$topic%' AND visibility ='public' AND approved = 1");
        $topicCount=mysqli_fetch_assoc($topicCount)['topicCount'];
      ?>
      <a href="search.php?query=<?php echo urlencode($topic);?>" class="topic">
        <p class="topicDetails">
          <span class="articleName"><?php echo $topic;?></span>
          <span class="articleCount"><?php echo $topicCount;?> Articles</span>
        </p>
        <span class="topicLink"><i class="fas fa-angle-double-right"></i></span>
      </a>
      <?php
      }
      ?>
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
      <div class="news-msg" style="color:white;"></div>
      <div class="news-btn-div">
        <button class="news-btn">Subscribe ➠</button>
      </div>
    </div>
    
  </section>
  
  <?php include('footer.html') ?>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="components/index.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>