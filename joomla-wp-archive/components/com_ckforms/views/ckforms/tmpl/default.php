<?php // no direct access

	defined('_JEXEC') or die('Restricted access'); 
	
	if ($this->ckforms->published != '1') return;

	$nbFields=count($this->ckforms->fields );
	
	$mandatory = false;
	$upload = false;
	$custominfo = false;
	$textareaRequired = false;
	for ($i=0;$i < $nbFields; $i++)
	{ 
		$field = $this->ckforms->fields[$i];
		if ($field->mandatory == "1") $mandatory = true;
		if ($field->typefield == "fileupload") $upload = true;
		if ($field->custominfo != "") $custominfo = true;
		if ($field->typefield == 'textarea' && $field->mandatory == '1' && $field->t_HTMLEditor == '1') $textareaRequired = true;
	}
	
?>

<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>"><?php echo $this->ckforms->title; ?></div>

<script type="text/javascript">

window.addEvent('domready', function(){
	var myTips = new Tips('.ckform_tooltip<?php echo $this->ckforms->id; ?>', {
		initialize:function(){
			this.fx = new Fx.Style(this.toolTip, 'opacity', {duration: 250, wait: false}).set(0);
		},
		onShow: function(toolTip) {
			this.fx.start(1);
		},
		onHide: function(toolTip) {
			this.fx.start(0);
		}
	});

<?php 
	if ($textareaRequired == true)
	{
?>		

	$('ckform<?php echo $this->ckforms->id; ?>').onsubmit = function(event){
<?php 
	for ($i=0;$i < $nbFields; $i++)
	{ 
		$field = $this->ckforms->fields[$i];
		if ($field->typefield == 'textarea' && $field->mandatory == 1 && $field->t_HTMLEditor == 1) {
?>
		if ($chk($('<?php echo $field->name; ?>')) && $chk($('<?php echo $field->name; ?>Cont'))) {
			$('<?php echo $field->name; ?>Cont').setProperty('value', tinyMCE.get('<?php echo $field->name; ?>').getContent());
		}
<?php 
		}
	}
?>		
	};

<?php 
	}
?>

	var myForm = new FormCheck('ckform<?php echo $this->ckforms->id; ?>', {
		fieldErrorClass : 'error',
		validateDisabled : true,
		display : {
			showErrors : 1,
			errorsLocation : 3,
			indicateErrors : 2,
			tipsPosition : 'right', 
			addClassErrorToField : true,
			scrollToFirst : true
		},
		alerts : {
			required:'<?php echo addslashes(JText::_( 'This field is required.' )); ?>',
			number:'<?php echo addslashes(JText::_( 'Please enter a valid number.' )); ?>',
			email:'<?php echo addslashes(JText::_( 'Please enter a valid email: <br /><span>E.g. yourname&#64;domain.com</span>' )); ?>',
			url:'<?php echo addslashes(JText::_( 'Please enter a valid url: <br /><span>E.g. http://www.domain.com</span>' )); ?>',
			confirm:'<?php echo addslashes(JText::_( 'This field is different from %0' )); ?>',
			length_str:'<?php echo addslashes(JText::_( 'The length is incorrect, it must be between %0 and %1' )); ?>',
			lengthmax:'<?php echo addslashes(JText::_( 'The length is incorrect, it must be at max %0' )); ?>',
			lengthmin:'<?php echo addslashes(JText::_( 'The length is incorrect, it must be at least %0' )); ?>',
			checkbox:'<?php echo addslashes(JText::_( 'Please check the box' )); ?>',
			radios:'<?php echo addslashes(JText::_( 'Please select a radio' )); ?>',
			select:'<?php echo addslashes(JText::_( 'Please choose a value' )); ?>'
		}

	})

	$(document.body).getElements('.captcharefresh').addEvents({
		'click': function(){
			if($chk($('captchacode'))) { 
				$('captchacode').setProperty('src', 'index.php?option=com_ckforms&task=captcha&sid=' + Math.random());
			}
		}
	});

<?php 
	for ($i=0;$i < $nbFields; $i++)
	{ 
		$field = $this->ckforms->fields[$i];
		if ($field->typefield == 'text' && $field->t_texttype == 'date') {
			
			// Chercher le format de la locale du l'utilisateur
			$dformat = "d/m/Y";
			if ($field->d_format != '0')
			{
				if ($field->d_format =='EUR')
				{
					$dformat = "d/m/Y";
				} else {
					$dformat = "m/d/Y";
				}
			}
?>
			var myCal<?php echo $field->id; ?> = new Calendar(
				{<?php echo $field->name; ?>: '<?php echo $dformat; ?>'},
				{ 
					days: ['<?php echo addslashes(JText::_( 'Sunday' )); ?>','<?php echo addslashes(JText::_( 'Monday' )); ?>','<?php echo addslashes(JText::_( 'Tuesday' )); ?>','<?php echo addslashes(JText::_( 'Wednesday' )); ?>','<?php echo addslashes(JText::_( 'Thursday' )); ?>','<?php echo addslashes(JText::_( 'Friday' )); ?>','<?php echo addslashes(JText::_( 'Saturday' )); ?>'], 
					months: ['<?php echo addslashes(JText::_( 'January' )); ?>','<?php echo addslashes(JText::_( 'February' )); ?>','<?php echo addslashes(JText::_( 'March' )); ?>','<?php echo addslashes(JText::_( 'April' )); ?>','<?php echo addslashes(JText::_( 'May' )); ?>','<?php echo addslashes(JText::_( 'June' )); ?>','<?php echo addslashes(JText::_( 'July' )); ?>','<?php echo addslashes(JText::_( 'August' )); ?>','<?php echo addslashes(JText::_( 'September' )); ?>','<?php echo addslashes(JText::_( 'October' )); ?>','<?php echo addslashes(JText::_( 'November' )); ?>','<?php echo addslashes(JText::_( 'December' )); ?>'],
					direction: 0,
					navigation: 2,
					tweak: {x: 6, y: 0}
				}
			);
<?php 
		}
	}
?>	
	
});

