<?php
if (isset($_POST["submit"])) {
    require_once("conn.php");
    $data = "select * from joinrequests";
    $Qdata = mysqli_query($mysqli, $data);
    if ($Qdata) {
        $results = array();
        while ($row = mysqli_fetch_assoc($Qdata)) {
            $results[] = $row;
        }
        $jsonresults = json_encode($results);
        header('Content-Type: application/json');
        echo $jsonresults;
    } else {
        echo "Error: " . mysqli_error($mysqli);
    }

}


?>