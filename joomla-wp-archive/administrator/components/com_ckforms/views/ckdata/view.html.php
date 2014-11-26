<?php
/**
 * Ckdata View for Ck forms Component
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
 * Ckdata View
 *
 * @package    CK.Joomla
 * @subpackage Components
 */
class CkformsViewCkdata extends JView
{
	/**
	 * Ckdata view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		$doc =& JFactory::getDocument();
		$css = '.icon-48-ckform {background:url(../administrator/components/com_ckforms/images/logo-banner.png) no-repeat;}';
   		$doc->addStyleDeclaration($css);
	
		JToolBarHelper::title(JText::_( 'CK Forms - Data' ), 'ckform' );

		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_ckforms/css/ckforms.css');
		
		if ($this->_layout == "detail")
		{
			JToolBarHelper::back('Data list');

			// Get data from the model
			$item = & $this->get( 'Detail');
			
			$this->assignRef('item',$item);

		} else {
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
			JToolBarHelper::custom('export','export.png','export.png','Export',false) ;
			JToolBarHelper::deleteList();
			JToolBarHelper::back('Forms','index.php?option=com_ckforms');
	
			// Get data from the model
			$items = & $this->get( 'Data');
			$pagination =& $this->get('Pagination');
			$sortf = JRequest::getVar('sortf','id');
			$sortd = JRequest::getVar('sortd','asc');		

			$this->assignRef('items',$items);
			$this->assignRef('pagination', $pagination);
			$this->assignRef('sortf' , $sortf);
			$this->assignRef('sortd' , $sortd);

		}
		
		$fields = & $this->get( 'Datafields');
		$this->assignRef('fields',$fields);

		parent::display($tpl);
	}
}
