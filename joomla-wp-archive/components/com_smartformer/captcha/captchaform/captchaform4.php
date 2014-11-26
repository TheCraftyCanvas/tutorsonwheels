<?php
/********************************************************************************
* @ Captchaform - captcha img post validation class
* @ Note PHP 4 specific class
* @ Author: scripts.titude.nl - NLD - 2007
* @ Requirements: PHP 4 - GD 2.0.1 for captcha 

* @ example image out

session_start();
require_once('captchaform.php');
$captcha = new captchaform();
$captcha->image();

* @ example post check

session_start();
require_once('captchaform.php');
$captcha = new captchaform();
if ($captcha->post()) {
	// Post actions, captcha code is oke
}

********************************************************************************/
class captchaform {

		/* session name for the captcha code */
		/* External change, set in image and form file to match */
	var $session = "captchaform";
		/* captcha code length */
	var $codelength = '';

		/* font dir location ( with .ttf fonts ) */
	var $fontdir = "fonts";
		/* ttf fonts to use - empty is autoscan fontdir */
	var $fonts = array();
	
		/* image width and height = fixed in class */
		/* External change, set in image and form file to match */
	var $width = 150;
	var $height = 35;
		/* image output type = gif jpg png */
	var $type = "png";
		/* image transparanty for backgroundless captcha */
	var $transparant = "FFFFFF";
	
	var $formkey = "captcha_code";
	
		/* dir for bg images - set to non existing or empty dir for no bg images */
	var $backgrounddir = "";
		/* bg images - empty and $backgrounddir exists = scan auto */
	var $backgrounds = array();

		/* font size */
	var $fontsize = 20;
		/* font colors */
	var $colors = array("FF0000");
		/* font shade colors */
	var $shades = array("FFFF00");
	var $shadesize = 2;
		/* font rotation max - 0 till 60 */
	var $rotate = 30;
	
		/* block on dnsbl ( deny ip in spam blacklist )
		for more info on dnsbl lists see http://www.moensted.dk/spam/
		http://www.sdsc.edu/~jeff/spam/cbc.html - http://www.declude.com/Articles.asp?ID=97
		example: array('zen.spamhaus.org','bl.spamcop.net','list.dsbl.org','tor.ahbl.org','opm.tornevall.org');
		*/
	var $forbidden = "FORBIDDEN";
	var $dnsbl = array();
	
