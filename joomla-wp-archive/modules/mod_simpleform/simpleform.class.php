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

class simpleForm extends JObject{
	var $code = '';
	var $elements = array();
	var $attachments = array();
	var $id = null;
	var $_key = '';
	var $hasCaptcha = false;
	var $hasSubmit = false;
	var $side = 'backend';
	var $moduleID = null;
	var $template = 'default';
	var $defaultError = '%s';
	
	function simpleForm($params,$isBackend=false){
		if(!$isBackend) $this->side = 'frontend';
		$result = $this->getElements($params);
		return $result;
	}
	
	function getElements($params){
		$pArray = $params->toArray();
		$this->elements = array();
		$paramNames = array('regex','label','error','onclick','onchange','value','type','class','required','multiple','width','height','extensions','maxsize','color','background');
		$optionParamNames = array('label','value','selected','onclick','onchange');
		foreach($pArray as $pKey=>$pVal){
			$pVal = trim($pVal);
			if(preg_match('~^el_([0-9]+)_option_([0-9]+)_([a-z]+)$~',$pKey,$matches)){
				if(!in_array($matches[3],$optionParamNames)) continue;
				$elKey = (int)$matches[1];
				$opKey = (int)$matches[2];
				if(!is_object($this->elements[$elKey])) $this->elements[$elKey] = new simpleFormElement(md5($elKey));
				if(!is_object($this->elements[$elKey]->options[$opKey])) $this->elements[$elKey]->options[$opKey] = new stdclass;
				$this->elements[$elKey]->options[$opKey]->$matches[3] = $pVal;
				if($pVal!='') $this->elements[$elKey]->values[] = $pVal;
				
			}
			else if(preg_match('~^el_([0-9]+)_([a-z]+)$~',$pKey,$matches)){
				if(!in_array($matches[2],$paramNames)) continue;
				$elKey = (int)$matches[1];
				if(!isset($this->elements[$elKey])||!is_object($this->elements[$elKey])) $this->elements[$elKey] = new simpleFormElement(md5($elKey));
				$this->elements[$elKey]->$matches[2] = $pVal;
			}
		}
		foreach($this->elements as $k=>$el){
			$elem = &$this->elements[$k];
			if($elem->label==''){
				unset($this->elements[$k]);
				continue;
			}
			$elem->required = (bool)($elem->required=='1');
			$elem->multiple = (bool)($elem->multiple=='1');
			if(isset($elem->value)) $elem->values[] = $elem->value;
			if($elem->type=='captcha'){
				if(!isset($elem->color)||!preg_match("/\#?[0-9ABCDEFabcdef]{6}/",$elem->color)) $elem->color = '';
				if(!isset($elem->background)||!preg_match("/\#?[0-9ABCDEFabcdef]{6}/",$elem->background)) $elem->background = '';
				$elem->required = true;
				$session =& JFactory::getSession();
				$elem->values[] = $session->get('simpleForm.captcha', null);
				if($this->hasCaptcha){
					unset($this->elements[$k]);
					continue;
				}
				$this->hasCaptcha = true;
			}
			else if($elem->type=='submit'){
				if($this->hasSubmit){
					unset($this->elements[$k]);
					continue;
				}
				$this->hasSubmit = true;
			}
			else if($elem->type=='file'){
				$exts = array();
				if($elem->extensions!=''){
					$tmpExts = explode(',',$elem->extensions);
					if(is_array($tmpExts)&&count($tmpExts)>0){
						foreach($tmpExts as $tmpExt){
							$tmpExt = trim($tmpExt);
							if(preg_match('/^[a-zA-Z0-9]{2,4}$/',$tmpExt)) $exts[] = $tmpExt;
						}
					}
				}
				$elem->extensions = $exts;
				$maxSize = 0;
				if($elem->maxsize!=''){
					$measure = strtolower(substr($elem->maxsize,-2));
					$size = (int)substr($elem->maxsize,0,-2);
					if($size>0&&($measure=='kb'||$measure=='mb')){
						if($measure=='mb') $maxSize = $size*1024*1024;
						else $maxSize = $size*1024;
					}
				}
				$elem->maxsize = $maxSize;
			}
		}
		return true;
	}
		
