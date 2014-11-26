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

if (!function_exists('mosRedirect')) {
	function mosRedirect( $url, $msg='' ) {
		global $mainframe;
		$mainframe->redirect( $url, $msg );
	}
}

function stripSybaseQuotes( $data ) {
    $result = array();
    foreach( $data as $key => $value ) {
        if( is_array($value) ) {
            $result[str_replace("''","'",$key)] = stripSybaseQuotes($value);
        } else {
            $result[str_replace("''","'",$key)] = str_replace("''","'",$value);
        }
    }
    return addMagicSlashes($result);
}

function addMagicSlashes( $data ) {
    $result = array();
    $list = '"\'\\';
    foreach( $data as $key => $value ) {
        if( is_array($value) ) {
            $result[addcslashes($key,$list)] = addMagicSlashes($value);
        } else {
            $result[addcslashes($key,$list)] = addcslashes($value,$list);
        }
    }
    return $result;
    }

if( ini_get('magic_quotes_sybase') && strtolower(ini_get('magic_quotes_sybase')) != 'off' ) {
    $_POST = stripSybaseQuotes($_POST);
    $_GET = stripSybaseQuotes($_GET);
}  elseif (!get_magic_quotes_gpc()) {
    $_POST = addMagicSlashes($_POST);
    $_GET = addMagicSlashes($_POST);
}

// no direct access
if (!defined( '_VALID_MOS' ) && !defined( '_JEXEC' )) die( 'Restricted access' );
if (!defined( '_JEXEC' )) {echo '<b style="color:red">Stop! The current version is for Joomla 1.5 only. Please contact IToris at <a href="http://www.itoris.com/index.php/itoris-contact-form">http://www.itoris.com/index.php/itoris-contact-form</a> for further information</b>'; return; }

// ensure user has access to this function
if (defined( '_VALID_MOS' ) && !defined( '_JEXEC' )) {
	if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' )| $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_smartformer' ))) mosRedirect( 'index2.php', _NOT_AUTH );
	if (isset($_REQUEST['cid'])) $cid=$_REQUEST['cid']; else $cid=array();
//	$cid = josGetArrayInts( 'cid' );

} else {
	$task = JRequest::getCmd('task');
	$GLOBALS['DOCUMENT_ROOT']=$_SERVER['DOCUMENT_ROOT'];
	$GLOBALS['sf_live_site']=substr(JURI::root(),0,strlen(JURI::root())-1);
	$GLOBALS['sf_absolute_path']=substr(__FILE__,0,strpos(strtolower(__FILE__),'administrator')-1);
	$cid	= JRequest::getVar('cid', array(0), 'method', 'array');
	$id=JRequest::getCmd( 'id' );
}

if (isset ($_REQUEST['fid'])) $fid=intval($_REQUEST['fid']);
if (isset ($_REQUEST['dataid'])) $dataid=intval($_REQUEST['dataid']);
if (isset ($_REQUEST['filter'])) $filter=$_REQUEST['filter'];
if (isset ($_REQUEST['menu_name'])) $menu_name=$_REQUEST['menu_name'];
if (isset ($_REQUEST['menu_type'])) $menu_type=$_REQUEST['menu_type'];
if (isset ($_REQUEST['published'])) $published=$_REQUEST['published'];
if (isset ($_REQUEST['name'])) $fname=$_REQUEST['name'];

require_once( $mainframe->getPath( 'admin_html' ) );

switch ($task) {
	case 'file':
		if (isset($fname)) {
			if (!isset($fid)) $fid=0;
			if (!isset($dataid)) $dataid=0;
			downloadFile($fname,$fid,$dataid);
		}
		if (defined('_VALID_MOS') && !defined( '_JEXEC' )) mosRedirect( 'index2.php?option=com_smartformer', 'Incorrect parameters' ); else
			$mainframe->redirect( 'index.php?option=com_smartformer', 'Incorrect parameters' );
		break;

	case 'cancel':
		cancelForm(intval($_POST['fid']));
		break;

	case 'exportAll':
		$cid='';
	case 'exportData':
		exportData(intval($_POST['fid']),$cid);
		break;
   	case 'exportPDF':
		exportPDF(intval($_POST['fid']),$cid);
		break;
	case 'backup':
		backupForm($fid);
		break;

	case 'upload':
		uploadForm();
		break;

	case 'cancelPreview':
	case 'data':
		viewDataList( $option, intval($fid) );
		break;

	case 'saveData':
		$dataid=$cid[0];
		saveData(intval($fid), intval($dataid),0);
		break;

	case 'applyData':
		$dataid=$cid[0];
		saveData(intval($fid), intval($dataid),1);
		break;

	case 'saveSettings':
		$dataid=$cid[0];
		saveSettings(intval($fid),0);
		break;

	case 'clone':
		cloneForm(intval($fid));
		break;

	case 'applySettings':
		$dataid=$cid[0];
		saveSettings(intval($fid),1);
		break;

	case 'settings':
		HTML_smartformer::editSettings( $option, intval($fid) );
		break;

	case 'cancelEdit':
		$dataid=$cid[0];
	case 'preview':
		HTML_smartformer::showPreview( $option, intval($fid), intval($dataid) );
		break;

	case 'edit_data':
		HTML_smartformer::editData( $option, intval($fid), $cid[0] );
		break;

	case 'update_list':
		updateList(intval($fid), $filter);
		viewDataList( $option, intval($fid) );
		break;

	case 'save':
		saveForm($task, $option);
		break;

	case 'apply':
		saveForm($task, $option);
		break;

	case 'edit':
		editForm( $id, $option );
		break;

	case 'new':
	case 'add':
		editForm( 0, $option );
		break;

	case 'publish':
		publishForm( 1, $cid );
		break;

	case 'unpublish':
		publishForm( 0, $cid );
		break;

	case 'remove':
		deleteForm( $cid );
		break;

	case 'removeData':
		deleteData( $cid, $fid );
		break;

	case 'publishData':
	    publishData( 1, $cid );
	    break;

	case 'unpublishData':
	    publishData( 0, $cid );
	    break;

	case 'create_menu':
		createMenuItem( $fid, $menu_name, $menu_type, $published );
		break;

	default:
		viewForms( $option );
		break;
}

