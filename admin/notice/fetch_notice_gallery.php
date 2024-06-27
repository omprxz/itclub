<?php
include_once('../../action/checklogin.php');
require('../../action/conn.php');
$admin_id = $_SESSION["admin_id"];

$imageLinks = [];

$imageFolder = "../../img/notices";
$directory = scandir($imageFolder);
foreach ($directory as $file) {
    if ($file !== '.' && $file !== '..') {
        $filePath = $imageFolder . '/' . $file;
        if (isImage($filePath)) {
            $imageLinks[] = $filePath;
        }
    }
}

echo json_encode($imageLinks);

$mysqli->close();

function isImage($filePath) {
    $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF, IMAGETYPE_WEBP);
    $detectedType = exif_imagetype($filePath);
    return in_array($detectedType, $allowedTypes);
}

?>