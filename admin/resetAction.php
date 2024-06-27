<?php
date_default_timezone_set('Asia/Kolkata');
require('../action/conn.php');
$response = array();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../libs/PHPMailer/src/Exception.php';
require '../libs/PHPMailer/src/PHPMailer.php';
require '../libs/PHPMailer/src/SMTP.php';
    
function generateOTP() {
    $otp = rand(1000, 9999);
    return $otp;
}
function getOtp($useridF,$mysqli) {
  $checkUserid = $mysqli->query("select admin_id,admin_name,admin_email,admin_username from adminCreds where admin_email = '$useridF' or admin_username ='$useridF'");
  if ($checkUserid->num_rows > 0) {
    $userData=$checkUserid->fetch_assoc();

    
    $mail = new PHPMailer(true);
    
    $user=['itclubngpp.4th@gmail.com','ngppitclub@gmail.com','itclubngpp.4th@gmail.com'];
    $pass=['sbvs elid cbzo iebe'];
    $subject='IT Club Password Reset OTP';
    $otp=generateOTP();
  /*  $message='<p>Hello '.$userData["admin_name"].',</p>
    <p>Here is your one-time password of IT Club Reset Password Request: '.$otp.'</p>
    <p>This OTP will expire shortly. Please use it for verification purposes.</p>
    <p>If you didn\'t request this OTP, please disregard this message.</p>'; */
    
    $message = '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Static Template</title>

    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap"
      rel="stylesheet"
    />
  </head>
  <body
    style="
      margin: 0;
      font-family: \'Poppins\', sans-serif;
      background: #ffffff;
      font-size: 14px;
    "
  >
    <div
      style="
        max-width: 680px;
        margin: 0 auto;
        padding: 45px 30px 60px;
        background: #f4f7ff;
        background-image: url(https://archisketch-resources.s3.ap-northeast-2.amazonaws.com/vrstyler/1661497957196_595865/email-template-background-banner);
        background-repeat: no-repeat;
        background-size: 800px 452px;
        background-position: top center;
        font-size: 14px;
        color: #434343;
      "
    >
      <header>
        <center>
                <img
                  alt="IT CLUB"
                  src="https://itclub.000.pe/img/itclublogo.webp"
                  height="110px" width="110px" style="border-radius:50%;"
                />
        </center>
      </header>

      <main>
        <div
          style="
            margin: 0;
            margin-top: 70px;
            padding: 92px 30px 115px;
            background: #ffffff;
            border-radius: 30px;
            text-align: center;
          "
        >
          <div style="width: 100%; max-width: 489px; margin: 0 auto;">
            <h1
              style="
                margin: 0;
                font-size: 24px;
                font-weight: 500;
                color: #1f1f1f;
              "
            >
              Your OTP
            </h1>
            <p
              style="
                margin: 0;
                margin-top: 17px;
                font-size: 16px;
                font-weight: 500;
              "
            >
              Hey '.$userData['admin_name'].',
            </p>
            <p
              style="
                margin: 0;
                margin-top: 17px;
                font-weight: 500;
                letter-spacing: 0.56px;
              "
            >
              This OTP will expire shortly. Please use it for verification purposes.
              <span style="font-weight: 600; color: #1f1f1f;"> This OTP will expire in 5 hours</span>.
              If you didn\'t request this OTP, please disregard this message.
            </p>
             <p
              style="
                margin: 0;
                margin-top: 60px;
                font-size: 40px;
                font-weight: 800;
                letter-spacing: 25px;
                color: #ba3d4f;
              "
            >
              '.$otp.'
            </p>
          </div>
        </div>

        <p
          style="
            max-width: 400px;
            margin: 0 auto;
            margin-top: 90px;
            text-align: center;
            font-weight: 500;
            color: #8c8c8c;
          "
        >
          Need help? Ask at
          <a
            href="mailto:itclubngpp.4th@gmail.com"
            style="color: #499fb6; text-decoration: none;"
            >itclubngpp.4th@gmail.com</a
          >
          or visit our
          <a
            href="https://itclub.000.pe/#contact"
            target="_blank"
            style="color: #499fb6; text-decoration: none;"
            >Help Center</a
          >
        </p>
      </main>

      <footer
        style="
          width: 100%;
          max-width: 490px;
          margin: 20px auto 0;
          text-align: center;
          border-top: 1px solid #e6ebf1;
        "
      >
        <p
          style="
            margin: 0;
            margin-top: 40px;
            font-size: 16px;
            font-weight: 800;
            color: #434343;
          "
        >
          IT Club
        </p>
        <p style="margin: 0; margin-top: 8px; color: #434343;">
          New Govt. Polytechnic Patliputra,Patna, Bihar, India, 800013
        </p>
        <div style="margin: 0; margin-top: 16px;">
          <a
            href="https://www.instagram.com/_itclub_official"
            target="_blank"
            style="display: inline-block; margin-left: 8px;"
          >
            <img
              width="36px"
              alt="Instagram"
              src="https://archisketch-resources.s3.ap-northeast-2.amazonaws.com/vrstyler/1661504218208_684135/email-template-icon-instagram"
          /></a>
        </div>
        <p style="margin: 0; margin-top: 16px; color: #434343;">
          Copyright © 2024 IT Club. All rights reserved.
        </p>
      </footer>
    </div>
  </body>
</html>
';
    
    $userId=$userData['admin_id'];
    $userEmail = $userData['admin_email'];
    $userName=$userData['admin_name'];
    
    
    try {
      $mail->isSMTP();
      //$mail->Host = 'smtp-relay.brevo.com';
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Port = 587;
      $mail->Username = $user[0];
      $mail->Password = $pass[0];

      // Sender and recipient settings
      $mail->setFrom('itclubngpp.4th@gmail.com', 'IT Club');
      $mail->addAddress($userEmail, $userName);


      // Email content
      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body = $message;

      // Send email
      $mail->send();
      
      $otpexpiry= date('Y-m-d H:i:s', strtotime('+1 hour', strtotime('now', strtotime('Asia/Kolkata'))));
      
      $saveOtp = $mysqli->query("insert into otps (otpuserid,otp,otpexpiry) values('$userId','$otp','$otpexpiry')");
      
      $response['status'] = 'success';
      $response['result'] = 'OTP Sent';
      echo(json_encode($response));
      exit();
    } catch (Exception $e) {
       $response['status'] = 'failed';
       $response['result'] = 'Failed to send OTP'.$mail->ErrorInfo;
       echo(json_encode($response));
       exit();
    }
  } else {
    $response['status'] = 'failed';
    $response['result'] = 'User not found';
    echo(json_encode($response));
    exit();
  }
}

function verifyOtp($useridF,$otpF,$mysqli){
  $currentTime = date('Y-m-d H:i:s', strtotime('now', strtotime('Asia/Kolkata')));
   $checkUserid = $mysqli->query("select admin_id,admin_name,admin_email,admin_username from adminCreds where admin_email = '$useridF' or admin_username ='$useridF'");
   $userData=$checkUserid->fetch_assoc();
   $userId=$userData['admin_id'];
  $verifyOtpQuery = $mysqli->query("select otp,otpid from otps where otpuserid = '$userId' AND otpisused = false AND otpexpiry > '$currentTime' order by otptimestamp desc limit 1");
  if($verifyOtpQuery->num_rows>0){
    $realOtpData =$verifyOtpQuery->fetch_assoc();
    $realOtp =$realOtpData['otp'];
    $otpid =$realOtpData['otpid'];
    if($realOtp==$otpF){
        $response['status'] = 'success';
        $response['result'] = 'OTP Verified';
        echo(json_encode($response));
        exit();
    }else{
      $response['status'] = 'failed';
      $response['result'] = 'Invalid OTP';
      echo(json_encode($response));
      exit();
    }
  }else{
    $response['status'] = 'failed';
    $response['result'] = 'Invalid OTP';
    echo(json_encode($response));
    exit();
  }
  
}

function changePassword($useridF,$otpF,$passF,$mysqli){
   $currentTime = date('Y-m-d H:i:s', strtotime('now', strtotime('Asia/Kolkata')));
    $checkUserid = $mysqli->query("select admin_id,admin_name,admin_email,admin_username from adminCreds where admin_email = '$useridF' or admin_username ='$useridF'");
   $userData=$checkUserid->fetch_assoc();
   $userId=$userData['admin_id'];
   $verifyOtpQuery = $mysqli->query("select otp,otpid from otps where otpuserid = '$userId' AND otpisused = false AND otpexpiry > '$currentTime' order by otptimestamp desc limit 1");
  if($verifyOtpQuery->num_rows>0){
    $realOtpData =$verifyOtpQuery->fetch_assoc();
    $realOtp =$realOtpData['otp'];
    $otpid =$realOtpData['otpid'];
    if($realOtp==$otpF){
      $expireOtp = $mysqli->query("update otps set otpisused = true where otpid = $otpid");
      if($expireOtp){
        $checkUserid = $mysqli -> query("select admin_id,admin_name,admin_email,admin_username from adminCreds where admin_email = '$useridF' or admin_username ='$useridF'");
        if($checkUserid->num_rows>0){
          $admId=$checkUserid->fetch_assoc()['admin_id'];
          $changePass = $mysqli->query("update adminCreds set admin_pass = '$passF' where admin_id = $admId");
          if($changePass){
            $response['status'] = 'success';
        $response['result'] = 'Password changed';
        echo(json_encode($response));
        exit();
          }else{
            $response['status'] = 'failed';
        $response['result'] = 'Failed to change password';
        echo(json_encode($response));
        exit();
          }
        }else{
          $response['status'] = 'failed';
        $response['result'] = 'User not found';
        echo(json_encode($response));
        exit();
        }
        
      }else{
        $response['status'] = 'failed';
        $response['result'] = 'Bad Request';
        echo(json_encode($response));
        exit();
      }
    }else{
      $response['status'] = 'failed';
      $response['result'] = 'Invalid OTP';
      echo(json_encode($response));
      exit();
    }
  }else{
    $response['status'] = 'failed';
    $response['result'] = 'Invalid OTP';
    echo(json_encode($response));
    exit();
  }
}

if($_POST['action']=='getOtp'){
  //$user_id = $_POST['userid'];
  getOtp($_POST['userid'],$mysqli);
}elseif($_POST['action']=='verifyOtp'){
  verifyOtp($_POST['userid'],$_POST['otp'],$mysqli);
}elseif($_POST['action']=='changePass'){
  changePassword($_POST['userid'],$_POST['otp'],$_POST['pass'],$mysqli);
}else{
  $response['status'] = 'failed';
    $response['result'] = 'Invalid action';
    echo(json_encode($response));
    exit();
}
?>