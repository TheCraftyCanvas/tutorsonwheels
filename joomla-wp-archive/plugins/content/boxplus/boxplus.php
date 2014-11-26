<?php
/**
* @file
* @brief    boxplus: a lightweight pop-up window engine for MooTools
* @author   Levente Hunyadi
* @version  0.9.2
* @remarks  Copyright (C) 2009-2011 Levente Hunyadi
* @remarks  Licensed under GNU/GPLv3, see http://www.gnu.org/licenses/gpl-3.0.html
* @see      http://hunyadi.info.hu/projects/boxplus
*/

/*
* boxplus: a lightweight pop-up window engine for MooTools
* Copyright 2011 Levente Hunyadi
*
* boxplus is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* boxplus is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with boxplus.  If not, see <http://www.gnu.org/licenses/>.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!defined('BOXPLUS_DEBUG')) {
	// Forces debug mode. Debug uses uncompressed version of scripts rather than the bandwidth-saving minified versions.
	define('BOXPLUS_DEBUG', false);
}

// import library dependencies
jimport('joomla.event.plugin');

class BoxPlusSettings {
	public $theme = 'lightsquare';
	public $autocenter = true;
	public $autofit = true;
	public $slideshow = 0;
	public $loop = false;
	public $captions = 'bottom';
	public $thumbs = 'inside';
	public $width = 800;
	public $height = 600;
	public $title = false;
	public $description = false;
	public $download = false;
	public $metadata = false;
	public $duration = 250;
	public $transition = 'sine';
	public $contextmenu = true;
	public $strict = false;
	public $activationtag = 'boxplus';

	public function getArray() {
		$params = array();
		foreach (get_class_vars(__CLASS__) as $name => $value) {  // enumerate properties in class
			$params[$name] = $this->$name;
		}
		return $params;
	}

	public function setArray(array $params) {
		foreach (get_class_vars(__CLASS__) as $name => $value) {  // enumerate properties in class
			if (isset($params[$name])) {
				$this->$name = $params[$name];
			}
		}

		$this->validate();
	}

	/**
	* Configuration settings as a JSON object.
	*/
	public function getJSON() {
		$options = $this->getArray();
		unset($options['title']);
		unset($options['description']);
		unset($options['download']);
		unset($options['metadata']);
		unset($options['strict']);
		unset($options['activationtag']);
		return json_encode($options);
	}

	public function setParameters(JRegistry $params) {
		foreach (get_class_vars(__CLASS__) as $name => $value) {  // enumerate properties in class
			$this->$name = $params->get($name, $value);  // set property class value as default if not present in XML
		}

		$this->validate();
	}

	/**
	* Validates settings and resets invalid values to their defaults.
	*/
	private function validate() {
		$default = new self();

		$this->theme = self::as_one_of($this->theme, array('lightsquare','darksquare','lightrounded','darkrounded','prettyphoto','shadow'));
		$this->autocenter = self::as_boolean($this->autocenter);
		$this->autofit = self::as_boolean($this->autofit);
		$this->slideshow = self::as_nonnegative_integer($this->slideshow, $default->slideshow);
		$this->loop = self::as_boolean($this->loop);
		$this->captions = self::as_one_of($this->captions, array('bottom','sideways','none'));
		$this->thumbs = self::as_one_of($this->thumbs, array('inside','outside','none'));
		$this->width = self::as_nonnegative_integer($this->width, $default->width);
		$this->height = self::as_nonnegative_integer($this->height, $default->height);
		$this->download = self::as_boolean($this->download);
		$this->metadata = self::as_boolean($this->metadata);
		$this->duration = self::as_nonnegative_integer($this->duration, $default->duration);
		$this->transition = self::as_one_of($this->transition, array('linear','quad','cubic','quart','quint','expo','circ','sine','back','bounce','elastic'));
		$this->contextmenu = self::as_boolean($this->contextmenu);
		$this->strict = self::as_boolean($this->strict);
		if (!is_string($this->activationtag) || !ctype_alpha($this->activationtag)) {
			$this->activationtag = $default->activationtag;
		}
	}

	/**
	* Casts a value to one of the set of values.
	*/
	private static function as_one_of($value, array $values) {
		if (in_array($value, $values, true)) {
			return $value;
		} else {
			return reset($values);
		}
	}

	/**
	* Casts a value to a true or false value.
	*/
	private static function as_boolean($value) {
		if (is_string($value)) {
			switch ($value) {
				case 'true': case 'on': case 'yes': case '1':
					return true;
			}
			return false;
		} else {
			return (bool) $value;
		}
	}

	/**
	* Casts a value to a nonnegative integer.
	*/
	private static function as_nonnegative_integer($value, $default = 0) {
		if (is_null($value) || $value === '') {
			return false;
		} elseif ($value !== false) {
			$value = (int) $value;
			if ($value <= 0) {
				$value = $default;
			}
		}
		return $value;
	}

	/**
	* Casts a value to a positive integer.
	*/
	private static function as_positive_integer($value, $default) {
		if (is_null($value) || $value === false || $value === '') {
			return $default;
		} else {
			$value = (int) $value;
			if ($value < 0) {
				$value = $default;
			}
			return $value;
		}
	}
}

