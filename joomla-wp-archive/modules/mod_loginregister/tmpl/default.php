<?php // no direct access
defined('_JEXEC') or die('Restricted access');
 ?>
 <script type="text/javascript">
<!--
	Window.onDomReady(function(){
		document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
	});
// -->
</script>
<?php
JHTML::_('behavior.mootools');
$document = &JFactory::getDocument();
require_once(dirname(__FILE__).DS.'recaptchalib.php');
$document->addScript(JURI::root() .'media/system/js/validate.js');
$publickey = $params->get('public');
$privatekey= $params->get('private');
$error='';
$document->addCustomTag('<script type="text/javascript">

jQuery.noConflict();

   function checkcapcha(){
   
    var agree=""; 
		if(agree= document.getElementById("formagree"))
		{
     if (!agree.checked) 
	 {
        alert("You Must Agree to our Terms of Use.");
        return false;
   }  
   } 
        var chell=   document.getElementById("recaptcha_challenge_field").value;

       var resp = document.getElementById("recaptcha_response_field").value ;
       var prikey = "'.$privatekey.'";
       document.getElementById("myDiv").innerHTML="";


var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();

  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {

  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
                            var responss=xmlhttp.responseText;
                            //alert (responss);
                             if(responss =="false2"){
                                       document.getElementById("myDiv").innerHTML= "'.JText::_( 'INVAID_PRIVATE').'" ;
                                    }else{
                                       if(responss =="true")  {
                                       document.josForm.submit();
									   
                                                      }else   { document.getElementById("myDiv").innerHTML= "'.JText::_( 'INVAID_CAPTCHA').'" ;
                                                                Recaptcha.reload ();
                                    }
                                    }
    }else  document.getElementById("myDiv").innerHTML= "<img src=\"modules/mod_loginregister/tmpl/loads.gif\" border=\"0\">" ;
  }

xmlhttp.open("GET","modules/mod_loginregister/tmpl/captchacheck.php?field1="+chell+"&field2="+resp+"&field3="+prikey,true);
xmlhttp.send();

   return false;
 }



   function xi(s){
                    if(s=="y") {
                    jQuery(".popup_register").hide(300);
                    jQuery(".passwret").show(300);
                    jQuery("#form2").hide();
                     jQuery("#form1").show();

                      }
                    if(s=="n")
                    {jQuery(".popup_register").show(300);
                    jQuery(".passwret").hide(300);
                    jQuery("#form2").show();
                     jQuery("#form1").hide();
                    }
                  }
</script>');
//JQuery Load
$jquery_source=$params->get('jqueryload');
if($jquery_source=='local')
{
$document->addScript(JURI::root() .'modules/mod_loginregister/tmpl/jquery.min.js');
}
else
{
$document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
}
if(!$params->get('disablelog'))
{

	$document->addCustomTag( '<script type="text/javascript">jQuery.noConflict();</script>' );
	
		$flags=1;
					}
	else
	{
	$flags=0;
	}

 function jm_getthem($params)
	{
		switch ($params->get('jmtheme'))
		{
			case '0':
				return 'red';
				break;
			case '1':
				return 'white';
				break;
			case '2':
				return 'blackglass';
				break;
			case '3':
				return 'clean';
				break;
		}
	}

?>
<?php
$usersConfig = &JComponentHelper::getParams( 'com_users' );
  if($flags && $usersConfig->get('allowUserRegistration') && $type != 'logout' ) :
  $check= $params->get('checkbox1'); 
 if($check==1) {
 ?>

  <div style="margin:0px;" id="login-form">
 <p style="padding:10px 0 0 20px;">
  <input type="radio" onclick="xi('y')"  name="group1" <?php if($params->get('view')==0) echo 'checked="checked"';?>  /> <?php echo JText::_('LOGIN') ?><br/>
<input type="radio" onclick="xi('n')"  name="group1" <?php if($params->get('view')==1) echo 'checked="checked"';?> /><?php echo JText::_('REGISTER'); ?><br/>
   </p>
   </div>
   <?php }  endif; ?>

