<?php
if(isset($_POST['stat'])){
$ip = $_SERVER['REMOTE_ADDR'];
$ipFilename = 'ip_addresses.txt';
$pageViewsFilename = 'page_views.txt';
file_put_contents($ipFilename, $ip . PHP_EOL, FILE_APPEND);

$pageViewsFilename = 'page_views.txt';
if (!file_exists($pageViewsFilename)) {
    $file = fopen($pageViewsFilename, 'w');
    fclose($file);
}
$pageViews = intval(file_get_contents($pageViewsFilename));
$pageViews++;
file_put_contents($pageViewsFilename, $pageViews);
echo "{page_views:$pageViews,ip_addr:$ip}";
}
?>