</script>

<table class="contentpaneopen<?php echo $this->params->get( 'pageclass_sfx' ); ?>" id="ckformcontainer">
<tr><td>
  <?php if (strcmp ( $this->ckforms->description , "" ) != 0) { ?>
	<p><?php echo $this->ckforms->description; ?></p>
  <?php } ?>

<?php 
	if ($mandatory == true)
	{
?>
	<p class="ck_mandatory"><?php echo JText::_( 'Required' ); ?> *</p>
<?php } ?>

	<form action="<?php echo $this->formLink; ?>" method="post" name="ckform" id="ckform<?php echo $this->ckforms->id; ?>" class="ckform <?php echo $this->ckforms->formCSSclass; ?>"<?php if($upload == true) { ?> enctype="multipart/form-data"<?php } ?>>
    
        <input name="id" id="id" type="hidden" value="<?php echo $this->ckforms->id; ?>" />

<?php if($upload == true) { ?>
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $this->ckforms->maxfilesize; ?>"" />
<?php } 
 
	for ($i=0;$i < $nbFields; $i++)
	{ 
		$field = $this->ckforms->fields[$i];
		if ($field->typefield == "hidden")
		{
?>
        <input name="<?php echo $field->name; ?>" id="<?php echo $field->name; ?>" type="hidden" value="<?php if ($field->t_filluid == "1") {echo uniqid($field->t_initvalueH,true);} else {echo $field->t_initvalueH;} ?>" />
<?php    
		}
	}

	for ($i=0;$i < $nbFields; $i++)
	{ 
		$field = $this->ckforms->fields[$i];
		
		if ($field->typefield != "hidden" && $field->typefield != "button" && $field->typefield != "fieldsep")
		{
	
			$validationclass = "validate[";
									 
			if ($field->mandatory == "1") {
				$validationclass = $validationclass."'required',";
			}
			if ($field->typefield == 'text' || $field->typefield == 'textarea')
			{
				$min = "0";
				if ($field->t_minchar != '')
				{
					$min = $field->t_minchar;
				}
				$max = "-1";
				if ($field->t_maxchar != '')
				{
					$max = $field->t_maxchar;
				}
				if ($min != '0' || $max != '-1')
				{
					if ($field->typefield == 'text' && $field->t_texttype == 'number') 
					{
						$validationclass = $validationclass."'digit[".$min.",".$max."]',";
					} else {
						$validationclass = $validationclass."'length[".$min.",".$max."]',";
					}
				}
			}

			if ($field->typefield == 'text' && $field->t_texttype == 'email') {
				$validationclass = $validationclass."'email',";
			}
			/*
			else if ($field->typefield == 'text' && $field->t_texttype == 'number') {
				$validationclass = $validationclass."'number',";
				
			} 
			*/
			else if ($field->typefield == 'text' && $field->t_texttype == 'url') {
				$validationclass = $validationclass."'url',";
			}

			$validationclass = rtrim($validationclass,',')."]";						 
									 
?>       
		<label class="ckCSSlabel<?php if ($field->custominfo != "" && $field->typefield == "textarea") echo " ckCSSbot5"; ?> <?php echo $field->labelCSSclass; ?>" id="<?php echo $field->name."lbl"; ?>" for="<?php echo $field->name; ?>"> <?php echo $field->label; ?>
<?php 
	if ($field->mandatory == '1') 
	{ 
?>
    	&nbsp;<span class="ck_mandatory">*</span>
<?php 
	}
	if ($field->custominfo != "" && $field->typefield == "textarea") 
    {
?>       
			<img class="ckform_tooltip<?php echo $this->ckforms->id; ?> ckform_tooltipcss" src="<?php echo JURI::root(true).'/components/com_ckforms/'; ?>img/info.png" />
<?php
	}
?>       
        </label>
<?php
	switch ($field->typefield)
	{
		case 'text':
?>
<?php        		
		
		if ($field->t_texttype == 'text' || $field->t_texttype == 'number' || $field->t_texttype == 'email' || $field->t_texttype == 'url')
		{
?>
		<input type="text" name="<?php echo $field->name; ?>" value="<?php if (empty($this->post) ==false) {echo $this->post[$field->name];} else {echo $field->t_initvalueT;} ?>" class="<?php echo $validationclass; ?> inputbox ckCSSinput <?php if ($field->custominfo != "") {echo "ckform_tooltip".$this->ckforms->id." ckCSSTip ";}?> <?php echo $field->fieldCSSclass; ?>"  <?php if ($field->readonly == "1") {echo ' readonly="true"';} ?>  title="<?php echo $field->custominfo; ?>" />
<?php        		
		}
		else if ($field->t_texttype == 'password' )
		{
?>		
        <input type="password" name="<?php echo $field->name; ?>" value="<?php if (empty($this->post) ==false) {echo $this->post[$field->name];} else {echo $field->t_initvalueT;} ?>" class="<?php echo $validationclass; ?> inputbox ckCSSinput <?php if ($field->custominfo != "") {echo "ckCSSTip";} else {echo "ckCSSnoTip";} ?> <?php echo $field->fieldCSSclass; ?>" <?php if ($field->readonly == "1") {echo ' readonly="true"';} ?> />
<?php
		}
		else if ($field->t_texttype == 'date' )
		{
?>
		<input type="text" name="<?php echo $field->name; ?>" id="<?php echo $field->name; ?>" value="<?php if (empty($this->post) ==false) {echo $this->post[$field->name];} else {echo $field->t_initvalueT;} ?>" class="<?php echo $validationclass; ?> inputbox ckCSSinput <?php echo $field->fieldCSSclass; ?>" maxlength="10" <?php if ($field->readonly == "1") {echo ' readonly="true"';} ?> />
<?php	
		}
		break; 		

		case 'fileupload':
?>
		<input name="<?php echo $field->name; ?>" type="file" class="<?php echo $validationclass; ?> ckCSSinput <?php if ($field->custominfo != "") {echo "ckCSSTip";} else {echo "ckCSSnoTip";} ?> <?php echo $field->fieldCSSclass; ?>" <?php if ($field->readonly == "1") {echo ' readonly="true"';} ?> />
<?php
		break; 	
	
		case 'textarea':
			if ($field->t_HTMLEditor == 1 &&  $field->readonly != "1") 
			{	
?>
		
        <div class="ckCSSclear ckCSSbot10">
        		<input style="float: right; margin-right: 20px; height: 1px; visibility:hidden;" type="text" class="<?php echo $validationclass; ?>" name="<?php echo $field->name; ?>Cont" id="<?php echo $field->name; ?>Cont" value="" />
<?php
				$INIThtml = $field->t_initvalueTA;
				if (empty($this->post) ==false) 
				{
					$INIThtml = $this->post[$field->name];
				}				

				$editorDesc = JFactory::getEditor();
				$editor_param['smilies'] = '0';
				$editor_param['layer'] = '0';
				echo $editorDesc->display($field->name, $INIThtml, '97%', 200, $field->t_columns, $field->t_rows,true,$editor_param);
				
?>
        </div>    
<?php
			} else {
				
?>
        <textarea class="<?php echo $validationclass; ?> ckCSSinput <?php echo $field->fieldCSSclass; ?>" name="<?php echo $field->name; ?>" cols="<?php echo $field->t_columns; ?>" rows="<?php echo $field->t_rows; ?>" wrap="<?php echo $field->t_wrap; ?>" <?php if ($field->readonly == "1") {echo ' readonly="true"';} ?>><?php if (empty($this->post) ==false) {echo $this->post[$field->name];} else {echo $field->t_initvalueTA;} ?></textarea>
<?php
            }
		break; 	
			
		case 'checkbox':
		
			if (empty($this->post) ==false && isset($this->post[$field->name])) 
			{
				$field->t_checkedCB = '1';
			}				

?>
		<input class="<?php echo $validationclass; ?> ckCSStop10 <?php echo $field->fieldCSSclass; ?>" name="<?php echo $field->name; ?>" type="checkbox" value="<?php echo $field->t_initvalueCB; ?>" <?php if ($field->t_checkedCB == '1') { ?> checked<?php } ?> <?php if ($field->readonly == "1") {echo ' readonly="true"';} ?> />
<?php
		break; 	
		
		case 'radiobutton':

			if ($field->t_displayRB == '' || $field->t_displayRB == 'INL')
			{
				$opt = explode("[-]", $field->t_listHRB);
				$k=count($opt);
				
				for ($j=0; $j < $k; $j++)
				{	
					$checked = "";
					$val = explode("==", $opt[$j]);
					$key = explode("||", $val[1]);
					$ipos = strpos ($key[1],' [default]');
					
					if (empty($this->post) == false && isset($this->post[$field->name])
						 && $this->post[$field->name] == $key[0]) 
					{
						$checked = "checked";
					} 
					else if ($ipos != false && (empty($this->post) == true || isset($this->post[$field->name]) == false)) 
					{
						$checked = "checked";
						$key[1] = substr($key[1],0,$ipos);
					}					
	?>
				<input class="<?php echo $validationclass; ?> ckCSStop10 <?php echo $field->fieldCSSclass; ?>" name="<?php echo $field->name; ?>" type="radio" value="<?php echo $key[0]; ?>" <?php echo $checked; ?> <?php if ($field->readonly == "1") {echo ' readonly="true"';} ?> />
				&nbsp;<?php echo $key[1]; ?>&nbsp;
	<?php 				
				} 
			
			}
			else 
			{

				$opt = explode("[-]", $field->t_listHRB);
				$k=count($opt);
				echo '<div class="ckCSSinput '.$field->fieldCSSclass.'">';
				for ($j=0; $j < $k; $j++)
				{	
					$checked = "";
					$val = explode("==", $opt[$j]);
					$key = explode("||", $val[1]);
					$ipos = strpos ($key[1],' [default]');
					
					if (empty($this->post) == false && isset($this->post[$field->name])
						 && $this->post[$field->name] == $key[0]) 
					{
						$checked = "checked";
					} 
					else if ($ipos != false && (empty($this->post) == true || isset($this->post[$field->name]) == false)) 
					{
						$checked = "checked";
						$key[1] = substr($key[1],0,$ipos);
					}					

					if($j!=0){
						echo '<br />';
					}
	?>
				<input class="<?php echo $validationclass; ?>" name="<?php echo $field->name; ?>" type="radio" value="<?php echo $key[0]; ?>" <?php echo $checked; ?> <?php if ($field->readonly == "1") {echo ' readonly="true"';} ?> />	&nbsp;<?php echo $key[1]; ?>
			<?php 			
		
				} 
				echo '</div>';

			}
			
		break;
			
		case 'select':
		?>
			<select class="<?php echo $validationclass; ?> ckCSSinput <?php echo $field->fieldCSSclass; ?>" name="<?php echo $field->name; ?>[]" size="<?php echo $field->t_heightS; ?>" <?php if ($field->t_multipleS == '1') { ?> multiple<?php } ?> <?php if ($field->readonly == "1") {echo ' readonly="true"';} ?> >
            
		<?php if (($field->t_multipleS != '1' && ($field->t_heightS == '' || $field->t_heightS <= 1)) && strpos($field->t_listHS,' [default]') == false) { ?>
			<option value="-1"><?php echo strpos($field->t_listHS,' [default]'); ?></option>
		<?php }  		
			
			$opt = explode("[-]", $field->t_listHS);
			$k=count($opt);
			for ($j=0;$j < $k; $j++)
			{	
				$checked = "";
				$val = explode("==", $opt[$j]);
				$key = explode("||", $val[1]);
				$ipos = strpos ($key[1],' [default]');
				
				if (empty($this->post) == false && isset($this->post[$field->name])
					 && in_array($key[0], $this->post[$field->name]) ) 
				{
					$checked = 'selected="selected"';
				} 
				else if ($ipos != false && (empty($this->post) == true || isset($this->post[$field->name]) == false)) 
				{
					$checked = 'selected="selected"';
					$key[1] = substr($key[1],0,$ipos);
				}					
		?>
			<option value="<?php echo $key[0]; ?>" <?php echo $checked; ?> ><?php echo $key[1]; ?>&nbsp;</option>
		<?php 				
			}
		?>
			</select>
		<?php 
		break;
	}	
	
	if ($field->mandatory == "1" || ($field->typefield == 'text' && ($field->t_texttype == 'email' || $field->t_texttype == 'number' || $field->t_minchar != ''))) 
	{
		$idError = $field->name;
		if ($field->typefield == 'textarea' && $field->t_HTMLEditor == 1 &&  $field->readonly != "1")
		{
			$idError = $field->name.'Cont';
		}
		
		if ($field->customerror != "") 
		{
?>
 	<div class="error" id="error<?php echo $idError; ?>">
		<?php echo $field->customerror; ?>
    </div>
<?php
		}
	}
?>

    <p class="ckCSSclear" />

<?php
	}   
	
	else if ($field->typefield == "fieldsep")
	{
		?><hr <?php if ($field->t_noborderFS == "1") {echo ' class="ckNoBorder"';} ?> /><?php
	}
	
  
	if ($field->customtext != '') {
 ?>
 		<div class="ckCustomText <?php echo $field->customtextCSSclass; ?>"><?php echo $field->customtext; ?></div>
<?php
	}	
	
}
?>

