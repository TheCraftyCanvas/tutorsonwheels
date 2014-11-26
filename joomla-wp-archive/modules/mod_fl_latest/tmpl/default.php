<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$style=$params->get('style');
$font_size = $params->get('font_size');
if ($font_size !='') {
	$fonts ='font-size:'.$font_size.';';
} else {
	$fonts = '';
}
$cuclass=$params->get('cuclass');
if ($cuclass !='') {
	$class =''.$cuclass.'';
} else {
	$cuclass = '';
}
	$flisti = $params->get('moduleclass_sfx');

	$flist = $params->get('typlist');
if ($flist == '1') {
	$flists = '<ul class="latestnews '.$cuclass.''.$params->get('moduleclass_sfx').'">';
	$fliste = '</ul>';
} else {
	$flists = '<ol class="latestnews '.$cuclass.''.$params->get('moduleclass_sfx').'">';
	$fliste = '</ol>';
}
?>
<div class="flatart"><?php echo $flists;?>

<?php if ($style=="0") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="1") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
    <span style="<?php echo $fonts;?>">
      <?php echo ' ('.$item->creationdate.')'; ?>
    </span>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="2") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
    <span style="<?php echo $fonts;?>">
      <?php echo ' ('.$item->name.')'; ?>
    </span>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="3") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
    <span style="<?php echo $fonts;?>">
      <?php echo ' ('.$item->creationdate.' - '.$item->name.')'; ?>
    </span>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="4") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
    <span style="<?php echo $fonts;?>">
      <?php echo ' ('.$item->name.' - '.$item->creationdate.')'; ?>
    </span>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="5") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
    <br />
    <span style="<?php echo $fonts;?>">
      <?php echo $item->creationdate; ?>
    </span>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="6") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
    <br />
    <span style="<?php echo $fonts;?>">
      <?php echo $item->name; ?>
    </span>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="7") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
    <br />
    <span style="<?php echo $fonts;?>">
      <?php echo $item->name.' - '.$item->creationdate; ?>
    </span>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="8") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
    <br />
    <span style="<?php echo $fonts;?>">
      <?php echo $item->creationdate.' - '.$item->name; ?>
    </span>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="9") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
    <br />
    <span style="<?php echo $fonts;?>">
      <?php echo '('.$item->creationdate.')'; ?>
    </span>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="10") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
    <br />
    <span style="<?php echo $fonts;?>">
      <?php echo '('.$item->name.')'; ?>
    </span>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="11") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
    <br />
    <span style="<?php echo $fonts;?>">
      <?php echo '('.$item->name.' - '.$item->creationdate.')'; ?>
    </span>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="12") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
    <br />
    <span style="<?php echo $fonts;?>">
      <?php echo '('.$item->creationdate.' - '.$item->name.')'; ?>
    </span>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="13") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <span style="<?php echo $fonts;?>">
      <?php echo $item->creationdate; ?>
    </span>
    <br />
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="14") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <span style="<?php echo $fonts;?>">
      <?php echo $item->name; ?>
    </span>
    <br />
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="15") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <span style="<?php echo $fonts;?>">
      <?php echo $item->creationdate.' - '.$item->name; ?>
    </span>
    <br />
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="16") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <span style="<?php echo $fonts;?>">
      <?php echo $item->name.' - '.$item->creationdate; ?>
    </span>
    <br />
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="17") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <span style="<?php echo $fonts;?>">
      <?php echo '('.$item->creationdate.')'; ?>
    </span>
    <br />
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="18") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <span style="<?php echo $fonts;?>">
      <?php echo '('.$item->name.')'; ?>
    </span>
    <br />
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="19") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <span style="<?php echo $fonts;?>">
      <?php echo '('.$item->creationdate.' - '.$item->name.')'; ?>
    </span>
    <br />
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
  </li>
<?php endforeach; ?>
			<?php }?>

<?php if ($style=="20") {?>
<?php foreach ($list as $item) :  ?>
  <li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
    <span style="<?php echo $fonts;?>">
      <?php echo '('.$item->name.' - '.$item->creationdate.')'; ?>
    </span>
    <br />
    <a href="<?php echo $item->link; ?>" class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
      <?php echo $item->text; ?>
    </a>
  </li>
<?php endforeach; ?>
			<?php }?>


<?php echo $fliste;?></div>
