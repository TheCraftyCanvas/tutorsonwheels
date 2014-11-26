<?php
/**
 * Ninja Shadowbox 2.0.9
 * Lead developer: Richie Mortimer
 * Support developers: Uwe Walter, Mark Simpson 
 * http://ninjaforge.com 
 * Shadowbox            (C) 2007 Michael J.I. Jackson GNU LGPL
 * Copyright            (C) 2010 Daniel Chapman http://ninjaforge.com
 * Email:               support@ninjaforge.com
 * Date:                January 2009
 * License :            http://www.gnu.org/copyleft/lgpl.html GNU/LGPL
 *
 * If there is sloppy code in this file, I am to blame - mark@ninjaforge.com
 */
    
  
//ensure this file is being included by a parent file */
defined ( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );


//module base
$modbase  =  JURI::base().'modules/mod_ninja_shadowbox/ninja_shadowbox/';
$document =& JFactory::getDocument();


// get the parameters and put them in an array
$param = array(
	'core'			=> $params->get('core'),
	'lang'			=> $params->get('lang'),
	'jstype'		=> $params->get('jstype',''),
	'skin'			=> $params->get('skin'),
	'incjs'			=> $params->get('incjs'),
	'useadv'		=> $params->get('useadv'),
	'emulate'   => $params->get('emulate')
);

//get the param for the Image Map ID
$imageMapID = $params->get('imageMap', '');

//this player needs to load regardless of whether the "Use Advanced Mode"
//option is set to true. If advanced mode is enabled, that param can override this one. 
$param['flvPlayer'] = $params->get('flvPlayer', '');


//if advanced options is selected, populate the options
//also populate the optionsjs for the other js librarys
if ($param['useadv']) {
  	$param['animate']			      = $params->get('animate', '');
  	$param['animateFade']		    = $params->get('animateFade', '');
  	$param['animSequence']	    = $params->get('animSequence', '');
  	$param['flvPlayer']			    = $params->get('flvPlayer', '');
  	$param['overlayColor']	    = $params->get('overlayColor', '');
  	$param['overlayOpacity']    = $params->get('overlayOpacity', '');
  	$param['flashBgColor']	    = $params->get('flashBgColor', '');
  	$param['listenOverlay']   	= $params->get('listenOverlay', '');
  	$param['autoplayMovies']    = $params->get('autoplayMovies', '');
  	$param['showMovieControls']	= $params->get('showMovieControls', '');
  	$param['slideshowDelay']	  = $params->get('slideshowDelay', '');
  	$param['resizeDuration']	  = $params->get('resizeDuration', '');
  	$param['fadeDuration']		  = $params->get('fadeDuration', '');
  	$param['displayNav']		    = $params->get('displayNav', '');
  	$param['continuous']		    = $params->get('continuous', '');
  	$param['displayCounter']	  = $params->get('displayCounter', '');
  	$param['counterType']		    = $params->get('counterType', '');
  	$param['counterLimit']		  = $params->get('counterLimit', '');
  	$param['viewportPadding']	  = $params->get('viewportPadding', '');
  	$param['handleOversize']	  = $params->get('handleOversize', '');
  	$param['handleException']	  = $params->get('handleException', '');
  	$param['initialHeight']		  = $params->get('initialHeight', '');
  	$param['initialWidth']		  = $params->get('initialWidth', '');
  	$param['enableKeys']	    	= $params->get('enableKeys', '');
  	$param['handleUnsupported']	= $params->get('handleUnsupported', '');

  	$options  = ' var options = { ';
  	$options .= !$param['animate']           ? 'animate: false,'									: '';
  	$options .= !$param['animateFade']       ? 'animateFade: false,'								: '';
  	$options .=  $param['animSequence']      ? 'animSequence: \''.$param['animSequence'].'\','		: '';
  	$options .=  $param['flvPlayer']         ? 'flvPlayer: \''.$param['flvPlayer'].'\','			: '';
  	$options .=  $param['overlayColor']      ? 'overlayColor: \''.$param['overlayColor'].'\','		: '';
  	$options .=  $param['overlayOpacity']    ? 'overlayOpacity: '.$param['overlayOpacity'].','		: '';
  	$options .=  $param['flashBgColor']      ? 'flashBgColor: \''.$param['flashBgColor'].'\','		: '';
  	$options .= !$param['listenOverlay']     ? 'modal: false,'										: '';
  	$options .= !$param['autoplayMovies']    ? 'autoplayMovies: false,'								: '';
  	$options .= !$param['showMovieControls'] ? 'showMovieControls: false,'							: '';
  	$options .=  $param['slideshowDelay']    ? 'slideshowDelay: \''.$param['slideshowDelay'].'\','	: '';
  	$options .=  $param['resizeDuration']    ? 'resizeDuration: '.$param['resizeDuration'].','		: '';
  	$options .=  $param['fadeDuration']      ? 'fadeDuration: '.$param['fadeDuration'].','			: '';
  	$options .= !$param['displayNav']        ? 'displayNav: false,'									: '';
  	$options .=  $param['continuous']        ? 'continuous: true,'									: '';
  	$options .= !$param['displayCounter']    ? 'displayCounter: false,'								: '';
  	$options .=  $param['counterType']       ? 'counterType: \''.$param['counterType'].'\','		: '';
  	$options .=  $param['counterLimit']      ? 'counterLimit: \''.$param['counterLimit'].'\','		: '';
  	$options .=  $param['initialHeight']     ? 'initialHeight: \''.$param['initialWidth'].'px\','	: '';
  	$options .=  $param['initialWidth']      ? 'initialWidth: \''.$param['initialWidth'].'px\','	: '';
  	$options .= !$param['enableKeys']        ? 'enableKeys: false,'									: '';
  	$options .=  'handleOversize: \''.$param['handleOversize'].'\',';
  	
  	if ($param['handleException'] != 'null') { 
  		$options .= 'handleException: \''.$param['handleException'].'\',';
    	}
    	$options .=  'handleUnsupported: \''.$param['handleUnsupported'].'\'';
    	$options .= ' } ';
    	$loadoptions = 'options';
  }

