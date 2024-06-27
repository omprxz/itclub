<?php
date_default_timezone_set('Asia/Kolkata');
require('conn.php');

$jsonArray = array();

$sql = "SELECT * FROM notices order by notice_timestamp desc";
$result = mysqli_query($mysqli, $sql);

if ($result) {
    while ($qsql = mysqli_fetch_assoc($result)) {
        // Create an associative array for each record
        $timeS = new DateTime($qsql['notice_timestamp']);
        $timeZ =new DateTimeZone('Asia/Kolkata');
        $timeS->setTimezone($timeZ);
        $timeS= $timeS->format('Y-m-d H:i:s');
        $data = array(
            'notice_title' => $qsql['notice_title'],
            'notice_content' => $qsql['notice_content'],
            'notice_imgurl' => $qsql['notice_imgurl'],
            'notice_timestamp' => $timeS,
            'notice_id' => $qsql['notice_id']
        );

        // Add the data to the JSON array
        $jsonArray[] = $data;
    }

    // Encode the entire JSON array
    $jsonData = json_encode($jsonArray);

    echo $jsonData;
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($mysqli);
}



?>