function uploadForm() {
	if (!isset($_FILES['upload'])) mosRedirect( 'index2.php?option=com_smartformer', '' );
	if (!file_exists($_FILES['upload']['tmp_name'])) mosRedirect( 'index2.php?option=com_smartformer', 'Cannot upload the file' );
	$s=file_get_contents($_FILES['upload']['tmp_name']);
	if (strpos($s,'#__smartformer_forms')===false) mosRedirect( 'index2.php?option=com_smartformer', 'Incorrect backup file' );
	global $database, $mainframe;
	if (defined( '_JEXEC' )) $database=& JFactory::getDBO();
	$database->_errorNum=0;
	$database->setQuery( $s );
	$database->query();
	if ($database->_errorNum!=0) mosRedirect( 'index2.php?option=com_smartformer', 'Incorrect backup file' );
	mosRedirect( 'index2.php?option=com_smartformer', 'The form has been uploaded successfully' );
}

function backupForm($fid) {
	global $database, $mainframe;
	if (defined( '_JEXEC' )) $database=& JFactory::getDBO();
	$query = "SELECT * FROM #__smartformer_forms where id=$fid";
	$database->setQuery( $query );
	$rows = $database->loadObjectList();
	$row=&$rows[0];
	if (!isset($row->id) || intval($row->id)<1) mosRedirect( 'index2.php?option=com_smartformer', 'Cannot backup the form' );
	$s='insert into `#__smartformer_forms` set ';
	foreach ($row as $key => $value) if ($key!='id') $s.='`'.$key.'`="'.preg_replace('/\n/','\\n',addcslashes($value,'"\\')).'",';
	$s=substr($s,0,strlen($s)-1);
	@ob_end_clean(); // clear output buffer
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // some day in the past
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Content-type: application/x-download");
	header("Content-Transfer-Encoding: binary");
	header("Accept-Ranges: bytes");
	header("Content-Length: ".strlen($s));
	header("Content-Disposition: attachment; filename=form".$fid.'_dump.sql');
	echo $s;
	exit;
}

function cloneForm($fid) {
	global $database, $mainframe;
	if (defined( '_JEXEC' )) $database=& JFactory::getDBO();
	$query = "SELECT * FROM #__smartformer_forms where id=$fid";
	$database->setQuery( $query );
	$rows = $database->loadObjectList();
	$row=&$rows[0];
	if (!isset($row->id) || isset($row->id) && intval($row->id)<1) mosRedirect( 'index2.php?option=com_smartformer', 'Cannot clone the form' );
	$s='insert into #__smartformer_forms set ';
	foreach ($row as $key => $value) if ($key!='id') {
		if ($key=='name') $value='Copy of '.addcslashes($value,'"\\');
		$s.='`'.$key.'`="'.addcslashes($value,'"\\').'",';
	}
	$s=substr($s,0,strlen($s)-1);
	$database->setQuery( $s );
	$database->query();
	if (defined('_VALID_MOS') && !defined( '_JEXEC' )) mosRedirect( 'index2.php?option=com_smartformer', 'Selected for has been cloned' ); else
		$mainframe->redirect( 'index.php?option=com_smartformer', 'Selected for has been cloned' );

}

function cleanHtml( $data ) {
	return addslashes(stripslashes($data));
    $data = html_entity_decode($data);
    $arr = preg_split("/(<[^<>]*>)/",$data,null,PREG_SPLIT_DELIM_CAPTURE);
    $result = '';
    foreach( $arr as $value ) {
        if( preg_match("/^(<[^<>]*>)$/",$value) ) {
            $result.=$value;
        } else {
            $result.=htmlspecialchars($value);
        }
    }
    return $result;
}

