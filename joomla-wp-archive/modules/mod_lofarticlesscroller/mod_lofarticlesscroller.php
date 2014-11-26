<?php
/**
 * $ModDesc
 * 
 * @version		$Id: helper.php $Revision
 * @package		modules
 * @subpackage	$Subpackage.
 * @copyright	Copyright (C) Dec 2009 LandOfCoder.com <@emai:landofcoder@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
 */
// no direct access
defined('_JEXEC') or die;
// Include the syndicate functions only once
require_once dirname(__FILE__).DS.'helper.php';

$list = modLofArticlesSrollerHelper::getList( $params );
// split pages following the max items display on each page.
$maxItemsPerRow = (int)$params->get( 'max_items_per_row', 3 );
$maxPages = (int)$params->get( 'max_items_per_page', 3 );
$pages = array_chunk( $list, $maxPages  );

// calculate width of each row.
$itemWidth = 100/$maxItemsPerRow -0.1;

$tmp = $params->get( 'module_height', 'auto' );
$moduleHeight   =  ( $tmp=='auto' ) ? 'auto' : (int)$tmp.'px';
$tmp = $params->get( 'module_width', 'auto' );
$moduleWidth    =  ( $tmp=='auto') ? 'auto': (int)$tmp.'px';
$openTarget 	= $params->get( 'open_target', 'parent' ); 
$class 			= $params->get( 'navigator_pos', 0 ) ? '':'lof-snleft';
$thumbWidth    = (int)$params->get( 'thumbnail_width', 180 );
$thumbHeight   = (int)$params->get( 'thumbnail_height', 60 );
$thumbnailAlignment = $params->get( 'thumbnail_alignment', '' );	
$displayButton = $params->get( 'display_button', '' );

$showReadmore = $params->get( 'show_readmore', '1' );
$showDate = $params->get( 'show_date', '1' );

modLofArticlesSrollerHelper::loadMediaFiles( $params, $module );

require( JModuleHelper::getLayoutPath($module->module) );

?>
<script type="text/javascript">
	var _lofmain =  $('lofarticlessroller<?php echo $module->id; ?>');

	var _button = {next:_lofmain.getElement('.lof-next'), previous:_lofmain.getElement('.lof-previous')}
	new LofArticleSroller(  _lofmain.getElement('.lof-main-wapper'), 
					  	  _lofmain.getElement('.lof-navigator-outer .lof-navigator'),
					  	  _lofmain.getElement('.lof-navigator-outer'),
						  { 
							  fxObject:{
										transition:<?php echo $params->get( 'effect', 'Fx.Transitions.Quad.easeIn' );?>,  
										duration:<?php echo (int)$params->get('duration', '700')?>
									  },
							  interval:<?php echo (int)$params->get('interval', '4500'); ?>,
							  direction :'<?php echo $params->get('layout_style','opacity');?>', 
							  navItemHeight:<?php echo $params->get('navitem_height', 15) ?>,
							  navItemWidth:<?php echo $params->get('navitem_width', 15) ?>
						  } ).registerButtonsControl( 'click', _button ).start( <?php echo $params->get('auto_start',1)?>  );
</script>
