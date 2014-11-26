<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
class CfactionXlsExport{
	var $formname;
	var $formid;
	var $group = array('id' => 'data_export', 'title' => 'Data Export');
	var $details = array('title' => 'XLS Export', 'tooltip' => 'Exports the data to XLS sheet (Actually HTML) which is supported by MS Excel.');
	
	function run($form, $actiondata){
		$mainframe =& JFactory::getApplication();
		$session =& JFactory::getSession();
		$params = new JParameter($actiondata->params);
		
		$data_path = trim($params->get('data_path', ''));
		$data = $form->get_array_value($form->data, explode('.', $data_path));		
		
		
		if(!empty($data) && is_array($data)){
			$data = array_values($data);
			$first_data_record = $data[0];
			$list_fields = strlen(trim($params->get('list_fields', ''))) ? explode(',', trim($params->get('list_fields', ''))) : array_keys($first_data_record);
			$list_headers = strlen(trim($params->get('list_headers', ''))) ? explode(',', trim($params->get('list_headers', ''))) : array_keys($first_data_record);
			
			$table_rows = '';
			//add headers
			$table_rows .= '<tr bgcolor="#999999">'."\n";
			foreach($list_headers as $k => $v){
				$table_rows .= '<td style="color:white">'.$v.'</td>'."\n";
			}
			$table_rows .= '</tr>'."\n";		
			//add data rows
			foreach($data as $record){
				$table_rows .= '<tr>'."\n";
				foreach($record as $k => $v){
					if(!in_array($k, $list_fields)){
						continue;
					}
					$table_rows .= '<td valign="top" style="mso-number-format:\@">'.$v.'</td>'."\n";
				}
				$table_rows .= '</tr>'."\n";
			}
			//finalize table
			$excel_table = "<table border='1'>".$table_rows."</table>";
			//set headers
			header("Pragma: public");  
			header("Expires: 0");  
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");  
			header("Content-Type: application/force-download");  
			header("Content-Type: application/octet-stream");  
			header("Content-Type: application/download");;  
			header("Content-Disposition: attachment;filename=".$params->get('file_name', 'cf_export.xls'));  
			header("Content-Transfer-Encoding: binary");
			header('Content-Encoding: UTF-8');
			//output data
			if((bool)$params->get('add_bom', 0) === true){
				echo "\xEF\xBB\xBF";
			}
			echo $excel_table;
			
			$mainframe =& JFactory::getApplication();
			$mainframe->close();
		}
	}
		
	function load($clear){
		if($clear){
			$action_params = array(
				'content1' => '',
				'enabled' => 1,
				'data_path' => '',
				'list_fields' => '',
				'list_headers' => '',
				'add_bom' => 0,
				'file_name' => 'cf_export.xls',
			);
		}
		return array('action_params' => $action_params);
	}
}
?>