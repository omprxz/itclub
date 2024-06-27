<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../libs/PHPMailer/src/Exception.php';
require '../../libs/PHPMailer/src/PHPMailer.php';
require '../../libs/PHPMailer/src/SMTP.php';
if (isset($_GET['blogid'])) {
  $blogid = $_GET['blogid'];
  require('../../action/conn.php');
  
  $isSent = false;
  $alreadyExist = false;
  $isSentSql = mysqli_query($mysqli, "select * from blogsMailSent where blogid = $blogid");
  if(mysqli_num_rows($isSentSql)>0){
    $alreadyExist=true;
    $isSentSql = mysqli_fetch_assoc($isSentSql);
    if($isSentSql['isSent'] == 0){
      $isSent = false;
    }elseif($isSentSql['isSent'] == 1){
      $isSent = true;
    }
  }else{
    $alreadyExist=false;
    $isSent=false;
  }

  if($isSent == false){
  $blogDets = mysqli_query($mysqli, "select * from blogs where id = $blogid");
  if(mysqli_num_rows($blogDets)>0){
    $blogDets = mysqli_fetch_assoc($blogDets);
    $adminId = $blogDets['adminId'];
    $adminDets = mysqli_fetch_assoc(mysqli_query($mysqli, "select * from adminCreds where admin_id = $adminId"));
  $getSubs = mysqli_query($mysqli, "select * from subscriptions where subscribed = 1");
  
  if (mysqli_num_rows($getSubs) > 0) {
    while ($row = mysqli_fetch_assoc($getSubs)) {
    $subs[] = $row;
}
    $mailAcc = [
      [
        'user' => 'itclubblogs@gmail.com',
        'pass' => 'hinj iroj zong cbzw',
        'name' => 'IT Club Blogs'
      ]
    ];
    
    //VARIABLES
    $blogTitle = $blogDets['title'];
    $blogid = $blogDets['id'];
    $blogUrl = "https://itclub.000.pe/blogs/blog.php?blogid=".$blogid;
    $blogAuthor = $adminDets['admin_name'];
    $authorUrl = "https://itclub.000.pe/blogs/author.php?author=".$adminDets['admin_username'];
    $blogThumbnail = "https://itclub.000.pe/blogs/thumbnails/".$blogDets['thumbnail'];
    $blogPreview = substr(strip_tags(htmlspecialchars_decode($blogDets['content'])), 0, 250)."...";
    $subject = 'New Blog Alert! '.$blogTitle;
    
    
    $mail = new PHPMailer(true);
    $template = file_get_contents("blogMailTemplate.html");
    
    // REPLACE PLACEHOLDERS
$template = str_replace('{{blog_url}}', $blogUrl, $template);
$template = str_replace('{{blog_title}}', $blogTitle, $template);
$template = str_replace('{{author_url}}', $authorUrl, $template);
$template = str_replace('{{blog_author}}', $blogAuthor, $template);
$template = str_replace('{{thumbnail}}', $blogThumbnail, $template);
$template = str_replace('{{blog_preview}}', $blogPreview, $template);
    

    try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Port = 587;
      $mail->Username = $mailAcc[0]['user'];
      $mail->Password = $mailAcc[0]['pass'];

      $mail->setFrom($mailAcc[0]['user'], $mailAcc[0]['name']);
      $mail->isHTML(true);
      $mail->Subject = $subject;

      foreach ($subs as $sub) {
        if($sub['subscribed']==1){
        $unsubscribeUrl = "https://itclub.000.pe/admin/blog/unsubscribe.php?subid=".$sub['id'];
        $message = $template;
        $message = str_replace('{{name}}', $sub['name'], $message);
        $message = str_replace('{{unsubscribe_url}}', $unsubscribeUrl, $message);
        $mail->addAddress($sub['email'], $sub['name']);

       //$message = $template;

        $mail->Body = $message;
        $mail->send();
        $mail->clearAddresses();
      }
      }
      
    if($alreadyExist==false){
      $sentSql =mysqli_query($mysqli, "INSERT INTO blogsMailSent (blogid,isSent) VALUES ($blogid,1)");
    }elseif($alreadyExist==true){
      $sentSql =mysqli_query($mysqli, "UPDATE blogsMailSent set isSent = 1 where blogid = $blogid");
    }
    echo("Mails sent successfully.");
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }


  }
  }
}
elseif($isSent == true){
  echo("Mails already sent.");
}
}
else{
  echo("Blogid not set");
}
?>