<?php if($type == 'logout') : ?>
<form action="index.php" method="post" name="login" id="form-login">
<?php if ($params->get('greeting')) : ?>
	<div>
	<?php if ($params->get('name')) : {
		echo JText::sprintf( 'HINAME', $user->get('name') );
	} else : {
		echo JText::sprintf( 'HINAME', $user->get('username') );
	} endif; ?>
	</div>
<?php endif; ?>
	<div align="center">
		<input type="submit" name="Submit" class="button" value="<?php echo JText::_( 'BUTTON_LOGOUT'); ?>" />
	</div>

	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="logout" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
</form>
<?php elseif(!$params->get('disablelog')) : ?>
<?php if(JPluginHelper::isEnabled('authentication', 'openid')) :
		$lang->load( 'plg_authentication_openid', JPATH_ADMINISTRATOR );
		$langScript = 	'var JLanguage = {};'.
						' JLanguage.WHAT_IS_OPENID = \''.JText::_( 'WHAT_IS_OPENID' ).'\';'.
						' JLanguage.LOGIN_WITH_OPENID = \''.JText::_( 'LOGIN_WITH_OPENID' ).'\';'.
						' JLanguage.NORMAL_LOGIN = \''.JText::_( 'NORMAL_LOGIN' ).'\';'.
						' var modlogin = 1;';
		$document = &JFactory::getDocument();
		$document->addScriptDeclaration( $langScript );
		JHTML::_('script', 'openid.js');
endif; ?>


<div style="margin:0px;display:<?php if($params->get('view')) {echo "none";} else {echo "block" ;}?>;"  class="passwret">
<form action="<?php echo JRoute::_( 'index.php', true, $params->get('usesecure')); ?>" method="post" name="loginregister" id="form-login" >
	<?php echo $params->get('pretext'); ?>
	<fieldset class="input">
	<p id="form-login-username">
		<label for="modlgn_username"><?php echo JText::_('USERNAME') ?></label><br />
		<input id="modlgn_username" type="text" name="username" class="inputbox" alt="username" size="18" />
	</p>

	<p id="form-login-password">
		<label for="modlgn_passwd"><?php echo JText::_('PASSWORD') ?></label><br />
		<input id="modlgn_passwd" type="password" name="passwd" class="inputbox" size="18" alt="password" />
	</p>
	<?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
	<p id="form-login-remember">
		<label for="modlgn_remember"><?php echo JText::_('REMEMBER_ME') ?></label>
		<input id="modlgn_remember" type="checkbox" name="remember" class="inputbox" value="yes" alt="Remember Me" />
	</p>
	<?php endif; ?>

	</fieldset>
	<ul>
		<li>
			<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>">
			<?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
		</li>
		<li>
			<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>">
			<?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a>
		</li>



	</ul>
	<input type="submit" name="Submit" class="button" value="<?php echo JText::_('LOGIN') ?>" /><BR/><BR/>

	<?php echo $params->get('posttext'); ?>

	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="login" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>

<?php endif;



  if($flags){
  if($params->get('view')) 
  $flag2=1;
  else $flag2=0;
  }
  else
  { $flag2=1; }     ?>
