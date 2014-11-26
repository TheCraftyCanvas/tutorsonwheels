<?php
/**
 * ckfields View for Ck forms Component
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
 * ckfields View
 *
 * @package    CK.Joomla
 * @subpackage Components
 */
class CkformsViewCkfields extends JView
{
	/**
	 * ckfields view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		$doc =& JFactory::getDocument();
		$css = '.icon-48-ckform {background:url(../administrator/components/com_ckforms/images/logo-banner.png) no-repeat;}';
   		$doc->addStyleDeclaration($css);

		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_ckforms/css/ckforms_min.css');
	
		JToolBarHelper::title(JText::_( 'CK Forms - Fields' ), 'ckform' );
	
		JToolBarHelper::custom('addsep','publish.png','publish.png',JText::_( 'Add separator' ),false) ;
		JToolBarHelper::divider();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		JToolBarHelper::custom('duplicate','publish.png','publish.png',JText::_( 'Duplicate' ),true) ;
		JToolBarHelper::divider();
		JToolBarHelper::back('Forms','index.php?option=com_ckforms');

		// Get data from the model
		$items = & $this->get( 'Data');
		$pagination =& $this->get('Pagination');

		$this->assignRef('items',$items);
		$this->assignRef('pagination', $pagination);

		parent::display($tpl);
	}
}
