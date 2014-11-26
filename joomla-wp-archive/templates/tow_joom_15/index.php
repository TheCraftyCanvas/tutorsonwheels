<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<jdoc:include type="head" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/tow_joom_15/css/main.css" type="text/css" />

</head>
<body>
<div class="wrapper">
	<div class="header">
		<div class="logoholder">
        <div class="logo"><a href="http://www.tutorsonwheels.com"><img src="<?php echo $this->baseurl ?>/templates/tow_joom_15/images/logo.jpg"/></a></div>
        </div>
         
		<div class="top_right">
        <ul class="topright_menu">
	       <jdoc:include type="modules" name="pos-1" style="xhtml" />
        </ul>
       
        <div class="searchbox"><jdoc:include type="modules"   name="pos-2"  style="xhtml"   /></div>
        <div class="clearfix"></div>
        <div class="socialicons"><jdoc:include type="modules"   name="pos-3"  style="xhtml"   /></div>
        <div class="clearfix"></div>
        <div class="toll"><jdoc:include type="modules"   name="toll"/></div>
        </div>
        
        <div class="menuholder"><jdoc:include type="modules"   name="menu"  style="xhtml"   /></div>
	</div>
    <div class="clearfix"></div>
   <div class="top_slider">
   
   <div id="topleft"><jdoc:include type="modules"   name="leftmenu"  style="xhtml"   /></div>
   <div id="topmiddle"><jdoc:include type="modules"   name="slider"  style="xhtml"   /></div>
   <div id="topright"><jdoc:include type="modules"   name="rightmenu"  style="xhtml"   /></div>
   </div> 

<div class="clearfix"></div> 
<div class="contentwrapper"> 
<jdoc:include type="component" style="xhtml" />
<?php if ($this->countModules('pos-tab')): ?>
<jdoc:include type="modules"  name="pos-tab"  />
<?php endif; ?>

</div>

    <div class="section_1">
   	<?php if ($this->countModules('pos-4')): ?>    
	    <div class="section_cols"><jdoc:include type="modules"   name="pos-4"  /></div>
     <?php endif; ?>
    	<?php if ($this->countModules('pos-5')): ?>
	    <div class="section_cols"><jdoc:include type="modules"   name="pos-5"   /></div>
     <?php endif; ?>
    	
	    <div class="section_cols_last">
        <?php if ($this->countModules('pos-6')): ?>
        <jdoc:include type="modules"   name="pos-6"  style="xhtml"   /></div>
         <?php endif; ?>
        <div class="clearfix"></div>
    </div> 

    	<?php if ($this->countModules('pos-9')): ?>
    <div class="section_2">
    <div class="section_2_cols"><jdoc:include type="modules"   name="pos-9"  style="xhtml"   /></div>
     <?php endif; ?>
<?php if ($this->countModules('pos-8')): ?>
    <div class="right_cols"><jdoc:include type="modules"   name="pos-8" style="xhtml"  /></div>
<?php endif; ?>
<?php if ($this->countModules('pos-7')): ?>
    <div class="section_3"><jdoc:include type="modules"   name="pos-7"  style="xhtml"   /></div>
<?php endif; ?>
<?php if ($this->countModules('pos-10')): ?>
    <div class="section_4">
    <div class="section_4_cols"><jdoc:include type="modules" name="pos-10" style="xhtml" /></div>
<?php endif; ?>
<?php if ($this->countModules('pos-11')): ?>
    <div class="section_4_cols"><jdoc:include type="modules"   name="pos-11" style="xhtml" /></div>
<?php endif; ?>
<?php if ($this->countModules('pos-12')): ?>
    <div class="section_4_cols"><jdoc:include type="modules"   name="pos-12" style="xhtml"   /></div>

    </div>

    </div>
  <?php endif; ?>  
    <div class="clearfix"></div>
    <div class="footer">
    	<div class="footer_wrapper">
	    	<div class="footer_sec"><a href="index.php?option=com_content&view=article&id=88&Itemid=67">FAQs</a><a href="http://blog.tutorsonwheels.com/">Blogs</a><a href="index.php?option=com_content&view=article&id=82&Itemid=108">Affiliations </a> <a href="index.php?option=com_content&view=article&id=86&Itemid=67">Press Releases </a><a href="index.php?option=com_content&view=article&id=83&Itemid=67">Education News </a><a href="index.php?option=com_content&view=article&id=85&Itemid=67">Resources</a><a href="index.php?option=com_content&view=article&id=81&Itemid=108">Privacy Policy</a><a href="index.php?option=com_content&view=article&id=80&Itemid=108">Tutoring Costs</a></div>
            <div class="footer_sec_right"><p>Copyright &copy; 2013 Tutors On Wheels. All rights reserved</div>
            <div class="clearfix"></div>
	    </div>
    </div>

</div>
</body>
</html>