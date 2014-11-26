<?php
/**
 * QUBER
 * This program is free software: you can redistribute it and/or modify it under the terms
 * of the GNU General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *@author CU3ER
 *@copyright (C) 2009 CU3ER
 *@link http://www.cu3er.com/ Official website
 *
 * Used and modified by Cédric Keiflin for Maximenu_CK for Joomla!
**/
defined('_JEXEC') or die( 'Restricted access' );
class JElementCp extends JElement
{
   var $_name = 'Cp';
    function fetchElement($name, $value, &$node, $control_name)
    {
        $path = 'modules/mod_maximenu_CK/elements/';
        $js = "
        window.addEvent('domready', function() {
            var params$name = new MooRainbow('colorpicker_".$name."', {
                'id': 'id_$name',
                'startColor': [58, 142, 246],
                'onChange': function(color) {
                    $('colorbox_".$name."').value = color.hex;
                    $('colorpicker_".$name."').setStyle('background-color', color.hex);
                }
            });
        });
        ";
        JHTML::_( 'behavior.mootools' );
        JHTML::stylesheet( 'cp.css', $path );
        JHTML::script( 'cp.js', $path );
        $document = JFactory::getDocument();
        $document->addScriptDeclaration( $js );
        $html = '';
        $html .= '<input id="colorbox_'.$name.'" name="params['.$name.']" value="'.$value.'" type="text" size="13" class="colorpicker" />';
        $html .= '<div id="colorpicker_'.$name.'" title="Pick a color / Choisir une couleur" style="background-color:'.$value.'" class="colorpicker" />';
        return $html;
    }
}