/**
* A thin wrapper for Joomla around the lightbox window implementation boxplus.
*/
class plgContentBoxPlus extends JPlugin {
	private static $debug = null;
	/** Default settings. */
	private $settings;
	/** Current settings. */
	private $current;
	/** Settings store. */
	private $store = array(
		'id' => array(),
		'rel' => array()
	);

	public function __construct( &$subject, $config ) {
		parent::__construct( $subject, $config );
		$this->settings = new BoxPlusSettings();
		$this->settings->setParameters($this->params);

		if (!isset(self::$debug)) {  // cannot alter debug mode once set
			self::$debug = BOXPLUS_DEBUG || (bool) $this->params->get('debug');
		}
	}

	/**
	* Adds cascading style sheet (CSS) references to the document <head>.
	*/
	private function addStyles() {
		$document = JFactory::getDocument();
		if (self::$debug) {
			$document->addStyleSheet(JURI::base(true).'/plugins/content/boxplus/css/boxplus.css');
		} else {
			$document->addStyleSheet(JURI::base(true).'/plugins/content/boxplus/css/boxplus.min.css');
		}
		$language = JFactory::getLanguage();
		if ($language->isRTL()) {
			$document->addStyleSheet(JURI::base(true).'/plugins/content/boxplus/css/boxplus.rtl.css');
		}
		$this->addCustomTag('<!--[if lt IE 9]><link rel="stylesheet" href="'.JURI::base(true).'/plugins/content/boxplus/css/boxplus.ie8.css" type="text/css" /><![endif]-->');
		$this->addCustomTag('<!--[if lt IE 8]><link rel="stylesheet" href="'.JURI::base(true).'/plugins/content/boxplus/css/boxplus.ie7.css" type="text/css" /><![endif]-->');
		$document->addStyleSheet(JURI::base(true).'/plugins/content/boxplus/css/boxplus.'.$this->current->theme.'.css', 'text/css', null, array('title'=>'boxplus-'.$this->current->theme));
		if (file_exists(JPATH_BASE.DS.'plugins'.DS.'content'.DS.'boxplus'.DS.'css'.DS.'boxplus.'.$this->current->theme.'.ie8.css')) {  // use IE-specific stylesheet only if it exists
			$this->addCustomTag('<!--[if lt IE 9]><link rel="stylesheet" href="'.JURI::base(true).'/plugins/content/boxplus/css/boxplus.'.$this->current->theme.'.ie8.css" type="text/css" title="boxplus-'.$this->settings->theme.'" /><![endif]-->');
		}
	}

	/**
	* Adds javascript code to the document <head>.
	*/
	private function addScripts($crossdomain = true) {
		$document = JFactory::getDocument();
		$language = JFactory::getLanguage();
		JHTML::_('behavior.mootools', self::$debug);
		if ($crossdomain) {
			$document->addScript(JURI::base(true).'/plugins/content/boxplus/js/jsonp.mootools'.(self::$debug ? '' : '.min').'.js');
		}
		$document->addScript(JURI::base(true).'/plugins/content/boxplus/js/boxplus'.(self::$debug ? '' : '.min').'.js');
		$document->addScript(JURI::base(true).'/plugins/content/boxplus/js/boxplus.lang'.(self::$debug ? '' : '.min').'.js?lang='.$language->getTag());
	}

