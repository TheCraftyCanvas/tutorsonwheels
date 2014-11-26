<?php echo $enableImageLink? '<a href="'.$row->link.'" title="'.$row->title.'">'.$row->mainImage.'</a>':$row->mainImage; ?> 
 <div class="lof-description">
<h4><?php echo $row->title;?></h4>
<?php if( $row->description != '...') : ?>
<p><?php echo $row->description;?></p>

<?php endif; ?>
</div>