<?
if(!empty($_POST))
{
		$filename = "contact.html";	
		if(email_send($filename,$_POST))
		{
			$msg = "Your contact information has been sent successfully";
			header("Location: contactus.php?msg=".rawurlencode($msg));
		}
}	
function email_send($filename='',$postval='')
{
		if(file_exists($filename)) 
		{
			$body=file_get_contents($filename);	
			foreach($postval as $key => $value)
			{				
				$body=str_replace("{".$key."}",$value,$body);
			}	
		}
		
		$fileatt_type = "application/octet-stream"; // File Type 

		$email_from = $postval['email']; // Who the email is from 
		$email_subject = "Contact Details";
		$email_message = ""; // Message that the email has in it 
		$email_to = "shahid7in@gmail.coms"; // Who the email is too 
		$headers = "From: ".$email_from; 
		$semi_rand = md5(time()); 
		$fileatt_name = "Tutor Detail Requested By Businesses";
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
			
		$headers .= "\nMIME-Version: 1.0\n" . 
					"Content-Type: multipart/mixed;\n" . 
					" boundary=\"{$mime_boundary}\""; 
		
		$body = chunk_split(base64_encode($body)); 
		$fileatt_type = "text/html";
		$email_message .= "--{$mime_boundary}\n" . 
						  "Content-Type: {$fileatt_type};\n" . 
						  " name=\"{$fileatt_name}\"\n" . 
						  "Content-Transfer-Encoding: base64\n\n" . 
						 $body . "\n\n" . 
						  "--{$mime_boundary}\n"; 
		
				
		$response = @mail($email_to, $email_subject, $email_message, $headers); 
		
		
		//$response = mail($to, $subject, $body, $headers);
		if($response)
			return true;
		else
			return false;
}
?>