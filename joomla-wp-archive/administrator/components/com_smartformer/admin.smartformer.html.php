<?php
/**
 * SmartFormer - Form Builder for Joomla 1.5.x websites
 * Copyright (C) 2006-2010 IToris Co.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see http://www.gnu.org/licenses/
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * -----------------------------------------------------------------------------
 * @package SmartFormer
 * @version 2.4.1 (J1.5 security fix)
 * @author The SmartFormer project (http://www.itoris.com/joomla-form-builder-smartformer.html)
 * @copyright IToris Co. 2006-2010
 * @license GNU GPL
 *
*/

// no direct access
if (!defined( '_VALID_MOS' ) && !defined( '_JEXEC' )) die( 'Restricted access' );

class HTML_smartformer {

    function _escapeJsStr($str) {
        return str_replace('&#039;',"\\'",addcslashes($str,"'"));
    }

	function editSettings ($option, $formid ) {
		global $my, $database;
		if (defined( '_JEXEC' )) { $database=& JFactory::getDBO(); $my = & JFactory::getUser(); } else { mosCommonHTML::loadOverlib(); }
		$query = "SELECT * FROM #__smartformer_forms where id=".$formid;
		$database->setQuery( $query );
		$rows2 = $database->loadObjectList();
		$row2=&$rows2[0];
		$fullhtml=stripcslashes($row2->data1.$row2->data2.$row2->data3.$row2->data4.$row2->data5.$row2->data6.$row2->data7.$row2->data8.$row2->data9.$row2->data10);
		$row2->element_to_page='datetime_system|0|0|Date/time (system)~username_system|0|0|Username (system)~ip_system|0|0|IP (system)~return_url_system|0|0|Return URL (system)~'.$row2->element_to_page;
		$objs=explode('~',$row2->element_to_page);
		$names = Array();
		foreach ($objs as $value) {
			$obj=explode('|',$value);
			if (isset($obj[2])) {
				if ($obj[2]==4) {
					$name='g'.$obj[12];
					$objs2=explode('~',$row2->groups);
					foreach ($objs2 as $value) {
						$obj2=explode('|',$value);
						if (isset($obj2[2]) && $obj2[0]==$obj[12]) $title=$obj2[2];
					}
				} else {
				if ($obj[0]!=0) $name='c'.$obj[0]; else $name=$obj[0];
				$title=$obj[3];
				}
				if (($obj[2]<=7 || $obj[2]==13) && !isset($names[$name]['title'])) {
					$names[$name]=array();
					$names[$name]['title']=$title;
					$names[$name]['prop']=$obj[2];
					$names[$name]['alias']=$obj[3];
				}
			$el['c_form_element'.$obj[0]]=array();
			$el['c_form_element'.$obj[0]]=$obj;
			}
		}
		$query = "SELECT * FROM #__smartformer_forms where id=".$formid;
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		$row=&$rows[0];
		?>
		<form action="<?php if (defined( '_JEXEC' )) echo "index.php?option=com_smartformer"; else echo "index2.php?option=com_smartformer"; ?>" method="post" name="adminForm">
		<?php if (defined('_VALID_MOS') && !defined( '_JEXEC' )) echo '<table class="adminheading"><tr><th>Edit Settings<font style="font-size:14px; color:#0066FF;"><br>Form:'.$row2->name.'</font></th></tr></table>'; ?>
			<table class="adminlist">
			<?php if (defined( '_JEXEC' )) echo "<thead>"; ?>
			<tr>
				<th align="center">Current form settings</th>
				<th align="center">General language settings (for all forms)</th>
			</tr>
			<?php if (defined( '_JEXEC' )) echo "</thead>"; ?>
			<tr><td align="left" valign="top">
			<table cellpadding="0" cellspacing="0">
				<tr><td align="right" width="100" nowrap><b>Email format:</b></td><td><input type="radio" name="email_format" value="1" <?php if (isset($row->email_format) && intval($row->email_format)==1) echo 'checked'; ?>>&nbsp;HTML&nbsp;&nbsp;<input type="radio" name="email_format" value="2" <?php if (isset($row->email_format) && intval($row->email_format)==2) echo 'checked'; ?>>&nbsp;Plain text</td></tr>
				<tr><th align="center" colspan="2">Email template for letter sent to the user</th></tr>
				<tr><td align="right" width="100" nowrap><b>Enable submitter<br>notification:</b></td><td><input type="checkbox" name="enable_notif" value="1" <?php if (isset($row->enable_notif) && intval($row->enable_notif)==1) echo 'checked'; ?>></td></tr>
				<tr><td align="right" width="100" nowrap><b>From Name:</b></td><td><input type="text" style="width:400px" name="fromname" value="<?php echo htmlspecialchars(stripcslashes($row->fromname)); ?>"></td></tr>
				<tr><td align="right" width="100" nowrap><b>From Email:</b></td><td><input type="text" style="width:400px" name="fromemail" value="<?php echo htmlspecialchars(stripcslashes($row->fromemail)); ?>"></td></tr>
				<tr><td align="right" width="100" nowrap><b>Subject:</b></td><td><input type="text" style="width:400px" name="subject" value="<?php echo htmlspecialchars(stripcslashes($row->subject)); ?>"></td></tr>
				<tr><td align="right" width="100" nowrap><b>Mail To:</b><td>The user's email</td></tr>
				<tr><td align="right" width="100" nowrap><b>CC:</b></td><td><input type="text" style="width:400px" name="cc" value="<?php echo htmlspecialchars(stripcslashes($row->cc)); ?>"></td></tr>
				<tr><td align="right" width="100" nowrap><b>BCC:</b></td><td><input type="text" style="width:400px" name="bcc" value="<?php echo htmlspecialchars(stripcslashes($row->bcc)); ?>"></td></tr>
				<tr><td align="left"><b>Message:</b></td><td align="right" nowrap>
                <select style="width:200px;" onchange="insertField(this.value); this.selectedIndex=0;">
                <option>Available fields</option>
				<?php
					foreach ($names as $key => $value) echo '<option value="{'.$key.':'.$value['title'].'}">{'.$key.':'.$value['title'].'}</option>';
				?>
				</select></td></tr>
				<tr>
				<td align="left" colspan="2">
					<textarea id="message" name="message" rows="75" cols="20" style="width: 100%; height:450px;"><?php echo stripcslashes($row->message);?></textarea>
<?php
//			if (!defined('_JEXEC')){
//					// parameters : areaname, content, hidden field, width, height, rows, cols
//					editorArea( 'message', stripcslashes($row->message), 'message', '100%;', '350', '75', '20' );
//			} else {
//					$editor =& JFactory::getEditor();
//					// parameters : areaname, content, width, height, cols, rows
//				echo $editor->display( 'message',  stripcslashes($row->message) ,  '100%', '350', '75', '20' );
//					}
?>
				</td></tr>
				</table>
<!-- admin template -->
<?php
$query = "SELECT admin_template FROM #__smartformer_forms where id=".$formid;
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		$row = &$rows[0];
		$row3=$row->admin_template;

		$row = explode ("{~-SePaRaT0R-~}", $row3);
 ?>
				<hr width="80%">
				<table cellpadding="0" cellspacing="0" border="0">
				<tr><td align="right" width="100" nowrap><b>Email format:</b></td>
                <td><input type="radio" name="email_format_admin" value="1" <?php if (isset($row[1]) && intval($row[1])==1) echo 'checked="checked"'; ?>>&nbsp;HTML&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="email_format_admin" value="2" <?php if ( (isset($row[1]) && intval($row[1])==2) || !isset($row[1])) echo 'checked="checked"'; ?>>&nbsp;Plain text</td></tr>
				<tr><td align="right" width="100" nowrap><b>Email template type:</b></td>
                <td><input type="radio" name="email_template_type_admin" value="1" <?php if ((isset($row[8]) && intval($row[8])==1) || !isset($row[8])) echo 'checked="checked"';?> onclick="showEditorText();">&nbsp;Default&nbsp;&nbsp;<input type="radio" name="email_template_type_admin" value="2" <?php if (isset($row[8]) && intval($row[8])==2) echo 'checked="checked"';?> onclick="hideEditorText();">&nbsp;Custom</td></tr>
				<tr><th align="center" colspan="2">Email template for letter sent to the admin</th></tr>
				<tr><td align="right" width="100" nowrap><b>From Name:</b></td>
                <td><input type="text" style="width:400px" name="fromname_admin" value="<?php echo htmlspecialchars(stripcslashes(@$row[2])); ?>"></td></tr>
				<tr><td align="right" width="100" nowrap><b>From Email:</b></td><td><input type="text" style="width:400px" name="fromemail_admin" value="<?php echo htmlspecialchars(stripcslashes(@$row[3])); ?>"></td></tr>
				<tr><td align="right" width="100" nowrap><b>Subject:</b></td><td><input type="text" style="width:400px" name="subject_admin" value="<?php echo htmlspecialchars(stripcslashes(@$row[4])); ?>"></td></tr>
				<tr><td align="right" width="100" nowrap><b>Mail To:</b><td>The admin email</td></tr>
				<tr><td align="right" width="100" nowrap><b>CC:</b></td><td><input type="text" style="width:400px" name="cc_admin" value="<?php echo htmlspecialchars(stripcslashes(@$row[5])); ?>"></td></tr>
				<tr><td align="right" width="100" nowrap><b>BCC:</b></td><td><input type="text" style="width:400px" name="bcc_admin" value="<?php echo htmlspecialchars(stripcslashes(@$row[6])); ?>"></td></tr>
				<tr><td align="left">
				<div id="message_div"><b>Message:</b></div>
				</td><td align="right" nowrap>
				<div id="fields_div">
                <select style="width:200px;" onchange="insertFieldAdmin(this.value); this.selectedIndex=0;">
                <option>Available fields</option>
				<?php
					foreach ($names as $key => $value) echo '<option value="{'.$key.':'.$value['title'].'}">{'.$key.':'.$value['title'].'}</option>';
				?>
				</select>
                </div>
				</td></tr>
				<tr>
				<td align="left" colspan="2" >
				<div id="message_admin_div">
					<textarea id="message_admin" name="message_admin" rows="75" cols="20" style="width: 100%; height:450px;"><?php echo stripcslashes(@$row[7]);?></textarea>
<?php
//            if (!defined('_JEXEC')){
//				// parameters : areaname, content, hidden field, width, height, rows, cols
//			    editorArea( 'message_admin', stripcslashes(@$row[7]), 'message_admin', '100%;', '350', '75', '20' ) ;
//			} else {
//				$editor =& JFactory::getEditor();
//				// parameters : areaname, content, width, height, cols, rows
//		        echo $editor->display('message_admin',  stripcslashes(@$row[7]) ,  '100%', '350', '75', '20');
//			}
?>              </div>
				</td></tr>
				</table>
			</td><td align="left" valign="top"><table cellpadding="0" cellspacing="0">
			<tr><td align="center"><b>Default</b></td><td align="center"><b>Currently defined</b></td></tr>
				<?php
					$database->setQuery( 'select * from #__smartformer_settings where type=1' );
					$settings = $database->loadObjectList();
					foreach ($settings as $value) {
						echo '<tr><td>'.htmlspecialchars(stripslashes($value->default)).'</td><td><input name="'.$value->variable.'" value="'.htmlspecialchars(stripslashes($value->value)).'" style="width:350px;"></td></tr>';
					}
				?>
				</table>
                <?php
               		$charsets = array('CP1250'=>'CP1250 Eastern European (ISO-8859-2)', 'CP1251'=>'CP1251 Cyrillic (ISO-8859-5)', 'CP1252'=>'CP1252 Western European (ISO-8859-1)', 'CP1253'=>'CP1253 Greek (ISO-8859-7)', 'CP1254'=>'CP1254 Turkish (ISO-8859-9)', 'CP1255'=>'CP1255 Hebrew (ISO-8859-8)', 'CP1256'=>'CP1256 Arabic (ISO-8859-6)', 'CP1257'=>'CP1257 Baltic (ISO-8859-4)', 'CP1258'=>'CP1258', 'default'=>'UTF-8 (not recommended)');
					$delimeters = array(','=>', (comma)', ';'=>'; (semicolon)', 'tab'=>'TAB');
					if (isset($row[9])) $csv_charset=$row[9]; else $csv_charset="CP1252";
					if (isset($row[10])) $csv_delimiter=$row[10]; else $csv_delimiter=",";
					if (isset($row[11])) $csv_encloser=$row[11]; else $csv_encloser="\"";
                ?>
				<table width=100%>
					<thead>
						<tr>
							<th>CSV Export options</th>
						</tr>
					</thead>
					<tr>
						<td align="left" valign="top">
							<table cellpadding="0" cellspacing="1" border="0">
								<tr>
									<td align="left" valign="middle"><b>Encoding charset:</b></td>
										<td align="left" valign="top">
										<select name="csv_charset">
										<?php
											foreach ($charsets as $key => $value) echo '<option value="'.$key.'" '.(($csv_charset==$key)?'selected':'').'>'.$value;
										?>
										</select>
									</td>
								</tr>
								<tr>
									<td align="left" valign="middle"><b>Fields separated by:</b></td>
									<td align="left" valign="top">
										<select name="csv_delimiter">
										<?php
											foreach ($delimeters as $key => $value) echo '<option value="'.$key.'" '.(($csv_delimiter==$key)?'selected':'').'>'.$value;
										?>
										</select>
									</td>
								</tr>
								<tr>
									<td align="left" valign="middle"><b>Fields enclosed by:</b></td>
									<td align="left" valign="top">
										<input type="text" name="csv_encloser" value="<?php echo htmlspecialchars($csv_encloser); ?>" style="width:50px;" />
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td></tr>

			</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" id="task" name="task" value="settings" />
		<input type="hidden" name="fid" value="<?php echo $formid; ?>" />
		<input type="hidden" name="boxchecked" value="1" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>

		<!-- TinyMCE -->
		<script type="text/javascript" src="<?php echo $GLOBALS['sf_live_site'];?>/administrator/components/com_smartformer/plugins/tiny_mce/tiny_mce.js"></script>

        <script type="text/javascript">

		tinyMCE.init({
			// General options
			mode : "textareas",
			theme : "advanced",
			plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Example word content CSS (should be your site CSS) this one removes paragraph margins
			content_css : "css/word.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",

		});

        function InsertEditorText( text, editor ) {
        if( tinyMCE) {
                tinyMCE.execInstanceCommand(editor, 'mceInsertContent',false,text);
        } else {
                insertAtCursor( document.getElementById(editor), text );
            }
        }
		function insertField(txt) {
            return InsertEditorText(txt,'message');
		}
			function insertFieldAdmin(txt) {
            return InsertEditorText(txt,'message_admin');
		}

		function showEditorText() {
	        document.getElementById('message_div').style.visibility='hidden';
	        document.getElementById('fields_div').style.visibility='hidden';
		    //document.getElementById('message_admin_div').style.visibility='hidden';
		    document.getElementById('message_admin_div').style.display='none';
		}
        function hideEditorText() {
        	document.getElementById('message_div').style.visibility='visible';
        	document.getElementById('fields_div').style.visibility='visible';
        	//document.getElementById('message_admin_div').style.visibility='visible';
        	document.getElementById('message_admin_div').style.display='block';
        }
        <?php if ((isset($row[8]) && intval($row[8])==1) || !isset($row[8])) echo "showEditorText();";?>
		</script>
		<?php
	}