	function render(){
		if(count($this->elements)==0) return false;
		$id = $this->id;
		$uri = &JURI::getInstance();
		$code = '<form method="post" action="'.JURI::root().'modules/mod_simpleform/engine.php" id="'.$id.'" name="'.$id.'" enctype="multipart/form-data" class="simpleForm">';
		$code.= '<input type="hidden" name="moduleID" value="'.$this->moduleID.'" />';
		$code.= '<input type="hidden" name="task" value="sendForm" />';
		$code.= '<input type="hidden" name="Itemid" value="'.JRequest::getInt( 'Itemid').'" />';
		$code.= '<input type="hidden" name="url" value="'.$uri->toString().'" />';
		foreach($this->elements as $elem){
			$code.= $this->renderElement($elem);
		}
		$code.= '</form>'."\n";
		$code.= base64_decode('PGRpdiBzdHlsZT0iYm9yZGVyLXRvcDoxcHggc29saWQgI2NjYzt0ZXh0LWFsaWduOnJpZ2h0OyI+PGEgdGFyZ2V0PSJfYmxhbmsiIHRpdGxlPSJzaW1wbGVGb3JtMiIgaHJlZj0iaHR0cDovL2FsbGZvcmpvb21sYS5jb20iIHN0eWxlPSJ2aXNpYmlsaXR5OnZpc2libGU7ZGlzcGxheTppbmxpbmU7Y29sb3I6I2NjYzsiPnNpbXBsZUZvcm0yPC9hPjwvZGl2Pg==');
		echo $code;
	}
	
	function processRequest($request){
		if(count($this->elements)==0){
			$this->setError(JText::_('No elements found in code'));
			return false;
		}
		$result = '';
		foreach($this->elements as $elem){
			if($elem->check($this,$request)!==true){
				$error = $elem->getError();
				$this->setError(($error?$error:sprintf($this->defaultError,$elem->label)));
				return false;
			}
			if(count($elem->requests)) $result.= $this->getTemplate('mail_form_item',array('label'=>$elem->label,'value'=>implode(', ',$elem->requests)));
		}
		return $result;
	}
	
	function renderElement($elem){
		$result = '';
		$name = $elem->name;
		$id = $elem->id;
		$class = @$elem->class;
		$default = @$elem->value;
		$label = '';
		if($elem->label!='') $label = '<label for="'.$elem->id.'">'.$elem->label.($elem->required?' <span>*</span>':'').'</label> ';
		switch($elem->type){
			case 'text':
				$onchange = @$elem->onchange;
				$result.= '<input type="text" name="'.$name.'" id="'.$id.'"'.($class?' class="'.$class.'"':'').($onchange?' onchange="'.$onchange.'"':'').' value="'.$default.'" />';
			break;
			case 'textarea':
				$onchange = @$elem->onchange;
				$result.= '<textarea name="'.$name.'" id="'.$id.'"'.($class?' class="'.$class.'"':'').($onchange?' onchange="'.$onchange.'"':'').'>'.$default.'</textarea>';
			break;
			case 'select':
				$multi = @$elem->multiple;
				$onchange = @$elem->onchange;
				$result = '<select'.($multi?' multiple="multiple"':'').' name="'.$name.($multi?'[]':'').'" id="'.$id.'"'.($class?' class="'.$class.'"':'').($onchange?' onchange="'.$onchange.'"':'').'>'.$result;
				foreach($elem->options as $option){
					$result.= '<option value="'.$option->value.'"'.($default==$option->value?' selected="selected"':'').'>'.$option->label.'</option>';
				}
				$result.= '</select>';
			break;
			case 'radio':
				foreach($elem->options as $option){
					$id = md5($name.'_'.$option->label);
					$onclick = @$option->onclick;
					$result.= '<input type="radio" name="'.$name.'" id="'.$id.'" value="'.$option->value.'"'.($class?' class="'.$class.'"':'').($onclick?' onclick="'.$onclick.'"':'').($default==$option->value?' checked="checked"':'').' /><label for="'.$id.'">'.$option->label.'</label>';
				}
			break;
			case 'button':
				$label = '';
				$default = @$elem->label;
				$onclick = @$elem->onclick;
				$result.= '<input type="button"'.($class?' class="'.$class.'"':'').($onclick?' onclick="'.$onclick.'"':'').' value="'.$default.'" />';
			break;
			case 'submit':
				$label = '';
				$default = @$elem->label;
				$id = $this->id.'_submit';
				$result.= '<input'.($class?' class="'.$class.'"':'').' type="submit" value="'.$default.'" id="'.$id.'" />';
			break;
			case 'reset':
				$label = '';
				$default = @$elem->label;
				$onclick = @$elem->onclick;
				$result.= '<input type="reset"'.($name?' name="'.$name.'"':'').($class?' class="'.$class.'"':'').($onclick?' onclick="'.$onclick.'"':'').' value="'.$default.'" />';
			break;
			case 'checkbox':
				$default = @$elem->value;
				$single = false;
				if(count($elem->options)==0){
					$elem->options = array($elem);
					$single = true;
				}
				foreach($elem->options as $option){
					$elid = $id;
					if(!$single){
						$elid = md5($name.'_'.$option->label);
						//$default = @$option->value;
					}
					$onclick = @$option->onclick;
					$optionCode = '<input type="checkbox" name="'.$name.(!$single?'[]':'').'" id="'.$elid.'"'.($class?' class="'.$class.'"':'').($onclick?' onclick="'.$onclick.'"':'').($default==$option->value?' checked="checked"':'').' value="'.$default.'" />';
					if($single) $result.= $optionCode;
					else{
						$optionCode.= ' <label for="'.$elid.'">'.$option->label.'</label>';
						$result.= $optionCode;
					}
				}
			break;
			case 'captcha':
				$default = @$elem->value;
				$urlAdd = array();
				$urlAdd[] = 'moduleID='.$this->moduleID;
				$urlAdd[] = 'rand='.rand(1,99999);
				$onclick = 'this.src=\''.JURI::root().'modules/mod_simpleform/engine.php?task=captcha'.(count($urlAdd)?'&'.implode('&',$urlAdd):'').'&rand=\'+Math.random();';
				$result.= '<img id="captcha_'.$this->id.'" src="'.JURI::root().'modules/mod_simpleform/engine.php?task=captcha'.(count($urlAdd)?'&'.implode('&',$urlAdd):'').'" alt="'.JText::_('Click to refresh').'" title="'.JText::_('Click to refresh').'" onclick="'.$onclick.'"'.($class?' class="'.$class.'"':'').' style="cursor:pointer;" />
				<div><input type="text" name="'.$name.'" id="'.$id.'"'.($class?' class="'.$class.'"':'').' value="'.$default.'" /></div>';
			break;
			case 'file':
				$onchange = @$elem->onchange;
				$result.= '<input type="file" name="'.$name.'" id="'.$id.'"'.($class?' class="'.$class.'"':'').($onchange?' onchange="'.$onchange.'"':'').' />';
			break;
		}
		if($label!='') $result = $label.$result;
		$result = '<p'.($class?' class="'.$class.'"':'').'>'.$result.'</p>';
		return $result;
	}
	