function saveSettings($fid,$mode) {
	global $database, $mainframe;
	if (defined( '_JEXEC' )) $database=& JFactory::getDBO();
	if (isset($_POST['fromname'])) $fromname=stripcslashes($_POST['fromname']); else $fromname='';
	if (isset($_POST['fromemail'])) $fromemail=stripcslashes($_POST['fromemail']); else $fromemail='';
	if (isset($_POST['subject'])) $subject=stripcslashes($_POST['subject']); else $subject='';
	if (isset($_POST['cc'])) $cc=stripcslashes($_POST['cc']); else $cc='';
	if (isset($_POST['bcc'])) $bcc=stripcslashes($_POST['bcc']); else $bcc='';
	if (isset($_POST['message'])) $message=cleanHtml($_POST['message']); else $message='';
	if (isset($_POST['enable_notif'])) $enable_notif=intval($_POST['enable_notif']); else $enable_notif=0;
	if (isset($_POST['email_format'])) $email_format=intval($_POST['email_format']); else $email_format=1;
	if (isset($_POST['fromname'])) {
	    $query = "update #__smartformer_forms set `email_format`=".
	    $email_format.", `enable_notif`=".$enable_notif.", `fromname`='".
	    addcslashes($fromname,'"\'\\')."', `fromemail`='".
	    addcslashes($fromemail,'"\'\\')."', `subject`='".
	    addcslashes($subject,'"\'\\')."', `cc`='".
	    addcslashes($cc,'"\'\\')."', `bcc`='".
	    addcslashes($bcc,'"\'\\')."', `message`='".
	    $message."' where `id`=".$fid;
	}
	$database->setQuery( $query );
	$total = $database->query();
//-admin settings-//

	$admin_settings_array=array($_POST['enable_notif_admin'],
								$_POST['email_format_admin'],
								$_POST['fromname_admin'],
								$_POST['fromemail_admin'],
								$_POST['subject_admin'],
								$_POST['cc_admin'],
								$_POST['bcc_admin'],
								cleanHtml($_POST['message_admin']),
								$_POST['email_template_type_admin'],
								$_POST['csv_charset'],
								$_POST['csv_delimiter'],
								$_POST['csv_encloser']
								);
	if (isset($_POST['fromname_admin'])) $query = "update #__smartformer_forms set `admin_template`='".join("{~-SePaRaT0R-~}", $admin_settings_array)."' where `id`=".$fid;

	$database->setQuery( $query );
	$total = $database->query();
	$database->setQuery('select * from #__smartformer_settings');
	$rows=$database->loadObjectList();
	foreach($rows as $value) if (isset($_POST[$value->variable])) {
		$database->setQuery( 'update #__smartformer_settings set `value`="'.addcslashes($_POST[$value->variable],'"\\').'" where `variable`="'.$value->variable.'"' );
		$database->query();
	}
	if ($mode==0) {
		if (defined('_VALID_MOS') && !defined( '_JEXEC' )) mosRedirect( 'index2.php?option=com_smartformer', 'The information has been updated' ); else
			$mainframe->redirect( 'index.php?option=com_smartformer', 'The information has been updated' );
	} else {
		if (defined('_VALID_MOS') && !defined( '_JEXEC' )) mosRedirect( 'index2.php?option=com_smartformer&task=settings&fid='.$fid, 'The information has been updated' ); else
			$mainframe->redirect( 'index.php?option=com_smartformer&task=settings&fid='.$fid, 'The information has been updated' );
	}
}

function saveData($fid,$dataid,$mode) {
	global $database, $mainframe;
	if (defined( '_JEXEC' )) $database=& JFactory::getDBO();
	$s='';
	foreach ($_POST as $key => $value) if (strpos($key,'c_form_element')!==false || strpos($key,'c_form_group')!==false || $key!='datetime_system' || $key!='username_system' || $key!='ip_system') $s.=$key.'=>'.$value.'{~-SePaRaT0R-~}';
	$query = "update #__smartformer_data set `data`='".addcslashes($s,'\'"\\')."' where `id`=".$dataid;
	$database->setQuery( $query );
	$total = $database->query();
	if ($mode==0) {
		if (defined('_VALID_MOS') && !defined( '_JEXEC' )) mosRedirect( 'index2.php?option=com_smartformer&task=preview&fid='.$fid.'&dataid='.$dataid, 'The information has been updated' ); else
			$mainframe->redirect( 'index.php?option=com_smartformer&task=preview&fid='.$fid.'&dataid='.$dataid, 'The information has been updated' );
	} else {
		if (defined('_VALID_MOS') && !defined( '_JEXEC' )) mosRedirect( 'index2.php?option=com_smartformer&task=edit_data&fid='.$fid.'&cid[]='.$dataid, 'The information has been updated' ); else
			$mainframe->redirect( 'index.php?option=com_smartformer&task=edit_data&fid='.$fid.'&cid[]='.$dataid, 'The information has been updated' );
	}

}

