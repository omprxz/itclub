<?php
  $query = $_GET['query'];
  $type = 'blog';
?>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width">
  <title><?php echo $query; ?> - Search Results</title>
  <link rel="stylesheet" href="components/search.css" type="text/css" media="all" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script>
    let query ="<?php echo $query; ?>";
    let type ="<?php echo $type; ?>";
  </script>
</head>
<body>
  
  <?php include('header.html');?>
  
  <section class="main">
    <div class="searched-div blog-div">
      <h2 class="heading">Search results for: <?php echo $query; ?></h2>
      <div class="searched-cards cards">
        <p style="color:#B9E0F2;font-size:17px;">Results Here</p>
      </div>
      <div class="searched-more-div">
        <button class="searched-more-btn">Load More ➠</button>
      </div>
    </div>
  </section>
  
  <?php include('footer.html');?>
  
  <script src="../eruda.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="components/search.js"></script>
</body>
</html>