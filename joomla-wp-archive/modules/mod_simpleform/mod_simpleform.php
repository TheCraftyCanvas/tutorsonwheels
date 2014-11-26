<?php
/**
* ZyX SimpleForm
*
* @package ZyX SimpleForm 1.1.0
* @copyright (C) 2011 ZyX allForJoomla.ru
* @url http://www.allForJoomla.ru/
* @authors ZyX <info@litecms.ru>
**/
defined('_JEXEC') or die(':)');
require_once ( JPATH_BASE .DS.'modules'.DS.'mod_simpleform'.DS.'simpleform.class.php' );
$id = 'simpleForm_'.rand(100,999);
$okText = addslashes($params->get('okText','OK'));
$config =& JFactory::getConfig();
$cache = $params->get('cache',0);
$cssFile = $params->get('cssFile','');
$sysCache = $config->getValue('config.caching');
$inclJquery = (int)$params->get('inclJquery',1);
$jqueryNoConflict = (int)$params->get('jqueryNoConflict',0);
$loadScriptsMode = $params->get('loadScriptsMode','body');

if($loadScriptsMode=='body'){
	$cache = 1;
	$sysCache = 1;
}

$jID = 'jQuery';

$script = '
'.($jqueryNoConflict?''.$jID.'.noConflict();':'').'
'.$jID.'(document).ready(function(){
	'.$jID.'("form#'.$id.'").bind("submit",function() {
		if(!document.getElementById("'.$id.'_wrap")){'.$jID.'("#'.$id.'_submit").wrap("<span id=\''.$id.'_wrap\' />");}
		'.$id.'_tmp = '.$jID.'("#'.$id.'_wrap").html();
		'.$jID.'("#'.$id.'_wrap").html("<img src=\"'.JURI::root().'modules/mod_simpleform/images/loading.gif\" alt=\"Loading...\" title=\"Loading...\" />");
		'.$jID.'(this).ajaxSubmit(function(data) {
				var key = data.substring(0,1);
				var text = data.substring(1);
				var captcha = '.$jID.'("#captcha_'.$id.'");
				captcha.click();
				if(key=="="){
					'.$jID.'("form#'.$id.'").html(text);
				}
				else if(key=="!"){
					'.$jID.'("#'.$id.'_wrap").html('.$id.'_tmp);
					alert(text);
				}
				else{
					'.$jID.'("#'.$id.'_wrap").html('.$id.'_tmp);
					alert("'.JText::_('Error').'");
				}
			}
		);
		return false;
	});
});
';
if($cache==1&&$sysCache==1){
	if(!defined('SIMPLEFORM')){
		if($inclJquery) echo '<script type="text/javascript" src="'.JURI::root().'modules/mod_simpleform/ajax/jquery.js"></script>'."\n";
		echo '<script type="text/javascript" src="'.JURI::root().'modules/mod_simpleform/ajax/jquery.form.js"></script>'."\n";
		if($cssFile!='') echo '<link rel="stylesheet" href="'.JURI::root().'modules/mod_simpleform/css/'.$cssFile.'.css" type="text/css" />';
	}
	echo '<script type="text/javascript">
	'.$script.'
	</script>';
}
else{
	$doc = &JFactory::getDocument();
	if(!defined('SIMPLEFORM')){
		if($inclJquery) $doc->addScript(JURI::root().'modules/mod_simpleform/ajax/jquery.js');
		$doc->addScript(JURI::root().'modules/mod_simpleform/ajax/jquery.form.js');
	}
	$doc->addScriptDeclaration($script);
	if($cssFile!='') $doc->addStyleSheet(JURI::root().'modules/mod_simpleform/css/'.$cssFile.'.css');
}

$form = new simpleform($params);
$form->set('id',$id);
$form->set('moduleID',$module->id);
$form->set('_key',$params->get('domainKey',''));
$form->render();
defined('SIMPLEFORM') or define('SIMPLEFORM',1);