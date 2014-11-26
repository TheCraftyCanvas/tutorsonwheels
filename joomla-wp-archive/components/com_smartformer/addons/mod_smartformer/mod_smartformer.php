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

// no direct access
if (!defined( '_VALID_MOS' ) && !defined( '_JEXEC' )) die( 'Restricted access' );

$id=$params->get('formid', 0);
	if (defined( '_JEXEC' )) {
		$database=& JFactory::getDBO();
		$GLOBALS['DOCUMENT_ROOT']=$_SERVER['DOCUMENT_ROOT'];
		$GLOBALS['sf_live_site']=substr(JURI::root(),0,strlen(JURI::root())-1);
		$sf_live_site=$GLOBALS['sf_live_site'];
		$GLOBALS['sf_absolute_path']=substr(__FILE__,0,strpos(strtolower(__FILE__),'modules')-1);
	}
	require_once($GLOBALS['sf_absolute_path'].'/components/com_smartformer/engine.php');
	$form = new SmartformerEngine;
	$smart_html=$form->render($id);
	echo $smart_html;
	return true;
