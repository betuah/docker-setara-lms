<?php

error_reporting(E_STRICT | E_ALL);

date_default_timezone_set('Etc/UTC');

require '../PHPMailerAutoload.php';

$mail = new PHPMailer;

$body = file_get_contents('contents.html');

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->SMTPKeepAlive = true; // SMTP connection will not close after each email sent, reduces SMTP overhead
$mail->Port = 25;
$mail->Username = 'pansedo@gmail.com';
$mail->Password = 'Edodoe291092';
$mail->setFrom('no-reply@pustekkom.com', 'Helpdesk Pustekkom');
$mail->addReplyTo('no-reply@pustekkom.com', 'Helpdesk Pustekkom');

$mail->Subject = "PHPMailer Simple database mailing list test";

//Same body for all messages, so set this before the sending loop
//If you generate a different body for each recipient (e.g. you're using a templating system),
//set it inside the loop
$mail->msgHTML($body);
//msgHTML also sets AltBody, but if you want a custom one, set it afterwards
$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';

// //Connect to the database and select the recipients from your mailing list that have not yet been sent to
// //You'll need to alter this to match your database
// $mysql = mysqli_connect('localhost', 'username', 'password');
// mysqli_select_db($mysql, 'mydb');
// $result = mysqli_query($mysql, 'SELECT full_name, email, photo FROM mailinglist WHERE sent = false');

$listname	= array(["pansedo@gmail.com", "Pansedo"],["pansera.oktasedu@gmail.com", "Pansera"]);
foreach ($listname as $row) { //This iterator syntax only works in PHP 5.4+
    $mail->addAddress($row[0], $row[1]);
	$mail->addAttachment('images/phpmailer_mini.png');

    if (!$mail->send()) {
        echo "Mailer Error (" . str_replace("@", "&#64;", $row[0]) . ') ' . $mail->ErrorInfo . '<br />';
        break; //Abandon sending
    } else {
        echo "Message sent to :" . $row[1] . ' (' . str_replace("@", "&#64;", $row[0]) . ')<br />';
    }
    // Clear all addresses and attachments for next loop
    $mail->clearAddresses();
    $mail->clearAttachments();
}
