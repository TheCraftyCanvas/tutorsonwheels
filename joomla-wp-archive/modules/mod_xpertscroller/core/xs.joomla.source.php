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
require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class XSJoomlaSource{
    
    function get_articles($config) {
        
        global $mainframe;
        
        $db         =& JFactory::getDBO();
        $user       =& JFactory::getUser();
        $userId     =  (int) $user->get('id');
        $aid        =  $user->get('aid', 0);
        $nullDate   =  $db->getNullDate();
        $date       =& JFactory::getDate();
        $now        =  $date->toMySQL();
        $where      = '';
        $cparams    =& $mainframe->getParams('com_content');
        
        $catId = $config['joomla_cat_id'];
        $catCondition ='';
        if(  $catId != '' ){
            if (! is_array ( $catId ) ) {
                $catId = preg_split ( '/,/', $catId );
            }
            $catId = '"' . implode ( '","', $catId ) . '"'; 
        }

        // ordering
        switch ($config['item_ordering']) {
            case 'date' :
                $orderby = 'a.created ASC';
                break;
            case 'rdate' :
                $orderby = 'a.created DESC';
                break;
            case 'alpha' :
                $orderby = 'a.title';
                break;
            case 'ralpha' :
                $orderby = 'a.title DESC';
                break;
            case 'order' :
                $orderby = 'a.ordering';
                break;
            default :
                $orderby = 'a.id DESC';
                break;
        }
        
        if($config['show_front'] != 2){
            if( $catId ) {
                $catCondition .= ' AND a.catid IN ('.$catId.') ';
            }    
        }
        // Content Items only
        $query  = 'SELECT a.*,
            cc.description as catdesc, cc.title as cattitle,s.description as secdesc, s.title as sectitle,' .
            ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
            ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":",cc.id,cc.alias) ELSE cc.id END as catslug,'.
            ' CASE WHEN CHAR_LENGTH(s.alias) THEN CONCAT_WS(":", s.id, s.alias) ELSE s.id END as secslug'. 
            "\n FROM #__content AS a".
            ($config['show_front'] == '0' ? ' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id' : '') .
            ($config['show_front'] == '2' ? ' INNER JOIN #__content_frontpage AS f ON f.content_id = a.id' : '') .
            ' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
            ' INNER JOIN #__sections AS s ON s.id = a.sectionid'.
            "\n WHERE a.state = 1". 
            ($catId && $config['show_front'] != 2 ? $catCondition : '').
            ($config['show_front'] == '0' ? ' AND f.content_id IS NULL ' : '').
            "\n AND ( a.publish_up = " . $db->Quote( $db->getNullDate() ) . " OR a.publish_up <= " . $db->Quote( $now  ) . " )".                    "\n AND ( a.publish_down = " . $db->Quote( $db->getNullDate() ) . " OR a.publish_down >= " . $db->Quote( $now  ) . " )"
            . ( ( !$mainframe->getCfg( 'shownoauth' ) ) ? "\n AND a.access <= " . (int) $aid : '' )
            ;
            
        $query .= ' ORDER BY ' . $orderby;
        $db->setQuery($query, 0, $config['max_article']);
        $rows = $db->loadObjectList();
        $i=0;
        $lists    = array();

        if(is_array($rows) && count($rows)>0){
            foreach($rows as $row){
                $lists[$i]->id = $row->id;
                $lists[$i]->created = $row->created;
                $lists[$i]->modified = $row->modified;
                $lists[$i]->title = htmlspecialchars( $row->title );
                $lists[$i]->introtext = $this->prepare_text($row->introtext,$config['intro_text_limit']);
                
                $lists[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
                $images = $this->get_image($row->introtext);
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
    
    function get_image($text) {      
        
        preg_match("/\<img.+?src=\"(.+?)\".+?\/>/", $text, $matches);        
        
        $images = new stdClass();
        $images->image = false;

        $paths = array();
        
        if (isset($matches[1])) {
            $image_path = $matches[1];

            //joomla 1.5 only
            $full_url = JURI::base();
            
//            //remove any protocol/site info from the image path
//            $parsed_url = parse_url($full_url);
//            $paths[] = $full_url;
//            if (isset($parsed_url['path']) && $parsed_url['path'] != "/") $paths[] = $parsed_url['path'];
//
//            foreach ($paths as $path) {
//                if (strpos($image_path,$path) !== false) {
//                    $image_path = substr($image_path,strpos($image_path, $path)+strlen($path));
//                }
//            }
//
//            // remove any / that begins the path
//            if (substr($image_path, 0 , 1) == '/') $image_path = substr($image_path, 1);
//
//            //if after removing the uri, still has protocol then the image
//            //is remote and we don't support thumbs for external images
//            if (strpos($image_path,'http://') !== false ||
//                strpos($image_path,'https://') !== false) {
//                return false;
//            }
            
            $images->image = JURI::Root(True)."/".$image_path;   
        } 
        return $images;
    }
}