		/* var values */
	var $code = "";
	var $dnsblname = "dnsblsession";

/********************************************************************************
* @ function 
* @ init class
********************************************************************************/
	function captchaform() {
		if (!session_id()) { 
			@session_start(); 
		}
	}

/********************************************************************************
* @ function image
* @ output for the captcha image
********************************************************************************/	
	function image( $formid ) {
		//$this->captchacode();
		header("Expires: Mon, 1 Jan 2000 00:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: no-store, no-cache, must-revalidate"); 
		header("Cache-Control: post-check=0, pre-check=0", false); 
		header("Pragma: no-cache"); 
		if ($this->type == "jpg") {
			header("Content-type: image/jpeg");		
		} else if ($this->type == "gif" && function_exists('imagecreatefromgif')) {
			header("Content-type: image/gif");		
		} else {
			header("Content-type: image/png",true); 
		}
		$this->width = (int) $_SESSION['smartformer']['sec_code'][$formid]['width'];
		$this->height = (int) $_SESSION['smartformer']['sec_code'][$formid]['height'];
		$this->code = $_SESSION['smartformer']['sec_code'][$formid]['code'];
		$image = imagecreatetruecolor($this->width, $this->height);
		if ($this->backgrounddir != "" && $this->readbgs()) {
			shuffle($this->backgrounds);
			$bg = $this->backgrounds[0];
			$parts = pathinfo($bg);
			$ext = strtolower($parts['extension']);
			$bgimg = false;
			if ($ext == "png") { 
				$bgimg = imagecreatefrompng($bg);
			} else if ($ext == "gif" && function_exists('imagecreatefromgif')) {  
				$bgimg = imagecreatefromgif($bg);
			} else if ($ext == "jpg" || $ext == "jpeg") {
				$bgimg = imagecreatefromjpeg($bg);
			}
			list($bg_w, $bg_h) = getimagesize($bg);
			if ($bgimg) {
				imagecopyresampled($image, $bgimg, 0, 0, 0, 0, $this->width, $this->height, $bg_w, $bg_h);
				imagedestroy($bgimg);
			}

		} else if ($this->transparant != "") {
			$dec = $this->deccolors($this->transparant);
			$bgcolor = imagecolorallocate($image, $dec[0], $dec[1], $dec[2]);
			imagefill($image, 0, 0, $bgcolor);
			imagecolortransparent($image, $bgcolor);
		}
		if ($this->readfonts()) {
			$space = 0;
			if ($this->dnsbl()) {
				$this->code = $this->forbidden;
				$this->rotate = 0;
				$this->fonts = array($this->fonts[0]);
				//unset($_SESSION['smartformer']['sec_code']);
			}
			if ($this->fontsize * (strlen($this->code) + 2) > $this->width) {
				$size = round($this->width / (strlen($this->code) + 2));
				$xo = $size;
			} else {
				$size = $this->fontsize;
				$xo = round(($this->width - ($size * strlen($this->code))) / 2);
			}
			if ($xo > $size) {
				$space = ($xo * 1.5) / strlen($this->code);
				$xo -= round($space * (strlen($this->code) - 1) /2);
			}
			$yo = round(($this->height - $size) / 2);
			for ($i = 0; $i < strlen($this->code); $i++) {
				shuffle($this->fonts);
				shuffle($this->colors);
				$xcor = $space * $i;
				$ycor = 0;
				$rotate = 0;
				if ($this->rotate != 0) {
					$rotate = rand(0, (int) $this->rotate);
					$rotate = (rand(1,2) == 2) ? $rotate * -1 : $rotate;
					$xcor = $size - (cos(deg2rad($rotate)) * $size) + $xcor;
					$ycor = (sin(deg2rad($rotate)) * $size) / 2;
				}
				if (!empty($this->shades)) {
					shuffle($this->shades);
					$this->shadesize = (int) $this->shadesize;
					$dec = $this->deccolors($this->shades[0]);
					$color = imagecolorallocate($image, $dec[0], $dec[1], $dec[2]);
					imagettftext($image, $size, $rotate, ($size * $i + $xo + $xcor), ($size + $yo + $ycor + $this->shadesize), $color, $this->fonts[0], $this->code[$i]); 
				}
				$dec = $this->deccolors($this->colors[0]);
				$color = imagecolorallocate($image, $dec[0], $dec[1], $dec[2]);
				imagettftext($image, $size, $rotate, ($size * $i + $xo + $xcor), ($size + $yo + $ycor), $color, $this->fonts[0], $this->code[$i]); 
			}
		}
		if ($this->type == "jpg") {
			imagejpeg($image);//,$GLOBALS['sf_absolute_path'].'/components/com_smartformer/captcha/captchaform/tmp.jpg');
		} else if ($this->type == "gif" && function_exists('imagecreatefromgif')) {
			imagegif($image);//,$GLOBALS['sf_absolute_path'].'/components/com_smartformer/captcha/captchaform/tmp.gif');		
		} else {
			imagepng($image);//,$GLOBALS['sf_absolute_path'].'/components/com_smartformer/captcha/captchaform/tmp.png'); 
		}
		imagedestroy($image);
	}
	
/********************************************************************************
* @ function post
* @ returns true/false - if a post is allowed - true
********************************************************************************/
	function post() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if (isset($_POST[$this->formkey]) && isset($_SESSION[$this->session]) && isset($_SESSION[$this->dnsblname])) {
				if ($_SESSION[$this->dnsblname] == "oke" && $_SESSION[$this->session] == strtolower(str_replace("0","O",$_POST[$this->formkey]))) {
					unset($_SESSION[$this->session]);
					return true;
				}
			}
			unset($_SESSION[$this->session]);	
		}
		return false;
	}

/********************************************************************************
* @ function captchacode
* @ sets a lowercase session and $this->code with the captcha code
********************************************************************************/
	function captchacode( $formid , $width , $height ) {
		// Captcha characters
		$chars = "abcdefghijklmnopqrstuvwxyz";
		$chars .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$chars .= "123456789";
		/* Note: no 0 used - input 0 replaced with O */
		$this->codelength = (int) $this->codelength;
		for ($i = 0; $i < $this->codelength; $i++) {
			$this->code .= $chars[rand(0, strlen($chars) - 1)];
		}
		$_SESSION['smartformer']['sec_code'][$formid] = array(
           'code' => strtolower($this->code) ,
           'width' => $width ,
           'height' => $height ,
           'type' => 2
       );
	}

