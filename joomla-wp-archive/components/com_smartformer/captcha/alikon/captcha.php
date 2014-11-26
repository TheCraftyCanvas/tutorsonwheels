<?php
/**
 * SmartFormer - Form Builder for Joomla 1.5.x websites
 * Copyright (C) 2006-2010 IToris Co.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see http://www.gnu.org/licenses/
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * -----------------------------------------------------------------------------
 * @package SmartFormer
 * @version 2.4.1 (J1.5 security fix)
 * @author The SmartFormer project (http://www.itoris.com/joomla-form-builder-smartformer.html)
 * @copyright IToris Co. 2006-2010
 * @license GNU GPL
 *
*/

if (!defined( '_VALID_MOS' ) && !defined( '_JEXEC' )) die( 'Restricted access' );

class alikoncaptcha {

   var $codelength = '';
   var $iwidth = 149;
   var $iheight = 34;

    function captchacode( $formid , $width , $height ) {

      $chars = "abcdefghijkmnpqrstuvwxyz";
      $chars .= "ABCDEFGHIJKMNPQRSTUVWXYZ";
      $chars .= "23456789";

      $fullchar='';
      for ($i = 0; $i < $this->codelength; $i++) {
         $fullchar .= $chars[rand(0, strlen($chars) - 1)];
      }

       $_SESSION['smartformer']['sec_code'][$formid] = array(
           'code' => $fullchar ,
           'width' => $width ,
           'height' => $height ,
           'type' => 1
       );

   }


   function image( $formid ) {

      //alikoncaptcha::captchacode();

      $cdata = $_SESSION['smartformer']['sec_code'][$formid];

      //print_r($cdata);
      $rndstring = $cdata['code'];
      $this->iwidth = $cdata['width'];
      $this->iheight = $cdata['height'];

      $font=$GLOBALS['sf_absolute_path'].'/components/com_smartformer/captcha/alikon/Arial.ttf';

      /* output type */
      # $output_type='jpeg';
      $output_type='png';

      /* font size range, angle range, character padding */
      $min_font_size = 14;
      $max_font_size = 20;
      $min_angle = -20;
      $max_angle = 20;
      $char_padding = 1;

      /* initialize variables  */
      $turing_string='';
      $data = array();
      $image_width = $image_height = 0;

      /* build the data array of the characters, size, placement, etc. */
      for($i=0; $i<$this->codelength; $i++) {

         $char = substr($rndstring, $i, 1);

         $size = mt_rand($min_font_size, $max_font_size);
         $angle = mt_rand($min_angle, $max_angle);

         $bbox = ImageTTFBBox( $size, $angle, $font, $char );

         $char_width = max($bbox[2],$bbox[4]) - min($bbox[0],$bbox[6]);
         $char_height = max($bbox[1],$bbox[3]) - min($bbox[7],$bbox[5]);

         $image_width += $char_width + $char_padding;
         $image_height = max($image_height, $char_height);

         $data[] = array(
            'char'        => $char,
            'size'        => $size,
            'angle'       => $angle,
            'height'      => $char_height,
            'width'       => $char_width,
         );
      }

      /* calculate the final image size, adding some padding */
      $x_padding = 12;

/*      $image_width += ($x_padding * 1);
      $image_height = ($image_height * 1.5) + 2;*/
      $image_width = $this->iwidth;
      $image_height = $this->iheight;

      /* build the image, and allocte the colors  */
      $im = ImageCreate($image_width, $image_height);

      $r = 51 * mt_rand(4,5);
      $g = 51 * mt_rand(4,5);
      $b = 51 * mt_rand(4,5);
      $color_bg = ImageColorAllocate($im,  $r,  $g,  $b );

      $r = 51 * mt_rand(3,4);
      $g = 51 * mt_rand(3,4);
      $b = 51 * mt_rand(3,4);
      $color_line0 = ImageColorAllocate($im,  $r,  $g,  $b );

      $r = 51 * mt_rand(3,4);
      $g = 51 * mt_rand(3,4);
      $b = 51 * mt_rand(3,4);
      $color_line1    = ImageColorAllocate($im,  $r,  $g,  $b );

      $r = 51 * mt_rand(1,2);
      $g = 51 * mt_rand(1,2);
      $b = 51 * mt_rand(1,2);
      $color_text = ImageColorAllocate($im,  $r,  $g,  $b );

      $color_border = ImageColorAllocate($im,   0,   0,   0 );

      /* make the random background lines */
      for($l=0; $l<10; $l++) {

         $c = 'color_line' . ($l%2);
         $lx = mt_rand(0,$image_width+$image_height);
         $lw = mt_rand(0,3);
         if ($lx > $image_width) {
            $lx -= $image_width;
            ImageFilledRectangle($im, 0, $lx, $image_width-1, $lx+$lw, $$c );
         } else {
            ImageFilledRectangle($im, $lx, 0, $lx+$lw, $image_height-1, $$c );
         }

      }

      /* output each character */
      $pos_x = $x_padding + ($char_padding / 2);
      foreach($data as $d) {

         $pos_y = ( ( $image_height + $d['height'] ) / 2 );
         ImageTTFText($im, $d['size'], $d['angle'], $pos_x, $pos_y, $color_text, $font, $d['char'] );
         $pos_x += $d['width'] + $char_padding;
         $generatecode=$d['char'];

      }

      /* a nice border */
      ImageRectangle($im, 0, 0, $image_width-1, $image_height-1, $color_border);

      /* write it */
    	header('Content-type: image/png',true);
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  // disable IE caching
		header("Last-Modified: " . gmdate( "D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");

      ImagePNG($im);//,$GLOBALS['sf_absolute_path'].'/components/com_smartformer/captcha/alikon/tmp.png');

      /* free memory */
      ImageDEstroy($im);
   }



}

?>
