<?php
/*
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//$pass='xsmtpsib-391595098995ee4a71e8c8a1aa83376d6a68828febaf22475952060fd623f6f6-4ErfHyjwRWdNKnCb';
$pass = 'xsmtpsib-ea44c8abb5bd3a8932909ccdd094477f96abae515ddd49ab22d90ea167d611e7-yDbYWgsdTv5UX8G9';
$user='ngpp13itclub@gmail.com';

$data = file_get_contents('failed_samp.json');
$entries = json_decode($data, true);
$sentEntries = [];

foreach ($entries as $entry) {
    $mail = new PHPMailer();

    $email = $entry['email'];
    $fullname = $entry['fullname'];
    $category = $entry['category'];

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp-relay.brevo.com';
        $mail->SMTPAuth = true;
        $mail->Port = 587;
        $mail->Username = $user;
        $mail->Password = $pass;

        $mail->setFrom('itclubngpp.4th@gmail.com', 'IT Club');
        $mail->addAddress($email, $fullname);

        $mail->isHTML(true);
        $mail->Subject = 'IT Club Entrance Exam Result';
        $mail->Body = '<b>Dear ' . $fullname . ',</b> <br> <br>
        We appreciate your participation in the IT Club recruitment process. After careful consideration, we regret to inform you that you have not been selected in <b>' . $category . '</b> category to proceed to the interview phase at this time. <br> <br>
        We want to thank you for your effort and interest. Don\'t be discouraged; your dedication is admirable, and we encourage you to explore other opportunities within the IT Club and beyond. <br> <br>
        Check out IT Club website for future hope & updates: <a href="https://itclub.000.pe">Click here</a>
        <p style="color:grey;font-weight:bold;">
        Best regards, <br>
        IT Club Team <br>
        </p>';

        $mail->send();

        $sentData = [
            'email' => $email,
            'fullname' => $fullname,
            'category' => $category
        ];
        $sentEntries[] = $sentData;

        //sleep(1);
    } catch (Exception $e) {
        echo "Email could not be sent to $email. Error: {$e->getMessage()}";
    }
}

$sentJSON = json_encode($sentEntries);
file_put_contents('sent5.json', $sentJSON);
*/
?>