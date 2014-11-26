<?php
/**
* @file
* @brief    boxplus: a lightweight pop-up window engine for MooTools
* @author   Levente Hunyadi
* @version  0.9.2
* @remarks  Copyright (C) 2011 Levente Hunyadi
* @remarks  Licensed under GNU/GPLv3, see http://www.gnu.org/licenses/gpl-3.0.html
* @see      http://hunyadi.info.hu/projects/boxplus
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (version_compare(PHP_VERSION, '5.2.0') < 0) {
	$app =& JFactory::getApplication();
	$app->enqueueMessage('boxplus requires PHP version 5.2 or later. Please instruct your server administrator to upgrade your PHP engine.', 'error');
} elseif (version_compare(JVERSION, '1.5.19') < 0 || version_compare(JVERSION, '1.6') < 0 && !JPluginHelper::isEnabled('system', 'mtupgrade')) {
	$app =& JFactory::getApplication();
	$app->enqueueMessage('boxplus requires MooTools version 1.2 or later. Please enable the <em>MooTools Upgrade</em> system plug-in in the Joomla 1.5 administration back-end.', 'error');
} else {
	require_once JPATH_PLUGINS.DS.'content'.DS.'boxplus'.DS.'boxplus.php';
}