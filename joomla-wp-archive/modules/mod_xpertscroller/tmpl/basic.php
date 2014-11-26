<?php
/**
 * @package XpertScroller
 * @version 1.3 January 27, 2011
 * @author ThemeXpert http://www.themexpert.com
 * @copyright Copyright (C) 2009 - 2011 ThemeXpert
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted accessd');

$index=0;
?>

<div class="<?php echo $this->settings['module_unique_id'];?> <?php echo $this->settings['moduleclass_sfx'];?> <?php echo $this->settings['scroller_layout'];?>">
    <?php if($this->settings['navigator']):?>
    <!-- wrapper for navigator elements -->
    <div class="navi"></div>
    <?php endif;?>
    <a class="prev browse left"></a>
    <div id="<?php echo $this->settings['module_unique_id'];?>" class="scroller">
        <div class="items">
        <?php for($i = 0; $i<$totalPane; $i++){?>
            <div class="pane">
            <?php for($col=0; $col<(int)$this->settings['col_amount']; $col++, $index++) {?>
                <?php if($index>=count($this->content)) break;?>
                <div class="item">
                    <div class="padding clearfix">
                        <?php echo ($this->settings['item_image'] == '1')? $this->content[$index]->image : '';?>

                        <?php if($this->settings['article_title'] === '1'):?>
                            <h4><?php echo $this->content[$index]->title;?></h4>
                        <?php endif;?>
                        
                        <?php if($this->settings['intro_text'] === '1'):?>
                            <div class="xs_intro"><?php echo $this->content[$index]->introtext;?></div>
                        <?php endif;?>
                        <?php if($this->settings['readmore'] === '1'):?>
                            <p class="xs_readmore"><a href="<?php echo $this->content[$index]->link?>">Readmore..</a></p>
                        <?php endif;?>
                        
                        <?php if($this->settings['content_source'] == 'vm'):?>
                            <div class="xs_vm_products"><?php echo $this->content[$index]->items;?></div>
                        <?php endif;?>
                    </div>
                </div>
                <?php if($col == (int)$this->settings['col_amount'] ){$col=0; break;} ?>
            <?php } ?>
            </div>
        <?php }?>
        </div>
    </div>
        <a class="next browse right"></a>
</div>