	/**
	* Adds activation code to the HTML document <head> that invokes the boxplus window on links that can open in a lightbox.
	*/
	private function addActivation() {
		static $activation = false;

		$this->addStyles();
		$this->addScripts();

		if (!$activation) {
			$activation = true;

			$document = JFactory::getDocument();
			$document->addScriptDeclaration('boxplus.autodiscover('.($this->current->strict ? 'true' : 'false').','.$this->current->getJSON().');');
		}
	}

	/**
	* Adds a custom tag to the HTML document <head> at most once.
	*/
	private function addCustomTag($tag) {
		static $customtags = array();

		if (!in_array($tag, $customtags)) {
			$document = JFactory::getDocument();
			$document->addCustomTag($tag);
			$customtags[] = $tag;
		}
	}

	/**
	* Joomla 1.5 compatibility method.
	*/
	public function onPrepareContent( &$row, &$params ) {
		$this->onContentPrepare( false, $row, $params, 0 );
	}

	/**
	* Fired when contents are to be processed by the plug-in.
	*/
	public function onContentPrepare( $context, &$article, &$params, $limitstart ) {
		if ($this->settings->strict && strpos($article->text, '{'.$this->settings->activationtag) === false) {
			return;  // short-circuit plug-in activation
		}

		$activationtag = preg_quote($this->settings->activationtag, '#');

		try {
			// find gallery tags and emit code
			$localcount = 0;
			$article->text = preg_replace_callback('#[{]'.$activationtag.'\b([^{}]*)(?<!/)[}](.*)[{]/'.$activationtag.'[}]#sSU', array($this, 'getLocalReplacement'), $article->text, -1, $localcount);

			// add script to bind images with the same rel attribute
			if (!empty($this->store['id']) || !empty($this->store['rel'])) {
				$selectors = array();

				// anchors found with their unique HTML identifier
				foreach ($this->store['id'] as $key => $settings) {
					$selectors['$("'.$key.'")'] = $settings;
				}

				// anchors found with a rel attribute
				foreach ($this->store['rel'] as $key => $settings) {
					$selectors['$$("a[href][rel='.$key.']")'] = $settings;
				}

				// subscribe to DOM ready event
				$document = JFactory::getDocument();
				$document->addScriptDeclaration('window.addEvent("domready", function () {');
				foreach ($selectors as $selector => $settings) {
					$document->addScriptDeclaration('new boxplus('.$selector.','.$settings->getJSON().');');
				}
				$document->addScriptDeclaration('});');
				$this->store = array();
			}

			$globalcount = 0;
			$article->text = preg_replace_callback('#[{]'.$activationtag.'\b([^{}]*)/[}]#sS', array($this, 'getGlobalReplacement'), $article->text, 1, $globalcount);
			if (!$globalcount && !$localcount && !$this->settings->strict && preg_match('#rel=[\'"]?boxplus#', $article->text)) {  // there are no {boxplus} tags but there exist links with rel="boxplus"
				$this->current = clone $this->settings;
				$this->addActivation();
				$this->current = null;
			}
		} catch (Exception $e) {
			$app =& JFactory::getApplication();
			$app->enqueueMessage( $e->getMessage(), 'error' );
		}
	}

	/**
	* Retrieves settings that belong to a previously used key or clones defaults.
	* @param {string} $key A string to use as a key.
	* @param {string} $section
	*/
	private function retrieveLocalSettings($key = false, $section = 'rel') {
		if ($key && isset($this->store[$section][$key])) {
			return $this->store[$section][$key];
		} else {
			return clone $this->settings;
		}
	}

	/**
	* Stores the current settings under a key.
	* @param {string|integer} $key A string or integer to use as a key.
	*/
	private function storeLocalSettings($key, $section = 'rel') {
		$this->store[$section][$key] = $this->current;
		$this->current = null;
	}

	private static function getIdentifier($index) {
		return sprintf('boxplus%03d', $index);
	}

	/**
	* Local configuration overrides.
	* @param string $string A list of settings as name=value pairs.
	* @return array Configuration settings as an array.
	*/
	private static function getOptions($string) {
		return self::string_to_array(htmlspecialchars_decode($string));
	}

	/**
	* True if the URL implies a cross-domain JSON request.
	*/
	private static function needsCrossDomain($url) {
		return preg_match('"https?://picasaweb.google.com/data/feed/(?:api|base)/user/([^\/?#]+)/albumid/([^\/?#]+)"', $url) > 0;
	}

