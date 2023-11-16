<?php
$input = file_get_contents('php://input');

if ($input !== false) {
  $uploadDir = '../../blogs/images/';

  if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }

  $fileName = isset($_GET['name']) ? basename($_GET['name']) : 'uploaded_file_' . uniqid();

  $filePath = $uploadDir . $fileName;

  // Check if the file already exists, if so, generate a new unique filename
  $counter = 1;
  while (file_exists($filePath)) {
    $fileName = isset($_GET['name']) ? pathinfo($_GET['name'], PATHINFO_FILENAME)."_$counter.".pathinfo($_GET['name'], PATHINFO_EXTENSION) : 'uploaded_file_' . uniqid();
    $filePath = $uploadDir . $fileName;
    $counter++;
  }

  if (file_put_contents($filePath, $input) !== false) {
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
      $protocol = 'https://';
    } else {
      $protocol = 'http://';
    }
    $filePath = $protocol.$_SERVER['HTTP_HOST'].'/blogs/images/'.basename($filePath);
    echo 'READY:' . $filePath;
  } else {
    echo 'ERROR: Unable to write blob data to destination';
  }
} else {
  echo 'ERROR: Invalid request or no data sent';
}
?>