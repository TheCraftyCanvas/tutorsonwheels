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

function com_install_outputErrors( $errors ) {
    if( $errors ) {
        echo '<p style="color: red;"><b>The component was not fully uninstalled. Some files remain in the following directories</b>:</p>';
        foreach( $errors as $error ) {
            echo '<p>'.$error."</p>";
        }
    }
}

// no direct access
if (!defined( '_VALID_MOS' ) && !defined( '_JEXEC' )) die( 'Restricted access' );
function com_uninstall() {
	global $database;
	if (defined( '_JEXEC' )) {
		$database=& JFactory::getDBO();
		$GLOBALS['DOCUMENT_ROOT']=$_SERVER['DOCUMENT_ROOT'];
		$GLOBALS['sf_live_site']=substr(JURI::root(),0,strlen(JURI::root())-1);
		$sf_live_site=$GLOBALS['sf_live_site'];
		$GLOBALS['sf_absolute_path']=substr(__FILE__,0,strpos(strtolower(__FILE__),'administrator')-1);
        $errors = array();
		if (!@unlink ($GLOBALS['sf_absolute_path'].'/plugins/content/smartformer.php')) {
            $errors['plugin.content'] = '/plugins/content/';
		}
		if (!@unlink ($GLOBALS['sf_absolute_path'].'/plugins/content/smartformer.xml')) {
			$errors['plugin.content'] = '/plugins/content/';
		}
		if (!@unlink ($GLOBALS['sf_absolute_path'].'/modules/mod_smartformer/mod_smartformer.php')){
			$errors['modules'] = '/modules/';
		}
		if (!@unlink ($GLOBALS['sf_absolute_path'].'/modules/mod_smartformer/mod_smartformer.xml')) {
			$errors['modules'] = '/modules/';
		}
		if (!@rmdir($GLOBALS['sf_absolute_path'].'/modules/mod_smartformer')) {
			$errors['modules'] = '/modules/';
		}
        com_install_outputErrors($errors);
		$database->setQuery( 'delete from `#__plugins` where `element`="smartformer"' );
		$database->query();
	} else {
		$GLOBALS['sf_absolute_path']=substr(__FILE__,0,strpos(strtolower(__FILE__),'administrator')-1);
        $errors = array();
		if (!@unlink ($GLOBALS['sf_absolute_path'].'/mambots/content/smartformer.php')) {
			$errors['mambots.content'] = '/mambots/content/';
		}
		if (!@unlink ($GLOBALS['sf_absolute_path'].'/mambots/content/smartformer.xml')) {
			$errors['mambots.content'] = '/mambots/content/';
		}
		if (!@unlink ($GLOBALS['sf_absolute_path'].'/modules/mod_smartformer.php')) {
			$errors['modules'] = '/modules/';
		}
		if (!@unlink ($GLOBALS['sf_absolute_path'].'/modules/mod_smartformer.xml')) {
			$errors['modules'] = '/modules/';
		}
        com_install_outputErrors($errors);
		$database->setQuery( 'delete from `#__mambots` where `element`="smartformer"' );
		$database->query();
		$database->setQuery( 'delete from `#__modules` where `module`="mod_smartformer"' );
		$database->query();

	}
}

?>