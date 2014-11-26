<?php defined('_JEXEC') or die('Restricted access');

/**
 * Vertical scroll recent article
 *
 * @package Vertical scroll recent article
 * @subpackage Vertical scroll recent article
 * @version   2.0 July 15, 2011
 * @author    Gopi http://www.gopiplus.com
 * @copyright Copyright (C) 2010 - 2011 www.gopiplus.com, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */
?>

<script language="JavaScript1.2">
function vsra_scroll() {
	crs_obj.scrollTop = crs_obj.scrollTop + 1;
	crs_scrollPos++;
	if ((crs_scrollPos%crs_heightOfElm) == 0) {
		crs_numScrolls--;
		if (crs_numScrolls == 0) {
			crs_obj.scrollTop = '0';
			vsra_content();
		} else {
			if (crs_scrollOn == 'true') {
				vsra_content();
			}
		}
	} else {
		setTimeout("vsra_scroll();", 10);
	}
}
var crs_Num = 0;
function vsra_content() {
	var tmp_vsrp = '';

	w_vsrp = crs_Num - parseInt(crs_numberOfElm);
	if (w_vsrp < 0) {
		w_vsrp = 0;
	} else {
		w_vsrp = w_vsrp%crs_array.length;
	}
	
	// Show amount of vsrru
	var elementsTmp_vsrp = parseInt(crs_numberOfElm) + 1;
	for (i_vsrp = 0; i_vsrp < elementsTmp_vsrp; i_vsrp++) {
		
		tmp_vsrp += crs_array[w_vsrp%crs_array.length];
		w_vsrp++;
	}

	crs_obj.innerHTML 	= tmp_vsrp;
	
	crs_Num 			= w_vsrp;
	crs_numScrolls 	= crs_array.length;
	crs_obj.scrollTop 	= '0';
	// start scrolling
	setTimeout("vsra_scroll();", 2000);
}
</script>

<?php
		
	@$vsra_height=$params->get('vsra_height');
	@$vsra_count=$params->get('vsra_count');
	@$vsra_width=$params->get('vsra_width');
	@$vsra_padding=$params->get('vsra_padding');
	
	$vsra_categories=$params->get('vsra_categories');
	$vsra_order=$params->get('vsra_order');
	
	if(!is_numeric(@$vsra_width))
	{
		@$vsra_width = 200;
	} 
	if(!is_numeric(@$vsra_height))
	{
		@$vsra_height = 30;
	}
	if(!is_numeric(@$vsra_count))
	{
		@$vsra_count = 5;
	}

global $Itemid, $mainframe;
$database = & JFactory :: getDBO();
$user = & JFactory :: getUser();

$gid 	= $user->get('gid');
$jnow	=& JFactory::getDate();
$now	= $jnow->toMySQL();
$nullDate = $database->getNullDate();

$contentConfig = & JComponentHelper :: getParams('com_content');
$access = !$contentConfig->get('shownoauth');

$query = "SELECT c.id AS id, c.title AS title, c.catid as catid, c.sectionid as sectionid, c.alias as title_alias
  FROM #__content AS c
		INNER JOIN #__categories AS cat
		ON(c.catid = cat.id) AND (cat.access <= $gid)
		INNER JOIN #__sections AS sec
		ON(c.sectionid = sec.id)
  WHERE c.state = '1'
    AND ( c.publish_up = " . $database->Quote($nullDate) . " OR c.publish_up <= " . $database->Quote($now) . " )
    AND ( c.publish_down = " . $database->Quote($nullDate) . " OR c.publish_down >= " . $database->Quote($now) . " )
	AND cat.published = '1'
	AND sec.published = '1' ";

if ($access) {
	$query .= "    AND c.access <= $gid \n";
}
if ($vsra_categories != '') {
	$query .= "AND cat.id in ($vsra_categories) \n";
}

if ($vsra_order != '')
{
	if ($vsra_order == 'created')
	{
		$query .= "ORDER BY c.created desc";
	}
	if ($vsra_order == 'modified')
	{
		$query .= "ORDER BY c.modified desc";
	}
	if ($vsra_order == 'ordering')
	{
		$query .= "ORDER BY c.ordering";
	}
} 
else
{
	$query .= "ORDER BY c.title";
}

$database->setQuery($query);
$rows = $database->loadObjectList();

@$crs_count = 0;
foreach ($rows as $row) 
{
	$url = "index.php?option=com_content&view=article&id={$row->id}";
	
	
	$crs_post_title = $row->title;
	
	if(!is_numeric(@$vsra_width))
	{
		@$vsra_width = 200;
	}
	//echo @$vsra_width;
	$crs_post_title = mysql_real_escape_string(trim($crs_post_title));
	$crs_post_title = substr($crs_post_title, 0, @$vsra_width);
	
	$get_permalink = $url;
	$get_permalink = mysql_real_escape_string(trim($get_permalink));
	
	$crs_post_title = substr($crs_post_title, 0, $vsra_width);

	$dis_height = $vsra_height."px";
	@$crs_html = @$crs_html . "<div class='crs_div' style='height:$dis_height;padding:2px 0px 2px 0px;'>"; 
	@$crs_html = @$crs_html . "<a href='$get_permalink'>$crs_post_title</a>";
	@$crs_html = @$crs_html . "</div>";
	
	$crs_post_title = trim($crs_post_title);
	$get_permalink = $get_permalink;
	@$crs_x = @$crs_x . "crs_array[$crs_count] = '<div class=\'crs_div\' style=\'height:$dis_height;padding:2px 0px 2px 0px;\'><a href=\'$get_permalink\'>$crs_post_title</a></div>'; ";	
	@$crs_count++;

}

	$vsra_height = $vsra_height + 4;
	if($crs_count >= $vsra_count)
	{
		$crs_count = $vsra_count;
		$crs_height = ($vsra_height * $vsra_count);
	}
	else
	{
		$crs_count = $crs_count;
		$crs_height = ($crs_count*$vsra_height);
	}
	$crs_height1 = $vsra_height."px";
	?>
	<div style="padding-top:8px;padding-bottom:8px;">
	  <div style="text-align:left;vertical-align:middle;text-decoration: none;overflow: hidden; position: relative; margin-left: 1px; height: <?php echo $crs_height1; ?>;" id="crs_Holder"><?php echo @$crs_html; ?></div>
	</div>
	<script type="text/javascript">
	var crs_array	= new Array();
	var crs_obj	= '';
	var crs_scrollPos 	= '';
	var crs_numScrolls	= '';
	var crs_heightOfElm = '<?php echo $vsra_height; ?>'; // Height of each element (px)
	var crs_numberOfElm = '<?php echo $crs_count; ?>';
	var crs_scrollOn 	= 'true';
	function vsra_createscroll() 
	{
		<?php echo $crs_x; ?>
		crs_obj	= document.getElementById('crs_Holder');
		crs_obj.style.height = (crs_numberOfElm * crs_heightOfElm) + 'px'; // Set height of DIV
		vsra_content();
	}
	</script>
	<script type="text/javascript">
	vsra_createscroll();
	</script>
