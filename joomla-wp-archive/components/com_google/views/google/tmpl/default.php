<?php 
/**
 * Google  Map default controller
 * 
 * @package    Joomla.component
 * @subpackage Components
 * @link http://inetlanka.com
 * @license		GNU/GPL
 * @auth inetlanka web team - [ info@inetlanka.com / inetlankapvt@gmail.com ]
 */
defined('_JEXEC') or die('Restricted access');



$conArr = $this->options;

$user_lan = $conArr[0]->mapLongitude;
$user_lat = $conArr[0]->mapLatitude;
$apiKeyVal = $conArr[0]->apiKey;

$apiWidth = $conArr[0]->mapWidth;
$apiHeight = $conArr[0]->mapHeight;
$api3dWidth = $conArr[0]->map3dWidth;
$api3dHeight = $conArr[0]->map3dHeight;


$apiComName = $conArr[0]->greeting;

$map3dview = $conArr[0]->map3dview;
$mapYaw =  $conArr[0]->mapYaw;
$mapPitch =  $conArr[0]->mapPitch;

$googleVideo = $conArr[0]->companyVideoProfile;
$imgDis  = $conArr[0]->mapPointImg ;
if($imgDis == NULL)
{
	$imgDis = "";
}
else
{
	$imgDis = "<img src=".$conArr[0]->mapPointImg." width='50' height='50'/>";
}

if($conArr[0]->mapViewHeight == NULL)
{
	$mapViewHeight = "18";
}
else
{
	$mapViewHeight = $conArr[0]->mapViewHeight;
}

if($conArr[0]->mapView == NULL)
{
	$mapView = "SATELLITE";
}
else
{
	$mapView = $conArr[0]->mapView;
}

	function generateCode($characters) {
	
			$possible = '987654321AbcdEFghJkMnpqrsTvwxYz';
	
			$code = '';
	
			$i = 0;
	
			while ($i < $characters) { 
	
				$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
	
				$i++;
	
			}
			
			
			
			
			return $code;
	
		}
		
	


$spamStatus = $conArr[0]->companySpamcheck;
$randSpamCode = generateCode(4);

///new

 ?>
<div>

<script language="javascript" >

	function comGoogleFrmValidate(comfrm)
		{
			
			var errorStr='';	
			my_name = document.comGoogleForm.myName;
			my_email = document.comGoogleForm.myEmail;
			mess_heading = document.comGoogleForm.messHeading;	
			messate_txt = document.comGoogleForm.messateTxt;	
			
			
			
			if(my_name.value == '')
				{
					errorStr += "<?php echo JText::_( 'GOOGLE_JS_NAME' ); ?>\n";
					my_name.style.borderColor  = "#FF0000";

				}
				
			if(my_email.value == '')
				{
					errorStr += "<?php echo JText::_( 'GOOGLE_JS_MYEMAIL' ); ?>\n";
					my_email.style.borderColor  = "#FF0000";
				}
			if(my_email.value!='')
				{
					
					if(echeck(my_email.value) == false)
					{
						errorStr += "<?php echo JText::_( 'GOOGLE_JS_VALEMAIL' ); ?>\n";
						my_email.style.borderColor  = "#FF0000";
					}
					
				}
				
			if(mess_heading.value == '')
				{
					errorStr += "<?php echo JText::_( 'GOOGLE_JS_MAILHEAD' ); ?>\n";
					mess_heading.style.borderColor  = "#FF0000";
				}
			if(messate_txt.value == '')
				{
					errorStr += "<?php echo JText::_( 'GOOGLE_JS_MAILTEXT' ); ?>\n";
					messate_txt.style.borderColor  = "#FF0000";
				}
				
			<?php
			
			if($spamStatus == "1")
			{
			?>
			messSpam_txt = document.comGoogleForm.messSpamtxt;		
			if(messSpam_txt.value == '')
				{
					errorStr += "<?php echo JText::_( 'GOOGLE_JS_MAILSPAM' ); ?>\n";
					messSpam_txt.style.borderColor  = "#FF0000";
				}
			if(messSpam_txt.value != '')
				{
					var spamTxtsend = "<?php echo $randSpamCode; ?>";
					if(messSpam_txt.value != spamTxtsend)
					{
						errorStr += "<?php echo JText::_( 'GOOGLE_JS_SPAMVAL' ); ?>\n";
						messSpam_txt.style.borderColor  = "#FF0000";
					}
					
				}
			<?php
			}
			?>
				
		
			if(errorStr=='')
				{
					return true;
				}
			else
				{
					alert(errorStr);
					return false;
				}			  
		}
	
	
	
	function echeck(str)
	   {
	   		
			var at="@"
			var dot="."
			var lat=str.indexOf(at)
			var lstr=str.length
			var ldot=str.indexOf(dot)
			
			if (str.indexOf(at)==-1){			  
			   return false
			}
	
			if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){		   
			   return false
			}
	
			if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){		   
				return false
			}
	
			 if (str.indexOf(at,(lat+1))!=-1){				
				return false
			 }
	
			 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){				
				return false
			 }
	
			 if (str.indexOf(dot,(lat+2))==-1){			  
				return false
			 }
		
	
			 return true
	   }