function downloadFile($fname,$fid,$dataid) {
	global $mainframe;
	$fname1=substr($fname,0,strpos($fname,'|'));
	$fname2=substr($fname,strpos($fname,'|')+1);
	if (file_exists($GLOBALS['sf_absolute_path'].'/components/com_smartformer/files/'.$fname2)) {
		@ob_end_clean(); // clear output buffer
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // some day in the past
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Content-type: application/x-download");
		header("Content-Transfer-Encoding: binary");
		header("Accept-Ranges: bytes");
		header("Content-Length: ".filesize($GLOBALS['sf_absolute_path'].'/components/com_smartformer/files/'.$fname2));
		header("Content-Disposition: attachment; filename=\"$fname1\"");
		readfile($GLOBALS['sf_absolute_path'].'/components/com_smartformer/files/'.$fname2);
		exit;
	} else {
		if ($dataid==0) {
			if (defined('_VALID_MOS') && !defined( '_JEXEC' )) mosRedirect( 'index2.php?option=com_smartformer&task=data&fid='.$fid, 'Cannot read the file or the file does not exist' ); else
				$mainframe->redirect( 'index.php?option=com_smartformer&task=data&fid='.$fid, 'Cannot read the file or the file does not exist' );
		} else {
			if (defined('_VALID_MOS') && !defined( '_JEXEC' )) mosRedirect( 'index2.php?option=com_smartformer&task=preview&fid='.$fid.'&dataid='.$dataid, 'Cannot read the file or the file does not exist' ); else
				$mainframe->redirect( 'index.php?option=com_smartformer&task=preview&fid='.$fid.'&dataid='.$dataid, 'Cannot read the file or the file does not exist' );
		}
	}
}

function exportData($formid,$cid){
	global $database, $mainframe;
	$s='';
	if (defined( '_JEXEC' )) { $database=& JFactory::getDBO(); $my = & JFactory::getUser(); } else { mosCommonHTML::loadOverlib(); }
	$query = "SELECT * FROM #__smartformer_forms where id=".$formid;
	$database->setQuery( $query );
	$rows2 = $database->loadObjectList();
	$row2=&$rows2[0];

	$row3=$row2->admin_template;
	$row3 = explode ("{~-SePaRaT0R-~}", $row3);
	if (isset($row3[9])) $csv_charset=$row3[9]; else $csv_charset="CP1252";
	if (isset($row3[10])) $csv_delimiter=$row3[10]; else $csv_delimiter=",";
	if (isset($row3[11])) $csv_encloser=$row3[11]; else $csv_encloser="\"";
	if ($csv_delimiter=='tab') $csv_delimiter = chr(9);

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
	foreach ($names as $key => $value) {
		$data=(isset($value['title'])?$value['title']:'');
		$data=preg_replace('/\n/','',stripcslashes($data));
		$s.=$csv_encloser.preg_replace('/\"/','""',$data).$csv_encloser.$csv_delimiter;
	}
	$s=$csv_encloser."Uniq. ID".$csv_encloser.$csv_delimiter.substr($s,0,strlen($s)-1)."\n";

	if ($cid=='') $query = "SELECT * FROM #__smartformer_data where form_id=".$formid; else
		$query = "SELECT * FROM #__smartformer_data where id in (".implode(',',$cid).")";
	$database->setQuery( $query );
	$rows = $database->loadObjectList();
	foreach ($rows as $row) {
		$row=&$row;
		$obj2=array();
		$objs3=explode('{~-SePaRaT0R-~}',$row->data);
		foreach ($objs3 as $value) if (strpos($value,"=>") !== false) {
			$name=substr($value,0,strpos($value,"=>"));
			$title=substr($value,strpos($value,"=>")+2);
			$obj2[$name]=$title;
			if (isset($el[$name][2]) && ($el[$name][2]==6 || $el[$name][2]==7) && strpos($title,',')!==false) { $obj2[$name]=substr($title,strpos($title,',')+1); }
		}
		$s.=$csv_encloser.$row->id.$csv_encloser.$csv_delimiter;
		foreach ($names as $key => $value) {
			if (isset($obj2[$key])) $data=$obj2[$key]; else $data='';
			if ($names[$key]['prop']==5 && isset($obj2[$key])) $data=(($obj2[$key]!='')?'checked':'');
			if ($names[$key]['prop']==4 && isset($obj2[$key])) $data=(isset($el['c'.substr($obj2[$key],1)][3])?$el['c'.substr($obj2[$key],1)][3]:$obj2[$key]);
			if ($names[$key]['prop']==13 && isset($obj2[$key]) && $obj2[$key]!='') $data=substr($obj2[$key],0,strpos($obj2[$key],'|'));
			$data=preg_replace('/\n/','',stripcslashes($data));
			$s.=$csv_encloser.preg_replace('/\"/','""',$data).$csv_encloser.$csv_delimiter;
		}
		$s=substr($s,0,strlen($s)-1)."\n";
	}
	if (defined('_JEXEC') AND $csv_charset!="default") $s = utf8_converter($s, $csv_charset);
	@ob_end_clean(); // clear output buffer
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // some day in the past
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Content-type: application/x-download");
	header("Content-Transfer-Encoding: binary");
	header("Accept-Ranges: bytes");
	header("Content-Length: ".strlen($s));
	header("Content-Disposition: attachment; filename=dataexport.csv");
	echo $s;
	exit;
}

function utf8_converter($str, $charset = 'CP1252') {
	require_once($GLOBALS['sf_absolute_path'].'/administrator/components/com_smartformer/plugins/charset_converter/utf8.class.php');
	$converter = new utf8($GLOBALS['sf_absolute_path'].'/administrator/components/com_smartformer/plugins/charset_converter/'.$charset.'.MAP');
	return $converter->utf8ToStr($str);
}

