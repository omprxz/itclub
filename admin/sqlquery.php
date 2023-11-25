<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}
require '../action/conn.php';
$admin_id = $_SESSION['admin_id'];
$query = "SELECT admin_level FROM adminCreds WHERE admin_id = $admin_id";
$result = $mysqli->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    $admin_level = $row['admin_level'];
    $result->free_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySQL Query</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
   <?php include 'header.php'; ?>
  <br/>
    <div class="container mt-4">
        <h2>MySQL Query
         <?php
if($admin_level < 7){
  echo "<p style='color:red;font-size:16px;'>This is not for you.</p>";
}
?>
        </h2>
        <textarea id="sqlQuery" class="form-control" rows="5" placeholder="Enter MySQL Query"></textarea>
        <div class="text-center">
        <button id="executeQuery" class="btn btn-primary mt-2">Execute Query</button>
        </div>
        <div id="result" class="mt-3"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#executeQuery').on('click', function () {
                var query = $('#sqlQuery').val();

                $.ajax({
                    type: 'get',
                    url: 'query.php',
                    data: { query: query },
                    success: function (response) {
                        $('#result').html(response);
                    },
error: function(error){
console.log(error)
}
                });
            });
        });
    </script>


<script src="../eruda.js"></script>

</body>
</html>