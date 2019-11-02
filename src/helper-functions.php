<?php

use SendGrid\Mail\Mail as SGMail;

function env($variable)
{
	try {
		return getenv($variable) ?? constant($variable);
	} catch (Exception $e) {
		throw new Exception("Unknown environment variable '{$variable}'");
	}
}

function send_mail($to,$subject,$message,$from_email='devclareo@gmail.com',$from_name='DevClareo',$type='sendgrid')
{
	switch ($type) {
		case 'sendgrid':
			$email = new SGMail();
			$email->setFrom($from_email, $from_name);
			$email->setSubject($subject);
			$email->addTo($to, null);
			$email->addContent("text/html", $message);
			$api_key = env('SENDGRID_API_KEY');
			$sendgrid = new SendGrid($api_key);
			try {
			    $response = $sendgrid->send($email);
			    return ($response->statusCode() == 202);
			} catch (Exception $e) {
				return false;
			}
			break;
		default:
			try {
				$headers = [];
				$headers[] = 'MIME-Version: 1.0';
				$headers[] = 'Content-type: text/html; charset=iso-8859-1';
				$headers[] = "From: $from_name <$from_email>";
				return mail($to, $subject, $message, implode("\r\n", $headers));
			} catch (Exception $e) {
				return false;
			}
			break;
	}
}