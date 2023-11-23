<?php
$result = array();
$result['status']='failed';
$result['posts']=[];
$result['allLoaded']=0;

if(isset($_GET['limit']) && isset($_GET['offset'])){
  require('../action/conn.php');
  $limit=$_GET['limit'];
  $offset=$_GET['offset'];
  $posts="select id,title,thumbnail,views,visibility,approved,adminId,publishTime,url from blogs where visibility = 'public' AND approved = 1 limit $limit offset $offset";
  $posts=mysqli_query($mysqli,$posts);
  if(mysqli_num_rows($posts) < $limit){
    $result['allLoaded']=1;
  }
  while($post=mysqli_fetch_assoc($posts)){
    $result['status']='success';
     $author=mysqli_query($mysqli,"select admin_name,admin_profilepic,admin_username from adminCreds where admin_id =". $post['adminId']);
    $author=mysqli_fetch_assoc($author);
          
    $pubTime = new DateTime($rec['publishTime']);
    $pubTime = $pubTime->format('d M, Y');
    $post['pubTime']=$pubTime;
    $post['admin_name']=$author['admin_name'];
    $post['admin_profilepic']=$author['admin_profilepic'];
    $post['admin_username']=$author['admin_username'];
   $result['posts'][]=$post;
    
  }
  
}else{
  $result['status']='failed';
  $result['posts']=[];
}

//$result['posts'] = json_encode($result['posts']);
$jsonRes = json_encode($result);
print_r($jsonRes);

?>