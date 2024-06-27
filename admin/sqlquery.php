<?php
include_once('../action/checklogin.php');
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
        <div id="sqlKeywords" class="d-flex flex-row gap-2 overflow-auto mt-3 py-2 border-top border-bottom">
    <span class="keyword border rounded py-2 px-3 text-nowrap">SELECT</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">*</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">FROM</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">INSERT</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">UPDATE</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">DELETE</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">WHERE</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">ORDER BY</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">GROUP BY</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">HAVING</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">JOIN</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">LEFT JOIN</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">RIGHT JOIN</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">INNER JOIN</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">LIMIT</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">DISTINCT</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">UNION</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">UNION ALL</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">AS</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">CASE</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">WHEN</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">THEN</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">ELSE</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">END</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">ON</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">IN</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">NOT IN</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">IS NULL</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">IS NOT NULL</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">BETWEEN</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">LIKE</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">EXISTS</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">COUNT</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">SUM</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">AVG</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">MIN</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">MAX</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">CREATE</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">ALTER</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">DROP</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">TRUNCATE</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">RENAME</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">COMMENT</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">GRANT</span>
    <span class="keyword border rounded py-2 px-3 text-nowrap">REVOKE</span>
</div>
        <div class="text-center d-flex flex-row gap-3 justify-content-center mt-3">
            <button id="executeQuery" class="btn btn-primary">Execute</button>
            <button id="executeQueryHtml" class="btn btn-primary">Execute (HTML)</button>
        </div>
        <div id="result" style="max-height:600px;width:100%;overflow:auto;border:1px solid grey;border-radius:3px;margin-bottom:15px;" class="mt-3"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.keyword').on('click', function () {
                var keyword = $(this).text() + ' ';
                var currentText = $('#sqlQuery').val();
                $('#sqlQuery').val(currentText + keyword);
            });

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

            $('#executeQueryHtml').on('click', function () {
                var query = $('#sqlQuery').val();

                $.ajax({
                    type: 'get',
                    url: 'queryhtml.php',
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