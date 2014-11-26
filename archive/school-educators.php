<?php

if($_POST['submit'])
{

	$message="";
	if(strlen($_POST['fname'])>0){
		$message.="First Name : ".$_POST["fname"]."\n";
	}
	if(strlen($_POST['org_name'])>0){
		$message.="Name of your School/Organization : ".$_POST["org_name"]."\n";
	}
	if(strlen($_POST['title'])>0){
		$message.="Your Title : ".$_POST["title"]."\n";
	}
	if(strlen($_POST['address'])>0){
		$message.="School Address : ".$_POST["address"]."\n";
	}
	if(strlen($_POST['city'])>0){
		$message.="City : ".$_POST["city"]."\n";
	}
	if(strlen($_POST['state'])>0){
		$message.="State : ".$_POST["state"]."\n";
	}
	if(strlen($_POST['zip'])>0){
		$message.="Zip Code/Postal Code : ".$_POST["zip"]."\n";
	}
	if(strlen($_POST['phone'])>0){
		$message.="Phone : ".$_POST["phone"]."\n";
	}
	if(strlen($_POST['email'])>0){
		$message.="E-mail address : ".$_POST["email"]."\n";
	}
	if(strlen($_POST['bt_call'])>0){
		$message.="Best time to call : ".$_POST["bt_call"]."\n";
	}
	if(strlen($_POST['about_us'])>0){
		$message.="How did you hear about us? : ".$_POST["about_us"]."\n";
	}
	if(strlen($_POST['help'])>0){
		$message.="How may we help you? : ".$_POST["help"]."\n";
	}
	

    $from=$_POST["email"];
    $subject="School educators form";
    // message lines should not exceed 70 characters (PHP rule), so wrap it
    // send mail
    mail("info@tutorsonwheels.com",$subject,$message,"From: $from\n");
	
    
 header("location:school-educators.html");


}
?>