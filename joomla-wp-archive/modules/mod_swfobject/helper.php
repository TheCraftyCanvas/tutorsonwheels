<?php
/**
 * Helper class for mod_swfobject module
 * 
 */
class mod_swfobject_helper
{
    /**
     * Add swfobject to document
     *
     * @access public
     */    
    function exec_module($params)
    {
        $url= JURI::base(). 'modules/mod_swfobject/lib/swfobject.js';
        $play= JURI::base(). 'modules/mod_swfobject/lib/test.swf';
		$express= JURI::base(). 'modules/mod_swfobject/lib/expressInstall.swf';
		$script= 'var flashvars = {';
		if($params->get('var_n1','')!=""){ 
			$script .= $params->get('var_n1','').':'.$params->get('var_v1','');
		}
		if($params->get('var_n2','')!=""){
			$script .= ',';
			$script .= $params->get('var_n2','').':'.$params->get('var_v2','');
		}
		if($params->get('var_n3','')!=""){ 
			$script .= ',';
			$script .= $params->get('var_n3','').':'.$params->get('var_v3','');
		}
		if($params->get('var_n4','')!=""){
			$script .= ',';
			$script .= $params->get('var_n4','').':'.$params->get('var_v4','');
		}
		$script .= '};';
		$script .= 'var params = {';
		$script .= 'menu:"'.$params->get('menu','').'"';
		if($params->get('wmode','')!=""){
				$script .= ',';
				$script .= 'wmode:"'.$params->get('wmode','').'"';
			}
		if($params->get('allowscriptaccess','')!=""){
				$script .= ',';
				$script .= 'allowscriptaccess:"'.$params->get('allowscriptaccess','').'"';
			}
		if($params->get('bgcolor','')!=""){
				$script .= ',';
				$script .= 'bgcolor:"'.$params->get('bgcolor','').'"';
			}
		if($params->get('param_n1','')!=""){
				$script .= ',';
				$script .= $params->get('param_n1','').':"'.$params->get('param_v1','').'"';
			}
		if($params->get('param_n2','')!=""){
				$script .= ',';
				$script .= $params->get('param_n2','').':"'.$params->get('param_v2','').'"';
			}
		if($params->get('param_n3','')!=""){
				$script .= ',';
				$script .= $params->get('param_n3','').':"'.$params->get('param_v3','').'"';
			}
		if($params->get('param_n4','')!=""){
				$script .= ',';
				$script .= $params->get('param_n4','').':"'.$params->get('param_v4','').'"';
			}
		$script .= '};';
		$script .= 'var attributes = {};';
		$script .= 'swfobject.embedSWF("'.JURI::base().$params->get('file',$play).'", "'.$params->get('id','flashid').'", "'.$params->get('width','120').'", "'.$params->get('height','240').'", "'.$params->get('version','10').'","'.$express.'", flashvars, params, attributes);';

        $doc = &JFactory::getDocument();
        $doc->addScript($url);
		$doc->addScriptDeclaration( $script );
 	}
}
?>