<?php

/**
 * @copyright	Copyright (C) 2010 C�dric KEIFLIN alias ced1870
 * http://www.ck-web-creation-alsace.com
 * http://www.joomlack.fr
 * Module Maximenu_CK for Joomla! 1.5
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

// retrieve color params
$document = &JFactory::getDocument();
$menuID = $params->get('menuid', 'maximenuCK');
$menubgcolor = $params->get('menubgcolor', '') ? "background:" . $params->get('menubgcolor', '') : '';


$menustyles = '';
if ($titlescolor = $params->get('titlescolor', ''))
    $menustyles .= "div#" . $menuID . " ul.maximenuCK li.maximenuCK > a span.titreCK {color:" . $titlescolor . ";} div#" . $menuID . " ul.maximenuCK li.maximenuCK > span.separator span.titreCK {color:" . $titlescolor . ";}";

if ($descscolor = $params->get('descscolor', ''))
    $menustyles .= "div#" . $menuID . " ul.maximenuCK li.maximenuCK > a span.descCK {color:" . $descscolor . ";} div#" . $menuID . " ul.maximenuCK li.maximenuCK > span.separator span.descCK {color:" . $descscolor . ";}";

if ($titleshovercolor = $params->get('titleshovercolor', ''))
    $menustyles .= "div#" . $menuID . " ul.maximenuCK li.maximenuCK > a:hover span.titreCK {color:" . $titleshovercolor . ";} div#" . $menuID . " ul.maximenuCK li.maximenuCK > span.separator:hover span.titreCK {color:" . $titleshovercolor . ";}";

if ($descshovercolor = $params->get('descshovercolor', ''))
    $menustyles .= "div#" . $menuID . " ul.maximenuCK li.maximenuCK > a:hover span.descCK {color:" . $descshovercolor . ";} div#" . $menuID . " ul.maximenuCK li.maximenuCK > span.separator:hover span.descCK {color:" . $descshovercolor . ";}";


$document->addStyleDeclaration($menustyles);

//check if we need Virtuemart for helper
$usevm = $params->get('usevm', '0');
if ($usevm) {
    require_once (dirname(__FILE__) . DS . 'helper_vm.php');
    $items = modmaximenu_CKvmHelper::GetMenu($params);
} else {
    require_once (dirname(__FILE__) . DS . 'helper.php');
    $items = modmaximenu_CKHelper::GetMenu($params);
}

//check if we need Virtuemart for layout
if ($usevm) {
    require(JModuleHelper::getLayoutPath('mod_maximenu_CK', 'default_vm'));
} else {
    require(JModuleHelper::getLayoutPath('mod_maximenu_CK'));
}
?>