<?php
/**
 * Deflaut View for CKForms Component
 * 
 * @package    CKForms
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the CKForms Component
 *
 * @package		CKForms
 * @subpackage	Components
 */
class CkformsViewCkforms extends JView
{
	function display($tpl = null)
	{	
		global $mainframe;
		$ckforms = & $this->get('Data');
	
		$post = JRequest::get('post', JREQUEST_ALLOWHTML);
		$this->assignRef( 'post',$post );
		
		$params =& $mainframe->getParams('com_content');
		$this->assignRef('params' , $params);
	
		$this->assignRef( 'ckforms',$ckforms );
		
		$formLink = "index.php?option=com_ckforms&view=ckforms&task=send&id=".$ckforms->id;		
		$this->assignRef( 'formLink',$formLink );

		$document =& JFactory::getDocument();
		
		$document->addCustomTag('<script type="text/javascript" src="'.JURI::root(true).'/media/system/js/mootools.js"></script>');
		$document->addCustomTag('<script type="text/javascript" src="'.JURI::root(true).'/components/com_ckforms/js/calendar.js"></script>');
		$document->addCustomTag('<script type="text/javascript" src="'.JURI::root(true).'/components/com_ckforms/js/formcheck.js"></script>');
		
		$document->addStyleSheet(JURI::root(true).'/components/com_ckforms/css/calendar.css');
		$document->addStyleSheet(JURI::root(true).'/components/com_ckforms/css/ckforms.css');
		$document->addStyleSheet(JURI::root(true).'/components/com_ckforms/css/tips.css');
		$document->addStyleSheet(JURI::root(true).'/components/com_ckforms/js/theme/classic/formcheck.css');
		
		parent::display($tpl);
		
	}

}
?>
