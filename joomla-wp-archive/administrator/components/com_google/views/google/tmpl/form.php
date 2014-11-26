<?php defined('_JEXEC') or die('Restricted access'); ?>
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
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>
		<div style="position:fixed; right:50px; border:1px solid #666666; background-color:#ccc; height:250px; overflow:scroll;">
		<table  class="admintable">
		<tr>
			<td>Width Between Info and contact Form</td>
			<td><input class="text_area" type="text" name="mapWidthOfForm" id="mapWidthOfForm" size="32" maxlength="250" value="<?php echo $this->google->mapWidthOfForm;?>" />
			</td>
		</tr>
		<tr>
			<td>Admin Send Mail address</td>
			<td><input class="text_area" type="text" name="adminMailAdress" id="adminMailAdress" size="32" maxlength="250" value="<?php 
			if($this->google->adminMailAdress == '')
			{
				echo "info@".$_SERVER['HTTP_HOST'];
			}
			else
			{
				echo $this->google->adminMailAdress;
			}
			
			?>" />
			</td>
		</tr>
			<tr style="padding:5px;">
				<td colspan="2">
				<a href="http://joomlasrilanka.blogspot.com/" target="_blank">Joomla Blog</a> |
				<a href="http://joomlacomponent.inetlanka.com/" target="_blank">Joomla Component</a> |
				<a href="http://hotellanka.lk/" target="_blank">Hotel Sri Lanka</a>
				</td>
			</tr>
			
				<tr>
					<td>Your Name</td>
					<td><input class="text_area" type="text" name="mapEnterYourNameForm" id="mapEnterYourNameForm" size="32" maxlength="250" value="<?php echo $this->google->mapEnterYourNameForm;?>" /></td>
				</tr>
				<tr>
					<td>E-mail address: </td>
					<td><input class="text_area" type="text" name="mapEnterEmailForm" id="mapEnterEmailForm" size="32" maxlength="250" value="<?php echo $this->google->mapEnterEmailForm;?>" /></td>
				</tr>
				<tr>
					<td>Message Subject: </td>
					<td><input class="text_area" type="text" name="mapEnterSubForm" id="mapEnterSubForm" size="32" maxlength="250" value="<?php echo $this->google->mapEnterSubForm;?>" /></td>
				</tr>
				<tr>
					<td>Enter your Message: </td>
					<td><input class="text_area" type="text" name="mapEnterMessForm" id="mapEnterMessForm" size="32" maxlength="250" value="<?php echo $this->google->mapEnterMessForm;?>" /></td>
				</tr>
				<tr>
					<td>Type here Spam image</td>
					<td><input class="text_area" type="text" name="mapEnterSpameForm" id="mapEnterSpameForm" size="32" maxlength="250" value="<?php echo $this->google->mapEnterSpameForm;?>" /></td>
				</tr>	
				<tr>
					<td> E-mail a copy</td>
					<td><input class="text_area" type="text" name="mapEnterEmailCopForm" id="mapEnterEmailCopForm" size="32" maxlength="250" value="<?php echo $this->google->mapEnterEmailCopForm;?>" /></td>
				</tr>
				<tr>
					<td>Button Send</td>
					<td><input class="text_area" type="text" name="mapEnterBtnForm" id="mapEnterBtnForm" size="32" maxlength="250" value="<?php echo $this->google->mapEnterBtnForm;?>" /></td>
				</tr>
				<tr>
					<td>Thanks Text</td>
					<td><input class="text_area" type="text" name="thanksTxt" id="thanksTxt" size="32" maxlength="250" value="<?php echo $this->google->thanksTxt;?>" /></td>
				</tr>				
			</table>
			
			
			<div>
				<script type="text/javascript"><!--
				google_ad_client = "pub-5651595635439260";
				google_ad_host = "pub-1556223355139109";
				/* 300x250, created 6/30/10 */
				google_ad_slot = "4036999007";
				google_ad_width = 300;
				google_ad_height = 250;
				//-->
				</script>
				<script type="text/javascript"
				src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
				</script>
			</div>
			
		</div>
		<table class="admintable">
		<tr>
			<td colspan="2">
			<?php 
			if($this->google->apiKey == NULL)
			{
				echo "<b>This is first time you are comming to this area so, First you have to save your <a href='http://code.google.com/apis/maps/signup.html' target=_blank>API Key</a><br> then after you can your Map here<b>";
			}
			?>
			
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="greeting">
					<?php echo JText::_( 'Company Name'); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="greeting" id="greeting" size="32" maxlength="250" value="<?php echo $this->google->greeting;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="greeting">
					<?php echo JText::_( 'Google Map API key'); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="apiKey" id="apiKey" size="32" maxlength="250" value="<?php echo $this->google->apiKey;?>" />
				<a href="http://code.google.com/apis/maps/signup.html" target="_blank">Get API Key</a>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="greeting">
					<?php echo JText::_( 'Map Width'); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="mapWidth" id="mapWidth" size="32" maxlength="250" value="<?php echo $this->google->mapWidth;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="greeting">
					<?php echo JText::_( 'Map Height'); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="mapHeight" id="mapHeight" size="32" maxlength="250" value="<?php echo $this->google->mapHeight;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key" valign="top">
				<label for="">
					<input class="text_area" type="text" name="mapEmailTxtBox" id="mapEmailTxtBox" size="32" maxlength="250" value="<?php echo $this->google->mapEmailTxtBox;?>" />
				</label>
			</td>
			<td>
			<input class="text_area" type="text" name="mapEmail" id="mapEmail" size="32" maxlength="250" value="<?php echo $this->google->mapEmail;?>" />
				
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key" valign="top">
				<label for="">
					<input class="text_area" type="text" name="mapFaxTxtBox" id="mapFaxTxtBox" size="32" maxlength="250" value="<?php echo $this->google->mapFaxTxtBox;?>" />
				</label>
			</td>
			<td>
			<input class="text_area" type="text" name="mapFax" id="mapFax" size="32" maxlength="250" value="<?php echo $this->google->mapFax;?>" />
				
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key" valign="top">
				<label for="">
					<input class="text_area" type="text" name="mapTpTxtBox" id="mapTpTxtBox" size="32" maxlength="250" value="<?php echo $this->google->mapTpTxtBox;?>" />
				</label>
			</td>
			<td>
			<input class="text_area" type="text" name="mapTp" id="mapTp" size="32" maxlength="250" value="<?php echo $this->google->mapTp;?>" />
				
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key" valign="top">
				<label for="">
					<input class="text_area" type="text" name="mapPhoneTxtBox" id="mapPhoneTxtBox" size="32" maxlength="250" value="<?php echo $this->google->mapPhoneTxtBox;?>" />
				</label>
			</td>
			<td>
			<input class="text_area" type="text" name="mapPhone" id="mapPhone" size="32" maxlength="250" value="<?php echo $this->google->mapPhone;?>" />
				
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key" valign="top">			
			</td>
			<td>			
			<textarea class="text_area" cols="50" rows="5" name="defaultTxt" id="defaultTxt"><?php echo $this->google->defaultTxt;?></textarea>	
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key" valign="top">			
			</td>
			<td>			
			<textarea class="text_area" cols="50" rows="5" name="defaultTxtBox" id="defaultTxtBox"><?php echo $this->google->defaultTxtBox;?></textarea>	
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key" valign="top">
				<label for="">
					<?php echo JText::_( 'Address'); ?>:
				</label>
			</td>
			<td>
			<textarea class="text_area" cols="50" rows="5" name="mapAddress" id="mapAddress"><?php echo $this->google->mapAddress;?></textarea>
				
			</td>
		</tr>
		
			<td width="100" align="right" class="key" valign="top">
				<label for="">
					<?php echo JText::_( 'Latitude'); ?>:
				</label>
			</td>
			<td>
			<input class="text_area" type="text" readonly=""  name="mapLatitude" id="mapLatitude" size="32" maxlength="250" value="<?php if(trim($this->google->mapLatitude) == "" ) {echo $user_lat = 79.9286111;} else {echo $this->google->mapLatitude; } ?>" />
				
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key" valign="top">
				<label for="">
					<?php echo JText::_( 'Longitude'); ?>:
				</label>
			</td>
			<td>
			<input class="text_area" type="hidden" name="placeDBox" id="placeDBox" size="32" maxlength="250" value="<?php echo $this->google->placeDBox;?>" />
			<input class="text_area" type="hidden" name="moreDBox" id="moreDBox" size="32" maxlength="250" value="<?php echo $this->google->moreDBox;?>" />
			<input class="text_area" type="text" readonly="" name="mapLongitude" id="mapLongitude" size="32" maxlength="250" value="<?php if($this->google->mapLongitude == ""  ){ echo $user_lan = 6.6641667;} else { echo $this->google->mapLongitude; }?>" />
				
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key" valign="top">
				<label for="">
					<?php echo JText::_( 'Map View'); ?>:
				</label>
			</td>
			<td>
			
			<select name="mapView" id="mapView">
			<?php
			$selectedVal = "";
			$saveHi = $this->google->mapView;
			$mapHightArr = array("","PHYSICAL","NORMAL","SATELLITE","HYBRID");
			$mapDisHightArr = array("Select One","PHYSICAL","NORMAL","SATELLITE","HYBRID");
			$selectedVal = "";
			for($m=0; $m < count($mapHightArr); $m++)
			{
				$selectedVal = "";
				if($mapHightArr[$m] == $saveHi)
				{
					$selectedVal ='selected="selected"';
					//echo  $saveHi.$mapHightArr[$m];
				
				}
				echo "<option value=".$mapHightArr[$m]." ".$selectedVal.">".$mapDisHightArr[$m]."</option><br>";
			}
			
			?>
					
			</select>
				
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key" valign="top">
				<label for="">
					<?php echo JText::_( 'Map View Height'); ?>:
				</label>
			</td>
			<td>
			<select name="mapViewHeight" id="mapViewHeight">
			<?php
			$selectedVal = "";
			$saveHi = $this->google->mapViewHeight;
			$mapHightArr = array("","18","12","14","1","2","3","4","5","6","7","8","9","10","11","13","15","16","17","19","20","21","22","23","24","25");
			$mapDisHightArr = array("Select View Height","200 ft","2 mi","900 me","1","2","3","4","5","6","7","8","9","10","11","13","15","16","17","19","20","21","22","23","24","25");
			$selectedVal = "";
			for($m=0; $m < count($mapHightArr); $m++)
			{
				$selectedVal = "";
				if($mapHightArr[$m] == $saveHi)
				{
					$selectedVal ='selected="selected"';
					//echo  $saveHi.$mapHightArr[$m];
				
				}
				echo "<option value=".$mapHightArr[$m]." ".$selectedVal.">".$mapDisHightArr[$m]."</option><br>";
			}
			
			?>
			
				</select>
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key" valign="top">
				<label for="">
					<?php echo JText::_( 'Point Image'); ?>:
				</label>
			</td>
			<td>
			<input class="text_area" type="text" name="mapPointImg" id="mapPointImg" size="32" maxlength="250" value="<?php echo $this->google->mapPointImg;?>" />
				
			</td>
		</tr>
		<tr>
		<tr>
			<td width="100" align="right" class="key" valign="top">
				<label for="">
					<?php echo JText::_( 'Company Video Profile (youtube code)'); ?>:
				</label>
			</td>
			<td>
			<input class="text_area" type="text" name="companyVideoProfile" id="companyVideoProfile" size="32" maxlength="250" value="<?php echo $this->google->companyVideoProfile;?>" />
				
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key" valign="top">
				<label for="">
					<?php echo JText::_( 'Do you need to add SPAM checking'); ?>:
				</label>
			</td>
			<td>
		
			<select name="companySpamcheck" id="companySpamcheck">
			<?php
			$selectedVal = "";
			$saveSpam = $this->google->companySpamcheck;
			$mapSpamArr = array("","1","0");
			$mapSpamTxtArr = array("Select Spam or not","Yes","No");
			for($m=0; $m < count($mapSpamArr); $m++)
			{
				$selected = "";
				if($mapSpamArr[$m] == $saveSpam)
				{
					$selectedVal ='selected="selected"';
				
				}
				else
				{
					$selectedVal="";
				}
				
				
				echo "<option value=".$mapSpamArr[$m]." ".$selectedVal.">".$mapSpamTxtArr[$m]."</option>";
			}
			
			?>
				</select>
			
				
			</td>
		</tr>
		<tr>
			<td colspan="2">
			
			<hr />
			3D map area
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key" valign="top">
				<label for="">
					<?php echo JText::_( 'is your country allow  3D Map'); ?>:
				</label>
			</td>
			<td>
		
			<select name="map3dview" id="map3dview">
			<?php
			$selectedVal = "";
			$save3dView = $this->google->map3dview ;
			$map3dViewArr = array("","1","0");
			$map3dViewTxtArr = array("3d allow","Yes","No");
			for($m=0; $m < count($map3dViewArr); $m++)
			{
				$selected = "";
				if($map3dViewArr[$m] == $save3dView)
				{
					$selectedVal ='selected="selected"';
				
				}
				else
				{
					$selectedVal="";
				}
				
				
				echo "<option value=".$map3dViewArr[$m]." ".$selectedVal.">".$map3dViewTxtArr[$m]."</option>";
			}
			
			?>
				</select>
			
				
			</td>
		</tr>
		
		
		</tr>
		
			<td width="100" align="right" class="key" valign="top">
				<label for="">
					<?php echo JText::_( '3d map width'); ?>:
				</label>
			</td>
			<td>
			<input class="text_area" type="text"  name="map3dWidth" id="map3dWidth" size="32" maxlength="250" value="<?php echo $this->google->map3dWidth; ?>" />
				
			</td>
		</tr>
		
		</tr>
		
			<td width="100" align="right" class="key" valign="top">
				<label for="">
					<?php echo JText::_( '3d map height'); ?>:
				</label>
			</td>
			<td>
			<input class="text_area" type="text" name="map3dHeight" id="map3dHeight" size="32" maxlength="250" value="<?php echo $this->google->map3dHeight; ?>" />
				
			</td>
		</tr>
		
		
		</tr>
		
			<td width="100" align="right" class="key" valign="top">
				<label for="">
					<?php echo JText::_( '3d map Yaw'); ?>: eg:150,180,325,180.25
				</label>
			</td>
			<td>
			<input class="text_area" type="text"  name="mapYaw" id="mapYaw" size="32" maxlength="250" value="<?php echo $this->google->mapYaw; ?>" />
				
			</td>
		</tr>
		
		
		</tr>
		
		<td width="100" align="right" class="key" valign="top">
				<label for="">
					<?php echo JText::_( '3d map mapPitch'); ?>: eg:-10,-20
				</label>
			</td>
			<td>
			<input class="text_area" type="text"  name="mapPitch" id="mapPitch" size="32" maxlength="250" value="<?php echo $this->google->mapPitch; ?>" />
				
			</td>
		</tr>
		
		
		
		
		<tr>
			
			<td colspan="2">
			
			<table border="0" cellpadding="4" cellspacing="5">
			<tr>
				<Td>
				<b><i>
				way to point your own locaiton
				<ul>
					<li>1. Put site relevent google API key</li>
					<li>2. Find you own location</li>
					<li>3. Click on the map</li>
					<li>4. Then it pop up form</li>
					<li>5. Fill the form and click save button - automaticaly set </li>
					<li>6. <a href="http://joomlacomponent.inetlanka.com">help</a></li>
					<li>7. For more details watch the video at the above site</li>
				</ul>
				</i></b>
				</Td>
			</tr>
			
			<tr>
			<td valign="top">
			<?php 
			if($this->google->apiKey == NULL)
			{
				echo "This is first time you are comming to this area so, First you have to save your <a href='http://code.google.com/apis/maps/signup.html' target=_blank>API Key</a><br> then after you can your Map here";
			}
			else
			{
			$apiKeyVal = $this->google->apiKey;
			
			$showGreetingTxt = $this->google->greeting;
			$showMapAddressTxt = $this->google->mapAddress;
			$showGreeting = $showGreetingTxt;
			$showMapAddress = $showGreetingTxt;
			
			if(trim($this->google->mapLongitude) == "" AND trim($this->google->mapLatitude) == "" )
			{
				$user_lan = 6.6641667;
				$user_lat = 79.9286111;
			}
			else
			{
				$user_lan = $this->google->mapLongitude;
				$user_lat = $this->google->mapLatitude;
			}
			
			if($this->google->mapViewHeight == NULL)
			{
				$mapViewHeight = "18";
			}
			else
			{
				$mapViewHeight = $this->google->mapViewHeight;
			}
			
			if($this->google->mapView == NULL)
			{

				$mapView = "SATELLITE";
			}
			else
			{
				$mapView = $this->google->mapView;
			}
			if($this->google->mapPointImg == NULL)
			{
				$imgDis = "";
			}
			else
			{
				$imgDis = "<img src=".$this->google->mapPointImg." width='50' height='50'/>";
			}
			
			?>
			
			
<style>

#home_town
{
	width:400px;
	height:250px;
	border:1px solid #000;
	overflow:hidden;
	clear:both;
}
</style>
			<div id="home_town"><!-- --></div>
			<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $apiKeyVal; ?>" type="text/javascript"></script>
                     	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $apiKeyVal; ?>" type="text/javascript"></script>
            <script src="http://www.google.com/uds/api?file=uds.js&v=1.0&key=<?php echo $apiKeyVal; ?>" type="text/javascript"></script>
        
<script language="javascript" >
						
									
										
	var map = new GMap(document.getElementById("home_town"));
    var recording_flag = 1;
    var x_array = new Array(0);
    var y_array = new Array(0);
    var segment_distance_array = new Array(0);
    var total_distance_array = new Array(0);
	var preLatTxt  ="";
	var preLonTxt  ="";
	var preConcatLocName ="";
	

    var inetLatLng = new GLatLng("<?php echo $user_lan; ?>", "<?php echo $user_lat; ?>");
	var zoomFactor = 14;
	
	map.setCenter(inetLatLng, zoomFactor);
	map.setMapType(G_HYBRID_MAP);
	map.addControl(new GSmallMapControl());
    map.addControl(new GMapTypeControl());
	
	
	var pinIcon = new GIcon(G_DEFAULT_ICON);
	pinIcon.image = "<?php echo JURI::Base(); ?>components/com_google/asset/img/mappin.png";
	pinIcon.iconSize = new GSize(20, 32);

	//Create the marker
	markerOptions = {title:'<?php echo $this->google->placeDBox; ?>', icon:pinIcon};
	var mapMarker = new GMarker(inetLatLng, markerOptions);
	map.addOverlay(mapMarker);

	GEvent.addListener(map, 'click', 
		function(overlay, point) {
		    if (point) {
			if (recording_flag > 0) {
			    x_array.push(point.x);
			    y_array.push(point.y);
			   // drawRoute();

			    document.getElementById('mapLongitude').value = point.y; 
			    document.getElementById('mapLatitude').value = point.x;
	
				
				var inputForm = document.createElement("form");
				inputForm.setAttribute("action","");
				inputForm.setAttribute("method","post");
				inputForm.onsubmit = function() {storeMarker(); return false;};
				//retrieve the longitude and lattitude of the click point
				var lng = point.lng();
				var lat = point.lat();
				inputForm.innerHTML = '<fieldset style="width:150px;">'
				+ '<legend>Next Point</legend>'
				+ '<label for="found">Place</label>'
				+ '<input type="text" id="place" value="<?php echo $this->google->placeDBox; ?>" style="width:100%;"/>'
				+ '<label for="found">More Details</label>'
				+ '<input type="text" id="moreD" value="<?php echo $this->google->moreDBox; ?>" style="width:100%;"/>'
				+ '<label for="left"></label>'
				+ '<input type="hidden" id="left" style="width:100%;"/>'
				+ '<input type="submit" value="Save"/>'
				+ '<input type="hidden" id="longitude" value="' + lng + '"/>'
				+ '<input type="hidden" id="latitude" value="' + lat + '"/>'
				+ '</fieldset>';
				map.openInfoWindow (point,inputForm); 
				


			}
		    }
			

		}
	    );   // end of GEvent.addListener

	
	

function storeMarker(){

	var lng = document.getElementById("longitude").value;
	var lat = document.getElementById("latitude").value;
	var disTxt = document.getElementById("place").value;
	
	document.getElementById("placeDBox").value = document.getElementById("place").value;
	document.getElementById("moreDBox").value = document.getElementById("moreD").value;
	
	var latlng = new GLatLng(parseFloat(lat),parseFloat(lng));
	
	
	var marker = new GMarker(latlng);
	
	
	GEvent.addListener(marker, 'click', function() {
	var markerHTML = disTxt;
	marker.openInfoWindowHtml(markerHTML);
	});
	map.addOverlay(marker);
	
	
	map.closeInfoWindow();
 



return false;



}								
			
			

</script>
			<?php
			}
			?>	

			
			</td>
			<td valign="top">
			<?php
			if($this->google->companyVideoProfile == NULL)
			{
				$videoDis = "";
			}
			else
			{
				$videoDis = '<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/'.$this->google->companyVideoProfile.'&hl=en&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'.$this->google->companyVideoProfile.'&hl=en&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object>';
			}
			?>
				
				<?php echo $videoDis; ?>
			</td>
		</tr>
		</table>
		</td>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_google" />
<input type="hidden" name="id" value="<?php echo $this->google->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="google" />
</form>
