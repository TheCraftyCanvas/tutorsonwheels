<?php
/**
 * SimpleForm
 *
 * @version 1.1.0
 * @package SimpleForm
 * @author ZyX (allforjoomla.ru)
 * @copyright (C) 2010 by ZyX (http://www.allforjoomla.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 * If you fork this to create your own project,
 * please make a reference to allforjoomla.ru someplace in your code
 * and provide a link to http://www.allforjoomla.ru
 **/
defined('_JEXEC') or die(':)');

class JElementSfSimpleCode extends JElement {

	var	$_name = 'SfSimpleCode';

	function fetchElement($name, $value, &$node, $control_name) {
		require_once(JPATH_ROOT.DS.'modules'.DS.'mod_simpleform'.DS.'simpleform.class.php');
		JHTML::script('jquery.js', JURI::root().'/modules/mod_simpleform/ajax/');
		$url2imgs = JURI::root().'/modules/mod_simpleform/images/';
		$params = $this->_parent;
		$form = new simpleForm($params,true);
		$options = array(
			JHTML::_('select.option', 'text', JText::_('Text field')),
			JHTML::_('select.option', 'textarea', JText::_('Text area')),
			JHTML::_('select.option', 'select', JText::_('Select list')),
			JHTML::_('select.option', 'radio', JText::_('Radio list')),
			JHTML::_('select.option', 'checkbox', JText::_('Check list')),
			JHTML::_('select.option', 'file', JText::_('File')),
			JHTML::_('select.option', 'captcha', JText::_('Captcha')),
			JHTML::_('select.option', 'button', JText::_('Button')),
			JHTML::_('select.option', 'reset', JText::_('Reset button')),
			JHTML::_('select.option', 'submit', JText::_('Submit button'))
		);
		$html = '<p><strong>'.JText::_('Add element').'</strong>: '.JHTML::_('select.genericlist', $options, 'sfType', '', 'value', 'text', '').' <img style="cursor:pointer;" onclick="sfAddElement(null,null)" src="'.$url2imgs.'icon_add.gif" align="top" /></p>';
		$html.= '<div id="sfParams"></div>';
		$html.= '
		<style type="text/css">
		#sfParams p{margin:0 0 5px;}
		#sfParams fieldset{background:#f0f0f0;position:relative;width:320px;}
		#sfParams legend img{cursor:pointer;}
		.sf2cols{height:33px;margin:0 0 5px;overflow:hidden;}
		.sf2cols div{width:140px;float:left;margin-right:10px;}
		.sf2cols input{width:140px;}
		.sf2listElems img{position:absolute;margin-left:5px;}
		.sf2listElems .sf2cols{height:18px;margin:0 0 3px;}
		</style>
		<script type="text/javascript">
		jQuery.noConflict();
		var sfNums = 0;
		var sfElNums = 0;
		function sfAddElement(type,dataObj){
			var html = "";
			if(!type) var type = jQuery("#sfType").val();
			if(!dataObj) dataObj = {label:"",value:"",regex:"",error:"",required:0,class:"",onchange:"",multiple:0,options:[],extensions:"",maxsize:"",width:"",height:"",color:"",background:"",onclick:""};
			var titles = {text:"'.JText::_('Text field').'",textarea:"'.JText::_('Text area').'",select:"'.JText::_('Select list').'",radio:"'.JText::_('Radio list').'",checkbox:"'.JText::_('Check list').'",file:"'.JText::_('File').'",captcha:"'.JText::_('Captcha').'",button:"'.JText::_('Button').'",reset:"'.JText::_('Reset button').'",submit:"'.JText::_('Submit button').'"};
			sfNums++;
			var obj = document.createElement("fieldset");
			html+= "<input type=\"hidden\" name=\"'.$control_name.'[el_"+sfNums+"_type]\" value=\""+type+"\" />";
			html+= "<legend><img onclick=\"sfMoveElement(this.parentNode.parentNode,\'up\')\" src=\"'.$url2imgs.'uparrow.gif\" align=\"absmiddle\" /><img onclick=\"sfMoveElement(this.parentNode.parentNode,\'down\')\" src=\"'.$url2imgs.'downarrow.gif\" align=\"absmiddle\" /> "+titles[type]+" <img onclick=\"sfRemoveElement(this.parentNode.parentNode)\" src=\"'.$url2imgs.'icon_delete.gif\" align=\"absmiddle\" /></legend>";
			if(type=="text"||type=="textarea"){
				html+= "<p>'.JText::_('Required').': <input name=\"'.$control_name.'[el_"+sfNums+"_required]\" value=\"0\" "+(dataObj.required==1?"":"checked=\"checked\"")+" type=\"radio\">'.JText::_('No').' <input name=\"'.$control_name.'[el_"+sfNums+"_required]\" value=\"1\" "+(dataObj.required==1?"checked=\"checked\"":"")+" type=\"radio\">'.JText::_('Yes').'</p>";
				html+= "<div class=\"sf2cols\">";
				html+= "<div>'.JText::_('Title').' *:<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_label]\" value=\""+dataObj.label+"\" /></div>";
				html+= "<div>'.JText::_('Default value').':<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_value]\" value=\""+dataObj.value+"\" /></div>";
				html+= "</div>";
				html+= "<div class=\"sf2cols\">";
				html+= "<div><span class=\"hasTip\" title=\"'.JText::_('Regular expression info').'\">'.JText::_('Regular expression').'</span>:<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_regex]\" value=\""+dataObj.regex+"\" /></div>";
				html+= "<div>'.JText::_('Error text').':<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_error]\" value=\""+dataObj.error+"\" /></div>";
				html+= "</div>";
				html+= "<div class=\"sf2cols\">";
				html+= "<div>'.JText::_('CSS class').':<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_class]\" value=\""+dataObj.class+"\" size=\"30\" /></div>";
				html+= "<div>'.JText::_('Onchange attribute').':<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_onchange]\" value=\""+dataObj.onchange+"\" size=\"30\" /></div>";
				html+= "</div>";
			}
			else if(type=="select"||type=="radio"||type=="checkbox"){
				if(type=="select") html+= "<p><span class=\"hasTip\" title=\"'.JText::_('Allow multiple info').'\">'.JText::_('Allow multiple').'</span>: <input name=\"'.$control_name.'[el_"+sfNums+"_multiple]\" value=\"0\" "+(dataObj.multiple==1?"":"checked=\"checked\"")+" type=\"radio\">'.JText::_('No').' <input name=\"'.$control_name.'[el_"+sfNums+"_multiple]\" value=\"1\" "+(dataObj.multiple==1?"checked=\"checked\"":"")+" type=\"radio\">'.JText::_('Yes').'</p>";
				html+= "<div class=\"sf2cols\">";
				html+= "<div>'.JText::_('Title').' *:<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_label]\" value=\""+dataObj.label+"\" /></div>";
				html+= "<div>'.JText::_('Default value').':<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_value]\" value=\""+dataObj.value+"\" /></div>";
				html+= "</div>";
				html+= "<div class=\"sf2cols\">";
				html+= "<div><span class=\"hasTip\" title=\"'.JText::_('Regular expression info').'\">'.JText::_('Regular expression').'</span>:<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_regex]\" value=\""+dataObj.regex+"\" /></div>";
				html+= "<div>'.JText::_('Error text').':<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_error]\" value=\""+dataObj.error+"\" /></div>";
				html+= "</div>";
				html+= "<div class=\"sf2cols\">";
				html+= "<div>'.JText::_('CSS class').':<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_class]\" value=\""+dataObj.class+"\" size=\"30\" /></div>";
				html+= "<div>'.JText::_('Onchange attribute').':<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_onchange]\" value=\""+dataObj.onchange+"\" size=\"30\" /></div>";
				html+= "</div>";
				html+= "<div style=\"height:16px;\"><strong>'.JText::_('List elements').'</strong> <img style=\"cursor:pointer\" onclick=\"sfAddListElement("+sfNums+",null,null)\" src=\"'.$url2imgs.'icon_add.gif\" align=\"absmiddle\" /></div>";
				html+= "<div class=\"sf2cols\" style=\"height:12px;\"><div>'.JText::_('Title').'</div><div>'.JText::_('Value').'</div></div>";
				html+= "<div class=\"sf2listElems sfEls_"+sfNums+"\">";
				for(var i=0;i<dataObj.options.length;i++){
					html+= sfAddListElement(sfNums,dataObj.options[i].label,dataObj.options[i].value);
				}
				html+= "</div>";
				
			}
			else if(type=="file"){
				html+= "<p>'.JText::_('Required').': <input name=\"'.$control_name.'[el_"+sfNums+"_required]\" value=\"0\" "+(dataObj.required==1?"":"checked=\"checked\"")+" type=\"radio\">'.JText::_('No').' <input name=\"'.$control_name.'[el_"+sfNums+"_required]\" value=\"1\" "+(dataObj.required==1?"checked=\"checked\"":"")+" type=\"radio\">'.JText::_('Yes').'</p>";
				html+= "<div class=\"sf2cols\">";
				html+= "<div>'.JText::_('Title').' *:<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_label]\" value=\""+dataObj.label+"\" /></div>";
				html+= "<div>'.JText::_('Error text').':<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_error]\" value=\""+dataObj.error+"\" /></div>";
				html+= "</div>";
				html+= "<div class=\"sf2cols\">";
				html+= "<div><span class=\"hasTip\" title=\"'.JText::_('Extensions info').'\">'.JText::_('Extensions').'</span>:<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_extensions]\" value=\""+dataObj.extensions+"\" /></div>";
				html+= "<div><span class=\"hasTip\" title=\"'.JText::_('Max size info').'\">'.JText::_('Max size').'</span>:<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_maxsize]\" value=\""+dataObj.maxsize+"\" /></div>";
				html+= "</div>";
				html+= "<div class=\"sf2cols\">";
				html+= "<div>'.JText::_('CSS class').':<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_class]\" value=\""+dataObj.class+"\" size=\"30\" /></div>";
				html+= "</div>";
			}
			else if(type=="captcha"){
				html+= "<div class=\"sf2cols\">";
				html+= "<div>'.JText::_('Title').' *:<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_label]\" value=\""+dataObj.label+"\" /></div>";
				html+= "<div>'.JText::_('Error text').':<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_error]\" value=\""+dataObj.error+"\" /></div>";
				html+= "</div>";
				html+= "<div class=\"sf2cols\">";
				html+= "<div>'.JText::_('Width').':<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_width]\" value=\""+dataObj.width+"\" /></div>";
				html+= "<div>'.JText::_('Height').':<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_height]\" value=\""+dataObj.height+"\" /></div>";
				html+= "</div>";
				html+= "<div class=\"sf2cols\">";
				html+= "<div><span class=\"hasTip\" title=\"'.JText::_('Captcha color info').'\">'.JText::_('Captcha color').'</span>:<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_color]\" value=\""+dataObj.color+"\" maxlength=\"7\" /></div>";
				html+= "<div><span class=\"hasTip\" title=\"'.JText::_('Captcha background info').'\">'.JText::_('Captcha background').'</span>:<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_background]\" value=\""+dataObj.background+"\" maxlength=\"7\" /></div>";
				html+= "</div>";
				html+= "<div class=\"sf2cols\">";
				html+= "<div>'.JText::_('CSS class').':<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_class]\" value=\""+dataObj.class+"\" size=\"30\" /></div>";
				html+= "</div>";
			}
			else if(type=="button"||type=="reset"||type=="submit"){
				html+= "<div class=\"sf2cols\">";
				html+= "<div>'.JText::_('Title').' *:<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_label]\" value=\""+dataObj.label+"\" /></div>";
				html+= "</div>";
				html+= "<div class=\"sf2cols\">";
				if(type!="submit") html+= "<div><span class=\"hasTip\" title=\"'.JText::_('Onclick attribute info').'\">'.JText::_('Onclick attribute').'</span>:<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_onclick]\" value=\""+dataObj.onclick+"\" /></div>";
				html+= "<div>'.JText::_('CSS class').':<br /><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_class]\" value=\""+dataObj.class+"\" size=\"30\" /></div>";
				html+= "</div>";
			}
			obj.innerHTML = html;
			jQuery("#sfParams").append(obj);
			jQuery("#sfParams").closest("div.content").css("height","");
		}
		
		function sfAddListElement(num,label,value){
			sfElNums++;
			var obj = document.createElement("div");
			obj.className = "sf2cols";
			obj.innerHTML = "<div><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_option_"+sfElNums+"_label]\" "+(label?"value=\""+label+"\"":"")+" /></div><div><input type=\"text\" name=\"'.$control_name.'[el_"+sfNums+"_option_"+sfElNums+"_value]\" "+(value?"value=\""+value+"\"":"")+" /><img style=\"cursor:pointer\" onclick=\"sfRemoveElement(this.parentNode.parentNode)\" src=\"'.$url2imgs.'icon_delete.gif\" align=\"top\" /></div>";
			if(label){
				var html = "<div class=\"sf2cols\">"+obj.innerHTML+"</div>";
				return html;
			}
			else jQuery(".sfEls_"+num).append(obj);
		}
		
		function sfRemoveElement(el){
			el.parentNode.removeChild(el);
			jQuery("#sfParams").closest("div.content").css("height","");
		}
		
		function sfMoveElement(obj,direction){
			var swapObj;
			if(direction=="up") swapObj = obj.previousSibling;
			else swapObj = obj.nextSibling;
			if(swapObj&&swapObj.tagName!="fieldset"&&swapObj.tagName!="FIELDSET"){
				if(direction=="up") swapObj = swapObj.previousSibling;
				else swapObj = swapObj.nextSibling;
			}
			if(!swapObj||(swapObj.tagName!="fieldset"&&swapObj.tagName!="FIELDSET")) return;
			var n1 = obj.cloneNode(true);
			var n2 = swapObj.cloneNode(true);
			obj.parentNode.insertBefore(n2,obj);
			obj.parentNode.removeChild(obj);
			swapObj.parentNode.insertBefore(n1,swapObj);
			swapObj.parentNode.removeChild(swapObj);
			
			var h1 = jQuery(n1).height();
			var h2 = jQuery(n2).height();
			if(direction=="up"){
				jQuery(n1).css("top",h1);
				jQuery(n2).css("top",h2*-1);
			}
			else{
				jQuery(n1).css("top",h1*-1);
				jQuery(n2).css("top",h2);
			}
			jQuery(n1).animate({top: 0,}, 100);
			jQuery(n2).animate({top: 0,}, 100);
		}
		';
		if(count($form->elements)){
			$html.= 'jQuery(document).ready(function(){';
			$sfElNums = 0;
			foreach($form->elements as $elem){
				$sfElNums++;
				$html.= 'sfAddElement("'.$elem->type.'",'.$elem->getJSCode().');'."\n";
			}
			$html.= '
			window.setTimeout(function(){jQuery("#sfParams").closest("div.content").css("height","");}, 500);
			});';
		}
		$html.= '
		</script>';
		return $html;
	}

}