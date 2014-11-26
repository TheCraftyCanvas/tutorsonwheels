<?php
/**
 * @package XpertScroller
 * @version 1.3
 * @author ThemeXpert http://www.themexpert.com
 * @copyright Copyright (C) 2009 - 2011 ThemeXpert
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

class JElementRadio extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Radio';

	function fetchElement($name, $value, &$node, $control_name)
	{
        $options = array ();
		foreach ($node->children() as $option)
		{
			$val	= $option->attributes('value');
			$text	= $option->data();
			$options[] = JHTML::_('select.option', $val, JText::_($text));
		}
        $i=1;
        $html ='';
        foreach($options as $option){            
            $selected = ($value == $option->value) ? 'selected':"" ;
            $class = ($i == 1) ? 'tx-enable':'tx-disable';    
            
            $html .= '<label for="'.$control_name.$name.$option->value.'" class="'.$class.' '.$selected.'"><span>'.$option->text.'</span></label>';
            $i++;
        }
		return JHTML::_('select.radiolist', $options, ''.$control_name.'['.$name.']','','value', 'text', $value, $control_name.$name ). $html;
	}
}
