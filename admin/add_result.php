<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}

?>
<?php
require '../action/conn.php';
$admin_id=$_SESSION["admin_id"];
$query = "SELECT admin_level FROM adminCreds WHERE admin_id = $admin_id";
$result = $mysqli->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    $admin_level = $row['admin_level'];
    $result->free_result();
}

$sql = "select email,fullname,rollno,branch from joinrequests";
$qsql = mysqli_query($mysqli, $sql);

?>
<html>
<head>
  <meta name="viewport" content="width=device-width">
    <title>Publish Result</title>
    <style>
        .resultForm {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            column-gap: 10px;
            row-gap:8px;
            margin-top: 10px;
            width: 100%;
        }

        h3 {
            text-align: center;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   <?php include 'header.php'; ?>
  <br/>
  <h2 style="text-align:center;margin-bottom:20px;">
    Publish Results
     <?php
if($admin_level < 6){
  echo "<p style='color:red;font-size:16px;'>You are currently not allowed to publish results.</p>";
}
?>
  </h2>
        <div style="display:flex;justify-content:center;gap:10px;" class="mx-2">
        <button name="testResult1" class="bulkFail failAllTest1 btn btn-outline-danger" type="button">Fail All in Test Choice 1</button>
        <button name="interviewResult1" class="bulkFail failAllInt1 btn btn-outline-danger" type="button">Fail All in Interview Choice 1</button>
         </div>
        <div style="display:flex;justify-content:center;gap:10px;margin:5px;" class="mx-2">
        <button name="testResult2" class="bulkFail failAllTest2 btn btn-outline-danger" type="button">Fail All in Test Choice 2</button>
        <button name="interviewResult2" class="bulkFail failAllInt2 btn btn-outline-danger" type="button">Fail All in Interview Choice 2</button>
         </div>
    <h3 class="mt-3">Release results</h3>
    <form class="resultForm px-3" id="resultForm">
      <!--  <select name="rollno" class="rollno">
            <option value="" disabled selected>Select student</option> -->
            <input type="text" list="stData" class="email form-control" placeholder="Email/Name/Roll/Branch" name="email">
            <datalist id="stData">
            <?php
            while ($students = mysqli_fetch_assoc($qsql)) {
                echo "<option value='" . $students['email'] . "'>" . $students['fullname'] . " (" . $students['rollno'] ." ". $students['branch']. ")</option>";
            }
            ?>
            </datalist>
      <!--  </select> -->
       <select name="resultType" class="resultType form-select">
            <option value="" disabled selected>Select exam</option>
            <option value="testResult1">Choice 1 Test Result</option>
            <option value="testResult2">Choice 2 Test Result</option>
            <option value="interviewResult1">Choice 1 Interview Result</option>
            <option value="interviewResult2">Choice 2 Interview Result</option>
        </select>
      <button name="passBtn" class="passBtn btn btn-outline-success mt-3 px-4" type="button">Pass</button>
    </form>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter',
                    Swal.stopTimer)
                toast.addEventListener('mouseleave',
                    Swal.resumeTimer)
            }
        })
        $('.passBtn').click(function () {
            console.log($('#resultForm').serialize()+"&passBtn=passBtn")
            $.ajax({
                url: 'result_manipulate.php',
                type: 'post',
                data: $('#resultForm').serialize()+"&passBtn=passBtn",
                success: function (data) {
                    resType=data.includes('Passed');
                    if(resType){
                        resAction='success'
                    }else{
                        resAction='warning'
                    }
                    Toast.fire({
                    icon: resAction,
                    title: data
                  })
                    $('#resultForm')[0].reset();
                },
                error:function(err){
                    Toast.fire({
                    icon: 'error',
                    title: err
                  })

                }
            })
        })

        $('.bulkFail').click(function (){
            $.ajax({
                url:'bulkFail.php',
                type:'post',
                data:"bulkbtn="+$(this).attr("name"),
                success:function(resp){
                    Toast.fire({
                    icon: 'success',
                    title: resp
                  })
                  console.log(resp)
                },
                error:function(err1){
                    Toast.fire({
                    icon: 'error',
                    title: err
                  })
                  console.log(err1)
                }
            })
        })
    </script>
</body>

</html>