</script>
	
<style>

#google_map
{
	width:<?php echo $apiWidth; ?>px;
	height:<?php echo $apiHeight; ?>px;
	border:1px solid #000;
	overflow:hidden;
	clear:both;
	border:1px #000000 solid;
}
#mapthreed
{
	width:<?php echo $api3dWidth; ?>px;
	height:<?php echo $api3dHeight; ?>px;
	border:1px solid #000;
	overflow:hidden;
	clear:both;
	border:1px #000000 solid;
	
}
</style>
 <table border="0" cellpadding="4" cellspacing="5">
 	<tr>
		<td valign="top" width="<?php echo $conArr[0]->mapWidthOfForm; ?>">
			<div align="left">
				<p><strong><?php echo $conArr[0]->greeting; ?></strong></p>  
				<p><?php echo nl2br($conArr[0]->mapAddress); ?></p>
				
				<?php
				if($conArr[0]->mapTpTxtBox != '')
				{
				?>
					<p><label id="contact_emailmsg" for="contact_email">
					&nbsp;<?php echo $conArr[0]->mapTpTxtBox; ?>
					</label><?php echo $conArr[0]->mapTp; ?></p>
				<?php
				}
				if($conArr[0]->mapPhoneTxtBox != '')
				{
				?>
				
					<p><label id="contact_emailmsg" for="contact_email">
					&nbsp;<?php echo $conArr[0]->mapPhoneTxtBox; ?>
					</label> <?php echo $conArr[0]->mapPhone; ?></p>
				<?php
				}
				if($conArr[0]->mapFaxTxtBox != '')
				{
				?>	
					<p><label id="contact_emailmsg" for="contact_email">
					&nbsp;<?php echo $conArr[0]->mapFaxTxtBox; ?>
					</label><?php echo $conArr[0]->mapFax; ?></p>
				<?php
				}
				if($conArr[0]->mapEmailTxtBox != '')
				{
				?>
					<p><label id="contact_emailmsg" for="contact_email">
					&nbsp;<?php echo $conArr[0]->mapEmailTxtBox; ?>
					</label><a href="mailto:<?php echo $conArr[0]->mapEmail; ?>"> <?php echo $conArr[0]->mapEmail; ?></a></p>
				<?php
				}
				if($conArr[0]->defaultTxt != '')
				{
				?>					
					<p><?php echo nl2br($conArr[0]->defaultTxt); ?></p>
				<?php
				}
				if($conArr[0]->defaultTxtBox != '')
				{
				?>
					<p><?php echo nl2br($conArr[0]->defaultTxtBox); ?></p>
					
				<?php
				}				
				?>
			</div>
		</td>
		<td>
			<form action="index.php" method="post" name="comGoogleForm" id="comGoogleForm" onsubmit="return comGoogleFrmValidate(this)">
		
			<label id="contact_emailmsg" for="contact_email">
				&nbsp;<?php echo $conArr[0]->mapEnterYourNameForm; ?>
			</label><br />			
				<input type="text" name="myName" id="myName"  /><br />		
			<label id="contact_emailmsg" for="contact_email">
				&nbsp;<?php echo $conArr[0]->mapEnterEmailForm; ?>
			</label><br />		
				 <input type="text" name="myEmail" id="myEmail"  />
				 <input type="hidden" name="ourSendEmail" id="ourSendEmail" value="<?php echo $conArr[0]->adminMailAdress; ?>"  />
				 <input type="hidden" name="ourEmail" id="ourEmail" value="<?php echo $conArr[0]->mapEmail; ?>"  />
				 <input type="hidden" name="thanksTxt" id="thanksTxt" value="<?php echo $conArr[0]->thanksTxt; ?>"  />
				  <input type="hidden" name="RedirectLinkComGoogle" id="RedirectLinkComGoogle" value="<?php echo $_SERVER['REQUEST_URI']; ?>"  /><br />
		
			<label for="contact_subject">
				&nbsp;<?php echo $conArr[0]->mapEnterSubForm; ?>
			</label><br />		
				<input type="text" name="messHeading" id="messHeading"  /><br />			
			<label id="contact_textmsg" for="contact_text">
				&nbsp;<?php echo $conArr[0]->mapEnterMessForm; ?>
			</label><br />		
				<textarea name="messateTxt" id="messateTxt" rows="5" cols="30"></textarea><br />
			<?php
			
			if($spamStatus == "1")
			{
			?>	
			
				
				<?php echo "<br /><img src='".JURI::Base()."components/com_google/asset/captcha/captcha.php?spamCode=$randSpamCode' alt='' title='' />"; ?><br /><br />
				<input type="text" name="messSpamtxt" id="messSpamtxt"  />
			<label for="contact_subject">
				&nbsp;<?php echo $conArr[0]->mapEnterSpameForm; ?>
			</label>	
				
				<br />
			<?php
			}
			?>
                 <input type="checkbox" value="copyMail" name="copyOfmail" id="copyOfmail"  />
			
				<label for="contact_email_copy">
					<?php echo $conArr[0]->mapEnterEmailCopForm; ?>
				</label>
				<div align="left">
				<input type="submit" align="left" name="task_button" class="button" value="<?php echo $conArr[0]->mapEnterBtnForm; ?>" />
				</div>
				<input type="hidden" name="option" value="com_google" />
				<input type="hidden" name="task" value="sendMail" />
				<input type="hidden" name="id" value="<?php echo $conArr[0]->id;?>" />
				<input type="hidden" name="Itemid" value="<?php echo $_GET['Itemid'];?>" />
				<?php echo JHTML::_( 'form.token' ); ?>
			</form>
		</td>
	</tr>
	<tr><?php
		if($map3dview != "1")
		{
			$colTd = 'colspan="2" ';
		}
		?>
	
		<td valign="top" <?php echo $colTd; ?> style="padding-right:10px;">
		
		<div id="google_map"></div>
		
		</td>
		
		<?php
		if($map3dview == "1"){
		?>
		<td valign="top">
		<div name="mapthreed" id="mapthreed"><!-- --></div>
		</td>
		<?php
		}
		?>
		
	</tr>
		
		
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $apiKeyVal; ?>" type="text/javascript"></script>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $apiKeyVal; ?>" type="text/javascript"></script>
<script src="http://www.google.com/uds/api?file=uds.js&v=1.0&key=<?php echo $apiKeyVal; ?>" type="text/javascript"></script>	
        