	function editData($option, $formid, $dataid) {
		global $my, $database;
		if (defined( '_JEXEC' )) { $database=& JFactory::getDBO(); $my = & JFactory::getUser(); } else { mosCommonHTML::loadOverlib(); }

		$query = "SELECT * FROM #__smartformer_forms where id=".$formid;
		$database->setQuery( $query );
		$rows2 = $database->loadObjectList();
		$row2=&$rows2[0];
		$fullhtml=stripcslashes($row2->data1.$row2->data2.$row2->data3.$row2->data4.$row2->data5.$row2->data6.$row2->data7.$row2->data8.$row2->data9.$row2->data10);
		$row2->element_to_page='datetime_system|0|0|Date/time (system)~username_system|0|0|Username (system)~ip_system|0|0|IP (system)~'.$row2->element_to_page;
		$objs=explode('~',$row2->element_to_page);
		$names = Array();
		foreach ($objs as $value) {
			$obj=explode('|',$value);
			if (isset($obj[2]) && $obj[2]>-1) {
				if ($obj[2]==4) {
					$name='c_form_group'.$obj[12];
					$objs2=explode('~',$row2->groups);
					foreach ($objs2 as $value) {
						$obj2=explode('|',$value);
						if (isset($obj2[2]) && $obj2[0]==$obj[12]) $title=$obj2[2];
					}
				} else {
					if ($obj[0]!=0) $name='c_form_element'.$obj[0]; else $name=$obj[0];
					$title=$obj[3];
				}
				if (($obj[2]<=7 || $obj[2]==13) && !isset($names[$name]['title'])) {
					$names[$name]=array();
					$names[$name]['title']=$title;
					$names[$name]['prop']=$obj[2];
					$names[$name]['alias']=$obj[3];
				}
			$el['c_form_element'.$obj[0]]=array();
			$el['c_form_element'.$obj[0]]=$obj;
			}
		}
		$query = "SELECT * FROM #__smartformer_data where id=".$dataid;
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		$row=&$rows[0];
		if (defined('_VALID_MOS') && !defined( '_JEXEC' )) mosMakeHtmlSafe($row); else {jimport('joomla.filter.output'); JFilterOutput::objectHTMLSafe( $row );}
		$obj2=array();
		$objs3=explode('{~-SePaRaT0R-~}',$row->data);
		foreach ($objs3 as $value) if (strpos($value,"=&gt;") !== false) {
			$name=substr($value,0,strpos($value,"=&gt;"));
			$title=substr($value,strpos($value,"=&gt;")+5);
			$obj2[$name]=$title;
			if (isset($el[$name][2]) && ($el[$name][2]==6 || $el[$name][2]==7) && strpos($title,',')!==false) { $obj2[$name]=substr($title,strpos($title,',')+1); }
		}
		?>
		<form action="<?php if (defined( '_JEXEC' )) echo "index.php?option=com_smartformer"; else echo "index2.php?option=com_smartformer"; ?>" method="post" name="adminForm">
		<?php if (defined('_VALID_MOS') && !defined( '_JEXEC' )) echo '<table class="adminheading"><tr><th>Form Data<font style="font-size:14px; color:#0066FF;"><br>Form: '.$row2->name.'</font></th></tr></table>'; ?>
			<table class="adminlist">
			<?php if (defined( '_JEXEC' )) echo "<thead>"; ?>
			<tr>
				<th align="center" width="200">Field Name</th>
				<th align="center">Data</th>
			</tr>
			<?php if (defined( '_JEXEC' )) echo "</thead>"; ?>
			<?php
				echo '<tr><td align="center"><b>Submit time</b></td><td><b>'.$row->datetime.'</b></td></tr>';
				echo '<tr><td align="center"><b>Unique ID</b></td><td><b>'.$row->id.'</b></td></tr>';
				foreach ($names as $key => $value) {
					echo '<tr><td align="center"><b>'.(isset($value['title'])?$value['title']:'').'</b></td>';
					if (isset($obj2[$key])) $data=$obj2[$key]; else $data='';
					if ($names[$key]['prop']==5 && isset($obj2[$key])) $data='<input type="checkbox" '.(($obj2[$key]!='')?'checked':'').' onclick="if (this.checked==true) document.getElementById(\''.$key.'\').value=\''.$key.'\'; else document.getElementById(\''.$key.'\').value=\'\';"><input id="'.$key.'" name="'.$key.'" type="hidden" value="'.$obj2[$key].'">';
						else if ($names[$key]['prop']==3 && isset($obj2[$key])) $data='<textarea name="'.$key.'" style="width:400px; height:70px;">'.$obj2[$key].'</textarea>';
						else if ($names[$key]['prop']==4 && isset($obj2[$key])) $data='<input name="'.$key.'" type="text" value="'.(isset($el['c'.substr($obj2[$key],1)][3])?$el['c'.substr($obj2[$key],1)][3]:$obj2[$key]).'" style="width:400px;">';
						else if ($names[$key]['prop']==13 && isset($obj2[$key]) && $obj2[$key]!='') $data='<a href="index'.((defined('_VALID_MOS') && !defined( '_JEXEC' ))?'2':'').'.php?option=com_smartformer&task=file&dataid='.$dataid.'&fid='.$formid.'&name='.$obj2[$key].'">'.substr($obj2[$key],0,strpos($obj2[$key],'|')).'</a><input id="'.$key.'" name="'.$key.'" type="hidden" value="'.$obj2[$key].'">';
						else if ($names[$key]['prop']==0) $data='<input readonly name="'.$key.'" type="text" value="'.(isset($obj2[$key])?$obj2[$key]:'').'" style="width:400px; background:#DDDDDD;">';
						else $data='<input name="'.$key.'" type="text" value="'.(isset($obj2[$key])?$obj2[$key]:'').'" style="width:400px;">';
					if ($names[$key]['prop']!=3) $data=preg_replace('/\n/','<br>',$data);
					if (isset($obj2[$key]) && $obj2[$key]!='' && $key=='hash' ) $data='<a href="index'.((defined('_VALID_MOS') && !defined( '_JEXEC' ))?'2':'').'.php?option=com_smartformer&form='.$obj2[$key].'">'.substr($obj2[$key],0,strpos($obj2[$key],'|')).'</a><input id="'.$key.'" name="'.$key.'" type="hidden" value="'.$obj2[$key].'">';
					echo '<td>'.stripcslashes($data).'</td>';
					echo '</tr>';
				}

			?>
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" id="task" name="task" value="data" />
		<input type="hidden" name="cid[]" value="<?php echo $dataid; ?>" />
		<input type="hidden" name="fid" value="<?php echo $formid; ?>" />
		<input type="hidden" name="boxchecked" value="1" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<?php
	}

