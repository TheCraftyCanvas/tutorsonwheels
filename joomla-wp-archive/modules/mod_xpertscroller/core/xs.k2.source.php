<?php
/**
 * @package XpertScroller
 * @version 1.3
 * @author ThemeXpert http://www.themexpert.com
 * @copyright Copyright (C) 2009 - 2011 ThemeXpert
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted accessd');
require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');

class XSK2Source{
    
    function get_items($config){
        global $mainframe;
        
        $db         =& JFactory::getDBO();
        $user       =& JFactory::getUser();
        $userId     =  (int) $user->get('id');
        $aid        =  $user->get('aid', 0);
        $nullDate   =  $db->getNullDate();
        $date       =& JFactory::getDate();
        $now        =  $date->toMySQL();
        
        $query = "SELECT i.*, c.name AS categoryname,c.id AS categoryid, c.alias AS categoryalias, c.params AS categoryparams";

            if ($config['k2_item_ordering'] == 'best')
            $query .= ", (r.rating_sum/r.rating_count) AS rating";

            if ($config['k2_item_ordering'] == 'comments')
            $query .= ", COUNT(comments.id) AS numOfComments";

            $query .= " FROM #__k2_items as i LEFT JOIN #__k2_categories c ON c.id = i.catid";

            if ($config['k2_item_ordering'] == 'best')
            $query .= " LEFT JOIN #__k2_rating r ON r.itemID = i.id";

            if ($config['k2_item_ordering'] == 'comments')
            $query .= " LEFT JOIN #__k2_comments comments ON comments.itemID = i.id";

            $query .= " WHERE i.published = 1 AND i.access <= {$aid} AND i.trash = 0 AND c.published = 1 AND c.access <= {$aid} AND c.trash = 0";

            $query .= " AND ( i.publish_up = ".$db->Quote($nullDate)." OR i.publish_up <= ".$db->Quote($now)." )";
            $query .= " AND ( i.publish_down = ".$db->Quote($nullDate)." OR i.publish_down >= ".$db->Quote($now)." )";
            
            if ($config['k2_cat_filter']) {
                if (!is_null($config['k2_cat_id'])) {
                    if (is_array($config['k2_cat_id'])) {
                        if ($config['k2_get_children']) {
                            require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'models'.DS.'itemlist.php');
                            $allChildren = array();
                            foreach ($config['k2_cat_id'] as $id) {
                                $categories = K2ModelItemlist::getCategoryChilds($id, true);
                                $categories[] = $id;
                                $categories = @array_unique($categories);
                                $allChildren = @array_merge($allChildren, $categories);
                            }

                            $allChildren = @array_unique($allChildren);
                            JArrayHelper::toInteger($allChildren);
                            $sql = @implode(',', $allChildren);
                            $query .= " AND i.catid IN ({$sql})";

                        } else {
                            JArrayHelper::toInteger($config['k2_cat_id']);
                            $query .= " AND i.catid IN(".implode(',', $config['k2_cat_id']).")";
                        }

                    } else {
                        if ($config['k2_get_children']) {
                            require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'models'.DS.'itemlist.php');
                            $categories = K2ModelItemlist::getCategoryChilds($config['k2_cat_id'], true);
                            $categories[] = $config['k2_cat_id'];
                            $categories = @array_unique($categories);
                            JArrayHelper::toInteger($categories);
                            $sql = @implode(',', $categories);
                            $query .= " AND i.catid IN ({$sql})";
                        } else {
                            $query .= " AND i.catid=".(int)$config['k2_cat_id'];
                        }

                    }
                }
            }

            if ($config['k2_featured_items'] == '0')
            $query .= " AND i.featured != 1";

            if ($config['k2_featured_items'] == '2')
            $query .= " AND i.featured = 1";

            /*if ($params->get('videosOnly'))
            $query .= " AND (i.video IS NOT NULL AND i.video!='')";*/

            if ($config['k2_item_ordering'] == 'comments')
            $query .= " AND comments.published = 1";


            switch ($config['k2_item_ordering']) {

                case 'date':
                    $orderby = 'i.created ASC';
                    break;

                case 'rdate':
                    $orderby = 'i.created DESC';
                    break;

                case 'alpha':
                    $orderby = 'i.title';
                    break;

                case 'ralpha':
                    $orderby = 'i.title DESC';
                    break;

                case 'order':
                    if ($config['k2_featured_items'] == '2')
                    $orderby = 'i.featured_ordering';
                    else
                    $orderby = 'i.ordering';
                    break;

                case 'rorder':
                    if ($config['k2_featured_items'] == '2')
                    $orderby = 'i.featured_ordering DESC';
                    else
                    $orderby = 'i.ordering DESC';
                    break;

                /*case 'hits':
                    if ($params->get('popularityRange')){
                        $datenow = &JFactory::getDate();
                        $date = $datenow->toMySQL();
                        $query.=" AND i.created > DATE_SUB('{$date}',INTERVAL ".$params->get('popularityRange')." DAY) ";
                    }
                    $orderby = 'i.hits DESC';
                    break;*/

                case 'rand':
                    $orderby = 'RAND()';
                    break;

                case 'best':
                    $orderby = 'rating DESC';
                    break;

                case 'comments':
                    /*if ($params->get('popularityRange')){
                        $datenow = &JFactory::getDate();
                        $date = $datenow->toMySQL();
                        $query.=" AND i.created > DATE_SUB('{$date}',INTERVAL ".$params->get('popularityRange')." DAY) ";
                    }*/
                    $query.=" GROUP BY i.id ";
                    $orderby = 'numOfComments DESC';
                    break;

                default:
                    $orderby = 'i.id DESC';
                    break;
            }


            $query .= " ORDER BY ".$orderby;
            $db->setQuery($query, 0, $config['max_article']);
            $items = $db->loadObjectList();
                
        $i=0;
        $lists    = array();
        
        if(is_array($items) && count($items)>0){
            foreach($items as $row){
                $lists[$i]->id = $row->id;
                $lists[$i]->created = $row->created;
                $lists[$i]->modified = $row->modified;
                $lists[$i]->title = htmlspecialchars( $row->title );
                $lists[$i]->introtext = $this->prepare_text($row->introtext,$config['intro_text_limit']);
                
                $lists[$i]->link = JRoute::_(K2HelperRoute::getItemRoute($row->id.':'.$row->alias, $row->catid.':'.$row->categoryalias));
                $images = $this->get_image($row->id, $config['k2_img_size']);
                
                $lists[$i]->image = $images->image;
                $i++;
            }
        }
        return $lists;
    }
    
    function prepare_text ($text, $num_charecter){
        $text = strip_tags($text,'<p><a>');
        
        if(strlen($text)>$num_charecter && $num_charecter!=0){
            $text1 = substr ($text, 0, $num_charecter) . "....";
            return $text1;
        }
        else return $text;
    }
    
    function get_image($id, $image_size) {      
        
        $images = new stdClass();
        $images->image = false;        
        
        if (file_exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$id).'_'.$image_size.'.jpg')) {
            $image_path = 'media/k2/items/cache/'.md5("Image".$id).'_'.$image_size.'.jpg';
            $images->image = JURI::Root(true).'/'.$image_path;
        } 
        else echo "No image found!";
        return $images;
    }
}
