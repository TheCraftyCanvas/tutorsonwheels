<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php 
	jimport( 'joomla.html.editor' ); 
?>

<script type="text/javascript">

window.addEvent('domready', function(){
	var myTabs = new mootabs('tabcontainer');
});
    
function submitbutton(pressbutton)	{
	var form = document.adminForm;

	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}

	// do field validation
	if (form.name.value == ""){
		alert( "<?php echo JText::_( 'Form must have a name', true ); ?>" );
	} else if (form.name.value.match(/[a-zA-Z0-9]*/) != form.name.value) {
		alert( "<?php echo JText::_( 'Field name contains bad caracters', true ); ?>" );
	} else {
		submitform( pressbutton );
	}
}

</script>


<form action="index.php" method="post" name="adminForm" id="adminForm">
 
    <div id="tabcontainer">
    
        <ul class="mootabs_title">
        	<li><a href="#fragment-1" class="active"><?php echo JText::_( 'General' ); ?></a></li>
          	<li><a href="#fragment-2"><?php echo JText::_( 'Result' ); ?></a></li>
          	<li><a href="#fragment-3"><?php echo JText::_( 'Email' ); ?></a></li>
          	<li><a href="#fragment-4"><?php echo JText::_( 'Advanced' ); ?></a></li>
            <li><a href="#fragment-5"><?php echo JText::_( 'Frontend display' ); ?></a></li>
            <li><a href="#fragment-6"><?php echo JText::_( 'Help' ); ?></a></li>
      	</ul>

		<div id="fragment-1" class="mootabs_panel active"> 
		<table class="admintable ckadmintable"> 
		<tr>
			<td class="key">
				<label for="title">
					<?php echo JText::_( 'Name' ); ?>:
				</label>
			</td>
			<td class="value">
				<input type="text" name="name" id="name" size="50" maxlength="50" value="<?php echo $this->ckforms->name;?>" />
                <img class="ckform_tooltip ckform_tooltipcss" src="<?php echo JURI::root(true).'/administrator/components/com_ckforms/'; ?>images/help.png" title="<?php echo JText::_( 'Field name##The field name can only contain the following characters : ##abcdefghijklmnopqrstuvwxy##ABCDEFGHIJKLMNOPQRSTUVWXYZ##0123456789' ); ?>" />
			</td>           
		</tr>
        
		<tr>
			<td class="key">
				<label for="title">
					<?php echo JText::_( 'Title' ); ?>:
				</label>
			</td>
			<td class="value">
				<input type="text" name="title" id="title" size="50" maxlength="250" value="<?php echo $this->ckforms->title;?>" />
			</td>
		</tr>        
        <tr>
			<td class="key">
				<label for="title">
					<?php echo JText::_( 'Published' ); ?>:
				</label>
			</td>
            <td class="value">
            <?php 
                echo JHTML::_('select.booleanlist',  'published', '', $this->ckforms->published); 
            ?>
            </td>        
        </tr>

        <tr>
			<td class="key">
				<label for="title">
					<?php echo JText::_( 'Description' ); ?>:
				</label>
			</td>
            <td class="value">
            <?php
                $editorDesc = JFactory::getEditor();
                echo $editorDesc->display('description',$this->ckforms->description, 600, 200, 10, 10);  
            ?>
            
            </td>
        </tr>
		</table>
        </div>
        
		<div id="fragment-2" class="mootabs_panel ui-tabs-hide">
		<table class="admintable ckadmintable"> 
        <tr>
			<td class="key">
				<label for="title">
					<?php echo JText::_( 'Save result' ); ?> :
				</label>
			</td>
            <td class="value">
                <?php echo JHTML::_('select.booleanlist',  'saveresult', '', $this->ckforms->saveresult); ?>
                <img class="ckform_tooltip ckform_tooltipcss" src="<?php echo JURI::root(true).'/administrator/components/com_ckforms/'; ?>images/help.png" title="<?php echo JText::_( "Save result 'YES'##save the data of your form in the Database and can be viewed and exported" ); ?>" />
    
            </td>             
        </tr>
        
        <tr>
			<td class="key">
				<label for="title"><?php echo JText::_( 'Text result' ); ?> :</label>
			</td>
            <td class="value">
                <?php 
                    $editorResult = JFactory::getEditor();
                    echo $editorResult->display('textresult',$this->ckforms->textresult, 600, 200, 10, 10);  
                ?>
                <img class="ckform_tooltip ckform_tooltipcss" src="<?php echo JURI::root(true).'/administrator/components/com_ckforms/'; ?>images/help.png" title="<?php echo JText::_( "Text Result##is the text displayed after the form submission" ); ?>" />
            </td>
        </tr>
		<tr>
			<td class="key">
				<label for="title">
					<?php echo JText::_( 'Redirect URL' ); ?> :
				</label>
			</td>
			<td class="value">
				<input class="longfield" type="text" name="redirecturl" id="redirecturl" maxlength="250" value="<?php echo $this->ckforms->redirecturl;?>" />
            	<img class="ckform_tooltip ckform_tooltipcss" src="<?php echo JURI::root(true).'/administrator/components/com_ckforms/'; ?>images/help.png" title="<?php echo JText::_( "Redirect URL##is the URL displayed after the form submission" ); ?>" />
			</td>
		</tr>        
		<tr>
			<td class="key">
				<label for="title">
					<?php echo JText::_( 'Redirect form data' ); ?> :
				</label>
			</td>
			<td class="value">
				<?php echo JHTML::_('select.booleanlist',  'redirectdata', '', $this->ckforms->redirectdata); ?>
			</td>
		</tr>        
		</table>        
        </div>
        
        <div id="fragment-3" class="mootabs_panel ui-tabs-hide">
        <table class="admintable x-hide-display ckadmintable" id="cktb3">
        <tr>
			<td class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'Email result' ); ?>:
				</label>
			</td>
        	<td class="value">
				<?php echo JHTML::_('select.booleanlist',  'emailresult', '', $this->ckforms->emailresult); ?>
            	<img class="ckform_tooltip ckform_tooltipcss" src="<?php echo JURI::root(true).'/administrator/components/com_ckforms/'; ?>images/help.png" title="<?php echo JText::_( "Email result##'Yes' send the data submited by email to the emails addresses with the subject." ); ?>" />
            </td>                
        </tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'Mail FROM' ); ?>:
				</label>
			</td>
			<td class="value">
				<input class="longfield" type="text" name="emailfrom" id="emailfrom" maxlength="250" value="<?php echo $this->ckforms->emailfrom;?>" />
            	<img class="ckform_tooltip ckform_tooltipcss" src="<?php echo JURI::root(true).'/administrator/components/com_ckforms/'; ?>images/help.png" title="<?php echo JText::_( "##Separate emails with comma" ); ?>" />
			</td>
		</tr> 
		<tr>
			<td class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'Mail TO' ); ?>:
				</label>
			</td>
			<td class="value">
				<input class="longfield" type="text" name="emailto" id="emailto" maxlength="250" value="<?php echo $this->ckforms->emailto;?>" />
            	<img class="ckform_tooltip ckform_tooltipcss" src="<?php echo JURI::root(true).'/administrator/components/com_ckforms/'; ?>images/help.png" title="<?php echo JText::_( "##Separate emails with comma" ); ?>" />
			</td>
		</tr> 
		<tr>
			<td class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'Mail CC' ); ?>:
				</label>
			</td>
			<td class="value">
				<input class="longfield" type="text" name="emailcc" id="emailcc" maxlength="250" value="<?php echo $this->ckforms->emailcc;?>" />
            	<img class="ckform_tooltip ckform_tooltipcss" src="<?php echo JURI::root(true).'/administrator/components/com_ckforms/'; ?>images/help.png" title="<?php echo JText::_( "##Separate emails with comma" ); ?>" />
			</td>
		</tr> 
		<tr>
			<td class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'Mail BCC' ); ?>:
				</label>
			</td>
			<td class="value">
				<input class="longfield" type="text" name="emailbcc" id="emailbcc" maxlength="250" value="<?php echo $this->ckforms->emailbcc;?>" />
            	<img class="ckform_tooltip ckform_tooltipcss" src="<?php echo JURI::root(true).'/administrator/components/com_ckforms/'; ?>images/help.png" title="<?php echo JText::_( "##Separate emails with comma" ); ?>" />
			</td>
		</tr> 
		<tr>
			<td class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'Mail Subject' ); ?>:
				</label>
			</td>
			<td class="value">
				<input class="longfield" type="text" name="subject" id="subject" maxlength="250" value="<?php echo $this->ckforms->subject;?>" />
			</td>
		</tr>    
                          
        <tr>
			<td class="key" nowrap="nowrap">
				<label for="title"><?php echo JText::_( 'Include fileupload file' ); ?>:</label>
			</td>
        	<td class="value">
			<?php echo JHTML::_('select.booleanlist',  'emailresultincfile', '', $this->ckforms->emailresultincfile); ?>
            </td>              
        </tr>
		<tr><td><hr /></td><td><hr /></td></tr>
        <tr>
			<td class="key" nowrap="nowrap">
				<label for="title"><?php echo JText::_( 'Email receipt' ); ?>:</label>
			</td>
        	<td class="value">
				<?php echo JHTML::_('select.booleanlist',  'emailreceipt', '', $this->ckforms->emailreceipt); ?>
            	<img class="ckform_tooltip ckform_tooltipcss" src="<?php echo JURI::root(true).'/administrator/components/com_ckforms/'; ?>images/help.png" title="<?php echo JText::_( "Email receipt##only if an 'E-Mail' field is present in the Form" ); ?>" />
            </td>              
        </tr>
        <tr>
			<td class="key" nowrap="nowrap">
				<label for="title"><?php echo JText::_( 'Email receipt Subject' ); ?>:</label>
			</td>
        	<td class="value">
			<input class="text_area" type="text" name="emailreceiptsubject" id="emailreceiptsubject" maxlength="250" value="<?php echo $this->ckforms->emailreceiptsubject;?>" />
            </td>              
        </tr>
        <tr>
			<td class="key">
				<label for="title"><?php echo JText::_( 'Email receipt Text' ); ?>:</label>
			</td>
            <td class="value">  
            <?php 
                $editorResultEMR = JFactory::getEditor();
                echo $editorResultEMR->display('emailreceipttext',$this->ckforms->emailreceipttext, 600, 150, 10, 10);  
            ?>
            </td>
        </tr>
        <tr>
			<td class="key" nowrap="nowrap">
				<label for="title"><?php echo JText::_( 'Include data' ); ?>:</label>
			</td>
        	<td class="value">
			<?php echo JHTML::_('select.booleanlist',  'emailreceiptincfield', '', $this->ckforms->emailreceiptincfield); ?>
            </td>              
        </tr>
        <tr>
			<td class="key" nowrap="nowrap">
				<label for="title"><?php echo JText::_( 'Include fileupload file' ); ?>:</label>
			</td>
        	<td class="value">
			<?php echo JHTML::_('select.booleanlist',  'emailreceiptincfile', '', $this->ckforms->emailreceiptincfile); ?>
            </td>              
        </tr>

       	</table>
    </div>
    
    <div id="fragment-4" class="mootabs_panel ui-tabs-hide">
		<table class="admintable ckadmintable"> 
        <tr>
			<td class="key">
				<label for="title"><?php echo JText::_( 'Use Captcha' ); ?> :</label>
			</td>
            <td class="value">
                <?php 
                    echo JHTML::_('select.booleanlist',  'captcha', '', $this->ckforms->captcha);  
                ?>
            </td>        
        </tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'Captcha tips text' ); ?>:
				</label>
			</td>
			<td class="value">
				<input class="text_area" type="text" name="captchacustominfo" id="captchacustominfo" maxlength="255" value="<?php echo $this->ckforms->captchacustominfo;?>" />
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'Captcha custom error text' ); ?>:
				</label>
			</td>
			<td class="value">
				<input class="text_area" type="text" name="captchacustomerror" id="captchacustomerror" maxlength="255" value="<?php echo $this->ckforms->captchacustomerror;?>" />
			</td>
		</tr>
        
        <tr><td colspan="2"><hr /></td></tr>
            
		<tr>
			<td class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'CSS class' ); ?>:
				</label>
			</td>
			<td class="value">
				<input class="text_area" type="text" name="formCSSclass" id="formCSSclass" maxlength="50" value="<?php echo $this->ckforms->formCSSclass;?>" />
			</td>
		</tr>
        
        <tr><td colspan="2"><hr /></td></tr>
            
        <tr>
			<td class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'Uploaded files path' ); ?>:
				</label>
			</td>
			<td class="value">
				<input class="text_area" type="text" name="uploadpath" id="uploadpath" maxlength="255" value="<?php echo $this->ckforms->uploadpath;?>" /><br/>
                <?php 
				if ($this->ckforms->uploadpath != null && trim($this->ckforms->uploadpath) != "") 
                {
					if (file_exists ($this->ckforms->uploadpath))					
					{
						echo "<span class=\"txtgreen\">(".JText::_( 'Directory exists' )."</span> - ";
						
						if (is_writable ($this->ckforms->uploadpath))
						{
							echo "<span class=\"txtgreen\">".JText::_( 'directory writables' ).")</span>";
						} else {
							echo "<span class=\"txtred\">".JText::_( 'ERROR : directory read only' )." !".")</span>";
						}
					} else {
						echo "<span class=\"txtred\">(".JText::_( 'ERROR : directory doesnt exist' )." !".")</span>";
					}
                }
				?>
			</td>          
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'File uploaded maximum size' ); ?>:
				</label>
			</td>
			<td class="value">
				<input class="text_area" type="text" name="maxfilesize" id="maxfilesize" size="32" maxlength="32" value="<?php echo $this->ckforms->maxfilesize;?>" />
			</td>
		</tr>
        
        <tr><td colspan="2"><hr /></td></tr>
            
		<tr>
			<td class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'Display "powered by" text' ); ?>:
				</label>
			</td>
			<td class="value">
			<?php 
                echo JHTML::_('select.booleanlist',  'poweredby', '', $this->ckforms->poweredby);  
            ?>

			</td>
		</tr>
        </table>
    </div>
        
    <div id="fragment-5" class="mootabs_panel ui-tabs-hide">
	<table class="admintable ckadmintable">            
	<tr>
		<td class="key" nowrap="nowrap">
            <label for="title">
                <?php echo JText::_( 'Display IP address' ); ?>:
            </label>
		</td>
		<td class="value">
			<?php 
                echo JHTML::_('select.booleanlist',  'displayip', '', $this->ckforms->displayip);  
            ?>
		</td>
	</tr>
	<tr>
		<td class="key" nowrap="nowrap">
            <label for="title">
                <?php echo JText::_( 'Display Data detail' ); ?>:
            </label>
		</td>
		<td class="value">
		<?php 
        	echo JHTML::_('select.booleanlist',  'displaydetail', '', $this->ckforms->displaydetail);  
        ?>
		</td>
	</tr>
    
	<tr>
		<td class="key" nowrap="nowrap">
            <label for="title">
                <?php echo JText::_( 'Auto Publish data' ); ?>:
            </label>
		</td>
		<td class="value">
		<?php 
        	echo JHTML::_('select.booleanlist',  'autopublish', '', $this->ckforms->autopublish);  
        ?>
		</td>
	</tr>


    <tr>
        <td class="key">
            <label for="title">
                <?php echo JText::_( 'Title' ); ?>:
            </label>
        </td>
        <td class="value">
            <input type="text" name="fronttitle" id="fronttitle" size="50" maxlength="250" value="<?php echo $this->ckforms->fronttitle;?>" />
        </td>
    </tr>        

    <tr>
        <td class="key">
            <label for="title">
                <?php echo JText::_( 'Description' ); ?>:
            </label>
        </td>
        <td class="value">
			<?php 	
                $editorDesc = JFactory::getEditor();
                echo $editorDesc->display('frontdescription',$this->ckforms->frontdescription, 600, 200, 10, 10);  
            ?>
        </td>
    </tr>

    </table>
    </div>
        
	<div id="fragment-6" class="mootabs_panel ui-tabs-hide">    