	function showPreview($option, $formid, $dataid) {
		global $my, $database;
		if (defined( '_JEXEC' )) { $database=& JFactory::getDBO(); $my = & JFactory::getUser(); } else { mosCommonHTML::loadOverlib(); }

		$query = "SELECT * FROM #__smartformer_forms where id=".$formid;
		$database->setQuery( $query );
		$rows2 = $database->loadObjectList();
		$row2=&$rows2[0];
		$row2->element_to_page='datetime_system|0|0|Date/time (system)~username_system|0|0|Username (system)~ip_system|0|0|IP (system)~return_url_system|0|0|Return URL (system)~'.$row2->element_to_page; //new
		$objs=explode('~',$row2->element_to_page);
		$names = Array();
		foreach ($objs as $value) {
			$obj=explode('|',$value);
			if (isset($obj[2]) && $obj[2]>-1) {
				if ($obj[2]==4) {
					$name='c_form_group'.$obj[12];
					$objs2=explode('~',$row2->groups);
					foreach ($objs2 as $value) {
						$obj2=explode('|',$value);
						if (isset($obj2[2]) && $obj2[0]==$obj[12]) $title=$obj2[2];
					}
				} else {
					if ($obj[0]!=0) $name='c_form_element'.$obj[0]; else $name=$obj[0];
					$title=$obj[3];
				}
				if (($obj[2]<=7 || $obj[2]==13) && !isset($names[$name]['title'])) {
					$names[$name]=array();
					$names[$name]['title']=$title;
					$names[$name]['prop']=$obj[2];
					$names[$name]['alias']=$obj[3];
				}
			$el['c_form_element'.$obj[0]]=array();
			$el['c_form_element'.$obj[0]]=$obj;
			}
		}
		$query = "SELECT * FROM #__smartformer_data where id=".$dataid;
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		$row=&$rows[0];
		if (defined('_VALID_MOS') && !defined( '_JEXEC' )) mosMakeHtmlSafe($row); else {jimport('joomla.filter.output'); JFilterOutput::objectHTMLSafe( $row );}
		$obj2=array();
		$objs3=explode('{~-SePaRaT0R-~}',$row->data);
		foreach ($objs3 as $value) if (strpos($value,"=&gt;") !== false) {
			$name=substr($value,0,strpos($value,"=&gt;"));
			$title=substr($value,strpos($value,"=&gt;")+5);
			$obj2[$name]=$title;
			if (isset($el[$name][2]) && ($el[$name][2]==6 || $el[$name][2]==7) && strpos($title,',')!==false) { $obj2[$name]=substr($title,strpos($title,',')+1); }
		}
		?>
		<form action="<?php if (defined( '_JEXEC' )) echo "index.php?option=com_smartformer"; else echo "index2.php?option=com_smartformer"; ?>" method="post" name="adminForm">
		<?php if (defined('_VALID_MOS') && !defined( '_JEXEC' )) echo '<table class="adminheading"><tr><th>Form Data<font style="font-size:14px; color:#0066FF;"><br>Form: '.$row2->name.'</font></th></tr></table>'; ?>
			<table class="adminlist">
			<?php if (defined( '_JEXEC' )) echo "<thead>"; ?>
			<tr>
				<th align="center" width="200">Field Name</th>
				<th align="center">Data</th>
			</tr>
			<?php if (defined( '_JEXEC' )) echo "</thead>"; ?>
			<?php
				echo '<tr><td align="center"><b>Submit time</b></td><td><b>'.$row->datetime.'</b></td></tr>';
				echo '<tr><td align="center"><b>Unique ID</b></td><td><b>'.$row->id.'</b></td></tr>';
				foreach ($names as $key => $value) {
					echo '<tr><td align="center"><b>'.(isset($value['title'])?$value['title']:'').'</b></td>';
					if (isset($obj2[$key])) $data=$obj2[$key]; else $data='';
					if ($names[$key]['prop']==5 && isset($obj2[$key])) $data='<input type="checkbox" '.(($obj2[$key]!='')?'checked':'').' disabled>';
					if ($names[$key]['prop']==4 && isset($obj2[$key])) $data=(isset($el['c'.substr($obj2[$key],1)][3])?$el['c'.substr($obj2[$key],1)][3]:$obj2[$key]);
					if ($names[$key]['prop']==13 && isset($obj2[$key]) && $obj2[$key]!='') $data='<a href="index'.((defined('_VALID_MOS') && !defined( '_JEXEC' ))?'2':'').'.php?option=com_smartformer&task=file&dataid='.$dataid.'&fid='.$formid.'&name='.$obj2[$key].'">'.substr($obj2[$key],0,strpos($obj2[$key],'|'));
					if ($names[$key]['title']=='Return URL (system)' && isset($row->hash) && $row->hash !=='')$data= '<a target="_blank" href="'.$GLOBALS['sf_live_site'].'/index.php?option=com_smartformer&form='.$row->hash.'">'.$row->hash.'</a>';//new
					$data=preg_replace('/\n/','<br>',$data);
					echo '<td>'.stripcslashes($data).'</td>';
					echo '</tr>';
				}

			?>
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" id="task" name="task" value="data" />
		<input type="hidden" name="cid[]" value="<?php echo $dataid; ?>" />
		<input type="hidden" name="fid" value="<?php echo $formid; ?>" />
		<input type="hidden" name="boxchecked" value="1" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<?php
	}

