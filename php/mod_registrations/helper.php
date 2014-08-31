<?php
defined('_JEXEC') or die('Direct Access to this location is not allowed.');


class ModRegistrationsHelper
{
	/**
	 * Insert data into SAP
	 */
	
	function insertXmlData($targetUrl, $cardxml){
		
		$username = 'test';
		$password = 'test';
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $targetUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/4");
		curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Content-Type: text/xml"));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $cardxml);
		$output = curl_exec($ch);
		
		if(!$output) {
			echo "<h1>CURL ERROR: " . curl_error($ch) . "</h1>\n";
		}
		curl_close($ch);
		return $output;	
	}
	
	function sendMail ($xml, $errorMsg){

		// Buile Mail Headers
		$to =  'brad.haas@diono.com';
		$subject = "Failed Registration Attempt";		
		$headers = "Content-Type: text/html; charset = utf-8\r\n";
		$headers .= "From: Brad Haas<brad.haas@diono.com\r\n";
		
		// Extract error from B1 Execption envelope
		$dom = new DOMDocument();
		$dom->loadXML($errorMsg);
		$root = $dom->documentElement;
		$firstsibling = $root->firstChild;
		$secondsibling = $firstsibling->nextSibling;
		$thirdsibling = $secondsibling->nextSibling;
		$payloadmessage = $thirdsibling->firstChild;
		$error =  $payloadmessage->nodeValue;

		$message = '<span style="font-family:sans-serif;font-size:14px;">There was a problem with a Customer Registration submission</span><br /><br />';
		$message .= '<span style="font-family:sans-serif;font-size:12px;">Error: ' . $error . '</span><br /><br />';		
		$message .= '<span style="font-family:sans-serif;text-decoration:underline;font-size:12px;">The original input as submitted by user:</span><br />';
		$message .= '<span style="font-family:sans-serif;text-decoration:none;font-size:12px;">' .$xml . '</span>';
		
		$mailsent = mail($to, $subject, $message, $headers);
	}

	
} //end RegistrationsHelper
?>