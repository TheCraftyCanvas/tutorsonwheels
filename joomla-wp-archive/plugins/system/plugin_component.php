<?php
/*------------------------------------------------------------------------
# plugin_component.php - Include component plugin
# ------------------------------------------------------------------------
# author    Mike Reumer
# copyright Copyright (C) 2011 tech.reumer.net. All Rights Reserved.
# @license - http://www.gnu.org/copyleft/gpl.html GNU/GPL
# Websites: http://tech.reumer.net
# Technical Support: http://tech.reumer.net/Contact-Us/Mike-Reumer.html 
# Documentation: http://tech.reumer.net/Include-component/Documentation/
--------------------------------------------------------------------------*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.html.parameter' );

class plgSystemPlugin_component extends JPlugin
{
	var $config;
	var $subject;
	var $params;
	var $regex;
	var $document;
	var $doctype;
	var $ignore_scripts;
	var $ignore_styles;
	var $method;
	var $cbreplace;
	var $replprint;
	var $repltmpl;
	var $caching;
	var $jversion;
	var $protocol;
	
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.0
	 */
	public function __construct( &$subject, $config )
//	public function plgSystemplugin_component( &$subject, $config )
	{
			parent::__construct( $subject, $config );

			// Do some extra initialisation in this constructor if required
			$this->subject = $subject;
			$this->config = $config;
			$this->jversion = JVERSION;
			// In Joomla 1.5 get the parameters in Joomla 1.6 and higher the plugin already has them
			if (substr($this->jversion,0,3)=="1.5") {
				$plugin = JPluginHelper::getPlugin('system', 'plugin_googlemap2');
				$this->params = new JParameter( $plugin->params);
			}
			// define the regular expression for the bot
			$this->regex = "#(<p\b[^>]*>\s*)?{component\surl=('|(&\#0{0,2}39;)|\"|&quot;|(&\#0{0,2}34;))(.*?)('|(&\#0{0,2}39;)|\"|&quot;|(&\#0{0,2}34;))\s*}(\s*</p>)?#s";
			// Get document and doctype
			$this->document = null;
			$this->doctype = null;
			// Get ignores for stylesheets and scripts
			$this->ignore_scripts = $this->params->get( 'ignore_script', '' );
			$this->ignore_scripts = preg_split("/[\n\r]+/", $this->ignore_scripts);
			$this->ignore_allscripts = $this->params->get( 'ignore_allscripts', '' );
			$this->ignore_styles = $this->params->get( 'ignore_style', '' );
			$this->ignore_styles = preg_split("/[\n\r]+/", $this->ignore_styles);
			$this->ignore_allstyles = $this->params->get( 'ignore_allstyles', '' );
			// Get method
			$this->method = $this->params->get( 'method', '' );
			$this->closesession = $this->params->get( 'closesession', '' );
			$this->ignoresef = $this->params->get( 'ignoresef', '' );
			$this->cbreplace = $this->params->get( 'cbreplace', '0' );
			$this->urlprint = $this->params->get( 'urlprint', '1' );
			$this->replprint = $this->params->get( 'replprint', '1' );
			$this->repltmpl = $this->params->get( 'repltmpl', '1' );
			$this->runadmin = $this->params->get( 'runadmin', '0' );
			$this->caching = $this->params->get( 'caching', '0' );
			// What is the url of website without / at the end
			$this->url = preg_replace('/\/$/', '', JURI::root());
			$this->base = JURI::root(true);
			if ($_SERVER['SERVER_PORT'] == 443)
				$this->protocol = "https://";
			else
				$this->protocol = "http://";
	}
	
	/**
	 * Do something onAfterInitialise 
	 */
	public function onAfterInitialise()
	{
	}
	
	/**
	 * onPrepareContent is rename in Joomla 1.6 to onContentPrepare
	 */
	public function onContentPrepare($context, &$article, &$params, $limitstart=0)
	{
		$app = JFactory::getApplication();
		if($app->isAdmin()) {
			return;
		}
		
		// get document types
		$this->_getdoc();

		$text = &$article->text;
		$introtext = &$article->introtext;
		
		// check whether plugin has been unpublished
		if ( !$this->params->get( 'enabled', 1 ) ) {
			$text = preg_replace( $this->regex, '', $text );
			return true;
		}
	
		// perform the replacement	
		$this->_replace( $text );	
		$this->_replace( $introtext );	
	}
	
	/**
	 * onPrepareContent is for Joomla 1.5
	 */
	 
	public function onPrepareContent(&$article)
	{
		$app = JFactory::getApplication();
		if($app->isAdmin()) {
			return;
		}
		
		// get document types
		$this->_getdoc();

		$text = &$article->text;
		$introtext = &$article->introtext;
		
		// check whether plugin has been unpublished
		if ( !$this->params->get( 'enabled', 1 ) ) {
			$text = preg_replace( $this->regex, '', $text );
			return true;
		}
	
		// perform the replacement	
		$this->_replace( $text );	
		$this->_replace( $introtext );	
	}
	
	/**
	 * Do something onAfterRoute 
	 */
	public function onAfterRoute()
	{
	}
	
	/**
	 * Do something onAfterDispatch 
	 */
	public function onAfterDispatch()
	{
		$app = JFactory::getApplication();

		if($app->isAdmin()&&$this->runadmin!='1') {
			return;
		}

		// get document types
		$this->_getdoc();

		// FEED
		if ($this->doctype=='feed'&&isset($this->document->items)) {
			foreach($this->document->items as $item) {
				$text = &$item->description;
				if ( !$this->params->get( 'enabled', 1 ) )
					$text = preg_replace( $this->regex, '', $text );
				else
					$this->_replace($text);
			}
			
			return true;
		}
		
		// In other components or leftovers
		$text = $this->document->getBuffer("component");
		if (strlen($text)>0) {
			// check whether plugin has been unpublished
			if ( !$this->params->get( 'enabled', 1 ) )
				$text = preg_replace( $this->regex, '', $text );
			else
				$this->_replace($text);			
				
			$this->document->setBuffer($text, "component"); 
		}
	}
	
	/**
	 * Do something onAfterRender 
	 */
	public function onAfterRender()
	{
	}
	
	function _getdoc() {
		if ($this->document==null) {
			$this->document = JFactory::getDocument();
			$this->doctype = $this->document->getType();
		}
	}

	function _replace(&$text) {
		$matches = array();
		$cnt = preg_match_all($this->regex,$text,$matches,PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);
		// print_r($matches);
		// Remove plugincode that are in the editor textarea
		$matches = $this->_checkeditorarea($text, $matches);
		$cnt = count($matches[0]);
		for($counter = 0; $counter < $cnt; $counter ++) {
			$content = $this->_process($matches[5][$counter][0]);
			$text = preg_replace($this->regex, $content, $text, 1);
		}
	}
	
	function _checkhead($text, $plgmatches) {
		$result = array(array(),array(),array(),array());
		$cnt = count($plgmatches[0]);
		// Get head location
		$end = stripos($text, '</head>');
		// check if match plugin is the head
		for($counter = 0; $counter < $cnt; $counter++) {
			if (!($plgmatches[0][$counter][1] > 0 &&$plgmatches[0][$counter][1]< $end)) {
					$result[0][] = $plgmatches[0][$counter];
					$result[1][] = $plgmatches[1][$counter];
					$result[2][] = $plgmatches[2][$counter];
					$result[3][] = $plgmatches[3][$counter];
			}
		}

		return $result;
	}
	
	function _checkeditorarea($text, $plgmatches) {
		$edmatches = $this->_getEditorPositions($text);
		$result = array(array(),array(),array(),array());
		if (count($edmatches)>0) {
			$cnt = count($plgmatches[0]);
			// check if match plugin is in match textarea
			for($counter = 0; $counter < $cnt; $counter++) {
				foreach ($edmatches as $ed) {
					if (!($plgmatches[0][$counter][1] > $ed['start']&&$plgmatches[0][$counter][1]< $ed['end'])) {
						$result[0][] = $plgmatches[0][$counter];
						$result[1][] = $plgmatches[1][$counter];
						$result[2][] = $plgmatches[2][$counter];
						$result[3][] = $plgmatches[3][$counter];
					}
				}
			}
		} else
			$result = $plgmatches;
			
		// Clean up variables
		unset($edmatches, $cnt, $counter, $ed);
		
		return $result;
	}
	
	function _getEditorPositions($strBody) {
		if (substr($this->jversion,0,3)=="1.5"||substr($this->jversion,0,3)=="1.6"||$this->jversion=="1.7.0"||$this->jversion=="1.7.1"||$this->jversion=="1.7.2")
			preg_match_all("/<!-- Start Editor -->(.*)<!-- End Editor -->/Ums", $strBody, $strEditor, PREG_PATTERN_ORDER);
		else
			preg_match_all("/<div class=\"edit item-page\">(.*)<\/form>\n<\/div>/Ums", $strBody, $strEditor, PREG_PATTERN_ORDER);

		$intOffset = 0;
		$intIndex = 0;
		$intEditorPositions = array();

		foreach($strEditor[0] as $strFullEditor) {
			$intEditorPositions[$intIndex] = array('start' => (strpos($strBody, $strFullEditor, $intOffset)), 'end' => (strpos($strBody, $strFullEditor, $intOffset) + strlen($strFullEditor)));
			$intOffset += strlen($strFullEditor);
			$intIndex++;
		}
		
		// Clean up variables
		unset($strEditor, $intOffset, $strFullEditor, $intIndex);
		
		return $intEditorPositions;
	}
		
	function _process( $url ) {
		// Clean url
		$reg[] = "/<span[^>]*?>/si";
		$repl[] = '';
		$reg[] = "/<\/span>/si";
		$repl[] = '';
		$url = preg_replace( $reg, $repl, trim($url) );
		$origurl = JUri::root(true).$url;
		$origurl = preg_replace('/&amp;/', '&', $origurl);
		
		if (strpos($url, 'index.php')!== false||$this->ignoresef=="1") {
			$sef=false;
			$url = $url.((strpos($url, '?')===false)?'?':'&').'tmpl=component'.(($this->urlprint=='1')?'&print=1':'');
			// Add origin too to the component so it can redirect to the origin if something goes wrong
			$url .= '&origin='.base64_encode( JUri::getInstance()->toString() );
		} else {
			$sef=true;
			$url = $url.((substr($url, -1)!='/')?'/':'').'tmpl,component'.(($this->urlprint=='1')?'/print,1':'');
			// Add origin too to the component so it can redirect to the origin if something goes wrong		
			$url .= '/origin='.base64_encode( JUri::getInstance()->toString() );
		}
	
		$url = str_replace('http://', $this->protocol, JUri::root()).$url;
		// We need to replace the &amp; to & because the &amp; is not recognized
		$url = preg_replace('/&amp;/', '&', $url);
		
		// Close session so the other component can use it
		if ($this->closesession=="1") {
			$session =& JFactory::getSession();
			$session->close();
		}
		
		$cache = & JFactory::getCache('plugin_component');
		if ($this->caching=="1")
			$cache->setCaching( 1 );
			
		$response = $cache->call(array ( $this, '_getURL'), $url, $sef); 
		
		// Start the session again?
	
		if ($response!="") {
			// Clean the returned page of all html tags
	
			// get head and remove it
			// to do move meta tags, scripts and links to header?
			$reg = "/(<HEAD[^>]*>)(.*?)(<\/HEAD>)(.*)/si";
			$count = preg_match_all($reg,$response,$html);	
			if ($count>0) {
				$head=$html[2][0];
				$response= $html[4][0];
			} else {
				$head='';
			}
	
			if ($this->doctype!="feed"&&$this->doctype!="pdf"&&$this->ignore_allscripts!='1') {
				// Find stylesheets and javascripts and add them to this document
				// javascript scripts
				$reg = '/<script.*src=[\'\"](.*?)[\'\"][^>]*[^<]*(<\/script>)?/i';

				$count = preg_match_all($reg,$head,$scripts);	
				if ($count>0)
					foreach ($scripts[1] as $script) {
						$this->_addScript($script);
					}
				
				// javascript embedded
				$reg = '/<script[^>]*>(.*?)<\/script>/si';
				$scripts= array();
				$count = preg_match_all($reg,$head,$scripts);	
				if ($count>0)
					foreach ($scripts[1] as $script) {
						if (trim($script)!='') {
							// remove special contents type around scripts
							$script = str_replace('<!--', '', $script);
							$script = str_replace('-->', '', $script);
							$this->document->addScriptDeclaration($script);
						}
					}
			}
	
			if ($this->doctype!="feed"&&$this->ignore_allstyles!='1') {
				// stylesheet links
				$reg = '/<link.*href=[\'\"](.*?)[\'\"][^>]*[^<]*(<\/link>)?/i';
				$count = preg_match_all($reg,$head,$styles);	
				if ($count>0)
					for ($x=0;$x<$count;$x++) {
						if ((preg_match('/type=[\'"]text\/css[\'"]/i', $styles[0][$x])>0)||(preg_match('/rel=[\'"]stylesheet[\'"]/i', $styles[0][$x])>0))
							$this->_addStyleSheet($styles[1][$x]);
					}
				
				// Embedded styles
				$reg = '/<style[^>]*>(.*?)<\/style>/si';
				$styles = array();
				$count = preg_match_all($reg,$head,$styles);	
				if ($count>0)
					foreach ($styles[1] as $style) {
						if (trim($style)!='')
							$this->document->addStyleDeclaration($style);
					}
			}
			
			if ($this->doctype!="feed") {
				// Add meta tags and description to calling page
				$reg = '/<meta.*name=[\'\"](keywords|description)[\'\"].*content=[\'\"](.*?)[\'\"][^\/>]*/i';
				$count = preg_match_all($reg,$head,$meta);	
				if ($count>0)
					for ($x=0;$x<$count;$x++) {
						$old = $this->document->getMetaData ($meta[1][$x]);
						// Check if not already added?
						if ($meta[2][$x]!="")
							if (strpos($old, $meta[2][$x])===false) {
								if (strlen($old)>0&&strlen($meta[2][$x])>0)
									if ($meta[1][$x]=='keywords')
										$meta[2][$x] = ", ".$meta[2][$x];
									else
										$meta[2][$x] = " ".$meta[2][$x];
									
								$this->document->setMetaData ($meta[1][$x], $old.$meta[2][$x]);
							}
					}
			}
			
			// get body and remove it
			// to do attributes of body onload and other move to the real page?
			$reg = '/(<BODY[^>]*>)(.*)(<\/BODY>)/si';
			$count = preg_match_all($reg,$response,$html);	
			if ($count>0)
				$response=$html[2][0];
	
			$reg = array();
			$repl = array();
			
			// clean javascript for feeds and pdf
			if ($this->doctype=="feed"||$this->doctype=="pdf"||$this->ignore_allscripts=='1') {
				// Find stylesheets and javascripts and remove them
				// javascript scripts
				$reg[] = '/<script.*src=[\'\"](.*?)[\'\"][^>]*[^<]*(<\/script>)?/i';
				$repl[] = '';
				// javascript embedded
				$reg[] = '/<script[^>]*>(.*?)<\/script>/si';
				$repl[] = '';
			}
	
			if ($this->doctype=="feed"||$this->ignore_allstyles=='1') {
				// stylesheet links
				$reg[] = '/<link.*href=[\'\"](.*?)[\'\"][^>]*[^<]*(<\/link>)?/i';
				$repl[] = '';
				// Embedded styles
				$reg[] = '/<style[^>]*>(.*?)<\/style>/si';
				$repl[] = '';
			}
	
			// Replace links
			if ($this->replprint=='1') {
				$reg[] = "/&amp;print=1/";
				$repl[] = '';
				$reg[] = "/&print=1/";
				$repl[] = '';
			}
			if ($this->repltmpl=='1') {
				$reg[] = "/&amp;tmpl=component/";
				$repl[] = '';
				$reg[] = "/&tmpl=component/";
				$repl[] = '';
				$reg[] = "/\?tmpl=component&/";
				$repl[] = '?';
			}
			$reg[] = "/index2.php/";
			$repl[] = 'index.php';
			$reg[] = '/\$/';
			$repl[] = '\\\$';
			$response = preg_replace( $reg, $repl, $response );
			
			// Replace forms with empty action or no action attribute with original url
			$reg = '/<form[^>](.*?)>/i';
			$forms = array();
			$count = preg_match_all($reg,$response,$forms);
	
			if ($count>0)
				for ($cnt=0;$cnt<$count;$cnt++) {
					$reg = '/action=[\'\"](.*?)[\'\"]/i';
					$actions = array();
					$c = preg_match_all($reg,$forms[1][$cnt],$actions);
					if ($c>0) {
						// Check empty to replace
						if ($actions[1][0]=="")
							$newform = str_replace ($actions[0][0], 'action="'.$origurl.'"', $forms[0][$cnt]);
						else 
							$newform = $forms[0][$cnt];
					} else {
						// Toevoegen
						$newform = str_replace (">", ' action="'.$origurl.'">', $forms[0][$cnt]);
					}
					
					$response = str_replace ($forms[0][$cnt], $newform, $response);						
				}
			
			
			// Replace tokes
			$reg = '/<input type=["\']hidden["\'] name=["\'][a-f0-9]+["\'] value=["\']1["\'] \/>/si';
			$tokens = array();
			$count = preg_match_all($reg,$response,$tokens);
			if ($count>0)
				foreach ($tokens[0] as $token) {
					$response = str_replace ($token, JHTML::_( 'form.token' ), $response);
				}
	
			// Replace CB tokens
			$cbfile0 = JPATH_ADMINISTRATOR."/components/com_comprofiler/plugin.class.php";
			$cbfile1 = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_comprofiler'.DS.'comprofiler.class.php';
			$cbfile2 = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_comprofiler'.DS.'plugin.foundation.php';
			if ($this->cbreplace&&file_exists($cbfile0)&&file_exists($cbfile1)&&file_exists($cbfile2)) {
				require_once($cbfile0);
				require_once($cbfile1);
				require_once($cbfile2);
				$reg = '/<input type=["\']hidden["\'] name=["\']'.cbSpoofField().'["\'] value=["\'].*?["\'] \/>/si';
				$tokens = array();
				$count = preg_match_all($reg,$response,$tokens);
				if ($count>0)
					foreach ($tokens[0] as $token) {
						$response = str_replace ($token, cbGetSpoofInputTag('registerForm', null), $response);
					}
				$reg = '/<input type=["\']hidden["\'] name=["\']'.cbGetRegAntiSpamFieldName().'["\'] value=["\'].*?["\'] \/>/si';
				$tokens = array();
				$count = preg_match_all($reg,$response,$tokens);
				if ($count>0)
					foreach ($tokens[0] as $token) {
						$response = str_replace ($token, cbGetRegAntiSpamInputTag(), $response);
					}
			}
		}

		$content = "\n<!-- Plugin Include component version 1.13 by Mike Reumer";
		$content .= "\n     for: ".$url." -->";
		$content .= "\n".$response;
		$content .= "\n<!-- End Plugin Include component -->";

		return $content;	
	}
	
	function _getURL($url, $sef) {
		$ok = false;
		$postcurl  = array();
		$post  = '';
		$cookie = '';
		$reg = '/^[a-f0-9]+$/si';
		
		//get all session parameters
		foreach ($_COOKIE as $key => $value) {
			if (preg_match($reg,$key)>0) {
				$cookie.="$key=$value; "; // separation in cookies is ; with space!
				$postcurl[$key]=$value;
				if ($sef)
					$post.=((strlen($post)>0)?'/':'')."$key,$value";
				else
					$post.=((strlen($post)>0)?'&':'')."$key=$value";
			}
		}
		
		if (ini_get('allow_url_fopen')&&$this->method!='curl')
			if ($response = @file_get_contents($url.((strlen($post)>0)?$post:'')))
				$ok = true;
	
		if (!$ok) {
			if (function_exists('curl_init')) {
				$ch = curl_init( $url );
				// Set curl options, see: http://www.php.net/manual/en/function.curl-setopt.php
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true); // to return the transfer as a string
				curl_setopt ($ch, CURLOPT_USERAGENT, 'spider'); // The contents of the "User-Agent: " header
				curl_setopt ($ch, CURLOPT_AUTOREFERER, true); // set referer on redirect
				
				// Send authentication
				$username = "";
				$password = "";
				// mod_php
		        if (isset($_SERVER['PHP_AUTH_USER'])) {
					$username = $_SERVER['PHP_AUTH_USER'];
					$password = $_SERVER['PHP_AUTH_PW'];
		        }
		       // most other servers
				elseif (isset($_SERVER['HTTP_AUTHENTICATION'])) {
					if (strpos(strtolower($_SERVER['HTTP_AUTHENTICATION']),'basic')===0) {
						list($username,$password) = explode(':',base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
					}  
				}
				if ($username!=""&&$password!="") {
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
					curl_setopt ($ch, CURLOPT_USERPWD, $username.":".$password); // set referer on redirect
				}
				
				$timeout = 5; // set to zero for no timeout
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				curl_setopt ($ch, CURLOPT_TIMEOUT, $timeout);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt ($ch, CURLOPT_MAXREDIRS, 10); // stop after 10 redirects
	
				if ( strlen($cookie)>0 ){
					curl_setopt($ch, CURLOPT_COOKIESESSION, false);  // False to keep all cookies of previous session
					curl_setopt($ch, CURLOPT_COOKIE, $cookie);
				}
				if ( strlen($post)>0 ){
					curl_setopt($ch, CURLOPT_POST, true); 
					curl_setopt($ch, CURLOPT_POSTFIELDS, $postcurl);
				}
				
				$response = curl_exec($ch);    
				if (curl_errno($ch)) {
					$response = "<!-- url not received: #".curl_errno($ch)." \"".curl_error($ch)."\" -->";
				} else {
					$ok = true;
				}
				curl_close($ch);
				
			} else 
				$response = "<!-- curl not available as PHP library -->";
		}
		
		return $response;
	}

	function _addScript($script) {
		$found = false;

		foreach ($this->ignore_scripts as $url) {
			if ($url == $script||($this->url.$url==$script)||($this->url."/".$url==$script)||($this->base."/".$url==$script))
				$found = true;
		}
		if (!$found)
			$this->document->addScript($script);
	}
	
	function _addStyleSheet($style) {
		$found = false;
		
		foreach ($this->ignore_styles as $url) {
			if ($url == $style||($this->url.$url==$style)||($this->url."/".$url==$style)||($this->base."/".$url==$style))
				$found = true;
		}
		if (!$found)
			$this->document->addStyleSheet($style);
	}
}

?>