	function showDataList( &$rows, &$pageNav, $option, $formid ) {
		global $my, $database;
		if (defined( '_JEXEC' )) { $database=& JFactory::getDBO(); $my = & JFactory::getUser(); } else { mosCommonHTML::loadOverlib(); }

		$query = "SELECT * FROM #__smartformer_forms where id=".$formid;
		$database->setQuery( $query );
		$rows2 = $database->loadObjectList();
		$row2=&$rows2[0];
		$row2->element_to_page='datetime_system|0|0|Date/time (system)~username_system|0|0|Username (system)~ip_system|0|0|IP (system)~return_url_system|0|0|Return URL (system)~'.$row2->element_to_page;//new
		$objs=explode('~',$row2->element_to_page);
		$names = Array();
		foreach ($objs as $value) {
			$obj=explode('|',$value);
			if (isset($obj[2]) && $obj[2]>-1) {
				if ($obj[2]==4) {
					$name='c_form_group'.$obj[12];
					$objs2=explode('~',$row2->groups);
					foreach ($objs2 as $value) {
						$obj2=explode('|',$value);
						if (isset($obj2[2]) && $obj2[0]==$obj[12]) $title=$obj2[2];
					}
				} else {
					if ($obj[0]!=0) $name='c_form_element'.$obj[0]; else $name=$obj[0];
					$title=$obj[3];
				}
				if (($obj[2]<=7 || $obj[2]==13) && !isset($names[$name]['title'])) {
					$names[$name]=array();
					$names[$name]['title']=$title;
					$names[$name]['prop']=$obj[2];
					$names[$name]['alias']=$obj[3];
				}
			$el['c_form_element'.$obj[0]]=array();
			$el['c_form_element'.$obj[0]]=$obj;
			}
		}
		$name=$row2->name;
		$objs=explode('{#sep#}',$row2->filter);

		?>
		<form action="<?php if (defined( '_JEXEC' )) echo "index.php?option=com_smartformer"; else echo "index2.php?option=com_smartformer"; ?>" method="post" name="adminForm">
		<?php if (defined('_VALID_MOS') && !defined( '_JEXEC' )) echo '<table class="adminheading"><tr><th>Data List<font style="font-size:14px; color:#0066FF;"><br>Form: '.$name.'</font></th></tr></table>'; ?>
		<div style="position:relative; width:100%; height:5px; top:-10px;" align="right">
			<b style="color:#0066FF; cursor:pointer;" onmouseover='this.style.color="red"' onmouseout='this.style.color="#0066FF"' onclick="showColumnSelector()">Click here to define visible columns</b>
		</div>
		<table class="adminlist">
		<?php if (defined( '_JEXEC' )) echo "<thead>"; ?>
		<tr>
			<th width="20">#</th>
			<th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" /></th>
			<th align="center" width="120" nowrap="nowrap">Date-time</th>
			<th width="80" nowrap="nowrap">Published</th>
			<th align="center" width="50" nowrap="nowrap">Preview</th>
			<?php
				foreach ($objs as $value2) if (isset($names[$value2]['title'])) echo '<th align="left">'.$names[$value2]['title']."</th>";
			?>
		</tr>
		<?php if (defined( '_JEXEC' )) echo '</thead><tfoot><tr><td colspan="'.(count($objs)+4).'">'.$pageNav->getListFooter().'</td></tr></tfoot>'; ?>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];
			if (defined('_VALID_MOS') && !defined( '_JEXEC' )) mosMakeHtmlSafe($row); else {jimport('joomla.filter.output'); JFilterOutput::objectHTMLSafe( $row );}

