<?php
if($_POST['submit'])
{
	$upload_dir="upload/";
	$file=$_FILES['upload']['name'];
	$filename=str_replace(" ","_",$file); 
	if($filename<>"")
	{
		$upload_file=$upload_dir.$filename;
		if(file_exists($upload_file))
		{
			$cnt=1;
			while(file_exists($upload_file))
			{
				if(!file_exists($upload_dir.$cnt.$filename))
				{
					$upload_file=$upload_dir.$cnt.$filename;
					$filename=$cnt.$filename;
				}
				else
				{
					$cnt++;
				}
			}
		}
			move_uploaded_file($_FILES['upload']['tmp_name'],$upload_file);
	}//image upload code end 
	
	$files= "<a href='http://endtoenddesigns.com/tutorsonwheels/".$upload_dir.$filename."'>".$filename."</a>"; 
	$message="";
	if(strlen($_POST['datepicker'])>0){
		$message.="Date : ".$_POST["datepicker"]."<br>";
	}
	if(strlen($_POST['fname'])>0){
		$message.="First Name : ".$_POST["fname"]."<br>";
	}
	if(strlen($_POST['mname'])>0){
		$message.="Middle Name : ".$_POST["mname"]."<br>";
	}
	if(strlen($_POST['lname'])>0){
		$message.="Last Name : ".$_POST["lname"]."<br>";
	}
	if(strlen($_POST['address'])>0){
		$message.="Address : ".$_POST["address"]."<br>";
	}
	if(strlen($_POST['country'])>0){
		$message.="Country : ".$_POST["country"]."<br>";
	}
	if(strlen($_POST['zip'])>0){
		$message.="Zip : ".$_POST["zip"]."<br>";
	}
	if(strlen($_POST['c_address'])>0){
		$message.="How long at current address : ".$_POST["c_address"]."<br>";
	}
	if(strlen($_POST['h_phone'])>0){
		$message.="Home Phone : ".$_POST["h_phone"]."<br>";
	}
	if(strlen($_POST['w_phone'])>0){
		$message.="Work Phone : ".$_POST["w_phone"]."<br>";
	}
	
	if(strlen($_POST['c_phone'])>0){
		$message.="Cell Phone : ".$_POST["c_phone"]."<br>";
	}
	if(strlen($_POST['alt_number'])>0){
		$message.="Alternate Number : ".$_POST["alt_number"]."<br>";
	}
	if(strlen($_POST['email'])>0){
		$message.="E-mail address : ".$_POST["email"]."<br><br>";
	}
	
	if(strlen($_POST['c_employer'])>0){
		$message.="Employment History<br>";
		$message.="Name of Current Employer : ".$_POST["c_employer"]."<br>";
	}
	if(strlen($_POST['address1'])>0){
		$message.="Address : ".$_POST["address1"]."<br>";
	}
	if(strlen($_POST['telephone'])>0){
		$message.="Telephone : ".$_POST["telephone"]."<br>";
	}
	if(strlen($_POST['c_position'])>0){
		$message.="Position : ".$_POST["c_position"]."<br>";
	}
	if(strlen($_POST['c_from'])>0){
		$message.="From : ".$_POST["c_from"]."<br>";
	}
	if(strlen($_POST['c_to'])>0){
		$message.="To : ".$_POST["c_to"]."<br>";
	}
	if(strlen($_FILES['upload']['name'])>0){
		$message.="File : ".$files."<br>";
	}
	if(strlen($_POST['c_duties'])>0){
		$message.="Duties : ".$_POST["c_duties"]."<br>";
	}
	if(strlen($_POST['name1'])>0){
		$message.="Two References Required<br>";
		$message.="Name : ".$_POST["name1"]."<br>";
	}
	if(strlen($_POST['email1'])>0){
		$message.="Email : ".$_POST["email1"]."<br>";
	}
	if(strlen($_POST['name2'])>0){
		$message.="Name : ".$_POST["name2"]."<br>";
	}
	if(strlen($_POST['email2'])>0){
		$message.="Email : ".$_POST["email2"]."<br><br>";
	}
	if(strlen($_POST['college'])>0){
		$message.="Education History\n";
		$message.="College : ".$_POST["college"]."<br>";
	}
	if(strlen($_POST['degree'])>0){
		$message.="Degree : ".$_POST["degree"]."<br>";
	}
	if(strlen($_POST['complete_year'])>0){
		$message.="Years completed : ".$_POST["complete_year"]."<br>";
	}
	if(strlen($_POST['grad_school'])>0){
		$message.="Graduate School : ".$_POST["grad_school"]."<br>";
	}
	if(strlen($_POST['degree1'])>0){
		$message.="Degree : ".$_POST["degree1"]."<br>";
	}
	if(strlen($_POST['work_days'])>0){
		$message.="Preference of work days : ".$_POST["work_days"]."<br>";
	}
	if(strlen($_POST['hours'])>0){
		$message.="Hours : ".$_POST["hours"]."<br>";
	}
	

    $from=$_POST["email"];
    $subject="Becaome a tutor sign up form";
    // message lines should not exceed 70 characters (PHP rule), so wrap it
    // send mail
	$headers = "From: $from\r\n". 
               "MIME-Version: 1.0" . "\r\n" . 
               "Content-type: text/html; charset=UTF-8" . "\r\n";
    mail("jobs@tutorsonwheels.com",$subject,$message,$headers);
	
    //echo '<script> alert("Thank you for your inquiry, a team member will be in touch shortly!"); </script>' ;
	header("location:become-a-tutor.html");


}  
?>