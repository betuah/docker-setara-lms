<?php
date_default_timezone_set('Asia/Jakarta');
require_once("../../config/database.php");
require '../PHPMailerAutoload.php';

$stmt	= $PDOconn->prepare("SELECT 
								mt.*, TIMESTAMPDIFF(HOUR, mt.date_created, NOW()) as daterange,
								me.name as esk, 
								mec.name as eskC, mec.email as eskCemail 
							FROM m_ticket mt 
								JOIN m_eskalasi me ON TIMESTAMPDIFF(HOUR, mt.date_created, NOW()) > 24
								JOIN m_eskalasi_contact mec ON mec.eskalasi = me.ID
							WHERE mt.`status` = 'Pending'");
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$mail = new PHPMailer;

$mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;

$mail->Username = "kegunaan.lainlain@gmail.com";
$mail->Password = "Edodoe291092";
$mail->setFrom('no-reply@pustekkom.com', 'Percobaan Awal');
$mail->addReplyTo('no-reply@pustekkom.com', 'Percobaan Awal');

//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';

foreach ($data as $row) {
	$mail->Subject = 'Eskalasi No.Tiket $row[ID]';
	$body = "<html><body style='font-size: 100%; border: 2px solid #000; border-radius: 5px; padding: 25px;'>";
	$body .= "Selamat Siang <b>$row[eskC]</b>,<br />
				<hr style='width: 100%'><br />
				<p>Saat ini Tiket dengan <b>No.Tiket $row[ID]</b> telah memasukki <b>$row[esk]</b>.</p>
				<br />
				Silakan lanjutkan informasi ini kepada Agent yang mengurus Tiket tersebut agar segera ditindaklanjuti. Untuk melihat detail tiket tekan tombol dibawah ini :<br /><br />
				<center>
					<a href='http://118.98.227.236/ticket_detail.php?no=$row[ID]' style='padding: 10px; border: 1px solid #2e6da4; border-radius: 8px; background: #337ab7; color: #fff; font-size:16px; font-weight: bold; text-decoration: none;'>Tiket Info</a>
				</center>
				<br /><br />
				Terima Kasih.<br />
				<b>Administrator Helpdesk PUSTEKKOM</b><br /><br />
				<hr style='width: 100%'>
				<a href='http://118.98.227.236/'><b>Helpdesk PUSTEKKOM</b></a><br />
				<hr style='width: 100%'>";
	$body .= "</body></html>";
	
	$mail->addAddress($row['eskCemail'], $row['eskC']);
	$mail->msgHTML($body);
	
	if (!$mail->send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent to $row[eskCemail] at ".date('d-m-Y H:i:s')."<br />";
	}
	
	$mail->clearAddresses();
}
?>