/********************************************************************************
* @ function readfonts
* @ rebuilds array $this->fonts with font + pad ( from user input or auto from dir )
********************************************************************************/
	function readfonts() {
		$ext = ".ttf"; 
		$dir = substr($this->fontdir, -1) != "/" ? $this->fontdir . "/" : $this->fontdir;
 		if (is_dir($dir) && !empty($this->fonts)) {
			for ($i = 0; $i < count($this->fonts); $i++) {
				if (!strstr($this->fonts[$i], $ext)) { 
					$this->fonts[$i] .= $ext;
				}
				if (file_exists($dir . $this->fonts[$i])) {
					$this->fonts[$i] = $dir . $this->fonts[$i];
				}
			}
		}
		if (empty($this->fonts) && is_dir($dir)) {
			$this->fonts = glob($dir . "*" . $ext);
		}
		return empty($this->fonts) ? false : true;
	}
	
	function readbgs() {
		$dir = substr($this->backgrounddir, -1) != "/" ? $this->backgrounddir . "/" : $this->backgrounddir;
 		if (is_dir($dir) && !empty($this->backgrounds)) {
			for ($i = 0; $i < count($this->backgrounds); $i++) {
				if (file_exists($dir . $this->backgrounds[$i])) {
					$this->backgrounds[$i] = $dir . $this->backgrounds[$i];
				}
			}
		}
		if (empty($this->backgrounds) && is_dir($dir)) {
			$this->backgrounds = glob($dir . "{*.gif,*.jpg,*jpeg,*.png}" , GLOB_BRACE);
		}
		return empty($this->backgrounds) ? false : true;
	}

/********************************************************************************
* @ function deccolors
* @ convert a hex color string to rgb
* @ returns an array (r,g,b) colors
********************************************************************************/
	function deccolors($color) {
		$color = str_replace("#", "", $color);
		return array(hexdec(substr($color, 0, 2)), hexdec(substr($color, 2, 2)), hexdec(substr($color, 4, 2)));
	}

/********************************************************************************
* @ function dnsbl
* @ validate IP on DNSBL ( blacklists )
* @ sets $_SESSION[$this->dnsblname] - returns false on not listed
********************************************************************************/
	function dnsbl() { 
		if (isset($_SESSION[$this->dnsblname])) {
			return  $_SESSION[$this->dnsblname] == "oke" ? false : true;
		} else if (preg_match('/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/', $_SERVER["REMOTE_ADDR"])) { 
			$reversip = implode(".", array_reverse(explode(".", $_SERVER["REMOTE_ADDR"]))); 
			$win_os = strtolower(substr(PHP_OS, 0, 3)) == "win" ? true : false; 
			foreach ($this->dnsbl as $list){ 
				if (function_exists("checkdnsrr")) { 
					if (checkdnsrr($reversip . "." . $list . ".", "A")) { 
						$_SESSION[$this->dnsblname] = $list;
						return true;
					} 
				} else if ($win_os) { 
					$lookup = array(); 
					@exec("nslookup -type=A " . $reversip . "." . $list . ".", $lookup); 
					foreach ($lookup as $line) { 
						if (strstr($line, $list)) { 
							$_SESSION[$this->dnsblname] = $list;
							return true;
						} 
					} 
				} 
			} 
		} 
		$_SESSION[$this->dnsblname] = "oke";
		return false;
	} 
}
/********************************************************************************
Copyright (c) 2007, scripts.titude.nl - all rights reserved.
Author: scripts-AT-do-Not-Spam-titude.nl - Netherlands

Disclaimer & License

THE AUTHOR MAKES NO REPRESENTATIONS OR WARRANTIES, EXPRESS OR
IMPLIED. BY WAY OF EXAMPLE, BUT NOT LIMITATION, THE AUTHOR MAKES NO 
REPRESENTATIONS OR WARRANTIES OF MERCHANTABILITY OR FITNESS FOR
ANY PARTICULAR PURPOSE OR THAT THE USE OF THE SCRIPT, COMPONENTS, 
OR DOCUMENTATION WILL NOT INFRINGE ANY PATENTS, COPYRIGHTS, 
TRADEMARKS, OR OTHER RIGHTS. THE AUTHOR SHALL NOT BE HELD LIABLE 
FOR ANY LIABILITY NOR FOR ANY DIRECT, INDIRECT, OR CONSEQUENTIAL 
DAMAGES WITH RESPECT TO ANY CLAIM BY RECIPIENT OR ANY THIRD PARTY 
ON ACCOUNT OF OR ARISING FROM THIS AGREEMENT OR USE OF THIS SCRIPT
AND ITS COMPONENTS.

Released under GNU Lesser General var License - http://www.gnu.org/licenses/lgpl.html

********************************************************************************/
?>
