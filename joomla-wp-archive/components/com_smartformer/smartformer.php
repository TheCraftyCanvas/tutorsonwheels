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

function stripSybaseQuotes( $data ) {
    $result = array();
    foreach( $data as $key => $value ) {
        if( is_array($value) ) {
            $result[str_replace("''","'",$key)] = stripSybaseQuotes($value);
        } else {
            $result[str_replace("''","'",$key)] = str_replace("''","'",$value);
        }
    }
    return addMagicSlashes($result);
}

function addMagicSlashes( $data ) {
    $result = array();
    $list = '"\'\\';
    foreach( $data as $key => $value ) {
        if( is_array($value) ) {
            $result[addcslashes($key,$list)] = addMagicSlashes($value);
        } else {
            $result[addcslashes($key,$list)] = addcslashes($value,$list);
        }
    }
    return $result;
}

if( ini_get('magic_quotes_sybase') && strtolower(ini_get('magic_quotes_sybase')) != 'off' ) {
    $_POST = stripSybaseQuotes($_POST);
    $_GET = stripSybaseQuotes($_GET);
}  elseif (!get_magic_quotes_gpc()) {
    $_POST = addMagicSlashes($_POST);
    $_GET = addMagicSlashes($_POST);
}

// no direct access
if (!defined( '_VALID_MOS' ) && !defined( '_JEXEC' )) die( 'Restricted access' );
	if (defined( '_JEXEC' )) {
		$database=& JFactory::getDBO();
		//$GLOBALS['DOCUMENT_ROOT']=$_SERVER['DOCUMENT_ROOT'];
		$GLOBALS['sf_live_site']=substr(JURI::root(),0,strlen(JURI::root())-1);
		$sf_live_site=$GLOBALS['sf_live_site'];
		$GLOBALS['sf_absolute_path']=substr(__FILE__,0,strpos(strtolower(__FILE__),'components')-1);
	}
	include($GLOBALS['sf_absolute_path'].'/components/com_smartformer/engine.php');
	$form = new SmartformerEngine;
	switch( $task ) {
	    case 'captcha':
	    	if(!defined( '_JEXEC' )) {
                session_start();
            }
	        $form->captcha();
	    default:
    	    echo $form->render();
	}

?>