function to_utf8_converter($str, $charset = 'CP1252') {
	require_once($GLOBALS['sf_absolute_path'].'/administrator/components/com_smartformer/plugins/charset_converter/utf8.class.php');
	$converter = new utf8($GLOBALS['sf_absolute_path'].'/administrator/components/com_smartformer/plugins/charset_converter/'.$charset.'.MAP');
	return $converter->strToUtf8($str);
}

function exportPDF($formid,$cid){
	global $database, $mainframe;

	@ini_set('max_execution_time', 180);
	require_once($GLOBALS['sf_absolute_path']."/administrator/components/com_smartformer/plugins/tcpdf/tcpdf.php");

	$s='';
	if (defined( '_JEXEC' )) {
		$database=& JFactory::getDBO();
		$my = & JFactory::getUser();
	} else {
		mosCommonHTML::loadOverlib();
	}

	$query = "SELECT * FROM #__smartformer_forms where id=".$formid;
	$database->setQuery($query);
	$rows2 = $database->loadObjectList();
	$row2=&$rows2[0];
	$form_name=$row2->name;
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

	if (count($cid)>1) {

		$s="<td width=53>Uniq. ID</td>".$s;
		$pdf_page_w=53; // width pdf page
		foreach ($names as $key => $value) {
			$data=(isset($value['title'])?$value['title']:'');
			$data=preg_replace('/\n/','',stripcslashes($data));
			$s.="<td width=200>".relace_tags($data)."</td>";
			$pdf_page_w=$pdf_page_w+200;
		}
		$s="<tr>".$s."</tr>";

    } else {
        $i=0;
    	$ss[$i]="<td width=150>Uniq. ID</td>";
    	$i++;
		$pdf_page_w=150; // width pdf page
		foreach ($names as $key => $value) {
			$data=(isset($value['title'])?$value['title']:'');
			$data=preg_replace('/\n/','',stripcslashes($data));
			$ss[$i]="<td width=150>".relace_tags($data)."</td>";
			$i++;
		}
    }


	if ($cid!="") { // 1 record selected
		$query = "SELECT * FROM #__smartformer_data where id in (".implode(',',$cid).")";
	} else {
		$query = "SELECT * FROM #__smartformer_data where form_id=".$formid;
	}


	$database->setQuery( $query );
	$rows = $database->loadObjectList();
	foreach ($rows as $row) {
		$row=&$row;
		$obj2=array();
		$objs3=explode('{~-SePaRaT0R-~}',$row->data);
		foreach ($objs3 as $value) if (strpos($value,"=>") !== false) {
			$name=substr($value,0,strpos($value,"=>"));
			$title=substr($value,strpos($value,"=>")+2);
			$obj2[$name]=$title;
			if (isset($el[$name][2]) && ($el[$name][2]==6 || $el[$name][2]==7) && strpos($title,',')!==false) { $obj2[$name]=substr($title,strpos($title,',')+1); }
		}

		if (count($cid)>1) {

			$s.="<tr><td width=53>".$row->id."</td>";
			foreach ($names as $key => $value) {
				if (isset($obj2[$key])) $data=$obj2[$key]; else $data='';
				if ($names[$key]['prop']==5 && isset($obj2[$key])) $data=(($obj2[$key]!='')?'checked':'');
				if ($names[$key]['prop']==4 && isset($obj2[$key])) $data=(isset($el['c'.substr($obj2[$key],1)][3])?$el['c'.substr($obj2[$key],1)][3]:$obj2[$key]);
				if ($names[$key]['prop']==13 && isset($obj2[$key]) && $obj2[$key]!='') $data=substr($obj2[$key],0,strpos($obj2[$key],'|'));
				$data=preg_replace('/\n/','',stripcslashes($data));
				if ($data=="") $data="&nbsp;";
				$s.="<td width=200>".relace_tags($data)."</td>";
			}
			$s=$s."</tr>";

		} else {

			$i=0;
			$s="<tr>".$ss[$i]."<td width=600><center>".$row->id."</center></td></tr>";
			$pdf_page_w=$pdf_page_w+600;
			$i++;
			foreach ($names as $key => $value) {
				if (isset($obj2[$key])) $data=$obj2[$key]; else $data='';
				if ($names[$key]['prop']==5 && isset($obj2[$key])) $data=(($obj2[$key]!='')?'checked':'');
				if ($names[$key]['prop']==4 && isset($obj2[$key])) $data=(isset($el['c'.substr($obj2[$key],1)][3])?$el['c'.substr($obj2[$key],1)][3]:$obj2[$key]);
				if ($names[$key]['prop']==13 && isset($obj2[$key]) && $obj2[$key]!='') $data=substr($obj2[$key],0,strpos($obj2[$key],'|'));
				$data=preg_replace('/\n/','',stripcslashes($data));
				if ($data=="") $data="&nbsp;";
				$s.="<tr>".$ss[$i]."<td width=600>".relace_tags($data)."</td></tr>";
				$i++;
			}

		}
	}

    $echo_data="<table border=1>".$s."</table>";
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	if (version_compare(phpversion(), "5.0.0", ">=")) {
		$pdf->setHeaderFont(array("dejavusans", '', 10));
		$pdf->setFooterFont(array("dejavusans", '', 8));
		$pdf->SetFont("dejavusans");
	} else { // in php4 output errors any UTF-font, therefore set helvetica font
		$pdf->setHeaderFont(array("helvetica", '', 10));
		$pdf->setFooterFont(array("helvetica", '', 8));
		$pdf->SetFont("helvetica");
	}

	$pdf->setHeaderData("",0, "", "SmartFormer - List of records\nForm: {$form_name}");
	$pdf->lMargin=7;
	$pdf->rMargin=7;
	$pdf_page_w=$pdf_page_w+(7*3)+(7*3);

	$pdf->fwPt=$pdf_page_w; //pt
	$pdf->w=$pdf_page_w/4; //
	$pdf->FontSizePt=10;
	$pdf->tMargin=22;
	$pdf->bMargin=22;
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	// Initialize PDF Document
	$pdf->AliasNbPages();
	$pdf->AddPage();

	// Build the PDF Document string from the document buffer
	if (!defined('_JEXEC')) $echo_data=to_utf8_converter($echo_data);
   	$pdf->WriteHTML($echo_data, true, 0, true, 0);
	//$pdf->Write(5, $echo_data, '', 1);
	
	@ob_end_clean(); // clear output buffer for joomla 1.0
	//echo print_r($pdf); exit;
	$pdf->Output('dataexport.pdf', 'D');
	exit;
}

