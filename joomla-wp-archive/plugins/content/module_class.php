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

class plugin_module_class{	
	

	function load_module($module_id, $module_class, $module_style){
		
		$database = JFactory::getDBO();	
		
		//check if FUA is installed
		$access_script = JPATH_ROOT.DS.'components'.DS.'com_frontenduseraccess'.DS.'menuaccess2.php';
		if(file_exists($access_script)){
			//check if FUA is enabled and if module access is enabled
			
			$database->setQuery("SELECT config "
			."FROM #__fua_config "
			."WHERE id='fua' "
			."LIMIT 1"
			);		
			$rows = $database->loadObjectList();
			$raw = '';
			foreach($rows as $row){	
				$raw = $row->config;	
			}
			if(strpos($raw, 'fua_enabled=1') && strpos($raw, 'modules_active=true')){
			
				//FUA is enabled and if module access is enabled
				//check if user has access to module				
				require_once($access_script);
				$fua_access_class = new frontenduseraccessMenuAccessChecker;
				//check if the method exist yet in this version
				if(method_exists('frontenduseraccessMenuAccessChecker','check_module_access')){					
					if(!$fua_access_class->check_module_access($module_id)){
						//user has no access so return empty
						return '';
					}
				}
			}
		}	
					
		$document	= &JFactory::getDocument();
		$renderer	= $document->loadRenderer('module');
		
		$contents = '';
			
		//get module as an object
		$database->setQuery("SELECT * FROM #__modules WHERE id='$module_id' ");
		$modules = $database->loadObjectList();
		$module = 0;
		foreach($modules as $temp){
			$module = $temp;
		}
		
		if(!$module){
			return '';
		}	
			
		//just to get rid of that stupid php warning
		$module->user = '';
		
		$params = array('style'=>$module_style);
		
		$contents = $renderer->render($module, $params);
		
		return $contents;
			
	}
	
	
}

?>