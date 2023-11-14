<?php
require 'action/conn.php';
$sql = "select * from joinrequests order by fullname";

$qsql = mysqli_query($mysqli, $sql);


?>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">
  <title>Check Result - IT Club</title>
  <link rel="stylesheet" href="common.css">
  <style>
    .email {
      outline: 0;
      border: 1px solid #0d0d0d;
      height: 40px;
      width: 250px;
      margin: 5px;
      border-radius: 6px;
      padding: 10px;
    }
    .showResult{
      margin-bottom: 50px;
    }
    .showResultBg {
      margin-top: 50px;
      width: clamp(150px,75%,400px);
    }
    .inputsDiv {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 5px;
    }

    .inputsDiv select {
      height: 30px;
      margin: 10px;
      width: 200px;
      border-radius: 6px;
    }

    .inputsDiv button {
      width: 110px;
      height: 40px;
      border: 0;
      margin: 5px;
      background-color: #3685ff;
      border-radius: 5px;
      font-weight: 500;
      transition: all 0.4s;
      cursor: pointer;
    }

    .inputsDiv button:hover {
      background-color: red;
      color: white;
    }

    .checkResultLabel {
      text-align: center;
      color: var(--six-color);
      margin-bottom: 5px;
      margin-top: 15px;
    }
  </style>
</head>

<body>
  <?php include 'header.html'; ?>

  <h2 class="checkResultLabel">Check Result</h2>
  <div class="inputsDiv">
    <!-- <select name="rollno" class="rollno">
                <option value="" disabled selected>Select student</option>
                <?php /*
            while ($students = mysqli_fetch_assoc($qsql)) {
                echo "<option value='" . $students['rollno'] . "'>" . $students['fullname'] . " (" . $students['rollno'] . ")</option>";
            }*/
    ?>
            </select> -->

    <input type="text" name="email" class="email" placeholder="Enter your email" />
  <button name="checkResult" class="checkResult">Check Result</button>
</div>
<div class="showResult">
  <center><img src="img/resultill.svg" alt="Result Image" class="showResultBg"></center>
</div>
<?php include 'footer.html'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
$('.checkResult').click(function () {
$.ajax({
url: 'action/fetch_result.php',
type: 'get',
data: "email=" + $('.email').val(),
 beforeSend: function (){$(this).text("Checking...");
},
success: function (data) {
 $(this).text("Check Result");
var resultHtml = '<style>.results{color:var(--six-color);margin:5px 20px;}.pending{color:#3586ff;}.failed{color:red;}.passed{color:green;}</style><h3 style="text-align: center;margin-bottom: 10px;color: var(--six-color);">' + data['response']['fullname'] +' (4th Induction)</h3>'
if (data['status'] == 'success') {
if (data['response']['testResult1'] == 1) {
testResult1 = 'Passed'
resStatust1 = 'passed'
} else if (data['response']['testResult1'] == -1) {
testResult1 = 'Failed'
resStatust1 = 'failed'
} else {
testResult1 = 'Pending'
resStatust1 = 'pending'
}

if (data['response']['interviewResult1'] == 1) {
interviewResult1 = 'Passed'
resStatusi1 = 'passed'
} else if (data['response']['interviewResult1'] == -1) {
interviewResult1 = 'Failed'
resStatusi1 = 'failed'
} else {
interviewResult1 = 'Pending'
resStatusi1 = 'pending'
}


resultHtml += '<h4 style="text-align: center;margin-bottom: 10px;color: var(--fif-color);">1st choice - ' + data['response']['choice1'] + '</h4><div class="result1Html"><div class="results">Test Result > <span class="'+resStatust1+'">' + testResult1 + '</span></div><div class="results">Interview Result > <span class="'+resStatusi1+'">' + interviewResult1 + '</span></div></div>'

if (data['response']['choice2']) {
if (data['response']['testResult2'] == 1) {
testResult2 = 'Passed'
resStatust2 = 'passed'
} else if (data['response']['testResult2'] == -1) {
testResult2 = 'Failed'
resStatust2 = 'failed'
} else {
testResult2 = 'Pending'
resStatust2 = 'pending'
}

if (data['response']['interviewResult2'] == 1) {
interviewResult2 = 'Passed'
resStatusi2 = 'passed'
} else if (data['response']['interviewResult2'] == -1) {
interviewResult2 = 'Failed'
resStatusi2 = 'failed'
} else {
interviewResult2 = 'Pending'
resStatusi2 = 'pending'
}

resultHtml += '<h4 style="text-align: center;margin-bottom: 10px;color: var(--fif-color);">2nd choice - ' + data['response']['choice2'] + '</h4><div class="result2Html"><div class="results">Test Result > <span class="'+resStatust2+'">' + testResult2 + '</span></div><div class="results">Interview Result > <span class="'+resStatusi2+'">' + interviewResult2 + '</span></div></div>'
}
$('.showResult').html(resultHtml)
} else {
Toast.fire({icon:'error',title:'Student not found!'})
}

},
error: function (err) {
Toast.fire({
icon: 'error',
title: err
})
console.log(err);
}
})
})
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>