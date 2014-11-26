<?php
/**
 * @copyright	Copyright (C) 2010 Cédric KEIFLIN alias ced1870
 * http://www.ck-web-creation-alsace.com
 * http://www.joomlack.fr
 * Module Maximenu_CK for Joomla! 1.5
 * @license		GNU/GPL
**/

// no direct access
defined('_JEXEC') or die('Restricted access');
if ($params->get('style','moomenu') == 'clickclose') {
    $close = '<span class="maxiclose">'.JText::_('MAXICLOSE').'</span>';
} else {
    $close = '';
}
?>
<!-- debut maximenu_CK, par cedric keiflin sur http://www.joomlack.fr -->
<?php if ($params->get('orientation', '0') == 1) {?>
<div class="maximenuCKV" id="<?php echo $params->get('menuid','maximenuCK'); ?>" style="z-index:<?php echo $params->get('zindexlevel','10'); ?>;">
<?php } else { ?>
<div class="maximenuCKH" id="<?php echo $params->get('menuid','maximenuCK'); ?>" style="z-index:<?php echo $params->get('zindexlevel','10'); ?>;">
<?php } ?>
    <div class="maxiRoundedleft"></div>
    <div class="maxiRoundedcenter">
	<ul class="menu<?php echo $params->get('moduleclass_sfx'); ?> maximenuCK" style="<?php echo $menubgcolor; ?>">
		<?php 
			$user = & JFactory::getUser();
			$zindex = 12000;

			foreach ($items as $i => &$item)		{
                            
				if ($item->access <= $user->get('aid', 0)) {
					if ($item->content) {
						echo '<li class="maximenuCK'.$item->classe.' level'.$item->sublevel.' '.$item->liclass.'">'.$item->content;
					}
					elseif (isset($item->colonne)) {
						if (!isset($item->onlycoltitle)) echo '</ul><div class="clr"></div></div><div class="maximenuCK2" '.$item->colwidth.'><ul class="maximenuCK2">';
                                                if (isset($item->coltitle)) echo $item->coltitle;
					}
					elseif ($item->name != "") {
                                                if ($params->get('imageonly', '0') == '1' || isset($item->img)) $item->name ='';
						echo '<li class="maximenuCK'.$item->classe.' level'.$item->sublevel.' '.$item->liclass.'" style="z-index : '.$zindex.';">';
								switch ($item->type) :
									default:
										echo '<a class="maximenuCK '.$item->anchor_css.$item->anchor_title.'" href="'.$item->link.'"'.$item->rel.'><span class="titreCK">'.$item->image.$item->name.$item->desc.'</span></a>';
										break;
									case 'separator':
										echo '<span class="separator '.$item->anchor_css.'"><span class="titreCK">'.$item->image.$item->name.$item->desc.'</span></span>';
										break;
									case 'url':
									case 'component':
										switch ($item->browserNav) :
											default:
											case 0:
												echo '<a class="maximenuCK '.$item->anchor_css.'" href="'.$item->link.'"'.$item->rel.$item->anchor_title.'><span class="titreCK">'.$item->image.$item->name.$item->desc.'</span></a>';
												break;
											case 1:
												// _blank
												echo '<a class="maximenuCK '.$item->anchor_css.'" href="'.$item->link.'"'.$item->rel.$item->anchor_title.' target="_blank"><span class="titreCK">'.$item->image.$item->name.$item->desc.'</span></a>';
												break;
											case 2:
												// window.open
												//$attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,'.$this->_params->get('window_open');
												echo '<a class="maximenuCK '.$item->anchor_css.'" href="'.$item->link.'&tmpl=component" onclick="window.open(this.href,\'targetWindow\',\'$attribs\');return false;"'.$item->rel.$item->anchor_title.'><span class="titreCK">'.$item->image.$item->name.$item->desc.'</span></a>';
												break;
										endswitch;
										break;
								endswitch;
					} else {
                                            
                                        }
				}
					
					if ($item->deeper)
					{
                                                if (isset($item->submenuswidth) || $item->leftmargin || $item->topmargin || $item->colbgcolor) {
                                                    $item->styles = "style=\"";
                                                    if ($item->leftmargin)
                                                            $item->styles .= "margin-left:".$item->leftmargin."px;";
                                                    if ($item->topmargin)
                                                            $item->styles .= "margin-top:".$item->topmargin."px;";
                                                    if (isset($item->submenuswidth))
                                                            $item->styles .= "width:".$item->submenuswidth."px;";
                                                    if (isset($item->colbgcolor) && $item->colbgcolor)
                                                            $item->styles .= "background:".$item->colbgcolor.";";
                                                    
                                                    $item->styles .= "\"";
                                                } else {
                                                    $item->styles = "";
                                                }
                                                
						echo "\n\t<div class=\"floatCK".$item->divclasse."\" ".$item->styles.">".$close."<div class=\"maxidrop-top\"><div class=\"maxidrop-top2\"></div></div><div class=\"maxidrop-main\"><div class=\"maxidrop-main2\"><div class=\"maximenuCK2 first \" ".$item->colwidth.">\n\t<ul class=\"maximenuCK2\">";

                                                if (isset($item->coltitle)) echo $item->coltitle;
					}
					// The next item is shallower.
					elseif ($item->shallower)
					{
						echo "\n\t</li>";
						echo str_repeat("\n\t</ul>\n\t<div class=\"clr\"></div></div><div class=\"clr\"></div></div></div><div class=\"maxidrop-bottom\"><div class=\"maxidrop-bottom2\"></div></div></div>\n\t</li>", $item->level_diff);
					}
					// the item is the last.
					elseif ($item->is_end)
					{			
						echo str_repeat("</li>\n\t</ul>\n\t<div class=\"clr\"></div></div><div class=\"clr\"></div></div></div><div class=\"maxidrop-bottom\"><div class=\"maxidrop-bottom2\"></div></div></div>", $item->level_diff);
						echo "</li>";
					}
					// The next item is on the same level.
					else {
						if (!isset($item->colonne)) echo "\n\t\t</li>\n";
					}
				
				$zindex--;
			}
		?>
	</ul>
    </div>
    <div class="maxiRoundedright"></div>
    <div style="clear:both;"></div>
</div>
<div style="clear:both;"></div>
<!-- fin maximenuCK -->
