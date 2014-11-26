<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

// add the Joomla validation
JHTMLBehavior::formvalidation();

// include the helper file
require_once (dirname(__FILE__).DS.'helper.php');

//output the javascript
$loDoc =& JFactory::getDocument();
$loDoc->addScript(JURI::root().'modules'.DS.'mod_ampcontact'.DS.'mod_ampcontact.js');
$loDoc->addStyleSheet(JURI::root().'modules'.DS.'mod_ampcontact'.DS.'mod_ampcontact.css');

// get required parameters
$lsSubmitText = $params->get('submit_button', 'Make Contact');
$lsStyleSuffix = $params->get('moduleclass_sfx', null);

// check if form has been submitted
$lsAction = JRequest::getVar('ampContactAction', null, 'POST');
if ($lsAction == 'send') {
    $lsMessage = modAmpContactHelper::sendEmail($params);
}

// get the introduction parameter
if (!isset($lsMessage)) $lsMessage = $params->get('introtext');
// get credit status
$credit = @$params->get( 'credit');
?>
<script language="javascript">
	function myAmpContactValidate(f)
	{
		if (document.formvalidator.isValid(f)) {
			f.check.value='<?php echo JUtility::getToken(); ?>'; //send token
			return true; 
		} else {
			alert('Some values are not acceptable. Please retry.');
		}
		return false;
	}
</script>
<div id="ampContact"><?php echo $lsStyleSuffix; ?>
	<p><?php echo $lsMessage; ?></p>
	<form id="ampContactForm" method="post" class="form-validate" onSubmit="return myAmpContactValidate(this);" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<input type="text" name="name" value="Your Name" onFocus="clearAmpContactText(this)" class="ampContactText" />
		<br />
		<input type="text" name="email" value="Email Address" onFocus="clearAmpContactText(this)" class="ampContactText required validate-email" />
		<br />
		<input type="text" name="telephone" value="Telephone" onFocus="clearAmpContactText(this)" class="ampContactText" />
		<br />
		<textarea name="text" class="ampContactTextarea"></textarea>
		<br />
		<input type="submit" value="<?php echo $lsSubmitText; ?>" class="ampContactButton" />
		<input type="hidden" name="ampContactAction" value="send" />
		<input type="hidden" name="check" value="post" />
	</form>
<?php if ($credit == 1) { ?>
	<div id="ampcredit"><a href="http://www.projectamplify.com/" title="Amplify UK" target="_blank" >Powered by AmpContact</a></div>
<?php } else { ?>
	<div id="ampcredit0"><a href="http://www.projectamplify.com/" title="Amplify UK" target="_blank" >Powered by AmpContact</a></div>
<?php } ?>
</div>