<?php if($type != 'logout') : ?>
<div style="margin:0px; display:<?php if($flag2) {echo "block";} else {echo "none" ;}?>;" class="popup_register">

 		<form action="<?php echo JRoute::_( 'index.php?option=com_user' ); ?>" method="post" id="josForm" name="josForm" class="form-validate"  <?php if ($params->get('enablecap') && $publickey && $privatekey){?>  onsubmit="return checkcapcha()" <?php } ?> >

                    <p>
						<label id="namemsg" for="name">
                        	<?php echo JText::_( 'NAME' ); ?>:
						*</label><br/>
                     	<input style= "width:80%; " type="text" name="name" id="name" size="20" value="" class="inputbox required"  />
                     </p>


                     <p>
						<label id="usernamemsg" for="username">
							<?php echo JText::_( 'USERNAME' ); ?>:
						*</label>  <br/>
						<input style= "width:80%; " type="text" id="username" name="username" size="20" value="" class="inputbox required validate-username" />
                    </p>

                    <p>
						<label id="emailmsg" for="email">
							<?php echo JText::_( 'EMAIL' ); ?>:
						*</label> <br/>
						<input style= "width:80%; " type="text" id="email" name="email" size="20" value="" class="inputbox required validate-email"  />
                    </p>

                    <p>
						<label id="pwmsg" for="password">
							<?php echo JText::_( 'PASSWORD' ); ?>:
						*</label><br/>
                     	<input  style= "width:80%; " class="inputbox required validate-password" type="password" id="password" name="password" size="20" value=""  />
                    </p>

                    <p>
						<label id="pw2msg" for="password2">
                             <?php echo JText::_( 'VERIFY_PASSWORD' ); ?>:
						*</label><br/>
						<input style= "width:80%; "  class="inputbox required validate-passverify" type="password" id="password2" name="password2" size="20" value=""  />
                    </p>
                          <BR/>
						  <?php 
	 $tou= $params->get('tou');
	$articleid= $params->get('articleid');
	$newwindow= $params->get('newwindow');
	$title= $params->get('title');
	 $check= $params->get('checkbox');
	 if($check=='checked')
	 {
	$terms_of_use="<input name='terms' type='checkbox' checked='checked' id='formagree' />&nbsp<a href='index.php?option=com_content&view=article&id=$articleid' target='$newwindow'>$title</a><br>";
		
	 }
	 else
	 $terms_of_use="<input name='terms' class='inputbox required' type='checkbox' id='formagree' /><a href='index.php?option=com_content&view=article&id=$articleid' target='$newwindow'> $title </a><br>";
	
	if ($tou==1) {
	echo $terms_of_use;
	 } 
	 else 
	 {
	
		}
	?>

  <?php
 if ($params->get('enablecap') ) :
      if($publickey && $privatekey):
                    $theme= jm_getthem($params);
                    echo recaptcha_get_html($publickey, $error, $theme);
                    echo'<div style="height:130px; margin:0px; padding0px;"> </div>';
      else: echo '<div style="color:red;font-weight:bold; margin:0px; padding0px;">'.JText::_( 'ENTER_VALID_KEYS').'</div>';
      endif;
 endif; ?>
 
<BR/> <input type="submit" name="Submit" class="button validate" value="<?php echo JText::_('Register') ?>" /><BR/>
					<input type="hidden" name="task" value="register_save" />
					<input type="hidden" name="id" value="0" />
					<input type="hidden" name="gid" value="0" />
					<?php echo JHTML::_( 'form.token' ); ?>
				</form>

</div>

 <?php 
 $usersConfig = &JComponentHelper::getParams( 'com_users' );
  if($flags && $usersConfig->get('allowUserRegistration') && $type != 'logout' ) :
  $check= $params->get('checkbox1'); 
 if($check== 0) {?>

  <div style="margin:0px;" id="login-form">
 <p style="padding:10px 0 0 20px;">
   <input type="radio" onclick="xi('y')"  name="group1" <?php if($params->get('view')==0) echo 'checked="checked"';?>   /> <?php echo JText::_('LOGIN') ?><br/>
<input type="radio" onclick="xi('n')"  name="group1" <?php if($params->get('view')==1) echo 'checked="checked"';?>  /><?php echo JText::_('REGISTER'); ?><br/>
   </p>
   </div>
   <?php }
?>
<?php 
endif; ?>

 <div id="myDiv" style="color: #CF1919;  font-weight: bold;   margin: 0 0 0 20px;   padding: 0 0 0 20px; "></div>



<?php endif; ?>