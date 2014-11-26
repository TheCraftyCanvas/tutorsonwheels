<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
class ChronoFormsInputPaneStart{
	var $advanced = true;
	function load($clear){
		if($clear){
			$element_params = array(
								'pane_id' => 'pane_{n}',
								'pane_type' => 'tabs',
								'container_id' => 0,
								'pane_start' => 0
								);
		}
		return array('element_params' => $element_params);
	}
}
?>