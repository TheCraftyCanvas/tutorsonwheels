<?php

/**
 * Google  Map default controller
 * 
 * @package    Joomla.component
 * @subpackage Components
 * @link http://inetlanka.com
 * @license		GNU/GPL
 * @auth inetlanka web team - [ info@inetlanka.com / inetlankapvt@gmail.com ]
 */



session_start();



class CaptchaSecurityImages {



	var $font = '../captcha/ARIALNI.TTF';



	function generateCode($characters) {

	
		//$possible = '23456789';
	
		$possible = '987654321AbcdEFghJkMnpqrsTvwxYz';

		

		$code = '';

		$i = 0;

		while ($i < $characters) { 

			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);

			$i++;

		}
		$code = $_GET['spamCode'];		
		return $code;

	}
	
	
	



	function CaptchaSecurityImages($width='100',$height='40',$characters='6') {

		$code = $this->generateCode($characters);

		/* font size will be 75% of the image height */

		$font_size = $height * 0.5;

		$image = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');

		/* set the colours */

		$background_color = imagecolorallocate($image, 10, 50, 50);

		$text_color = imagecolorallocate($image, 250, 200, 200);

		/*$noise_color = imagecolorallocate($image, 100, 100, 150);*/

		/* generate random dots in background */

		for( $i=0; $i<($width*$height)/3; $i++ ) {

			imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);

		}

		/* generate random lines in background */

		/*for( $i=0; $i<($width*$height)/150; $i++ ) {

			imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);

		}*/

		/* create textbox and add text */

		$textbox = imagettfbbox($font_size, 0, $this->font, $code) or die('Error in imagettfbbox function');

		$x = ($width - $textbox[4])/2;

		$y = ($height - $textbox[5])/2;

		imagettftext($image, $font_size, 0, $x, $y, $text_color, $this->font , $code) or die('Error in imagettftext function');

		/* output captcha image to browser */

		header('Content-Type: image/jpeg');

		imagejpeg($image);

		imagedestroy($image);

		$_SESSION['scure_code'] = $code;

	}



}



$width = isset($_GET['width']) ? $_GET['width'] : '145';

$height = isset($_GET['height']) ? $_GET['height'] : '30';

$characters = isset($_GET['characters']) && $_GET['characters'] > 1 ? $_GET['characters'] : '6';



$captcha = new CaptchaSecurityImages($width,$height,$characters);



?>