			$task 	= $row->status ? 'unpublishData' : 'publishData';//new
			$img 	= $row->status ? 'publish_g.png' : 'publish_x.png';
			$alt 	= $row->status ? 'Published' : 'Unpublished';//new

			$obj2=array();
			$objs3=explode('{~-SePaRaT0R-~}',$row->data);
			foreach ($objs3 as $value) if (strpos($value,"=&gt;") !== false) {
				$name=substr($value,0,strpos($value,"=&gt;"));
				$title=substr($value,strpos($value,"=&gt;")+5);
				$obj2[$name]=$title;
				if (isset($el[$name][2]) && ($el[$name][2]==6 || $el[$name][2]==7) && strpos($title,',')!==false) { $obj2[$name]=substr($title,strpos($title,',')+1); }
			}
			$row->checked_out=0;
			if (defined('_VALID_MOS') && !defined( '_JEXEC' )) $checked 	= mosCommonHTML::CheckedOutProcessing( $row, $i ); else $checked	= JHTML::_('grid.checkedout',   $row, $i );
 			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center"><?php if (defined('_VALID_MOS') && !defined( '_JEXEC' )) echo $pageNav->rowNumber( $i ); else echo $pageNav->getRowOffset($i); ?></td>
				<td align="center"><?php echo $checked; ?></td>
				<td align="center"><?php echo $row->datetime; ?></td>