function relace_tags($data){
$data=str_replace("<", "[", $data);
$data=str_replace(">", "]", $data);
return $data;
}

function deleteData( $cid, $formid) {
	global $database, $mainframe;
	if (defined( '_JEXEC' )) $database=& JFactory::getDBO();
	$query = "delete from #__smartformer_data where id in (".implode($cid,',').")";
	$database->setQuery( $query );
	$total = $database->query();
	if (defined('_VALID_MOS') && !defined( '_JEXEC' )) mosRedirect( 'index2.php?option=com_smartformer&task=data&fid='.$formid, 'Selected records have been successfully deleted' ); else
		$mainframe->redirect( 'index.php?option=com_smartformer&task=data&fid='.$formid, 'Selected records have been successfully deleted' );

}

function updateList( $formid, $filter ) {
	global $database;
	if (defined( '_JEXEC' )) $database=& JFactory::getDBO();
	if ($formid>0) {
		$query = 'update #__smartformer_forms set filter="'.addcslashes($filter,'"\\').'" where id='.$formid;
		$database->setQuery( $query );
		$total = $database->query();
	}
}


function viewDataList( $option, $formid ) {
	global $database, $mainframe, $mosConfig_list_limit;
	if (defined( '_JEXEC' )) {
		$database=& JFactory::getDBO();
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart = $mainframe->getUserStateFromRequest( "viewban{$option}limitstart", 'limitstart', 0, 'int' );
 	} else {
		$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
		$limitstart = intval( $mainframe->getUserStateFromRequest( "viewban{$option}limitstart", 'limitstart', 0 ) );
	}

	$query = "SELECT COUNT(*) FROM #__smartformer_data where form_id=".$formid;
	$database->setQuery( $query );
	$total = $database->loadResult();

	if (defined( '_JEXEC' )) {
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );
	} else {
		require_once( $GLOBALS['sf_absolute_path'] . '/administrator/includes/pageNavigation.php' );
		$pageNav = new mosPageNav( $total, $limitstart, $limit );
	}

	$query = "SELECT * FROM #__smartformer_data where form_id=".$formid." order by datetime desc";
	$database->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
	$rows = $database->loadObjectList();

	HTML_smartformer::showDataList( $rows, $pageNav, $option, $formid );

}

function publishData( $mode, $cid ) {
    global $my, $database;
	if (defined( '_JEXEC' )) { $database=& JFactory::getDBO(); $my = & JFactory::getUser(); } else { mosCommonHTML::loadOverlib(); }
	$query = "UPDATE #__smartformer_data SET status = '" . $mode . "' where id IN ( '".join("','",$cid) ."') ";
	$database->_errorNum=0;
	$database->setQuery( $query );
	$database->query();
	mosRedirect( 'index2.php?option=com_smartformer&task=data&fid='.$_REQUEST['fid'], 'The data access status has changed' );
}

function viewForms( $option ) {
	global $database, $mainframe, $mosConfig_list_limit;

	if (defined( '_JEXEC' )) {
		$database=& JFactory::getDBO();
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart = $mainframe->getUserStateFromRequest( "viewban{$option}limitstart", 'limitstart', 0, 'int' );
 	} else {
		$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
		$limitstart = intval( $mainframe->getUserStateFromRequest( "viewban{$option}limitstart", 'limitstart', 0 ) );
	}

	$query = "SELECT COUNT(*) FROM #__smartformer_forms";
	$database->setQuery( $query );
	$total = $database->loadResult();

	if (defined( '_JEXEC' )) {
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );
	} else {
		require_once( $GLOBALS['sf_absolute_path'] . '/administrator/includes/pageNavigation.php' );
		$pageNav = new mosPageNav( $total, $limitstart, $limit );
	}

	$query = "SELECT * FROM #__smartformer_forms";
	$database->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
	$rows = $database->loadObjectList();

	HTML_smartformer::showForms( $rows, $pageNav, $option );

	HTML_smartformer::createMenuManager($option);
}

