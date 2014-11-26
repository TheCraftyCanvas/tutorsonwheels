<?php
/**
* @package load module into article
* @version 1.1.0
* @copyright Copyright (C) 2008-2011 Carsten Engel. All rights reserved.
* @license GPL
* @author http://www.pages-and-items.com
* @joomla Joomla is Free Software
*/

//no direct access
if(!defined('_VALID_MOS') && !defined('_JEXEC')){
	die('Restricted access');
}

$mainframe->registerEvent( 'onPrepareContent', 'plugin_get_module_output' );

function plugin_get_module_output( &$row, &$params, $page=0 ){	
	
	require_once(dirname(__FILE__).'/module_class.php');
	$plugin_module_class = new plugin_module_class; 	
	
	$regex = '/{(module)\s*(.*?)}/i';	
		
	$matches = array();
	$preg_set_order = PREG_SET_ORDER;
   	preg_match_all($regex, $row->text, $matches, $preg_set_order);  
	
	foreach ($matches as $match){   		
		$module = '';
		$arguments = array();   		
		preg_match_all('/\[.*?\]/', $match[2], $arguments);		
		if ($arguments){
			foreach ($arguments as $i=>$argument){
				$module = preg_replace("/\[|]/", '', $argument);
			}
		}		
			
		$module_id = $module[0];	
		//$module_class = $module[1];
		$module_class = 0;
		//$module_style = $module[2];
		$module_style = 0;		
		
		$module_output = $plugin_module_class->load_module($module_id, $module_class, $module_style);		
			
		$row->text = preg_replace($regex, $module_output, $row->text, 1);
	}  
	
}

?>