				<td align="center">
				<a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')">
				<img src="images/<?php echo $img;?>" width="12" height="12" border="0" title="<?php echo $alt; ?>" />
				</a>
				</td>
				<?php
					echo '<td align="center"><a href="index'.((defined('_VALID_MOS') && !defined( '_JEXEC' ))?'2':'').'.php?option=com_smartformer&task=preview&fid='.$formid.'&dataid='.$row->id.'">Preview</a></td>';
					foreach ($objs as $value2) if (isset($names[$value2]['title'])) {
						if (isset($obj2[$value2])) {
							if ($names[$value2]['prop']==5) echo '<td align="center"><input type="checkbox" '.(($obj2[$value2]!='')?'checked':'').' disabled></td>';
								else if ($names[$value2]['prop']==13 && isset($obj2[$value2]) && $obj2[$value2]!='') echo '<td align="center"><a href="index'.((defined('_VALID_MOS') && !defined( '_JEXEC' ))?'2':'').'.php?option=com_smartformer&task=file&fid='.$formid.'&name='.$obj2[$value2].'">'.substr($obj2[$value2],0,strpos($obj2[$value2],'|')).'</td>';
								else if ($names[$value2]['prop']==4) echo '<td align="left">'.(isset($el['c'.substr($obj2[$value2],1)][3])?$el['c'.substr($obj2[$value2],1)][3]:$obj2[$value2])."</td>";
								else echo '<td align="left">'.stripcslashes($obj2[$value2])."</td>";
						} else {
							if ($names[$value2]['title']=='Return URL (system)' && isset($row->hash) && $row->hash !==''){
								echo'<td align="center"><a target="_blank" href="'.$GLOBALS['sf_live_site'].'/index.php?option=com_smartformer&form='.$row->hash.'">'.$row->hash.'</a></td>';//new
								} else {echo "<td></td>";}
						}
					}
				?>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php if (defined('_VALID_MOS') && !defined( '_JEXEC' )) echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" id="task" name="task" value="data" />
		<input type="hidden" name="fid" value="<?php echo $formid; ?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<form action="<?php if (defined( '_JEXEC' )) echo "index.php?option=com_smartformer"; else echo "index2.php?option=com_smartformer"; ?>" method="post" name="createmenu">
		<div id="mask" onmousemove="get_mouse_coords(event); floatwindow_move();" style="position:absolute; visibility:hidden; left:0px; top:0px; width:100px; height:100px; background-color:black; opacity:.3; filter:progid:DXImageTransform.Microsoft.BasicImage(opacity=.3)"></div>
		<table id="floatwindow" cellpadding="0" cellspacing="0" style="position:absolute; visibility:hidden; left:100px; top:100px; width:530px; height:300px; border:1px; border-color:black; border-style:solid;" onmousemove="get_mouse_coords(event); floatwindow_move();" onmouseout="fl=false">
		<tr><td id="float_title" width="510" style="background-image:url('<?php echo $GLOBALS['sf_live_site']; ?>/administrator/components/com_smartformer/admin/background.png'); height:18px; color:white; cursor:move;" onmousedown="floatwindow_down();" onmouseup="floatwindow_up();"></td>
			<td align="center" width="18" nowrap style="background-image:url('<?php echo $GLOBALS['sf_live_site']; ?>/administrator/components/com_smartformer/admin/background.png'); cursor:pointer;"><img src="<?php echo $GLOBALS['sf_live_site']; ?>/administrator/components/com_smartformer/admin/x.png" onclick="floatwindow_close();"></td></tr>
		<tr><td height="100%" colspan="2"><div id="float_content" style="width:528px; height:100%; background-color:white; border-color:black; border-width:1px; border-style:solid;"></div>
		</td></tr>
		</table>
		<input type="hidden" id="filter" name="filter" value="<?php if (isset($row->filter)) echo $row->filter ;?>" />

		<input type="hidden" name="option" value="<?php echo $option; ?>" />

		</form>
		<script>
		mouse = new Array ('x','y','ox','oy','fx1','fy1','fx2','fy2');
		fl=false;
		function showColumnSelector(){
			document.getElementById('mask').style.visibility="visible";
			document.getElementById('mask').style.height=document.body.offsetHeight+"px";
			document.getElementById('mask').style.width=document.body.offsetWidth+"px";
			document.getElementById('floatwindow').style.visibility="visible";
			document.getElementById('floatwindow').style.left=Math.floor((document.body.clientWidth-500)/2)+"px";
			document.getElementById('floatwindow').style.top="200px";
			document.getElementById('floatwindow').style.height="330px";
			document.getElementById('float_title').innerHTML='<b>&nbsp;&nbsp;Select columns to display</b>';
			s='<table height="100%" width="100%"><tr><td align="center" valign="middle">';
			s+='<table cellpadding="1" cellspacing="0">';
			s+='<tr><td align="left" colspan=2><b>Available columns</b></td><td align="left" colspan=2><b>Selected columns</b></td>';
			s+='<tr><td align="left"><select multiple size=20 style="width:200px;" id="left_list">';
			<?php
				foreach ($names as $key => $value) {
					if (strpos($row2->filter.'{#sep#}',$key) === false) echo "s+='<option value=\'$key\'>".$names[$key]['title']."';";
				}
			?>
			s+='</select></td><td align="center" valign="middle">';
			s+='<input type="button" value="<" onclick="rem()"><br><br>';
			s+='<input type="button" value=">" onclick="add()"><br><br>';
			s+='<input type="button" value="<<" onclick="remAll()"><br><br>';
			s+='<input type="button" value=">>" onclick="addAll()"></td>';
			s+='<td align="right"><select multiple size=20 style="width:200px;" id="right_list">';
			<?php
				foreach ($objs as $value2) if (isset($names[$value2]['title'])) echo "s+='<option value=\'$value2\'>".$names[$value2]['title']."';";
			?>
			s+='</select></td><td align="center" valign="middle">';
			s+='<input type="button" value="Move Up" onclick="up()"><br><br>';
			s+='<input type="button" value="Move Down" onclick="down()">';
			s+='</td></tr><tr>';
			s+='<td colspan="4"><input type="button" value="Update List" onclick="updateList()">&nbsp;&nbsp;&nbsp;<input type="button" value="Cancel" onclick="floatwindow_close()"></td>';
			s+='</tr></table>';
			document.getElementById('float_content').innerHTML=s;

			}
			function up() {
				for (i=1; i<document.getElementById("right_list").options.length; i++) if (document.getElementById("right_list").options[i].selected) {
					tmp=document.createElement("OPTION");
					tmp.value=document.getElementById("right_list").options[i].value;
					tmp.text=document.getElementById("right_list").options[i].text;
					tmp.selected=document.getElementById("right_list").options[i].selected;
					document.getElementById("right_list").remove(i);
					document.getElementById("right_list").options.add(tmp,i-1);
				}
			}
			function down() {
				for (i=document.getElementById("right_list").options.length-2; i>=0; i--) if (document.getElementById("right_list").options[i].selected) {
					tmp=document.createElement("OPTION");
					tmp.value=document.getElementById("right_list").options[i].value;
					tmp.text=document.getElementById("right_list").options[i].text;
					tmp.selected=document.getElementById("right_list").options[i].selected;
					document.getElementById("right_list").remove(i);
					document.getElementById("right_list").options.add(tmp,i+1);
				}
			}
			function add() {
				for (i=0; i<document.getElementById("left_list").options.length; i++) if (document.getElementById("left_list").options[i].selected) {
					tmp=document.createElement("OPTION");
					tmp.value=document.getElementById("left_list").options[i].value;
					tmp.text=document.getElementById("left_list").options[i].text;
					document.getElementById("right_list").options.add(tmp);
				}
				for (i=document.getElementById("left_list").options.length-1; i>=0; i--) if (document.getElementById("left_list").options[i].selected) document.getElementById("left_list").remove(i);
			}
			function rem() {
				for (i=0; i<document.getElementById("right_list").options.length; i++) if (document.getElementById("right_list").options[i].selected) {
					tmp=document.createElement("OPTION");
					tmp.value=document.getElementById("right_list").options[i].value;
					tmp.text=document.getElementById("right_list").options[i].text;
					document.getElementById("left_list").options.add(tmp);
				}
				for (i=document.getElementById("right_list").options.length-1; i>=0; i--) if (document.getElementById("right_list").options[i].selected) document.getElementById("right_list").remove(i);
			}
			function addAll() {
				for (i=0; i<document.getElementById("left_list").options.length; i++) {
					tmp=document.createElement("OPTION");
					tmp.value=document.getElementById("left_list").options[i].value;
					tmp.text=document.getElementById("left_list").options[i].text;
					document.getElementById("right_list").options.add(tmp);
				}
				for (i=document.getElementById("left_list").options.length-1; i>=0; i--) document.getElementById("left_list").remove(i);
			}
			function remAll() {
				for (i=0; i<document.getElementById("right_list").options.length; i++) {
					tmp=document.createElement("OPTION");
					tmp.value=document.getElementById("right_list").options[i].value;
					tmp.text=document.getElementById("right_list").options[i].text;
					document.getElementById("left_list").options.add(tmp);
				}
				for (i=document.getElementById("right_list").options.length-1; i>=0; i--) document.getElementById("right_list").remove(i);
			}
			function updateList() {
				s='';
				for (i=0; i<document.getElementById("right_list").options.length; i++) s+=document.getElementById("right_list").options[i].value+'{#sep#}';
				document.getElementById("filter").value=s;
				document.getElementById("task").value="update_list";
				document.adminForm.submit();
			}
			function floatwindow_down() {
				mouse['flx']=mouse['x']; mouse['fly']=mouse['y'];
				fl=true;
			}
			function floatwindow_move() {
				if (fl) {
					mouse['flx2']=mouse['x']-mouse['flx'];
					mouse['fly2']=mouse['y']-mouse['fly'];
					document.getElementById('floatwindow').style.left=px_int(document.getElementById('floatwindow').style.left)+mouse['flx2']+"px";
					document.getElementById('floatwindow').style.top=px_int(document.getElementById('floatwindow').style.top)+mouse['fly2']+"px";
					mouse['flx']=mouse['x']; mouse['fly']=mouse['y'];
				}
			}
			function floatwindow_up() {
				fl=false;
			}