	/**
	* Replacement for the activation tag syntax {boxplus param=value}text{/boxplus}.
	* @param $match A regular expression match captured by preg_replace_callback.
	*/
	private function getLocalReplacement($match) {
		static $counter = 1;

		$options = array_merge(
			array(
				'href' => 'javascript:void(0);'
			),
			self::getOptions($match[1])
		);
		if (isset($options['rel'])) {
			$this->current = $this->retrieveLocalSettings($options['rel'], 'rel');
		} else {
			$this->current = clone $this->settings;
		}
		$this->current->setArray($options);

		// add styles and scripts to page header (may depend on settings in activation tag)
		$this->addStyles();
		$this->addScripts(self::needsCrossDomain($options['href']));

		// determine settings store key and section
		if (isset($options['rel'])) {  // use "rel" attribute supplied
			$key = $options['rel'];
			$section = 'rel';
		} else {
			if (!isset($options['id'])) {
				$options['id'] = self::getIdentifier($counter++);  // use counter value as numeric key
			}
			$key = $options['id'];
			$section = 'id';
		}

		// update settings store
		$this->storeLocalSettings($key, $section);

		// transfer common attributes from activation tag to HTML anchor
		$attrs = array();
		foreach (array('id','href','rel','class','style','title') as $attr) {
			if (isset($options[$attr])) {
				$attrs[$attr] = $options[$attr];
			}
		}

		// emit HTML code
		$html = self::make_html('a', $attrs, $match[2]);
		return $html;
	}

	/**
	* Replacement for the activation tag syntax {boxplus param=value /}.
	* @param $match A regular expression match captured by preg_replace_callback.
	*/
	private function getGlobalReplacement($match) {
		$this->current = clone $this->settings;
		$options = self::getOptions($match[1]);
		if (!empty($options)) {
			$this->current->setArray($options);
		}

		$this->addActivation();

		$this->current = null;
		return '';
	}

	/**
	* Converts a string containing key-value pairs into an associative array.
	* @param string $string The string to split into key-value pairs.
	* @param string $separator The optional string that separates the key from the value.
	* @return array An associative array that maps keys to values.
	*/
	private static function string_to_array($string, $separator = '=', $quotechars = array("'",'"')) {
		$separator = preg_quote($separator);
		if (is_array($quotechars)) {
			$quotedvalue = '';
			foreach ($quotechars as $quotechar) {
				$quotechar = preg_quote($quotechar[0]);  // escape characters with special meaning to regex
				$quotedvalue .= $quotechar.'[^'.$quotechar.']*'.$quotechar.'|';
			}
		} else {
			$quotechar = preg_quote($quotechar[0]);  // make sure quote character is a single character
			$quotedvalue = $quotechar.'[^'.$quotechar.']*'.$quotechar.'|';
		}
		$regularchar = '[A-Za-z0-9_.:/-]';
		$namepattern = '([A-Za-z_]'.$regularchar.'*)';  // html attribute name
		$valuepattern = '('.$quotedvalue.'-?[0-9]+(?:[.][0-9]+)?|'.$regularchar.'+)';
		$pattern = '#(?:'.$namepattern.$separator.')?'.$valuepattern.'#';

		$array = array();
		$matches = array();
		$result = preg_match_all($pattern, $string, $matches, PREG_SET_ORDER);
		if (!$result) {
			return false;
		}
		foreach ($matches as $match) {
			$name = $match[1];
			$value = trim($match[2], implode('', $quotechars));
			if (strlen($name) > 0) {
				$array[$name] = $value;
			} else {
				$array[] = $value;
			}
		}
		return $array;
	}

	/**
	* Builds HTML from tag name, attribute array and element content.
	* @param string $element Tag name.
	*/
	private static function make_html($element, $attrs = false, $content = false) {
		$html = '<'.$element;
		if ($attrs !== false) {
			foreach ($attrs as $key => $value) {
				if ($value !== false) {
					$html .= ' '.$key.'="'.htmlspecialchars($value).'"';
				}
			}
		}
		if ($content !== false) {
			$html .= '>'.$content.'</'.$element.'>';
		} else {
			$html .= '/>';
		}
		return $html;
	}
}