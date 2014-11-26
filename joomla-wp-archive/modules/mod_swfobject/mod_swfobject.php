<?php
/**
 * mod_swfobject Module Entry Point
 * 
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Include the syndicate functions only once
require_once( dirname(__FILE__).'/helper.php' );

mod_swfobject_helper::exec_module($params);
require( JModuleHelper::getLayoutPath( 'mod_swfobject' ) );
?>