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
?>
<div id="lofarticlessroller<?php echo $module->id; ?>" class="lof-articlessroller<?php echo $params->get('moduleclass_sfx','');?> <?php echo $class;?>" style="height:<?php echo $moduleHeight;?>; width:<?php echo $moduleWidth;?>">

<?php if( $displayButton != 'hidden') : ?>
    <!-- NAVIGATOR -->
<div class="lof-navigator-wapper">
<?php if( $displayButton  != 'bullets' ) : ?>
    <a class="lof-previous"  href="" onclick="return false;"><?php echo JText::_('Previous');?></a>
    <a class="lof-next" href="" onclick="return false;"><?php echo JText::_('Next');?></a>
    <?php endif; ?>
    <?php if( $displayButton  != 'pre-next' ) : ?>         
  <div class="lof-navigator-outer">
  		<ul class="lof-navigator">
        <?php foreach( $pages as $key => $row ): ?>
            <li><span><?php echo  $key+1;?></span></li>
         <?php endforeach; ?> 		
        </ul>

  </div>
   <?php endif; ?>
</div>  
  <!------------>
  <?php endif; ?>
 <!-- MAIN CONTENT --> 
  <div class="lof-main-wapper" style="height:<?php echo $moduleHeight;?>;width:<?php echo $moduleWidth;?>">
  		
 		<?php foreach( $pages as $key => $list ): ?>
  		<div class="lof-main-item page-<?php echo $key+1;?>">
        		<?php foreach( $list as $i => $row ): ?>
        		 <div class="lof-row" style="width:<?php echo $itemWidth;?>%">
                    <div class="lof-inner">
                    	<?php if( $row->thumbnail ): ?>
                    	<a target="<?php echo $openTarget; ?>" class="lof-image-link <?php echo $thumbnailAlignment;?>" style=" height:<?php echo $thumbHeight ;?>px; width:<?php echo $thumbWidth ;?>px; display:block" title="<?php echo $row->title;?>" href="<?php echo $row->link;?>">
						 <?php echo $row->thumbnail; ?> 
                        </a> 
                        <?php endif ; ?>
                         <h4><a target="<?php echo $openTarget; ?>" title="<?php echo $row->title;?>" href="<?php echo $row->link;?>"><?php echo $row->title;?></a></h4>                            <?php echo $row->description;?>
                            <?php if( $showReadmore ) : ?>
                            <a target="<?php echo $openTarget; ?>" class="lof-readmore" title="<?php echo $row->title;?>" href="<?php echo $row->link;?>">
                            	<?php echo JText::_('READ_MORE');?>
                            </a>
                            <?php endif; ?>
							<?php if( $showDate ): ?>			
                         <div class="lof-date"><?php echo $row->date; ?></div>
                           <?php endif; ?>
                     </div>
				</div>      
                <?php  if( ($i+1) % $maxItemsPerRow == 0 && $i < count($list)-1 ) : ?>
                	<div class="lof-clearfix"></div>
                <?php endif; ?>       
                <?php endforeach; ?>
        </div> 
   		<?php endforeach; ?>
        
        	
  </div>
  <!-- END MAIN CONTENT --> 
 </div> 
