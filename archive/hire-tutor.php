<?php

if($_POST['submit'])
{

	$message="";
	if(strlen($_POST['datepicker'])>0){
		$message.="Enrollment Date : ".$_POST["datepicker"]."\n";
	}
	if(strlen($_POST['fname'])>0){
		$message.="Student First Name : ".$_POST["fname"]."\n";
	}
	if(strlen($_POST['lname'])>0){
		$message.="Student Last Name : ".$_POST["lname"]."\n";
	}
	if(strlen($_POST['p_fname'])>0){
		$message.="Parent's First Name : ".$_POST["p_fname"]."\n";
	}
	if(strlen($_POST['p_lname'])>0){
		$message.="Parent's Last Name : ".$_POST["p_lname"]."\n";
	}
	if(strlen($_POST['address'])>0){
		$message.="Address : ".$_POST["address"]."\n";
	}
	if(strlen($_POST['city'])>0){
		$message.="City : ".$_POST["city"]."\n";
	}
	if(strlen($_POST['state'])>0){
		$message.="State : ".$_POST["state"]."\n";
	}
	if(strlen($_POST['zip'])>0){
		$message.="Zip : ".$_POST["zip"]."\n";
	}
	if(strlen($_POST['h_phone'])>0){
		$message.="Home Phone : ".$_POST["h_phone"]."\n";
	}
	if(strlen($_POST['w_phone'])>0){
		$message.="Work Phone : ".$_POST["w_phone"]."\n";
	}
	
	if(strlen($_POST['c_phone'])>0){
		$message.="Cell Phone : ".$_POST["c_phone"]."\n";
	}
	if(strlen($_POST['email'])>0){
		$message.="E-mail address : ".$_POST["email"]."\n";
	}
	if(strlen($_POST['bt_call'])>0){
		$message.="Best Times To Call Home : ".$_POST["bt_call"]."\n";
	}
	if(strlen($_POST['bt_work'])>0){
		$message.="Employment History\n";
		$message.="Best Times To Call Work : ".$_POST["bt_work"]."\n";
	}
	if(strlen($_POST['s_school'])>0){
		$message.="Student’s School : ".$_POST["s_school"]."\n";
	}
	if(strlen($_POST['s_subject'])>0){
		$message.="Student’s Subject : ".$_POST["s_subject"]."\n";
	}
	if(strlen($_POST['s_grade'])>0){
		$message.="Student’s Grade : ".$_POST["s_grade"]."\n";
	}
	if(strlen($_POST['child_disability'])>0){
		$message.="Does your child have a learning disability? : ".$_POST["child_disability"]."\n";
	}
	if(strlen($_POST['learn_about'])>0){
		$message.="How did you learn about us? : ".$_POST["learn_about"]."\n";
	}
	if(strlen($_POST['prefered_tutor'])>0){
		$message.="Where would you prefer tutoring? : ".$_POST["prefered_tutor"]."\n";
	}
	
	

    $from=$_POST["email"];
    $subject="Hire a tutor sign up form";
    // message lines should not exceed 70 characters (PHP rule), so wrap it
    // send mail
    mail("info@tutorsonwheels.com",$subject,$message,"From: $from\n");
	
    //echo '<script> alert("Thank you for your inquiry, a team member will be in touch shortly!"); </script>' ;
 header("location:hire-a-tutor.html");


}
?>