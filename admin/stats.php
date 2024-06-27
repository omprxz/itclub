<?php
if(isset($_POST['stat'])){
    $ip = $_SERVER['REMOTE_ADDR'];

// If you suspect the user might be behind a proxy or load balancer, check other headers
if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}elseif (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
}

    $statsFilename = 'site_stats.json';

    if (!file_exists($statsFilename)) {
        $data = [
            'page_views' => 0
        ];
    } else {
        $data = json_decode(file_get_contents($statsFilename), true);
    }

    $data['page_views']++;

    file_put_contents($statsFilename, json_encode($data));
}
?>