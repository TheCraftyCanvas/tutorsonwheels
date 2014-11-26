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

jimport( 'joomla.application.component.view');


class GoogleViewGoogle extends JView
{
	function display($tpl = null)
	{
			$google_id = JRequest::getVar( 'id', 0, '', 'int' );
		    $db		=& JFactory::getDBO();
			if($google_id >0 )
			{
				$query = 'SELECT * FROM #__googlemap WHERE id='.$google_id;
			}
			else
			{
				$query = 'SELECT * FROM #__googlemap';
			}
			
			$db->setQuery($query);
			$options = $db->loadObjectList();
			$this->assignRef('options',	$options);

		    parent::display($tpl);
	}
}
?>
