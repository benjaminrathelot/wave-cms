<?php
//WV_contact_form 2015 Agencys/Benjamin Rathelot
// 4 Juillet 2015
$conf = $db->get("wv_contact_form:config");
if(isset($_POST['data'])) {
	$_POST = $_POST['data'];
} 
if(isset($_POST['field'], $_POST['message'], $_POST['captcha'], $_SESSION['WV_CAPTCHA'])) {
	if($sha1($_POST['captcha'])!=$_SESSION['WV_CAPTCHA']) {
		$_SESSION['WV_CAPTCHA'] = sha1(mt_rand(1,9999)); //Might be used for avanced bruteforce security
		$echo->error("Invalid captcha.");
		exit;
	}
	$fields = (array) $conf->fields;
	foreach($fields as $field) {
		if(!isset($_POST['field'][$field])) {
			$echo->error("Missing field: ".$field);
			exit;
		}
		else
		{
			if(preg_match("#mail#", $field) AND !filter_var($_POST['field'][$field], FILTER_VALIDATE_EMAIL)) {
				$echo->error("Invalid $field format.");
				exit;
			}
		}
	}
	$_SESSION['WV_CAPTCHA'] = sha1(mt_rand(1,9999)); // Reset the CAPTCHA anyway.
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= "From: ".$conf->fromMail."\r\n".$conf->customHeaders;
	$content = "<i>".nl2br(htmlspecialchars(print_r($_POST['field'])))."</i><br /><br />".htmlspecialchars($_POST['message']);
	if(mail($conf->mail, $conf->subject, $content, $headers)) {
		$echo->ok("Message sent.");
	}
	else
	{
		$echo->error("Unable to send mail, please try again.");
	}
}