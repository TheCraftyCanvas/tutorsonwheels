<?php

/*

* mod_jtabs allows created jQuery tabs, and inclusion of free scripts, in Joomla/Mambo Modules

* Joomla 1.5 jQuery Tabs module,
* jTabs allow to created  jQuery Tabs and Accordion via joomla modules
* and free to insert HTML code for tabs.

* This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.

* Design, www.xsor.net
* email: xsor@xsor.net

* To modify styles check http://jqueryui.com/themeroller/

* date: May 12 2011

* Release: 1.1.1

* 2009 - 2011

*/

// ensure this file is being included by a parent file

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );


// mod_php version
$ver = '1.1.1';
$joomla_root ="/";

$themes=$params->get( "themes" );

$css_class=$params->get( "css_class" );

$height=$params->get( "height" );
if ($height == "0"){
	$height = "auto";
	}else{ $height = $height ."px";}
	
$width=$params->get( "width" );
if ($width == "0"){
	$width = "auto";
	}else{ $width = $width ."px";}

global $mainframe;

// count instances
if (!isset($GLOBALS['mod_jtabs'])) {
	$GLOBALS['mod_jtabs'] = 1;
} else {
	$GLOBALS['mod_jtabs']++;
}

require_once (dirname(__FILE__).DS.'data/data.php');

// disable edit ability icon
$access = new stdClass();
$access->canEdit	= 0;
$access->canEditOwn = 0;
$access->canPublish = 0;

$list = ReturnCategory::getList($params, $access);

// check if any results returned
$items = count($list);
if (!$items) {
	return;
}

// keep it random ID for multipal modules
$id = rand(100000000000, 100000000000000);
$idt = "#".$id;


// please note, you could not load more than one style per page but could have multipal jTab modules per pege
?>
<script type="text/javascript" src="<?php echo JURI::root(); ?>modules/mod_jtabs/jquery/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="<?php echo JURI::root(); ?>modules/mod_jtabs/jquery/jquery-ui-1.8.11.custom.js"></script>
<link rel="stylesheet" href="<?php echo JURI::root(); ?>modules/mod_jtabs/themes/<?php echo "$themes";  ?>/jquery-ui-1.8.11.custom.css" type="text/css" />
 
 
<div class="<?php echo $css_class; ?>" style="width:<?php echo $width; ?>; height:<?php echo $height; ?>;">



 
<?php
$tabs_type=$params->get( "tabs_type" );
if ( $tabs_type =="Accordion") {     //if accordion
?>

	<script type="text/javascript">
	jQuery.noConflict();
	jQuery(document).ready(function($){
		$("<?php echo $idt; ?>").accordion

        <?php
         $options=$params->get( "Accordion_Options" );

         if ($options=="mouseover"){
         ?>    ({
			event: "mouseover"
		}); 	});
        <?php
         }
         if($options=="autoHeight"){

         ?>    ({
			autoHeight: false
		});
	});
        <?php
         }

         if($options=="autoHeight_over"){

             ?>    ({
                   event: "mouseover" , autoHeight: false
		});
	});

          <?php
         }
         if($options=="default"){

            echo '(); 	}); ' ;
         }

         ?>
	</script>
    


<div id="<?php echo $id ?>">

<?php
for ($i=0; $i < $items; $i++) {

?>
	<h3><a href="#"><?php  echo $list[$i]->title; ?></a></h3>
	<div>
		<p>
                    <?php  ReturnCategory::renderItem($list[$i], $params, $access); ?> 
        </p>
	</div>
<?php



	}//end for



  echo '</div>'; //close  accordion  div

}//--------------  end if accordion  ----------------

  if ( $tabs_type =="Horizontal") {     //if Tabs type
?>

     	<script type="text/javascript">
		jQuery.noConflict();
	jQuery(document).ready(function($){
		$("<?php echo $idt; ?>").tabs

        <?php
         $options=$params->get( "Tabs_Options" );

         if ($options=="mouseover"){
         ?>    ({
			event: "mouseover"
		}); 	});
        <?php
         }

         if($options=="default"){

            echo '(); 	}); ' ;
         }

         ?>
	</script>



<div id="<?php echo $id; ?>">
	<ul>
    <?php for ($i=0; $i < $items; $i++) { ?>    
		<li><a href="#tabs-<?php  echo $i; ?>"><?php  echo $list[$i]->title; ?></a></li>
	<?php }?>
	</ul>
    
    <?php for ($i=0; $i < $items; $i++) { ?>    
	<div id="tabs-<?php  echo $i; ?>">
		<p> <?php  ReturnCategory::renderItem($list[$i], $params, $access); ?></p>
	</div>
	<?php }?>
    
</div>

<?php 
}//end if Tabs type 
?>

</div>