	function getUserIp() { 
		if (getenv('REMOTE_ADDR')) $ip = getenv('REMOTE_ADDR'); 
		elseif(getenv('HTTP_X_FORWARDED_FOR')) $ip = getenv('HTTP_X_FORWARDED_FOR'); 
		else $ip = getenv('HTTP_CLIENT_IP');
		return $ip;
	}
	
	function getTemplate($tmpl,$vars){
		global $mainframe;
		$tPath = JPATH_BASE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'mod_simpleform'.DS.$tmpl.'.php';
		$bPath = JPATH_BASE.DS.'modules'.DS.'mod_simpleform'.DS.'tmpl'.DS.$tmpl.'.php';
		if(file_exists($tPath)) $tmplPath = $tPath;
		else $tmplPath = $bPath;
		unset($tmpl);
		unset($tPath);
		unset($bPath);
		extract($vars);
		ob_start();
		include($tmplPath);
		$content = ob_get_clean();
		return $content;
	}
}

class simpleFormElement extends JObject{
	var $code = null;
	var $name = null;
	var $id = null;
	var $label = '';
	var $value = null;
	var $values = array();
	var $regex = null;
	var $error = null;
	var $type = null;
	var $requests = array();
	var $options = array();
	var $required = false;
	var $multiple = false;
	
	function simpleFormElement($id){
		$this->name = $id;
		$this->id = $id;
	}
	
