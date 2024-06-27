<?php
if(isset($_GET['notice_id'])){
require('action/conn.php');

$notice_id=$_GET['notice_id'];
$sql = "SELECT notice_id FROM notices WHERE notice_id = $notice_id";
$result = mysqli_query($mysqli, $sql);

if (mysqli_num_rows($result) > 0) {


$sql="select * from notices where notice_id=$notice_id";
if ($qsql=mysqli_fetch_array(mysqli_query($mysqli,$sql))) {
  $notice_title=$qsql['notice_title'];
  $notice_timestamp=$qsql['notice_timestamp'];
  $notice_timestamp = new DateTime($notice_timestamp);
  $notice_timestamp = $notice_timestamp ->format('d F Y');

  $notice_content=htmlspecialchars_decode($qsql['notice_content']);
  $notice_imgurl=$qsql['notice_imgurl'];
} else {
  echo "Error: " . $sql . "<br>" . $mysqli->error;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <title><?php echo $notice_title; ?> - Notice</title>
    <style>
@import url('https://fonts.googleapis.com/css2?family=Gabarito:wght@400;500;600;700&display=swap');
        
:root{
  --fir-color:  #000000;
  --sec-color:  #0c0c0c;
  --thi-color:  #686868;
  --fou-color:  #a1a1a1;
  --fif-color:  #cccccc;
  --six-color:  #ffffff;
  --sev-color:  #272631;

}

*{
  font-family: Gabarito;
}
        .notice-div {
                color: #ffffff;
                padding: 20px;
                text-align: left ;
                margin: 5px auto;
                border-radius: 5px;
            }
    
            .notice-div-title {
                font-size: 24px;
                text-align:center;
            }
    
            .notice-div-date {
                font-size: 14px;
                margin-top: 10px;
                text-align:center;
            }
    
            .notice-div-image {
                width: clamp(150px,90%,360px);
                height: auto;
border-radius:5px;
margin:10px auto;
            }
    
            .notice-div-content {
                font-size: 16px;
                margin-top: 10px;
                overflow-wrap: break-word;
                
            }
    </style>
</head>
<body>

<?php include 'header.html'; ?>

  <div class="notice-div" style="margin:10px auto;">
            <h1 style="text-align:center;">NOTICE</h1>
            <div class="notice-div-title"><?php echo $notice_title; ?></div>
            <div class="notice-div-date">Notice Date: <?php echo $notice_timestamp; ?></div>
            <?php 
     if($notice_imgurl!=''){
    ?>
            <center><img class="notice-div-image" src="img/notices/<?php echo $notice_imgurl; ?>" alt="<?php echo $notice_title; ?>">
            <?php } ?></center>
            <p class="notice-div-content"><?php echo $notice_content; ?></p>
        </div>


<?php include 'footer.html'; ?>

<script>
  window.addEventListener("scroll", function () {
    const lazyImages = document.querySelectorAll("img[loading='lazy']");

    for (const img of lazyImages) {
      if (isInViewport(img)) {
        img.src = img.dataset.src;
        img.removeAttribute("loading");
      }
    }
  });

  function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
      rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
  }
</script>

</body>
</html>
<?php
} else {
  echo "Invalid notice id $notice_id";
  echo "<a href='index.php'>Redirecting you to homepage</a>";
  sleep(1);
  header("Location: index.php");
}}else{
echo "Something went wrong!";
}
?>