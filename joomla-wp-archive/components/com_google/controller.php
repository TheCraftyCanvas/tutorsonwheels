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
jimport('joomla.application.component.controller');

/**
 * com_google Component Controller
 *
 * @package		com_google
 */
class GoogleController extends JController
{
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display()
	{
		parent::display();
	}
	function sendMail()
	{
		
		$senderName	= JRequest::getVar( 'myName', 0, '', 'text' );
		$friendEmail	= JRequest::getVar( 'myEmail', 0, '', 'text' );
		$mailHeading	= JRequest::getVar( 'messHeading', 0, '', 'text' );
		$messText	= JRequest::getVar( 'messateTxt', 0, '', 'text' );
		$copyOfmail	= JRequest::getVar( 'copyOfmail', 0, '', 'text' );
		$ourEmail	= JRequest::getVar( 'ourEmail', 0, '', 'text' );
		$ourSendEmail	= JRequest::getVar( 'ourSendEmail', 0, '', 'text' );
		
		
		
		
		$googleId	= JRequest::getInt( 'id',			0,			'post' );
		$googleItemId	= JRequest::getInt( 'Itemid',			0,			'post' );
		$googleRedItemId	= JRequest::getInt( 'RedirectLinkComGoogle',			0,			'post' );
		
		$from = $ourSendEmail;
		if($from == '')
		{
			$from  ="info@".$_SERVER['HTTP_HOST'];
		}
		
		$sender =$senderName;
		$email = $friendEmail;
		$subject = $mailHeading;
		$body = "From ".$friendEmail."\n".$messText;
		
		if($friendEmail != "")
		{
			JUtility::sendMail($from, $sender, $ourEmail, $subject, $body);
			
			if($copyOfmail == "copyMail" && $copyOfmail != "0")
			{
				JUtility::sendMail($from, $sender, $email, $subject, $body);
			}
			$msg = JText::_('GOOGLE_THANKS');
		}
		else
		{
			$msg = JText::_('GOOGLE_JS_MYEMAIL');
		}
		
		$link = JRoute::_('index.php?option=com_google&view=google&id='.$googleId.'&Itemid='.$googleItemId, false);
		
		$this->setRedirect($link, $msg);
			
		
		
		
	}

}
?>