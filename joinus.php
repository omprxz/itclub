<!DOCTYPE html>
<?php 
date_default_timezone_set('Asia/Kolkata');

require('action/conn.php');
// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
// Set the expiry date and time
$keyToFetch = 'joinForm_expiry';
$result = $mysqli->query("SELECT value1 FROM extra WHERE key1 = '$keyToFetch'");
$row = $result->fetch_assoc();
$expiryDate = strtotime($row['value1']);

//$expiryDate = strtotime("2023-12-01 19:26:00"); // October 17, 2023, at 11:59:59 PM
// Get the current date and time
$currentDate = time();
?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Join IT Club</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="common.css" type="text/css" media="all" />

  <style>
    :root {
      --join-color-ink-lowest-contrast: rgba(47, 60, 85, 0.18);
      --join-color-ink-low-contrast: rgba(60, 60, 67, 0.3);
      --join-color-ink-medium-contrast: rgba(19, 19, 21, 0.6);
      --join-color-interaction: #1e4bd1;
      --join-color-interaction-minus-two: rgba(73, 133, 224, 0.12);
      --join-color-danger: #b50706;
      --join-color-bg-low-contrast: #eff1f2;
      --join-color-ink-high-contrast: #121212;
      --join-color-bg-high-contrast: #ffffff;

    }

    body {
      margin: 0;
    }

    .joinLabel {
      text-align: center;
      margin-bottom: 5px;
      color: white;
    }
    .formStatus{
      text-align: center;
      padding: 5px 10px;
      width: 60px;
      border-radius: 25px;
      margin: 10px auto;
    }
    .formStatusActive{
      color: #7cec00;
      box-shadow: #7cec00 0 0 9px 3px;
    }
    .formStatusInactive{
      color: #ec0000;
      box-shadow: #ec0000 0 0 9px 3px;
    }

    div.formContainer {
      display: flex;
      align-items: flex-start;
      justify-content: space-around;
      margin-top: 5px;
      padding: 21px;
      border-radius: 4px;
    }

    /** END: Non Openmrs CSS **/
    div.join-input-group {
      margin-bottom: 1.5rem;
      position: relative;
      width: 95%;
    }

    /* Input*/
    .join-input-filled>input {
      border: none;
      border-bottom: 0.125rem solid var(--join-color-ink-medium-contrast);
      width: 100%;
      height: 2rem;
      font-size: 1.0625rem;
      padding-left: 0.875rem;
      line-height: 147.6%;
      padding-top: 0.825rem;
      padding-bottom: 0.5rem;
      border-radius: 5px;
    }

    .join-input-filled>input:focus {
      outline: none;
    }

    .join-input-filled>.join-input-label {
      position: absolute;
      top: 0.9375rem;
      left: 0.875rem;
      line-height: 147.6%;
      color: var(--join-color-ink-medium-contrast);
      transition: top .2s;

    }

    .join-input-filled>.join-input-helper {
      font-size: 0.9375rem;
      color: var(--join-color-ink-medium-contrast);
      letter-spacing: 0.0275rem;
      margin: 0.125rem 0.875rem;
    }

    .join-input-filled>input:hover {}

    .join-input-filled>input:focus+.join-input-label,
    .join-input-filled>input:valid+.join-input-label {
      top: 0;
      font-size: 0.9375rem;
      margin-bottom: 32px;
      ;
    }

    .join-input-filled:not(.join-input-danger)>input:focus+.join-input-label {
      color: var(--join-color-interaction);
    }

    .join-input-filled:not(.join-input-danger)>input:focus {
      border-color: var(--join-color-interaction);
    }

    /** DANGER **/
    .join-input-filled.join-input-danger>.join-input-label,
    .join-input-filled.join-input-danger>.join-input-helper {
      color: var(--join-color-danger);
    }

    .join-input-danger>input {
      border-color: var(--join-color-danger);
    }

    .join-input-filled>input {
      background: var(--six-color);
    }

    .join-select {
      width: 105%;
      height: 3rem;
      border-radius: 5px;
    }

    .captchaDiv {
      display: flex;
      flex-direction: row;
      justify-content: space-around;
    }

    .captchaDiv .join-input-group {
      width: 37%;
      border-radius: 5px;
    }

    #captcha {
      width: 80px;
      color: red;
      font-weight: 400;
      height: 3.2rem;
      user-select: none;
      text-decoration: line-through;
      font-style: italic;
      font-size: x-large;
      border-radius: 5px;
      border: var(--fou-color) 2px solid;
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .regenerate {
      color: var(--six-color);
      width: 40px;
      height: 40px;
      text-align: center;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      font-size: 22px;
      align-items: center;
      margin-left: 15px;
      margin-top: 8px;
    }

    .submitBtnDiv {
      text-align: center;
    }

    .apply {
      display: relative;
      width: 110px;
      height: 45px;
      cursor: pointer;
      background: #389cff;
      border: 0;
      outline: none;
      border-radius: 7px;
    }
    .apply:disabled{
      background: #616161;
    }

    .apply span {
      color: var(--fir-color);
      font-size: 18px;
      font-weight: 500;
    }
  </style>

