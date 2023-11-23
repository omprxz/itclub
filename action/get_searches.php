<?php
// Include your database connection file
include 'conn.php';

// Track success, failed, and empty results
$response = array();

// Results array to store retrieved data
$results = array();

if (isset($_GET['query']) && isset($_GET['type'])) {
    $query = $_GET['query'];
    $type = $_GET['type'];

    if ($type === 'blog') {
        $query = '%' . $query . '%';
        $blogSearch = "SELECT title, id, publishTime, thumbnail FROM blogs WHERE title LIKE '$query' OR tags LIKE '$query'";
        $blogResult = $mysqli->query($blogSearch);

        while ($row = $blogResult->fetch_assoc()) {
            // Convert MySQL date to desired format
            $publishTime = date("j M, Y", strtotime($row['publishTime']));

            $thumbnail = (!empty($row['thumbnail'])) ? "../blogs/thumbnails/" . $row['thumbnail'] : '';

            $results[] = array(
                'title' => $row['title'],
                'url' => '../blogs/blog.php?blogid=' . $row['id'],
                'type' => 'Blog',
                'datetime' => $publishTime,
                'thumbnail' => $thumbnail
            );
        }
    } elseif ($type === 'all') {
        $query = '%' . $query . '%';

        // Execute blog search
        $blogSearch = "SELECT title, id, publishTime, thumbnail FROM blogs WHERE title LIKE '$query' OR tags LIKE '$query' AND visibility = 'public' AND approved = 1 ORDER BY publishTime DESC";
        $blogResult = $mysqli->query($blogSearch);

        while ($row = $blogResult->fetch_assoc()) {
            // Convert MySQL date to desired format
            $publishTime = date("j M, Y", strtotime($row['publishTime']));

            $thumbnail = (!empty($row['thumbnail'])) ? "../blogs/thumbnails/" . $row['thumbnail'] : '';

            $results[] = array(
                'title' => $row['title'],
                'url' => '../blogs/blog.php?blogid=' . $row['id'],
                'type' => 'Blog',
                'datetime' => $publishTime,
                'thumbnail' => $thumbnail
            );
        }

        // Execute event search
        $eventSearch = "SELECT event_title AS title, event_id, event_date AS publishTime, event_imgurl AS thumbnail FROM events WHERE event_title LIKE '$query'";
        $eventResult = $mysqli->query($eventSearch);

        while ($row = $eventResult->fetch_assoc()) {
            // Convert MySQL date to desired format
            $publishTime = date("j M, Y", strtotime($row['publishTime']));

            $thumbnail = (!empty($row['thumbnail'])) ? "../img/events/" . $row['thumbnail'] : '';

            $results[] = array(
                'title' => $row['title'],
                'url' => '../event.php?event_id=' . $row['event_id'],
                'type' => 'Event',
                'datetime' => $publishTime,
                'thumbnail' => $thumbnail
            );
        }

        // Execute notice search
        $noticeSearch = "SELECT notice_title AS title, notice_id, notice_timestamp AS publishTime, notice_imgurl AS thumbnail FROM notices WHERE notice_title LIKE '$query' LIMIT 5";
        $noticeResult = $mysqli->query($noticeSearch);

        while ($row = $noticeResult->fetch_assoc()) {
            // Convert MySQL date to desired format
            $publishTime = date("j M, Y", strtotime($row['publishTime']));

            $thumbnail = (!empty($row['thumbnail'])) ? "../img/notices/" . $row['thumbnail'] : '';

            $results[] = array(
                'title' => $row['title'],
                'url' => '../notice.php?notice_id=' . $row['notice_id'],
                'type' => 'Notice',
                'datetime' => $publishTime,
                'thumbnail' => $thumbnail
            );
        }
    }

    if (count($results) > 0) {
        $response['status'] = 'success';
    } else {
        $response['status'] = 'empty';
    }
} else {
    $response['status'] = 'failed';
}

// Output results
$response['results'] = $results;

echo json_encode($response);
?>