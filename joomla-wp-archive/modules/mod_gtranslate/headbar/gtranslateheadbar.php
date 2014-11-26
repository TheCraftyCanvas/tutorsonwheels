<?php
/**
* @version   $Id: gtranslateheadbar.php 146 2012-03-15 00:11:28Z edo888 $
* @package   GTranslate
* @copyright Copyright (C) 2008-2012 Edvard Ananyan. All rights reserved.
* @license   GNU/GPL v3 http://www.gnu.org/licenses/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

class JElementGTranslateHeadbar extends JElement {

    var $_name = 'GTranslateHeadbar';

    function fetchTooltip() {
        return;
    }

    function fetchElement($name, $value, &$node) {
        $html = '<div style="float:left;padding:10px;"><a href="http://gtranslate.net/" target="_blank"><img src="http://gtranslate.net/templates/gtranslate/images/gt_logo_joomla.png" alt="GTranslate"></a></div>
<div class="toolbar">
    <table class="toolbar">
    <tr>
        <td class="button">
            <a class="toolbar" rel="help" onclick="popupWindow(\'http://gtranslate.net/docs/54-joomla-module-documentation?tmpl=component\', \'Documentation\', 700, 500, 1)" href="#">
            <span class="icon-32-help"></span>Documentation</a>
        </td>
    </tr>
    </table>
</div>
<div class="clr"></div>';

        //TODO: update notification 

        return $html;
    }
}