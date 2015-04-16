<?php
/****
*  file:  site-3rdparty-settings
*
*
*****/

/** Google Analytics **/
$GAuid = 'UA-33192781-1';


/** Facebook **/
$facebookEmbed = '
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));</script>
';

/** ShareThis **/
$shareThisScriptsEmbed = '
<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "877f4031-157b-4bf0-a685-b126256f651b"});</script>
';

$shareThisIconBarEmbed = '
<div class="shareThisIconBar">
  <span class="st_sharethis_large" displayText="ShareThis"></span>
  <span class="st_facebook_large" displayText="Facebook"></span>
  <span class="st_twitter_large" displayText="Tweet"></span>
  <span class="st_linkedin_large" displayText="LinkedIn"></span>
  <span class="st_googleplus_large" displayText="Google +"></span>
  <span class="st_email_large" displayText="Email"></span>
</div><!--/#shareThisIconBar-->
';

?>

<style>
#tccTutorThemeBanner {
  background-size: contain;
  background-repeat: no-repeat;
  width:100%;
  height: 100%;
  display:block;
  z-index:-10;
  -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=80)";
  filter: alpha(opacity=70);
  opacity: 0.7;
  position:absolute;
  top: 0;
  left: 0;
}
</style>