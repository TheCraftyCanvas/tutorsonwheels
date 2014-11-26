<?php
/**
 * @package XpertScroller
 * @version 1.3
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
<a class="prev browse left"></a>
    <div id="<?php echo $this->settings['module_unique_id'];?>" class="scroller">
        <div class="items">
        <?php for($i = 0; $i<$totalPane; $i++){?>
            <div class="pane">
            <?php for($col=0; $col<(int)$this->settings['col_amount']; $col++, $index++) {?>
                <?php if($index>=count($this->content)) break;?>
                <div class="item">
                    <div class="padding clearfix">
                        
                        <div class="xs_vm_products"><?php echo $this->content[$index]->items;?></div>
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