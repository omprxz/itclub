<?php
require('conn.php');
// Query to fetch data from the 'events' table
$sql = "SELECT * FROM events order by event_date desc";

$result = $mysqli->query($sql);
    // Fetch data and store it in an array
    $events_data = array();
    while ($row = $result->fetch_assoc()) {
        $events_data[] = $row;
    }

    // Close the database connection
    $mysqli->close();

    // Prepare the response
    $response = array(
        'status' => 'success',
        'response' => $events_data
    );
    echo json_encode($response);

?>