<?php // Display Help & Tips ?>
            <ul class="ckhelptd">
                 <li><?php echo JText::_( '<b>Form name</b><br/> The form name can only contain the following characters : <br/><b>abcdefghijklmnopqrstuvwxy<br/>ABCDEFGHIJKLMNOPQRSTUVWXYZ<br/>0123456789</b>' ); ?></li>
                                
                <li><?php echo JText::_( '<b>Save result "YES"</b> save the data of your form in the Database and can be viewed and exported' ); ?></li>
                <li><?php echo JText::_( '<b>Text Result</b> is the text displayed after the form submission' ); ?></li>
                <li><?php echo JText::_( '<b>Redirect URL</b> is the URL displayed after the form submission' ); ?></li>
                
				<li><?php echo JText::_( 'Separate emails with comma' ); ?></li>
                <li><?php echo JText::_( '<b>Email result</b> "Yes" send the data submited by email to the emails addresses with the subject.' ); ?></li>
                <li><?php echo JText::_( '<b>Email receipt</b> (only if a "E-Mail" field is present in the Form)' ); ?></li>
                
            </ul>
<?php ?>            
    </div> 
            
</div>

<input type="hidden" name="option" value="com_ckforms" />
<input type="hidden" name="id" value="<?php echo $this->ckforms->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="ckforms" />

</form>

<div class="ckformbottom">
	CKForms V1.3.5, &copy; 2008-2009 Copyright by <a href="http://ckforms.cookex.eu" target="_blank" class="smallgrey">Cookex</a>, all rights reserved. 
    CKForms is Free Software released under the GNU/GPL License. 
</div>