<?php 
	if ($this->ckforms->captcha == 1)
	{
		
?>
	<div class="captchaCont">
        <img id="captchacode" class="captchacode" src="index.php?option=com_ckforms&task=captcha&sid=c4ce9d9bffcf8ba3357da92fd49c2457" align="absmiddle"> &nbsp;           
        <img alt="<?php echo JText::_( 'Refresh Captcha' ); ?>" class="captcharefresh" src='<?php echo JURI::root(true).'/components/com_ckforms/'; ?>captcha/images/refresh.gif' align="absmiddle"> &nbsp;
        <input class="validate['required']" type="text" id="ck_captcha_code" name="ck_captcha_code" />        
        <?php 	
			if ($this->ckforms->captchacustominfo != "") 
			{
				?> 
        		<img class="ckform_tooltip<?php echo $this->ckforms->id; ?> ckform_tooltipcss" src="<?php echo JURI::root(true).'/components/com_ckforms/'; ?>img/info.png" title="<?php echo $this->ckforms->captchacustominfo; ?>" />
				<?php
			}
		?>        
        <div class="error" id="errorck_captcha_code">
        <?php 	
			if ($this->ckforms->captchacustomerror != "") 
			{
				echo $this->ckforms->captchacustomerror;
			}
		?>
        </div>    
    </div>
<?php
	} 
?>
    
    <div class="ckBtnCon">
	<?php 
	for ($i=0;$i < $nbFields; $i++)
	{ 
		$field = $this->ckforms->fields[$i];
		if ($field->typefield == "button")
		{
			$jsbutton = "";
			if ($field->t_typeBT == "submit") {
	?>
    			<input name="submit_bt" id="submit_bt" type="submit" value="<?php echo $field->label; ?>" <?php echo $jsbutton; ?> />
   			&nbsp;
<?php 		
		} else if ($field->t_typeBT == "reset") {
		
	?>
    		<input name="reset_bt" id="reset_bt" type="reset" value="<?php echo $field->label; ?>" />&nbsp;
    <?php 
		}?>
    <?php    
	}
}
?>
	</div>
    
</form>

</td></tr>
</table>
