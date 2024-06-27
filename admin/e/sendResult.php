<?php
/*

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../libs/PHPMailer/src/Exception.php';
require '../../libs/PHPMailer/src/PHPMailer.php';
require '../../libs/PHPMailer/src/SMTP.php';

//$jsonData = file_get_contents('result.json');
//$data = json_decode($jsonData, true);



$mail = new PHPMailer(true);
$user=['itclubngpp.4th@gmail.com','ngppitclub@gmail.com'];
$pass=['sbvs elid cbzo iebe','pyus wmoo bqlq tfnl'];

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Port = 587;
    $mail->Username = $user[1];
    $mail->Password = $pass[1];

    $mail->setFrom('ngppitclub@gmail.com', 'IT Club');
    $mail->isHTML(true);
    $mail->Subject = 'Welcome to IT Club! ';
    
    foreach ($data as $entry) {
        $mail->addAddress($entry['Email'], $entry['Name']);
        $message = '
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="x-apple-disable-message-reformatting">
        <meta name="description" content="Congratulations! You are In: IT Club Selection & Important Updates">
        <div style="padding:15px;background:lightgrey;border-radius:5px;">
            <hr>
            <h3>Congratulations ' . $entry['Name'] . '</h3>
            Great news! <br><br>
            You made it into the IT Club! <br><br>
            Your hard work and talents got you a spot with us. You have been selected for the <b>' . $entry['Category'] . '</b> category.<br><br>
             We really need what you can bring to our club. We\'re thrilled about your ideas and enthusiasm. <br><br><br>
            
            <span style="color:red;"><b>Important Notice:</b></span> <br><br>
            Also, to check your results, visit our IT Club website or click this link: <a href="https://itclub.000.pe/result.php" style="color:red;">IT Club Results</a><br><br>
            If you\'ve qualified, you\'ll be added to our IT Club WhatsApp group by 7 PM this evening. If not added, please contact us on WhatsApp at <b><a href="https://wa.me/916200181564?text=I%27ve%20qualified%20for%20the%20' . $entry['Category'] . '%20but%20still%20haven%27t%20been%20added%20to%20IT%20Club%27s%20WhatsApp%20group." style="color:green;">6200181564<a/></b> to join.<br><br>
            Stay tuned for further updates through our WhatsApp group.<br><br><br>
            Congratulations once again and see you there!
            <br><br>
            Welcome to the team!
            <hr>
        </div>
        ';
        $mail->Body = $message;
        $mail->send();
        $mail->clearAddresses();
        echo($entry['Email'].'<br>');
    }
    
    echo '<br>Email sent successfully!<br>';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

*/
?>