<?php
include "src/PHPMailer.php";
include "src/Exception.php";
include "src/OAuth.php";
include "src/POP3.php";
include "src/SMTP.php";
 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail() {
	try {
		$mail = new PHPMailer(true);       
		//Server settings
		$mail->SMTPDebug = 2;                                 // Enable verbose debug output
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'bachtran1928@gmail.com';                 // SMTP username
		$mail->Password = 'Adfectiver@1928';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to
	 
		//Recipients
		$mail->setFrom('bachtran1928@gmail.com', 'Mailer');
		$mail->addAddress('bachtran1928@gmail.com', 'Joe User');     // Add a recipient
		$mail->addAddress('txbachit@gmail.com');               // Name is optional
		$mail->addReplyTo('bachtran1928@gmail.com', 'Information');

	 
		//Content
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = 'Here is the subject';
		$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	 
		$mail->send();
		echo 'Message has been sent';
	} catch (Exception $e) {
		echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
	}
}
sendMail();
?>