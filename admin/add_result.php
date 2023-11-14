<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}

?>
<?php
require '../action/conn.php';
$sql = "select * from joinrequests";
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
        .resultForm input,.resultForm select,.resultForm button {
            height:30px;
        }

        h3 {
            text-align: center;
        }
    </style>

</head>

<body>
        <div style="display:flex;justify-content:center;gap:10px;height:50px;">
        <button name="testResult1" class="bulkFail failAllTest1" type="button">Fail All in Test Choice 1</button>
        <button name="interviewResult1" class="bulkFail failAllInt1" type="button">Fail All in Interview Choice 1</button>
         </div>
        <div style="display:flex;justify-content:center;gap:10px;height:50px;margin:5px;">
        <button name="testResult2" class="bulkFail failAllTest2" type="button">Fail All in Test Choice 2</button>
        <button name="interviewResult2" class="bulkFail failAllInt2" type="button">Fail All in Interview Choice 2</button>
         </div>
    <h3>Release results</h3>
    <form class="resultForm" id="resultForm">
      <!--  <select name="rollno" class="rollno">
            <option value="" disabled selected>Select student</option> -->
            <input type="text" list="stData" class="email" name="email">
            <datalist id="stData">
            <?php
            while ($students = mysqli_fetch_assoc($qsql)) {
                echo "<option value='" . $students['email'] . "'>" . $students['fullname'] . " (" . $students['rollno'] ." ". $students['branch']. ")</option>";
            }
            ?>
            </datalist>
      <!--  </select> -->
       <select name="resultType" class="resultType">
            <option value="" disabled selected>Select exam</option>
            <option value="testResult1">Choice 1 Test Result</option>
            <option value="testResult2">Choice 2 Test Result</option>
            <option value="interviewResult1">Choice 1 Interview Result</option>
            <option value="interviewResult2">Choice 2 Interview Result</option>
        </select>
      <button name="passBtn" class="passBtn" type="button">Pass</button>
    </form>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
                url: '../action/result_manipulate.php',
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
                url:'../action/bulkFail.php',
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