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

if (isset($_POST['form_name']) && $_POST['form_name']=='') $task='edit';
if (defined( '_VALID_MOS' ) && !defined( '_JEXEC' )) {
	switch ($task) {

		case 'edit':
		case 'new':
		case 'apply':
		case 'add':
			global $id;
			mosMenuBar::startTable();
//			mosMenuBar::preview( 'contentwindow', true );
//			mosMenuBar::spacer();
			mosMenuBar::save();
			mosMenuBar::spacer();
			mosMenuBar::apply();
			mosMenuBar::spacer();
			if ( $id ) {
				// for existing content items the button is renamed `close`
				mosMenuBar::cancel( 'cancel', 'Close' );
			} else {
				mosMenuBar::cancel();
			}
			mosMenuBar::endTable();
			break;

		case 'cancelPreview':
		case 'update_list':
		case 'data':
			mosMenuBar::startTable();
			mosMenuBar::publishList('publishData');
			mosMenuBar::spacer();
			mosMenuBar::unpublishList('unpublishData');
			mosMenuBar::spacer();
			mosMenuBar::custom( 'exportAll', 'archive.png', 'archive_f2.png', 'Export All', false );
			mosMenuBar::spacer();
			mosMenuBar::custom( 'exportData', 'archive.png', 'archive_f2.png', 'Export', true );
			mosMenuBar::spacer();
			mosMenuBar::custom( 'exportPDF', 'archive.png', 'archive_f2.png', 'PDF', true );
			mosMenuBar::spacer();
			mosMenuBar::deleteList('', 'removeData', 'Delete');
			mosMenuBar::spacer();
			mosMenuBar::cancel('cancelData');
			mosMenuBar::endTable();
			break;
		case 'saveData':
		case 'cancelEdit':
		case 'preview':
			mosMenuBar::startTable();
			mosMenuBar::custom( 'exportPDF', 'archive.png', 'archive_f2.png', 'PDF', true );
			mosMenuBar::spacer();
			mosMenuBar::editListX('edit_data');
			mosMenuBar::spacer();
			mosMenuBar::deleteList('', 'removeData', 'Delete');
			mosMenuBar::spacer();
			mosMenuBar::cancel('cancelPreview');
			mosMenuBar::endTable();
			break;
		case 'applyData':
		case 'edit_data':
			mosMenuBar::startTable();
			mosMenuBar::save('saveData');
			mosMenuBar::spacer();
			mosMenuBar::apply('applyData');
			mosMenuBar::spacer();
			mosMenuBar::cancel('cancelEdit', 'Cancel');
			mosMenuBar::endTable();
			break;
		case 'settings':
			mosMenuBar::startTable();
			mosMenuBar::save('saveSettings');
			mosMenuBar::spacer();
			mosMenuBar::apply('applySettings');
			mosMenuBar::spacer();
			mosMenuBar::cancel('cancelSettings', 'Cancel');
			mosMenuBar::endTable();
			break;

		default:
			mosMenuBar::startTable();
			mosMenuBar::publishList();
			mosMenuBar::spacer();
			mosMenuBar::unpublishList();
			mosMenuBar::spacer();
			mosMenuBar::deleteList();
			mosMenuBar::spacer();
			mosMenuBar::addNewX();
			mosMenuBar::spacer();
			mosMenuBar::endTable();

			break;
	}
} else {
	switch ($task) {

		case 'edit':
		case 'new':
		case 'apply':
		case 'add':
			JToolBarHelper::title( "Form Editor", 'generic.png' );
			global $id;
//			JToolBarHelper::preview( 'contentwindow', true );
			JToolBarHelper::save();
			JToolBarHelper::apply();
			if ( $id ) {
				// for existing content items the button is renamed `close`
				JToolBarHelper::cancel( 'cancel', 'Close' );
			} else {
				JToolBarHelper::cancel();
			}
			break;

		case 'cancelPreview':
		case 'update_list':
		case 'data':
			JToolBarHelper::title( "Data List", 'generic.png' );
			JToolBarHelper::publishList('publishData');
			JToolBarHelper::unpublishList('unpublishData');
			JToolBarHelper::custom( 'exportAll', 'archive.png', 'archive.png', 'Export All', false );
			JToolBarHelper::custom( 'exportData', 'archive.png', 'archive.png', 'Export', true );
			JToolBarHelper::custom( 'exportPDF', 'archive.png', 'archive.png', 'PDF', true );
			JToolBarHelper::deleteList('', 'removeData', 'Delete');
			JToolBarHelper::cancel('cancelData', 'Cancel');
			break;
		case 'saveData':
		case 'cancelEdit':
		case 'preview':
			JToolBarHelper::title( "Form Data", 'generic.png' );
			JToolBarHelper::custom( 'exportPDF', 'archive.png', 'archive.png', 'PDF', true );
			JToolBarHelper::editListX('edit_data');
			JToolBarHelper::deleteList('', 'removeData', 'Delete');
			JToolBarHelper::cancel('cancelPreview', 'Cancel');
			break;

		case 'applyData':
		case 'edit_data':
			JToolBarHelper::title( "Edit Data", 'generic.png' );
			JToolBarHelper::save('saveData');
			JToolBarHelper::apply('applyData');
			JToolBarHelper::cancel('cancelEdit', 'Cancel');
			break;

		case 'settings':
			JToolBarHelper::title( "Edit Settings", 'generic.png' );
			JToolBarHelper::save('saveSettings');
			JToolBarHelper::apply('applySettings');
			JToolBarHelper::cancel('cancelSettings', 'Cancel');
			break;

		default:
			JToolBarHelper::title( "Form Manager", 'generic.png' );
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
			JToolBarHelper::deleteList();
			JToolBarHelper::addNewX();

			break;
	}

}

?>
