<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div class="swf<?php echo $params->get('moduleclass_sfx'); ?>">
	<div id="<?php echo $params->get('id','flashid');?>">
		<p><strong><?php echo $params->get('alt_content',JText::_('Alternative flash content'));?></strong></p>
		<p><?php echo JText::_('Requirements');?></p>
		<?php if($params->get('flash_link')) : ?><p><a href="http://www.adobe.com/go/getflashplayer" title="<?php echo JText::_('Get Adobe Flash player');?>" ><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="<?php echo JText::_('Get Adobe Flash player');?>" style="border:0;" /></a></p><?php endif; ?>
	</div>
</div>