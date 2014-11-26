<?php
/**
 * Ck forms default controller
 * 
 * @package    CK.Joomla
 * @subpackage Components
 * @link http://www.cookex.eu
 * @license		GNU/GPL
 */

jimport('joomla.application.component.controller');

/**
 * Ck forms Component Controller
 *
 * @package	CkForms
 */
class CkformsController extends JController
{
	/**
	 * Method to display the captcha to validate the form
	 *
	 * @access	public
	 */
	function captcha()
	{
		include("components/com_ckforms/captcha/securimage.php");
				
		$document = &JFactory::getDocument();
		$doc = &JDocument::getInstance('raw');
		$document = $doc;
		$img = new Securimage();
		$img->ttf_file = "components/com_ckforms/captcha/elephant.ttf";
		$img->show();
	}

	function  display()
	{
		$model = $this->getModel('ckforms');
		$model->addHits();
		
		$_SESSION['ck_send_once'.JRequest::getCmd('id')] = "1";
		$_SESSION['ck_cache_page_'.JRequest::getCmd('id')] = md5(JRequest::getURI());
		
		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * and send emails
	 * @return void
	 */
	function send()
	{		
		$model = $this->getModel('ckforms');
		$ckform = $model->getData();		

		if ($ckform->captcha == 1)
		{
			include("components/com_ckforms/captcha/securimage.php");
			
			$img = new Securimage();
			
			$valid = $img->check($_POST['ck_captcha_code']);			
			
			if($valid == false) {
				JError::raiseWarning( 0, JText::_( "Sorry, the code you entered was invalid" ));
				
				$this->display();
				return false;
			}
		}
		if (isset($_SESSION['ck_send_once'.$ckform->id]))
		{
			session_unregister('ck_send_once'.$ckform->id);			
		} else {
			JError::raiseWarning( 0, JText::_( "Sorry, you can send the form only once" ));
			return false;		
		}

		session_unregister('securimage_code_value');

		$post = JRequest::get('post', JREQUEST_ALLOWHTML);	
		
		$model->saveData($post);				

		if (isset($_SESSION['ck_cache_page_'.$ckform->id]))
		{
			$cacheid = $_SESSION['ck_cache_page_'.$ckform->id];
			$cache = &JFactory::getCache();
			$cacheresult = $cache->remove($cacheid, 'page'); 
		}
		
		$msg = JText::sprintf('Form successfully sent', 1);
		
		if ( isset($ckform->redirecturl) && $ckform->redirecturl != "") {
			
			$params = '';
			
			if ($ckform->redirectdata == 1)
			{
				foreach ($post as $key => $value) {
					$params = $params . '&'. $key.'='.htmlentities($value);
				}
				if (strlen($params) > 0 && strpos($ckform->redirecturl, '?') === false)
				{
					$params = '?'.substr($params, 1); 
				}
			}
			$this->setRedirect($ckform->redirecturl.$params);
			
			
		} else if ((isset($ckform->redirecturl) == false || $ckform->redirecturl == "")
			&& ((isset($ckform->textresult) == false || $ckform->textresult == ""))) {
			$this->setRedirect(JURI::base(), $msg);
		}
		
		JRequest::setVar( 'view', 'ckforms' );
		JRequest::setVar( 'layout', 'message'  );
		
		parent::display();

	}
}
?>