function editForm( $formid, $option, $message='' ) {
	global $database, $my;
	if (defined( '_JEXEC' )) { $database=& JFactory::getDBO(); $my = & JFactory::getUser(); }
	if ($formid>0) {
		$query = 'update #__smartformer_forms set checked_out=1, checked_out_time=now(), editor="'.$my->username.'" where id='.$formid;
		$database->setQuery( $query );
		$total = $database->query();
		$query = "SELECT * FROM #__smartformer_forms where id=$formid";
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		$row = &$rows[0];
 	} else {
		$row->data1=""; $row->data2=""; $row->data3=""; $row->data4=""; $row->data5=""; $row->data6=""; $row->data7=""; $row->data8=""; $row->data9="";$row->data10="";
		$row->name=""; $row->status=0; $row->element_to_page=""; $row->groups=""; $row->css_list=""; $row->js_list=""; $row->php_list="";
	}

	require_once( $GLOBALS['sf_absolute_path'].'/administrator/components/com_smartformer/admin/edit.php' );
}

function saveForm( $task, $option) {
	global $database, $mainframe;
	if (defined( '_JEXEC' )) $database=& JFactory::getDBO();
	if ($_POST['form_name']=='') {
		$row->data1=stripcslashes($_POST['page1_html']); $row->data2=stripcslashes($_POST['page2_html']); $row->data3=stripcslashes($_POST['page3_html']); $row->data4=stripcslashes($_POST['page4_html']);
		$row->data5=stripcslashes($_POST['page5_html']); $row->data6=stripcslashes($_POST['page6_html']); $row->data7=stripcslashes($_POST['page7_html']); $row->data8=stripcslashes($_POST['page8_html']);
		$row->data9=stripcslashes($_POST['page9_html']); $row->data10=stripcslashes($_POST['page10_html']);
		$row->name=$_POST['form_name']; $row->status=$_POST['form_published']; $row->element_to_page=stripcslashes($_POST['element_to_page']);
		$row->groups=stripcslashes($_POST['groups']);
		$row->css_list=stripcslashes($_POST['css_list']);
		$row->js_list=stripcslashes($_POST['js_list']);
		$row->php_list=stripcslashes($_POST['php_list']);
		$formid=$_POST['fid'];
		$message='Please enter the form name';
		require_once( $GLOBALS['sf_absolute_path'].'/administrator/components/com_smartformer/admin/edit.php' );
	} else {
		if (intval($_POST['fid'])>0) {
			$query = 'update #__smartformer_forms set checked_out=0 where id='.intval($_POST['fid']);
			$database->setQuery( $query );
			$total = $database->query();
			$query = 'update #__smartformer_forms set '.
			'data1="'.$_POST['page1_html'].'",'.
			'data2="'.$_POST['page2_html'].'",'.
			'data3="'.$_POST['page3_html'].'",'.
			'data4="'.$_POST['page4_html'].'",'.
			'data5="'.$_POST['page5_html'].'",'.
			'data6="'.$_POST['page6_html'].'",'.
			'data7="'.$_POST['page7_html'].'",'.
			'data8="'.$_POST['page8_html'].'",'.
			'data9="'.$_POST['page9_html'].'",'.
			'data10="'.$_POST['page10_html'].'",'.
			'name="'.$_POST['form_name'].'",'.
			'status="'.$_POST['form_published'].'",'.
			'groups="'.$_POST['groups'].'",'.
			'css_list="'.$_POST['css_list'].'",'.
			'js_list="'.$_POST['js_list'].'",'.
			'php_list="'.$_POST['php_list'].'",'.
			'element_to_page="'.$_POST['element_to_page'].'" '.
			'where id='.intval($_POST['fid']);
			$database->setQuery( $query );
			$total = $database->query();
		} else {
			$fromname="Admin ".$_SERVER['HTTP_HOST'];
			$fromemail="admin@".$_SERVER['HTTP_HOST'];
			$subject="No reply email";
			$cc=""; $bcc="";
			$message="Dear user,\n\n\nThank you for your interest in our project.\n\nAdmin\n\nP.S. Please do not reply to this message. It has been generated automatically.";
			$query = 'insert into #__smartformer_forms set '.
			'data1="'.$_POST['page1_html'].'",'.
			'data2="'.$_POST['page2_html'].'",'.
			'data3="'.$_POST['page3_html'].'",'.
			'data4="'.$_POST['page4_html'].'",'.
			'data5="'.$_POST['page5_html'].'",'.
			'data6="'.$_POST['page6_html'].'",'.
			'data7="'.$_POST['page7_html'].'",'.
			'data8="'.$_POST['page8_html'].'",'.
			'data9="'.$_POST['page9_html'].'",'.
			'data10="'.$_POST['page10_html'].'",'.
			'name="'.$_POST['form_name'].'",'.
			'status="'.$_POST['form_published'].'",'.
			'groups="'.$_POST['groups'].'",'.
			'css_list="'.$_POST['css_list'].'",'.
			'php_list="'.$_POST['php_list'].'",'.
			'js_list="'.$_POST['js_list'].'",'.
			'element_to_page="'.$_POST['element_to_page'].'",'.
			'fromname="'.$fromname.'",'.
			'fromemail="'.$fromemail.'",'.
			'subject="'.$subject.'",'.
			'cc="'.$cc.'",'.
			'bcc="'.$bcc.'",'.
			'message="'.$message.'" ';
			$database->setQuery( $query );
			$total = $database->query();
			$query = "SELECT max(id) FROM #__smartformer_forms";
			$database->setQuery( $query );
			$rows = $database->loadRow();
			$_POST['fid'] = &$rows[0];
		}
		if ($task=="save") {
			if (defined('_VALID_MOS') && !defined( '_JEXEC' )) mosRedirect( 'index2.php?option=com_smartformer', 'The form has been successfully saved' );
				else $mainframe->redirect( 'index.php?option=com_smartformer', 'The form has been successfully saved' );
		}
		if ($task=="apply") {
			if (defined('_VALID_MOS') && !defined( '_JEXEC' )) mosRedirect("index2.php?option=com_smartformer&task=edit&id=".intval($_POST['fid']), ""); else $mainframe->redirect("index.php?option=com_smartformer&task=edit&id=".intval($_POST['fid']), "");
			//editForm( intval($_POST['fid']), $option );
		}
	}
}