	function check(&$form,$request){
		$checkVal = $this->getParam($request,$this->name,null);
		if(in_array($this->type,array('text','textarea'))){
			$checkVal = trim($checkVal);
			if(($this->required&&$checkVal=='')||($this->regex!=''&&!preg_match($this->regex,$checkVal))){
				$this->setError($this->error);
				return false;
			}
			$this->requests[] = $checkVal;
		}
		else if(in_array($this->type,array('select','radio','checkbox'))){
			if(is_array($checkVal)){
				$has = array_intersect($checkVal,$this->values);
				if($this->required&&count($has)==0||(count($checkVal)>0&&count($has)==0)){
					$this->setError($this->error);
					return false;
				}
				$this->requests = $checkVal;
			}
			else if(is_null($checkVal)){
				$this->requests[] = '';
				if($this->required){
					$this->setError($this->error);
					return false;
				}
			}
			else{
				$checkVal = trim($checkVal);
				if(($this->required&&$checkVal=='')||(count($this->values)>0&&!in_array($checkVal,$this->values))){
					$this->setError($this->error);
					return false;
				}
				$this->requests[] = $checkVal;
			}
		}
		else if(in_array($this->type,array('button','submit','reset'))){
			
		}
		else if($this->type=='captcha'){
			$session =& JFactory::getSession();
			$session->set('simpleForm.captcha', null);
			$checkVal = trim($checkVal);
			if($checkVal==''||!in_array($checkVal,$this->values)){
				$this->setError($this->error);
				return false;
			}
		}
		else if($this->type=='file'){
			$fileData = $_FILES[$this->name];
			if($this->required&&!is_file($fileData['tmp_name'])){
				$this->setError($this->error);
				return false;
			}
			else if(!is_file($fileData['tmp_name'])) return true;
			if($this->maxsize>0&&$fileData['size']>$this->maxsize){
				$fSize = round($fileData['size']/1024,2);
				$error = sprintf(JText::_('File size is too big'),$fileData['name'].' ('.$fSize.'Kb)',round($this->maxsize/1024,2).'Kb');
				$this->setError($error);
				return false;
			}
			if(count($this->extensions)>0){
				$match = false;
				foreach($this->extensions as $ext){
					if(preg_match("/\.".$ext."$/",$fileData['name'])){
						$match = true;
						break;
					}
				}
				if(!$match){
					$this->setError(sprintf(JText::_('File extension is forbidden'),$fileData['name'],implode(', ',$this->extensions)));
					return false;
				}
			}
			$file = new stdclass;
			$file->file = $fileData['tmp_name'];
			$file->name = $fileData['name'];
			$form->attachments[] = $file;
		}
		
		return true;
	}
	
	function getParam( &$arr, $name, $def=null, $mask=0 ){
		static $noHtmlFilter	= null;
		static $safeHtmlFilter	= null;
	
		$var = JArrayHelper::getValue( $arr, $name, $def, '' );
	
		if (!($mask & 1) && is_string($var)) {
			$var = trim($var);
		}
	
		if ($mask & 2) {
			if (is_null($safeHtmlFilter)) {
				$safeHtmlFilter = & JFilterInput::getInstance(null, null, 1, 1);
			}
			$var = $safeHtmlFilter->clean($var, 'none');
		} elseif ($mask & 4) {
			$var = $var;
		} else {
			if (is_null($noHtmlFilter)) {
				$noHtmlFilter = & JFilterInput::getInstance(/* $tags, $attr, $tag_method, $attr_method, $xss_auto */);
			}
			$var = $noHtmlFilter->clean($var, 'none');
		}
		return $var;
	}
	
	function getJSCode(){
		$codeEls = array();
		$params = get_object_vars($this);
		foreach($params as $param=>$val){
			if(in_array($param,array('name','id'))) continue;
			if(is_string($val)) $codeEls[] = $param.':"'.$this->escapeForJS($val).'"';
			if(is_bool($val)) $codeEls[] = $param.':'.(int)$val;
			if($param=='extensions') $codeEls[] = $param.':"'.implode(',',$val).'"';
			if($param=='maxsize'){
				if($val>0){
					$size = round($val/1024);
					$codeEls[] = $param.':"'.$size.'Kb"';
				}
				else $codeEls[] = $param.':""';
			}
		}
		$opEls = array();
		foreach($this->options as $option){
			$opEls[] = '{label:"'.$this->escapeForJS($option->label).'",value:"'.$this->escapeForJS($option->value).'"}';
		}
		$codeEls[] = 'options:['.implode(',',$opEls).']';
		$code = '{'.implode(',',$codeEls).'}';
		return $code;
	}
	
	function escapeForJS($val){
		$escapes = array(
			'"'		=>	'&quot;',
			'\\'	=>	'\\\\',
			"'"		=>	"\'",
		);
		$val = str_replace(array_keys($escapes),array_values($escapes),$val);
		return $val;
	}
}