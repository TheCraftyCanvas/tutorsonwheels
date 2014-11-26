<?php
/**
* @version		$Id: helper.php 10857 $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class modFenrislatestHelper
{
	function getList(&$params)
	{
		global $mainframe;

		$db			=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$userId		= (int) $user->get('id');

		$count		= (int) $params->get('count', 5);
		$catid		= trim( $params->get('catid') );
		$secid		= trim( $params->get('secid') );
		$fdate = $params->get('fdate');
		$usertyp = $params->get('usertyp');
		$datetyp = $params->get('datetyp');
		$show_front	= $params->get('show_front', 1);
		$aid		= $user->get('aid', 0);

		$contentConfig = &JComponentHelper::getParams( 'com_content' );
		$access		= !$contentConfig->get('show_noauth');

		$nullDate	= $db->getNullDate();

		$date =& JFactory::getDate();
		$now = $date->toMySQL();

		$where		= 'a.state = 1'
			. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
			. ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
			;

		// User Filter
		switch ($params->get( 'user_id' ))
		{
			case 'by_me':
				$where .= ' AND (created_by = ' . (int) $userId . ' OR modified_by = ' . (int) $userId . ')';
				break;
			case 'not_me':
				$where .= ' AND (created_by <> ' . (int) $userId . ' AND modified_by <> ' . (int) $userId . ')';
				break;
		}

		// Ordering
		switch ($params->get( 'ordering' ))
		{
			case 'm_dsc':
				$ordering		= 'a.modified DESC, a.created DESC';
				break;
			case 'c_dsc':
			default:
				$ordering		= 'a.created DESC';
				break;
		}

		if ($catid)
		{
			$ids = explode( ',', $catid );
			JArrayHelper::toInteger( $ids );
			$catCondition = ' AND (cc.id=' . implode( ' OR cc.id=', $ids ) . ')';
		}
		if ($secid)
		{
			$ids = explode( ',', $secid );
			JArrayHelper::toInteger( $ids );
			$secCondition = ' AND (s.id=' . implode( ' OR s.id=', $ids ) . ')';
		}

		// Content Items only
		$query = 'SELECT a.*, u.id, u.name, u.username,' .
			' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
			' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
			' FROM #__content AS a' .
			($show_front == '0' ? ' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id' : '') .
			' LEFT JOIN #__users AS u ON u.id = a.created_by' .
			' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
			' INNER JOIN #__sections AS s ON s.id = a.sectionid' .
			' WHERE '. $where .' AND s.id > 0' .
			($access ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
			($catid ? $catCondition : '').
			($secid ? $secCondition : '').
			($show_front == '0' ? ' AND f.content_id IS NULL ' : '').
			' AND s.published = 1' .
			' AND cc.published = 1' .
			' ORDER BY '. $ordering;
		$db->setQuery($query, 0, $count);
		$rows = $db->loadObjectList();

		$i		= 0;
		

// urèuje typ data - vytvoøení
if ($datetyp =='0')
{
// urèuje typ zobrazeného jména
switch ($usertyp)
{
case 0:
		$lists	= array();
		foreach ( $rows as $row )
		{
			if($row->access <= $aid)
			{
				$lists[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
			} else {
				$lists[$i]->link = JRoute::_('index.php?option=com_user&view=login');
			}
			$lists[$i]->text = htmlspecialchars( $row->title );
			$lists[$i]->creationdate = JHTML::_('date', $row->created, $fdate);
			$lists[$i]->created_by_alias = htmlspecialchars( $row->created_by_alias );
			$lists[$i]->created = htmlspecialchars( $row->created );
			$lists[$i]->name = htmlspecialchars( $row->name );
			$i++;
		};
break;
case 1:
		$lists	= array();
		foreach ( $rows as $row )
		{
			if($row->access <= $aid)
			{
				$lists[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
			} else {
				$lists[$i]->link = JRoute::_('index.php?option=com_user&view=login');
			}
			$lists[$i]->text = htmlspecialchars( $row->title );
			$lists[$i]->creationdate = JHTML::_('date', $row->created, $fdate);
			$lists[$i]->created_by_alias = htmlspecialchars( $row->created_by_alias );
			$lists[$i]->created = htmlspecialchars( $row->created );
			$lists[$i]->name = htmlspecialchars( $row->username );
			$i++;
		};
break;
case 2:
		$lists	= array();
		foreach ( $rows as $row )
		{
			if($row->access <= $aid)
			{
				$lists[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
			} else {
				$lists[$i]->link = JRoute::_('index.php?option=com_user&view=login');
			}
			$lists[$i]->text = htmlspecialchars( $row->title );
			$lists[$i]->creationdate = JHTML::_('date', $row->created, $fdate);
			$lists[$i]->created_by_alias = htmlspecialchars( $row->created_by_alias );
			$lists[$i]->created = htmlspecialchars( $row->created );
			$lists[$i]->name = htmlspecialchars( $row->created_by_alias );
			$i++;
		};
};
}
// urèuje typ data - úpravy
else {
// urèuje typ zobrazeného jména
switch ($usertyp){
case 0:
		$lists	= array();
		foreach ( $rows as $row )
		{
			if($row->access <= $aid)
			{
				$lists[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
			} else {
				$lists[$i]->link = JRoute::_('index.php?option=com_user&view=login');
			}
			$lists[$i]->text = htmlspecialchars( $row->title );
			$lists[$i]->creationdate = JHTML::_('date', $row->modified, $fdate);
			$lists[$i]->created_by_alias = htmlspecialchars( $row->created_by_alias );
			$lists[$i]->modified = htmlspecialchars( $row->modified );
			$lists[$i]->name = htmlspecialchars( $row->name );
			$i++;
		};
break;
case 1:
		$lists	= array();
		foreach ( $rows as $row )
		{
			if($row->access <= $aid)
			{
				$lists[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
			} else {
				$lists[$i]->link = JRoute::_('index.php?option=com_user&view=login');
			}
			$lists[$i]->text = htmlspecialchars( $row->title );
			$lists[$i]->creationdate = JHTML::_('date', $row->modified, $fdate);
			$lists[$i]->created_by_alias = htmlspecialchars( $row->created_by_alias );
			$lists[$i]->modified = htmlspecialchars( $row->modified );
			$lists[$i]->name = htmlspecialchars( $row->username );
			$i++;
		};
break;
case 2:
		$lists	= array();
		foreach ( $rows as $row )
		{
			if($row->access <= $aid)
			{
				$lists[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
			} else {
				$lists[$i]->link = JRoute::_('index.php?option=com_user&view=login');
			}
			$lists[$i]->text = htmlspecialchars( $row->title );
			$lists[$i]->creationdate = JHTML::_('date', $row->modified, $fdate);
			$lists[$i]->created_by_alias = htmlspecialchars( $row->created_by_alias );
			$lists[$i]->modified = htmlspecialchars( $row->modified );
			$lists[$i]->name = htmlspecialchars( $row->created_by_alias );
			$i++;
		};
}
}
		return $lists;
	}
}