function publishForm( $mode, $cid) {
	global $database, $mainframe;
	if (defined( '_JEXEC' )) $database=& JFactory::getDBO();
	$query = "update #__smartformer_forms set status=$mode where id in (".implode($cid,',').")";
	$database->setQuery( $query );
	$total = $database->query();
	if (defined('_VALID_MOS') && !defined( '_JEXEC' )) mosRedirect( 'index2.php?option=com_smartformer', 'Selected forms have been successfully '.(($mode==1)?'published':'unpublished') );
		else $mainframe->redirect( 'index.php?option=com_smartformer', 'Selected forms have been successfully '.(($mode==1)?'published':'unpublished') );


}

function deleteForm( $cid) {
	global $database, $mainframe;
	if (defined( '_JEXEC' )) $database=& JFactory::getDBO();
	$query = "delete from #__smartformer_forms where id in (".implode($cid,',').")";
	$database->setQuery( $query );
	$total = $database->query();
	if (defined('_VALID_MOS') && !defined( '_JEXEC' )) mosRedirect( 'index2.php?option=com_smartformer', 'Selected forms have been successfully deleted' ); else
		$mainframe->redirect( 'index.php?option=com_smartformer', 'Selected forms have been successfully deleted' );

}
function cancelForm($formid){
	global $database, $mainframe;
	if (defined( '_JEXEC' )) $database=& JFactory::getDBO();
	if ($formid>0) {
		$query = 'update #__smartformer_forms set checked_out=0 where id='.$formid;
		$database->setQuery( $query );
		$total = $database->query();
	}
	if (defined('_VALID_MOS') && !defined( '_JEXEC' )) mosRedirect( 'index2.php?option=com_smartformer', '' ); else $mainframe->redirect( 'index.php?option=com_smartformer', '' );
}

function createMenuItem( $fid, $menu_name, $menu_type, $published ) {
	global $database, $mainframe;
	if (defined( '_JEXEC' )) $database=& JFactory::getDBO();
	$query = 'SELECT id FROM #__components where `option`="com_smartformer"';
	$database->setQuery( $query );
	$componentid = $database->loadResult();

	$url=$GLOBALS['sf_live_site'].'/index.php?option=com_smartformer&formid='.$fid;
	$query = 'insert into #__menu set published="'.$published.'", menutype="'.$menu_type.'", name="'.$menu_name.
		'", link="index.php?option=com_smartformer", params="formid='.$fid.'", '.
		'type="components", componentid='.$componentid.', ordering=1, utaccess=3';
	if (defined( '_JEXEC' )) {
		$query = 'insert into #__menu set published="'.$published.'", menutype="'.$menu_type.'", name="'.$menu_name.
			'", link="index.php?option=com_smartformer", params="formid='.$fid.'", '.
			'type="component", componentid='.$componentid.', ordering=1, utaccess=3, `alias`="form_'.$fid.'"';
	}
	$database->setQuery( $query );
	$total = $database->query();

	$query = 'SELECT id FROM #__menu order by id desc limit 1';
	$database->setQuery( $query );
	$formid = $database->loadResult();
	if (defined('_VALID_MOS') && !defined( '_JEXEC' )) mosRedirect( "index2.php?option=com_menus&menutype=$menu_type&task=edit&id=$formid&hidemainmenu=1", 'New form menu item has been created. You may complete the details here'); else
		$mainframe->redirect( "index.php?option=com_menus&menutype=$menu_type&task=edit&cid[]=$formid&hidemainmenu=1", 'New form menu item has been created. You may complete the details here' );
}
?>
