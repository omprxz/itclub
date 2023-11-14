<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
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
    <div class="container mt-4">
        <h2>MySQL Query</h2>
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