//because none of the other js systems work as well as mootools, with the simple window.addevent option
//we have to have some more code to make it work with the options
    $endload = '});';
    switch ($param['jstype']) {
    	case "jquery":
    		$onload = 'jQuery.noConflict(); jQuery(document).ready(function(){';
    		break;
    	case "dojo":
    		$onload = 'dojo.addOnLoad(function(){';
    		break;
    	case "ext":
    		$onload = 'Ext.onReady(function(){';
    		break;
    	case "prototype":
    		$onload = 'Event.observe(window, \'load\',function() {';
    		break;
    	case "yui":
    		$onload = 'window.onload = function() {';
    		$endload = '};';
    		break;
    }


    // Get player list.
    $players = array();
    if ($params->get('img'))    array_push($players, '"img"');
    if ($params->get('swf'))    array_push($players, '"swf"');
    if ($params->get('flv'))    array_push($players, '"flv"');
    if ($params->get('qt'))     array_push($players, '"qt"');
    if ($params->get('wmp'))    array_push($players, '"wmp"');
    if ($params->get('iframe')) array_push($players, '"iframe"');
    if ($params->get('html'))   array_push($players, '"html"');   
 
    
    // Load language, skin and players.
    $jscode = '
    	Shadowbox.loadSkin("'.$param['skin'].'", "'.$modbase.'js/skin");
    	Shadowbox.loadLanguage("'.$param['lang'].'", "'.$modbase.'js/lang");
    	Shadowbox.loadPlayer(['.implode(',', $players).'], "'.$modbase.'js/player");
    ';

    //we need to load the flv player if it is selected even if the advanced options are disabled
    $loadflv = " var options = {flvPlayer: '".$param['flvPlayer']."'}";
    
    //check to see if there is an ID for an image map provided. If there is, generate the imagemap js
    $useImageMap = '';
    if ($imageMapID != '') {
      $useImageMap = "\tShadowbox.setup(document.getElementById('".$imageMapID."').getElementsByTagName('area'));\n\t";
    }
    
    //we also check to see if we have chosen advanced options and load the script accordingly
    if ($param['useadv'])
    {
		    $jscode .= "\t".$onload."\n\t\t".$options."\n\t\tShadowbox.init(options);\n\t".$useImageMap.$endload;
		} 
    else if ($params->get('flv')) {
        $jscode .= "\t".$onload."\n\t\t".$loadflv."\n\t\tShadowbox.init(options);\n\t".$useImageMap.$endload;
    }
		else
    {
        $jscode .= "\t".$onload."\n\t\twindow.onload = Shadowbox.init;\n\t".$useImageMap.$endload;
    }

    
	//lets include the JS library the user has chosen
    if ($param['core']) {
		$document->addScript($modbase . "js/lib/".$param['jstype'].".js");


		//if were using ext, then lets put in the support library
		if ($param['jstype'] == "ext") {
			$document->addScript($modbase . "js/lib/ext-all.js");  
		} 


		//because prototype is no where near as good as mootools, or anyother library for that matter
		//we have to include scriptaculous and all its files
		if ($param['jstype'] == "prototype") {
			$document->addScript($modbase . "js/lib/scriptaculous/scriptaculous.js");
			$document->addScript($modbase . "js/lib/scriptaculous/builder.js");
			$document->addScript($modbase . "js/lib/scriptaculous/controls.js");
			$document->addScript($modbase . "js/lib/scriptaculous/dragdrop.js");
			$document->addScript($modbase . "js/lib/scriptaculous/effects.js");
			$document->addScript($modbase . "js/lib/scriptaculous/slider.js");
			$document->addScript($modbase . "js/lib/scriptaculous/sound.js");
			$document->addScript($modbase . "js/lib/scriptaculous/unittest.js");
		}
    }


    //lets include the shadowbox library our user has chosen
    $document->addScript($modbase . "js/adapter/shadowbox-".$param['jstype'].".js");


    //Lets include the shadowbox main js file, if we are told to
    if ($param['incjs']){
		$document->addScript($modbase . "js/shadowbox.js");
    }


    //lets stick it all in the head
    $document->addScriptDeclaration( $jscode );


    // This is the IE8 fix
    switch ($param['emulate']) {
            
        case 'meta':
            $document->setMetaData('X-UA-Compatible', 'IE=EmulateIE7', true);
            break;
                
        case 'header':
            header('X-UA-Compatible: IE=EmulateIE7');
            break;
            
        case 'no':
            break;
    }
?>