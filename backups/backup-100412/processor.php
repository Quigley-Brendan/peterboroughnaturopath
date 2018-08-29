<?php

	// setup variables
	$alert_status = 'none';
	$alert_message = '';
	global $alert_status, $alert_message; 

	date_default_timezone_set('America/Toronto');
	$date = date("M jS Y, h:iA T");
	

	// Check the Referrer (if local, thats cool, else deny access and email admin)
	if($_SERVER['HTTP_REFERER']){
		if(stristr($_SERVER['HTTP_REFERER'],"peterboroughnaturopath.ca")){
			$foreign = false;
		} else {
			$foreign = true;			
		}
	}

	if($foreign){
		echo "Foreign AJAX request attempted. Access denied. Event details logged.";

		// Get IP Address
		if (getenv(HTTP_CLIENT_IP)){
			$ip = getenv(HTTP_CLIENT_IP);
		} else {
			$ip=getenv(REMOTE_ADDR);
		}

		// Email Admin:
		$EmailMessage = "\n Foreign request attempted:\n\nDate: " . $date . "\n\nReferer: ".$_SERVER['HTTP_REFERER']."\n\nOriginating IP: " . $ip . "\n\n--End--";
		$EmailHeaders = "From: Services@PeterboroughNaturopath.ca \n";

		mail("steve@stevehiggs.com","Foreign request attempted and denied.", $EmailMessage, $EmailHeaders);
		exit;
	}
	

	// If POST Data exists
	if ($_SERVER['REQUEST_METHOD'] == 'POST' ){

	//			 C o n t a c t   f o r m    S u b m i t
	//_______________________________________________________________

		if ($_POST['action'] == "ContactForm"){

			// Set original alert status to green
			$alert_status = 'green';

			// Get posted values
			$name = $_POST['name'];
			$email = $_POST['email'];
			$subject = stripslashes($_POST['subject']);
			$message = stripslashes($_POST['message']);

			// Define the Date
			date_default_timezone_set('America/Toronto');
			$date = date("M jS Y, h:iA T");
	
	
			// Get IP Address
			if (getenv(HTTP_CLIENT_IP)){
				$ip = getenv(HTTP_CLIENT_IP);
			} else {
				$ip=getenv(REMOTE_ADDR);
			}
			

			// Check for required variables
			if ($name == '' || $message == '')
			{
				$alert_status = 'red';
				$alert_message .= "<p>Sorry, You forgot to fill in one of the required fields (Name/Message).</p> \n";
			}
		
	
			if ($alert_status == "green"){

				if ($subject == '')
					$subject = '[No Subject]';
	
				// Email Admin:
				$EmailHeaders = "From: Services@PeterboroughNaturopath.ca \n";
				$site = "PeterboroughNaturopath.ca";

				$EmailMessage = "\nEmail message received from contact form at ".$site.":\n\nDate: " . $date . "\n\nName:  " . $name . "\n\nEmail:  " . $email . "\n\nSubject:  " . $subject . "\n\nMessage: \n \n" . $message . "\n\nOriginating IP: " . $ip . "\n\nReferrer: ".$_SERVER['HTTP_REFERER']."\n\n--End--";					
				$EmailHeaders .= "Reply-To: ".$name." <".$email.">\n";
	
				mail("info@PeterboroughNaturopath.ca",$subject . " - From ". $name . " - ".$site." Email Form", $EmailMessage, $EmailHeaders);
	
				// Try to extract the user's first name
				$first_name = strtok($name," ");

				// If all we got was Mr., or Mrs etc than just use the full provided name
				if($first_name == "Mr." || $first_name == "Mrs." || $first_name == "Ms." || $first_name == "Miss")
					$first_name = $name;
				
				$alert_message = "<p>Thank you ". $first_name .",<br />Your message has been sent successfully.</p><p>we will get back to you as soon as we can.</p> \n";

				if($fleming)
					$alert_message .= "<p><a href=\"javascript:history.back();\">&lt; Back</a></p> \n";

			}

			
	//			 		T e m p l a t e
	//_______________________________________________________________
			
		} elseif ($_POST['action'] == "template"){

			$alert_status = 'none';
		
		
		
// No Action received:
		} else {
			$alert_status = 'red';
			$alert_message .= "<p>No Recognized action provided</p> \n";
			
		}

// No POST received:
	} else {
		$alert_status = 'red';
		$alert_message .= "<p>No post data recieved.</p> \n";
	}		
	

// Final echo out of result alert message:
	if($alert_status != "none"){
		echo "<div class=\"alert ". $alert_status . "\"> \n";
		echo "	".$alert_message;
		echo "</div>";
	}

?>