<script language="javascript" >

		var localSearch = new GlocalSearch();		
		localSearch.setSearchCompleteCallback(null, 
				function() {initMap("<?php echo $user_lan; ?>","<?php echo $user_lat; ?>"); });	
					
				localSearch.execute(""+ ", ");	
				
		function initMap(lat, lng)
		{
	
		
			if (GBrowserIsCompatible()) 
			{
				var panoClient;
				  panoClient = new GStreetviewClient();  
				var map = new GMap2(document.getElementById("google_map"));
				map.setMapType(G_<?php echo $mapView; ?>_MAP);
				var inetLatLng = new GLatLng(lat, lng);
				var fenwayPOV = {yaw:<?php echo $mapYaw; ?>,pitch:<?php echo $mapPitch; ?>};
				//developer wapnishantha@gmail.com 03/09/2009 Sri Lanka
				var zoomFactor = <?php echo $mapViewHeight; ?>;
				
				
				var threeDview = "<?php echo $map3dview; ?>";
				if(threeDview == "1")
				{
				//Create the map and center 
				 // map.addControl(new GOverviewMapControl());
				  map.addControl(new GLargeMapControl3D());
				  map.setCenter(inetLatLng, zoomFactor);
				}
				else
				{
					map.addControl(new GSmallMapControl());
					map.addControl(new GMapTypeControl());
					map.addControl(new GScaleControl());
					 map.setCenter(inetLatLng, zoomFactor);
				}
				
				
				//Custom Icon
				var pinIcon = new GIcon(G_DEFAULT_ICON);
				pinIcon.image = "<?php echo JURI::Base(); ?>components/com_google/asset/img/mappin.png";
				pinIcon.iconSize = new GSize(20, 32);
			
				//Create the marker
				markerOptions = {title:'<?php echo $apiComName; ?>', icon:pinIcon};
				var mapMarker = new GMarker(inetLatLng, markerOptions);
				
				//Add click event for the marker
				GEvent.addListener(mapMarker, 'click', 
					function() {
						var address = "<span style='font-family:Arial;font-size:11px;'><b><?php echo $conArr[0]->placeDBox; ?></b><br/><?php echo $conArr[0]->moreDBox; ?><br /><?php echo $imgDis; ?><span> ";
						mapMarker.openInfoWindow(address);
					} ); 
					
					
					  
					  if(threeDview == "1")
					  {
						  myPano = new GStreetviewPanorama(document.getElementById("mapthreed"));
						  myPano.setLocationAndPOV(inetLatLng, fenwayPOV);
						  GEvent.addListener(myPano, "error", handleNoFlash);  
						  panoClient.getNearestPanorama(inetLatLng, showPanoData1);    
					  
					  }  
					 
					
				map.addOverlay(mapMarker);
			}	
		}
		
		
	function showPanoData(panoData) {
      if (panoData.code != 200) {
        return;
      }
      var displayString = [
        panoData.location.description
      ].join("");
      map.openInfoWindowHtml(panoData.location.latlng, displayString);
      myPano.setLocationAndPOV(panoData.location.latlng);
    }
    
    function showPanoData1(panoData) {
        return;
    }
    
    function handleNoFlash(errorCode) {
      if (errorCode == 603) {
        alert("Error: Flash doesn't appear to be supported by your browser");
        return;
      }
    }  
    
    function overlayDirections() {
	    fromAddress = document.getElementById("street").value;
	    var language  = document.getElementById("language").options[document.getElementById("language").selectedIndex].value; 
	    gdir.load("from: " + fromAddress + " to: " + toAddress, { "locale": language });
    }
    
    function toggleTraffic() {
      if (toggleState == 1) {
        map.removeOverlay(trafficInfo);
        toggleState = 0;
      } else {
        map.addOverlay(trafficInfo);
        toggleState = 1;
      }
    }

	function handleErrors(){
   		if (gdir.getStatus().code == G_GEO_UNKNOWN_ADDRESS) {
     		alert("No corresponding geographic location could be found for one of the specified addresses." 
     		+ " This may be due to the fact that the address is relatively new, or it may be incorrect.\nError code: " 
     		+ gdir.getStatus().code);
   		} else if (gdir.getStatus().code == G_GEO_SERVER_ERROR) {
	     	alert("A geocoding or directions request could not be successfully processed," 
	     	+ "yet the exact reason for the failure is not known.\n Error code: " 
	     	+ gdir.getStatus().code);
   		} else if (gdir.getStatus().code == G_GEO_MISSING_QUERY) {
     		alert("The HTTP q parameter was either missing or had no value. For geocoder requests, " 
     		+ "this means that an empty address was specified as input. For directions requests," 
     		+ "this means that no query was specified in the input.\n Error code: " 
     		+ gdir.getStatus().code);
   		} else if (gdir.getStatus().code == G_GEO_BAD_KEY) {
   			alert("The given key is either invalid or does not match the domain for which it was given. \n Error code: " 
   			+ gdir.getStatus().code);
   		} else if (gdir.getStatus().code == G_GEO_BAD_REQUEST) {
   			alert("A directions request could not be successfully parsed.\n Error code: " + gdir.getStatus().code);
   		} else {
   			alert("An unknown error occurred.");
   		}
}
		
	</script>

	<tr>
		<?php
		if($googleVideo !="")
		{
		
		?>
			<td valign="top" colspan="2">
			
				<object width="350" height="225"><param name="movie" value="http://www.youtube.com/v/<?php echo $googleVideo; ?>&hl=en&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/<?php echo $googleVideo; ?>&hl=en&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="350" height="225"></embed></object>
			</td>
		<?php
		}
		?>
	</tr>
 </table>
 

</div>
