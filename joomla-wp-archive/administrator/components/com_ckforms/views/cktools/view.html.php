<?php
/**
 * ckfield View for CK forms Component
 * 
 * @package    CK.Joomla
 * @subpackage Components
 * @link http://www.cookex.eu
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * ckfield View
 *
 * @package    CK.Joomla
 * @subpackage Components
 */
class CkformsViewCktools extends JView
{
	/**
	 * display method of ckfield view
	 * @return void
	 **/
	function display($tpl = null)
	{
		
		$doc =& JFactory::getDocument();
		$css = '.icon-48-ckform {background:url(../administrator/components/com_ckforms/images/logo-banner.png) no-repeat;}';
   		$doc->addStyleDeclaration($css);

		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_ckforms/css/ckforms.css');
	
		switch (JRequest::getVar('layout'))
		{
			case 'css':
				JToolBarHelper::title(JText::_( 'CK Forms - Tools - Edit CSS' ) , 'ckform' );
				JToolBarHelper::apply();
				JToolBarHelper::save();

				$css = & $this->get( 'Css');
				$this->assignRef('css',$css);
			break;
			case 'backup':
				JToolBarHelper::title(JText::_( 'CK Forms - Tools - Backup' ) , 'ckform' );
				JToolBarHelper::custom( 'startbackup', 'save.png', 'save_f2.png', JText::_('Backup'), false, false );
			break;
			case 'restore':
				JToolBarHelper::title(JText::_( 'CK Forms - Tools - Restore' ) , 'ckform' );
				JToolBarHelper::custom( 'startrestore', 'save.png', 'save_f2.png', JText::_('Restore'), false, false );
			break;
		}
					
		JToolBarHelper::cancel( 'cancel', 'Close' );

		JRequest::setVar( 'hidemainmenu', 1 );
		
		parent::display($tpl);
	}
}
