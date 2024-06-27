<?php
if (isset($_GET['blogid']) || isset($_GET['authorid']) || isset($_GET['popular'])) {
  require('../action/conn.php');
  $suggestions = array();

  if (isset($_GET['blogid'])) {
    $related = [];
    $id = $_GET['blogid'];
    $tags = explode(',', mysqli_fetch_assoc(mysqli_query($mysqli, "select tags from blogs where id = '$id'"))['tags']);
    $cond = [];
    foreach ($tags as $tag) {
      $cond[] = "tags like '%$tag%'";
    }
    $whereClause = implode(' OR ', $cond);
    $relatedSql = mysqli_query($mysqli, "SELECT id, title, thumbnail, publishTime FROM blogs WHERE ($whereClause) AND visibility = 'public' and approved = 1 and id != $id ORDER BY publishTime DESC LIMIT 7");
    if(mysqli_num_rows($relatedSql)>0){
     $suggestions['relatedStatus']=1;
    while ($row = mysqli_fetch_assoc($relatedSql)) {
      $related[] = $row;
    }
    }else{
      $suggestions['relatedStatus']=0;
    }
    $suggestions['related'] = $related;
  }else{
      $suggestions['relatedStatus']=0;
  }

  if (isset($_GET['authorid'])) {
    $byauthor = [];
    $authorid = $_GET['authorid'];
$byauthorSql = mysqli_query($mysqli, "SELECT id, title, thumbnail, publishTime FROM blogs WHERE adminId = '$authorid' AND visibility = 'public' and approved = 1 and id != $id ORDER BY publishTime DESC LIMIT 7");
 if(mysqli_num_rows($byauthorSql)>0){
     $suggestions['byauthorStatus']=1;
while ($row = mysqli_fetch_assoc($byauthorSql)) {
    $byauthor[] = $row;
}
}else{
      $suggestions['byauthorStatus']=0;
    }

$suggestions['byauthor'] = $byauthor;
  }else{
      $suggestions['byauthorStatus']=0;
    }

  if (isset($_GET['popular'])) {
    $popular = [];
    $popularSql = mysqli_query($mysqli, "SELECT id, title, thumbnail, publishTime FROM blogs WHERE approved = 1 AND visibility = 'public' ORDER BY views DESC LIMIT 7");
 if(mysqli_num_rows($popularSql)>0){
     $suggestions['popularStatus']=1;
while ($row = mysqli_fetch_assoc($popularSql)) {
    $popular[] = $row;
}
}else{
      $suggestions['popularStatus']=0;
    }
$suggestions['popular'] = $popular;
   }else{
      $suggestions['popularStatus']=0;
    }

$suggestions=json_encode($suggestions);
echo($suggestions);
}

?>