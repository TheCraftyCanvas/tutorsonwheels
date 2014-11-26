<?php
/**
 * ckform View for ckforms Component
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
 * ckform View
 *
 * @package    CK.Joomla
 * @subpackage Components
 */
class CkformsViewCkform extends JView
{
	/**
	 * display method of ckform view
	 * @return void
	 **/
	function display($tpl = null)
	{
		$ckforms =& $this->get('Data');
		$isNew = ($ckforms->id < 1);
		
		$doc =& JFactory::getDocument();
		$css = '.icon-48-ckform {background:url(../administrator/components/com_ckforms/images/logo-banner.png) no-repeat;}';
   		$doc->addStyleDeclaration($css);
	
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(JText::_( 'CK Forms' ).': <small><small>[ ' . $text.' ]</small></small>' , 'ckform' );
		
		JToolBarHelper::save();
		JToolBarHelper::apply('apply');
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		JToolBarHelper::divider();
		JToolBarHelper::custom('fields','edit.png','edit.png','Fields',false) ;
		
		JRequest::setVar( 'hidemainmenu', 1 );
		
		$document =& JFactory::getDocument();
		$document->addScript(JURI::root(true).'/administrator/components/com_ckforms/js/mootabs.js');

		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_ckforms/css/mootabs.css');
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_ckforms/css/ckforms.css');
		
		$this->assignRef('ckforms',$ckforms);
		
		parent::display($tpl);
	}
}