			function floatwindow_close() {
				document.getElementById('floatwindow').style.visibility="hidden";
				document.getElementById('mask').style.visibility="hidden";
			}
			function get_mouse_coords(event) {
				mouse['y']=event.clientY + document.documentElement.scrollTop + document.body.scrollTop;
				mouse['x']=event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
			}
			function px_int (px) {
				if (px.toString().toLowerCase().indexOf("p")>-1) return px.toString().substring(0,px.toString().toLowerCase().indexOf("p"))*1;
				return px;
			}
		</script>
		<?php

	}

	function showForms( &$rows, &$pageNav, $option ) {
		global $my, $database;
		if (defined( '_JEXEC' )) { $database=& JFactory::getDBO(); $my = & JFactory::getUser(); } else { mosCommonHTML::loadOverlib(); }
		?>
		<form action="<?php if (defined( '_JEXEC' )) echo "index.php?option=com_smartformer"; else echo "index2.php?option=com_smartformer"; ?>" method="post" name="adminForm" enctype="multipart/form-data">
		<?php if (defined('_VALID_MOS') && !defined( '_JEXEC' )) echo '<table class="adminheading"><tr><th>Form Manager<font style="font-size:14px; color:#0066FF;"><br>Smart Former v2.4</font></th></tr></table>'; ?>

		<table class="adminlist">
		<?php if (defined( '_JEXEC' )) echo "<thead>"; ?>
		<tr>
			<th width="20">#</th>
			<th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" /></th>
			<th align="left" width="200" nowrap="nowrap">Form Name</th>
			<th width="80" nowrap="nowrap" align="center">Records #</th>
			<th width="80" nowrap="nowrap">Published</th>
			<th width="300">Actions</th>
			<th align="left">Direct URL to the form</th>
		</tr>
		<?php if (defined( '_JEXEC' )) echo '</thead><tfoot><tr><td colspan="7">'.$pageNav->getListFooter().'</td></tr></tfoot>'; ?>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];
			if (defined('_VALID_MOS') && !defined( '_JEXEC' )) mosMakeHtmlSafe($row); else {jimport('joomla.filter.output'); JFilterOutput::objectHTMLSafe( $row );}
			$link 		= 'index'.((defined('_VALID_MOS') && !defined( '_JEXEC' ))?'2':'').'.php?option=com_smartformer&task=edit&id='. $row->id;

			$task 	= $row->status ? 'unpublish' : 'publish';
			$img 	= $row->status ? 'publish_g.png' : 'publish_x.png';
			$alt 	= $row->status ? 'Published' : 'Unpublished';
			$formurl = $GLOBALS['sf_live_site'].'/index.php?option=com_smartformer&formid='.$row->id;

			if (defined('_VALID_MOS') && !defined( '_JEXEC' )) $checked 	= mosCommonHTML::CheckedOutProcessing( $row, $i ); else $checked	= JHTML::_('grid.checkedout',   $row, $i );
 			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center"><?php if (defined('_VALID_MOS') && !defined( '_JEXEC' )) echo $pageNav->rowNumber( $i ); else echo $pageNav->getRowOffset($i); ?></td>
				<td align="center"><?php echo $checked; ?></td>
				<td align="left">
				<?php
				if ( $row->checked_out && ( $row->editor != $my->username ) ) echo $row->name;
				else { ?>
					<a href="<?php echo $link; ?>" title="Edit Form"><?php echo $row->name; ?></a>
				<?php }	?>
				</td>
				<td align="center">
				<?php
					$query = 'select count(*) from #__smartformer_data where form_id='.$row->id;
					$database->setQuery( $query );
					$total = intval($database->loadResult());
					if ($total==0) echo "-"; else echo '<a href="index'.((defined('_VALID_MOS') && !defined( '_JEXEC' ))?'2':'').'.php?option=com_smartformer&task=data&fid='.$row->id.'"><b>'.$total.'</b></a>';
				?>
				</td>
				<td align="center">
				<a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')">
				<img src="images/<?php echo $img;?>" width="12" height="12" border="0" title="<?php echo $alt; ?>" />
				</a>
				</td>
				<td>
				<?php echo '<a href="index'.((defined('_VALID_MOS') && !defined( '_JEXEC' ))?'2':'').'.php?option=com_smartformer&task=settings&fid='.$row->id.'">Settings</a>'?>&nbsp;|&nbsp;
				<a href="#" onclick="showMenuManager('<?php echo HTML_smartformer::_escapeJsStr($row->name)?>',<?php echo $row->id; ?>)">Create a menu item</a>
				<?php if ($total!=0) echo '| <a href="index'.((defined('_VALID_MOS') && !defined( '_JEXEC' ))?'2':'').'.php?option=com_smartformer&task=data&fid='.$row->id.'">View data</a>'; ?>
				&nbsp;|&nbsp;<a href="index<?php echo ((defined('_VALID_MOS') && !defined( '_JEXEC' ))?'2':''); ?>.php?option=com_smartformer&task=clone&fid=<?php echo $row->id; ?>">Clone</a>
				&nbsp;|&nbsp;<a href="index<?php echo ((defined('_VALID_MOS') && !defined( '_JEXEC' ))?'2':''); ?>.php?option=com_smartformer&task=backup&fid=<?php echo $row->id; ?>">Backup</a></td>
				<td><a href="<?php echo $formurl; ?>" target="_blank"><?php echo $formurl; ?></a></td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		<tr><td></td><td colspan="6"><b>Upload form: </b><input name="upload" type="file" size="40">&nbsp;&nbsp;<input type="button" value="Upload" onclick="document.adminForm.task.value='upload'; document.adminForm.submit();"></td></tr>
		</table>
		<?php if (defined('_VALID_MOS') && !defined( '_JEXEC' )) echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<?php
	}

	function createMenuManager($option) {
		global $database;
		if (defined( '_JEXEC' )) { $database=& JFactory::getDBO(); $my = & JFactory::getUser(); } else { mosCommonHTML::loadOverlib(); }
		?>
		<form action="<?php if (defined( '_JEXEC' )) echo "index.php?option=com_smartformer"; else echo "index2.php?option=com_smartformer"; ?>" method="post" name="createmenu">
		<div id="mask" onmousemove="get_mouse_coords(event); floatwindow_move();" style="position:absolute; visibility:hidden; left:0px; top:0px; width:100px; height:100px; background-color:black; opacity:.3; filter:progid:DXImageTransform.Microsoft.BasicImage(opacity=.3)"></div>
		<table id="floatwindow" cellpadding="0" cellspacing="0" style="position:absolute; visibility:hidden; left:100px; top:100px; width:500px; height:300px; border:1px; border-color:black; border-style:solid;" onmousemove="get_mouse_coords(event); floatwindow_move();" onmouseout="fl=false">
		<tr><td id="float_title" width="480" style="background-image:url('<?php echo $GLOBALS['sf_live_site']; ?>/administrator/components/com_smartformer/admin/background.png'); height:18px; color:white; cursor:move;" onmousedown="floatwindow_down();" onmouseup="floatwindow_up();"></td>
			<td align="center" width="18" nowrap style="background-image:url('<?php echo $GLOBALS['sf_live_site']; ?>/administrator/components/com_smartformer/admin/background.png'); cursor:pointer;"><img src="<?php echo $GLOBALS['sf_live_site']; ?>/administrator/components/com_smartformer/admin/x.png" onclick="floatwindow_close();"></td></tr>
		<tr><td height="100%" colspan="2"><div id="float_content" style="width:498px; height:100%; background-color:white; border-color:black; border-width:1px; border-style:solid;"></div>
		</td></tr>
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" id="fid" name="fid" value="" />
		<input type="hidden" id="published" name="published" value="" />
		<input type="hidden" id="task" name="task" value="xzc" />
		</form>
		<script>
		mouse = new Array ('x','y','ox','oy','fx1','fy1','fx2','fy2');
		fl=false;
		function submitMenuItem (){
			msg="";
			if (document.getElementById("menu_name").value=="") msg+="The name of the item is obligatory\n";
			if (document.getElementById("menu_type").selectedIndex==-1) msg+="Menu type is obligatory";
			if (msg!="") alert(msg); else {
				if (confirm("Are you sure want to create a new menu item for the form?\nAfter creation the system will redirect you to the menu settings")) {
					document.createmenu.task.value="create_menu";
					if (document.getElementById("menu_published").checked==true) document.getElementById("published").value="1"; else document.getElementById("published").value="0";
					document.createmenu.submit();
				}
			}
		}
		function closeMenuManager () {
			floatwindow_close();
		}

		function showMenuManager (name, id) {
			document.getElementById('mask').style.visibility="visible";
			document.getElementById('mask').style.height=document.body.offsetHeight+"px";
			document.getElementById('mask').style.width=document.body.offsetWidth+"px";
			document.getElementById('floatwindow').style.visibility="visible";
			document.getElementById('floatwindow').style.left=Math.floor((document.body.clientWidth-500)/2)+"px";
			document.getElementById('floatwindow').style.top="200px";
			document.getElementById('floatwindow').style.height="150px";
			document.getElementById('float_title').innerHTML='<b>&nbsp;&nbsp;Create a new menu item</b>';
			s='<table height="100%" width="100%"><tr><td align="center" valign="middle">';
			s+='<table cellpadding="1" cellspacing="0" width="100%">';
			s+='<tr><td style="font-size:12px" align="right" width="100"><b>Item Name*:</b></td><td align="left"><input id="menu_name" name="menu_name" type="text" style="width:250px;" value="New Form"></td></tr>';
			s+='<tr><td style="font-size:12px" align="right" width="100"><b>Menu Type*:</b></td><td align="left"><select id="menu_type" name="menu_type" size="4" style="width:250px;">';
			<?php
				$query = 'SELECT * FROM #__modules where module="mod_mainmenu"';
				$database->setQuery( $query );
				$rows = $database->loadObjectList();
				foreach ($rows as $row) {
					$row=&$row;
					$params=explode("\n",$row->params);
					foreach ($params as $param) {
						$param=explode('=',$param);
						if (trim($param[0]=='menutype')) $op_value=trim($param[1]);
					}
					echo 's+=\'<option value="'.$op_value.'">'.HTML_smartformer::_escapeJsStr($row->title)."';";
				}

			?>
			s+='</select></td></tr>';
			s+='<tr><td style="font-size:12px" align="right" width="80"><b>Published:</b></td><td align="left"><input id="menu_published" name="menu_published" type="checkbox"></td></tr>';
			s+='<tr><td></td><td align="left"><input type="button" value="Create" onclick="submitMenuItem()">&nbsp;&nbsp;&nbsp;<input type="button" value="Cancel" onclick="closeMenuManager()"></td></tr>';
			s+='</table>';
			s+='<td></tr></table>';
			document.getElementById('float_content').innerHTML=s;

			document.getElementById("fid").value=id;
			document.getElementById("menu_name").value=name;
		}

		function floatwindow_down() {
			mouse['flx']=mouse['x']; mouse['fly']=mouse['y'];
			fl=true;
		}
		function floatwindow_move() {
			if (fl) {
				mouse['flx2']=mouse['x']-mouse['flx'];
				mouse['fly2']=mouse['y']-mouse['fly'];
				document.getElementById('floatwindow').style.left=px_int(document.getElementById('floatwindow').style.left)+mouse['flx2']+"px";
				document.getElementById('floatwindow').style.top=px_int(document.getElementById('floatwindow').style.top)+mouse['fly2']+"px";
				mouse['flx']=mouse['x']; mouse['fly']=mouse['y'];
			}
		}
		function floatwindow_up() {
			fl=false;
		}

		function floatwindow_close() {
			document.getElementById('floatwindow').style.visibility="hidden";
			document.getElementById('mask').style.visibility="hidden";
		}
		function get_mouse_coords(event) {
			mouse['y']=event.clientY + document.documentElement.scrollTop + document.body.scrollTop;
			mouse['x']=event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
		}
		function px_int (px) {
			if (px.toString().toLowerCase().indexOf("p")>-1) return px.toString().substring(0,px.toString().toLowerCase().indexOf("p"))*1;
			return px;
		}
		</script>
		<?php

	}
}

?>