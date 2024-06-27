<?php

// Blog Publisher >>>
date_default_timezone_set('Asia/Kolkata');
$current_time = date("Y-m-d H:i:s");

$sql = "select id,visibility,publishTime from blogs where publishTime <= '$current_time' AND visibility = 'schedule'";
$ids = [];
$eSql = mysqli_query($mysqli, $sql);
if (mysqli_num_rows($eSql) > 0) {
    while ($row = mysqli_fetch_assoc($eSql)) {
        $id = $row['id'];
        $ids[] = $id;
        $sql2 = "update blogs set visibility = 'public',publishTime = '$current_time' where id = $id";
        $eSql2 = mysqli_query($mysqli, $sql2);
    }
}
// <<< Blog Publisher

// SEND SUBSCRIPTIONS
foreach ($ids as $blogid) {
    $url = '../admin/blog/sendSubscriptions.php';
    $data = ['blogid' => $blogid];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    // Handle $response as needed
    if ($response === false) {
        // Handle error
    } else {
        $responseData = json_decode($response, true);
        // Process $responseData as needed
    }
}

?>