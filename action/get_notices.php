<?php
require('conn.php');

$jsonArray = array();

$sql = "SELECT * FROM notices order by notice_timestamp desc";
$result = mysqli_query($mysqli, $sql);

if ($result) {
    while ($qsql = mysqli_fetch_assoc($result)) {
        // Create an associative array for each record
        $data = array(
            'notice_title' => $qsql['notice_title'],
            'notice_content' => $qsql['notice_content'],
            'notice_imgurl' => $qsql['notice_imgurl'],
            'notice_timestamp' => $qsql['notice_timestamp'],
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