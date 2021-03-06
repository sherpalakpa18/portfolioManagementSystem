﻿<?php

// Replace this with your own email address
$siteOwnersEmail = 'user@website.com';


if($_POST) {

   $name = trim(stripslashes($_POST['contactName']));
   $email = trim(stripslashes($_POST['contactEmail']));
   $subject = trim(stripslashes($_POST['contactSubject']));
   $contact_message = trim(stripslashes($_POST['contactMessage']));
   $error = '';
   // Check Name
	if (strlen($name) < 2) {
		$error['name'] = "Please enter your name.";
	}
	// Check Email
	if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email)) {
		$error['email'] = "Please enter a valid email address.";
	}
	// Check Message
	if (strlen($contact_message) < 15) {
		$error['message'] = "Please enter your message. It should have at least 15 characters.";
	}
   // Subject
	if ($subject == '') { $subject = "Contact Form Submission"; }


   // Set Message
	$message = '';
	$message .= "Email from: " . $name . "<br />";
	$message .= "Email address: " . $email . "<br />";
	$message .= "Message: <br />";
	$message .= $contact_message;
	$message .= "<br /> ----- <br /> This email was sent from your site's contact form. <br />";

   // Set From: header
   $from =  $name . " <" . $email . ">";

   // Email Headers
	$headers = "From: " . $from . "\r\n";
	$headers .= "Reply-To: ". $email . "\r\n";
 	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


   if (!$error) {
   		include '../admin/classes/GMail.php';
   		include '../admin/classes/DB.php';
   		include '../admin/classes/Modal.php';
   		include '../admin/classes/Message.php';
   		include '../admin/classes/Config.php';
   		include '../admin/core/config.php';

      // ini_set("sendmail_from", $siteOwnersEmail); // for windows server
      // $mail = mail($siteOwnersEmail, $subject, $message, $headers);
   		$message = new Message;

   		try{
   			$mail = $message->create(array(
					'name' => $name,
					'email' => $email,
					'subject' => $subject,
					'message' => $contact_message,
				));

   			GMail::send($name, $email, $subject, $contact_message);
   			
   		}catch(Exception $e){
				die($e->getMessage());
			}
		if (isset($mail)) { 
			echo "OK"; 
		}else{ 
			echo "Something went wrong. Please try again.".$mail; 
		}
		
	} # end if - no validation error

	else {

		$response = (isset($error['name'])) ? $error['name'] . "<br /> \n" : null;
		$response .= (isset($error['email'])) ? $error['email'] . "<br /> \n" : null;
		$response .= (isset($error['message'])) ? $error['message'] . "<br />" : null;
		
		echo $response;

	} # end if - there was a validation error

}

?>