</head>

<body onload="generate()">

  <?php include('header.html'); ?>


  <h1 class="joinLabel">
    Join IT Club
  </h1>
  <?php
if ($currentDate > $expiryDate) {
    $fomrStatus = "Inactive";
    $fomrStatusClass="formStatusInactive";
    $formBtn="disabled";
} else {
$fomrStatus="Active";
$fomrStatusClass="formStatusActive";
$formBtn="";
}?>
  <p class="formStatus <?php echo $fomrStatusClass; ?>">
<?php echo $fomrStatus;?>
</p>
  <div class="formContainer">
    <form method="get" id="joinForm" class="joinForm">

      <div class="join-input-group">
        <label class="join-input-filled">
          <input name="fullname" class="fullname" required <?php echo $formBtn;?>>
          <span class="join-input-label">Full name</span>
        </label>
      </div>

      <div class="join-input-group">
        <label class="join-input-filled">
          <input name="rollno" class="rollno" required <?php echo $formBtn;?>>
          <span class="join-input-label">Roll no. (eg.  2023-CSE-02)</span>
        </label>
      </div>
      <div class="join-input-group">
        <label class="join-input-filled">
          <input name="email" class="email" required <?php echo $formBtn;?>>
          <span class="join-input-label">Email</span>
        </label>
      </div>

      <div class="join-input-group">
        <label class="join-input-filled">
          <input name="contact" type="number" class="contact" required <?php echo $formBtn;?>>
          <span class="join-input-label">Contact no.</span>
        </label>
      </div>

      <div class="join-input-group">
        <label class="join-input-filled">
          <input name="whatsapp" type="number" class="whatsapp" required <?php echo $formBtn;?>>
          <span class="join-input-label">Whatsapp No.</span>
        </label>
      </div>

      <div class="join-input-group">
        <label class="join-input-filled">
          <input name="session" list="sessions" class="session" required <?php echo $formBtn;?>>
          <span class="join-input-label">Session eg. 2023-26</span>
        </label>
      </div>
      <div class="join-input-group">
        <select name="branch" class="branch join-select" <?php echo $formBtn;?>>
          <option disabled selected>Select Branch</option>
          <option value="CSE">CSE</option>
          <option value="CE">CE</option>
          <option value="ECE">ECE</option>
          <option value="ME">ME</option>
          <option value="AUTOM">AUTOM</option>
          <option value="EE">EE</option>
        </select>
      </div>
      <div class="join-input-group">
        <select name="semester" class="semester join-select" <?php echo $formBtn;?>>
          <option disabled selected>Select Semester</option>
          <option value="1st">1st</option>
          <option value="2nd">2nd</option>
          <option value="3rd">3rd</option>
          <option value="4th">4th</option>
          <option value="5th">5th</option>
          <option value="6th">6th</option>
        </select>
      </div>
      <div class="join-input-group">
        <select name="choice1" class="choice1 join-select" <?php echo $formBtn;?>>
          <option value="" disabled selected>Select Your 1st Choice</option>
          <option value="Website Maintenance">Website Maintenance</option>
          <option value="Graphics Designing">Graphics Designing</option>
          <option value="Photography">Photography</option>
          <option value="Video Editing">Video Editing</option>
          <option value="Content Writing">Content Writing</option>
          <option value="Technical Team">Technical Team</option>
          <option value="Social Media Management">Social Media Management</option>
        </select>
      </div>
      <div class="join-input-group">
        <select name="choice2" class="choice2 join-select" <?php echo $formBtn;?>>
          <option value="">Select Your 2nd Choice</option>
          <option value="Website Maintenance">Website Maintenance</option>
          <option value="Graphics Designing">Graphics Designing</option>
          <option value="Photography">Photography</option>
          <option value="Video Editing">Video Editing</option>
          <option value="Content Writing">Content Writing</option>
          <option value="Technical Team">Technical Team</option>
          <option value="Social Media Management">Social Media Management</option>
        </select>
      </div>

      <div class="captchaDiv">
        <div class="join-input-group">
          <label class="join-input-filled">
            <input name="captcha" autocomplete="off" class="captcha-input" required <?php echo $formBtn;?>>
            <span class="join-input-label">Captcha</span>
          </label>
        </div>

        <div class="regenerate" onclick="generate()">
          <i class="fas fa-sync"></i>
        </div>

        <div id="captcha" class="inline" selectable="False"></div>

      </div>

      <div class="submitBtnDiv">
        <button type="button" class="apply" name="apply" <?php echo $formBtn;?>>
          <span>Apply</span>
        </button>
      </div>

    </form>
  </div>

  <?php include('footer.html'); ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function generate() {

      $('.captcha-input').val('')

      let uniquechar = "";

      const randomchar =
        "0123456789";

      for (let i = 1; i < 3; i++) {
        uniquechar += randomchar.charAt(
          Math.random() * randomchar.length)
      }

      $('#captcha').html(uniquechar)
    }
  </script>
  <script>
    $(document).ready(function () {

      // When the first choice is changed
      $('.choice1').on('change', function () {
        // Get the selected value
        var selectedValue = $(this).val();

        // If a value is selected, disable the corresponding option in the second choice
        if (selectedValue) {
          $('.choice2 option').prop('disabled', false);
          $('.choice2 option[value="' + selectedValue + '"]').prop('disabled', true);
        } else {
          // If no value is selected, enable all options in the second choice
          $('.choice2 option').prop('disabled', false);

        }
      });


      // When the second choice is changed
      $('.choice2').on('change',
        function () {
          // Get the selected value
          var selectedValue2 = $(this).val();

          // If a value is selected, disable the corresponding option in the second choice
          if (selectedValue2 != null) {
            $('.choice1 option').prop('disabled', false);
            $('.choice1 option[value="' + selectedValue2 + '"]').prop('disabled', true);
            $('.choice1 option[value=""]').prop('disabled',true)
          } else {
            // If no value is selected, enable all options in the second choice
            $('.choice1 option').prop('disabled', false);
            $('.choice1 option[value=""]').prop('disabled',true)
          }
        });
    });
  </script>

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

    $('.apply').click(function () {
      if ($('.captcha-input').val() != '') {
        let usr_input = $(".captcha-input").val();
        if ($(".captcha-input").val() == $('#captcha').html()) {
          generate();
          if ($('.fullname').val() != '' & $('.rollno').val() != '' & $('.email').val() != '' & $('.contact').val() != '' & $('.whatsapp').val() != '' & $('.session').val() != null & $('.branch').val() != null & $('.choice1').val() != null) {
        //    if (isValidEmail($('.email').val())) {
              $.ajax({
                url: 'action/joinrequest.php',
                type: 'get',
                data: $('#joinForm').serialize(),
                beforeSend: function () {
                  $('.apply').html('<i style="font-size:22px;color:black;" class="fas fa-spinner fa-spin"></i>')

                },
                complete: function () {
                  $('.apply').html('<span>Apply</span>')
                },
                success: function (data) {
                  if (data.includes('Applied')) {
                    var res = 'success';
                  } else {
                    var res = 'error'
                  }
                  Toast.fire({
                    icon: res,
                    title: data
                  })
                  
                 // $("#joinForm")[0].reset();
                  
                },
                error: function (error) {
                  Toast.fire({
                    icon: 'error',
                    title: error
                  })
                  console.log(error)
                }
              })
       /*     } else {
              generate();
              Toast.fire({
                icon: 'error',
                title: 'Invalid email!',
              })
            }*/
            function isValidEmail(emailAdd) {
              // Regular expression for email validation
              var emailRegex = /^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;

              return emailRegex.test(emailAdd);
            }
          } else {
            generate();
            Toast.fire({
              icon: 'info',
              title: 'All fields are mandatory!',
            })
          }
        } else {
          generate();
          Toast.fire({
            icon: 'error',
            title: 'Wrong Captcha'
          })
        }
      } else {
        generate();
        Toast.fire({
          icon: 'question',
          title: 'Enter Captcha'
        })
      }
    })
  </script>
</body>

</html>