<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

class modAmpContactHelper
{
	
	function sendEmail($params)
	{
		global $mainframe;
		
		$jAp =& JFactory::getApplication();
		
		if ($_POST['check'] != JUtility::getToken()) {
			if ($_POST['check'] == 'post') {
				$lsErrorMsg  = 'Please check all the fields of the contact form.<br />';
				$lsErrorMsg .= 'If your browser blocks javascript, then this form will never be successful. This is a security measure.';
				$jAp->enqueueMessage($lsErrorMsg,'error');
			}
			return false;
		}

		// get the parameters
		$lsEmail   = $params->get('receipt_email');
		$lsSubject = $params->get('subject');
		$lsThanks  = $params->get('thanks');
		$lsError   = $params->get('error');
		
		// get the posted data
		$lsUserName    		= JRequest::getVar('name', null, 'POST');
	    $lsUserEmail   		= JRequest::getVar('email', null, 'POST');
	    $lsUserTelephone    = JRequest::getVar('telephone', null, 'POST');
	    //$lsUserOrganisation = JRequest::getVar('organisation', null, 'POST');
	    $lsUserText    		= JRequest::getVar('text', 'Not Given', 'POST');
	    
	    $lsFromEmail = $mainframe->getCfg('mailfrom');
	    $lsFromName  = $mainframe->getCfg('fromname');
	    $lsFrom 	 = array($lsFromEmail, $lsFromName);
	    
	    // set up the email body
	    $lsBody = 'The following user has entered a message:'."\n";
	    $lsBody .= "Email: $lsUserEmail" . "\n";
	    $lsBody .= "Name: $lsUserName" . "\n";
	    $lsBody .= "Telephone: $lsUserTelephone" . "\n";
	    //$lsBody .= "Organisation: $lsUserOrganisation" . "\n\n";
	    $lsBody .= "Message: " . "\n";
	    $lsBody .= $lsUserText . "\n\n";
	    $lsBody .= "---------------------------" . "\n";
	    //$lsBody .= 'Thank you for using the AmpContact module, please visit us at: www.projectamplify.com';
	    
	    $loMailer =& JFactory::getMailer();
	    $loMailer->setSender($lsFrom);
	    $loMailer->addReplyTo($lsFrom);
	    $loMailer->addRecipient($lsEmail);
	    $loMailer->setSubject($lsSubject);
	    $loMailer->setBody($lsBody);
	    
	    if ($loMailer->Send() !== true) {
	    	return $lsError;
	    }
	    else {
	    	return $lsThanks;
	    }
	} //end function sendEmail
} //end class modAmpContactHelper
?>