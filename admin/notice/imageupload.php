<?php
$input = file_get_contents('php://input');

if ($input !== false) {
  include_once('../../libs/ImgCompressor/ImgCompressor.class.php');
  $uploadDir = '../../img/notices';
 
  if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }

   $fileName = isset($_GET['name']) ? pathinfo($_GET['name'],PATHINFO_FILENAME).'_'.uniqid().'.'.pathinfo($_GET['name'],PATHINFO_EXTENSION) : 'uploaded_file_' . uniqid().'.jpg';


  $filePath = $uploadDir .'/'. $fileName;

  if (file_put_contents($filePath, $input) !== false) {
   
   $setting = array(
            'directory' => $uploadDir,
            'file_type' => array(
              'image/jpg',
              'image/jpeg',
              'image/png',
              'image/gif'
            )
          );
      $ImgCompressor = new ImgCompressor($setting);
      $extension=pathinfo($_GET['name'], PATHINFO_EXTENSION);
      $compImg = $ImgCompressor->run($filePath, $extension, 5);
      if($compImg['status']=='success'){
        
          if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
      $protocol = 'https://';
    } else {
      $protocol = 'http://';
    }
    $filePath = $protocol.$_SERVER['HTTP_HOST'].'/img/notices/'.$compImg['data']['compressed']['name'];
    echo 'READY:' . $filePath;
        
      }elseif($compImg['status']=='error'){
          $response['status'] = 'failed';
          $response['result'] = $compImg['message'];
          echo json_encode($response);
          $mysqli->close();
          exit;
      }else{
          $response['status'] = 'failed';
          $response['result'] = 'Error uploading file';
          echo json_encode($response);
          $mysqli->close();
          exit;
          }
    
  } else {
    echo 'ERROR: Unable to write blob data to destination';
  }
} else {
  echo 'ERROR: Invalid request or no data sent';
}
?>