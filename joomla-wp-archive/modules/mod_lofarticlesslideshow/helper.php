<?php 
/**
 * $ModDesc
 * 
 * @version		$Id: helper.php $Revision
 * @package		modules
 * @subpackage	mod_lofslidenews
 * @copyright	Copyright (C) JAN 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>. All rights reserved.
 * @website 	htt://landofcoder.com
 * @license		GNU General Public License version 2
 */
// no direct access
defined('_JEXEC') or die;

require_once JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php';
if( !defined('PhpThumbFactoryLoaded') ) {
	require_once dirname(__FILE__).DS.'libs'.DS.'phpthumb/ThumbLib.inc.php';
	define('PhpThumbFactoryLoaded',1);
}
abstract class modLofArticlesSlideShowHelper {
	
	/**
	 * get list articles
	 */
	public static function getList( $params ){
		return self::getListJLOneFive( $params );
	}
	
	/**
	 * get the list of articles, using for joomla 1.5.x
	 * 
	 * @param JParameter $params;
	 * @return Array
	 */
	public static function getListJLOneFive( $params ){
		global $mainframe;
		$maxTitle  	   = $params->get( 'max_title', '100' );
		$maxDesciption = $params->get( 'max_description', 100 );
		$openTarget    = $params->get( 'open_target', 'parent' );
		$formatter     = $params->get( 'style_displaying', 'title' );
		$titleMaxChars = $params->get( 'title_max_chars', '100' );
		$descriptionMaxChars = $params->get( 'description_max_chars', 100 );
		$condition     = self::buildConditionQuery( $params );
		$isThumb       = $params->get( 'auto_renderthumb',1);
		$ordering      = $params->get( 'ordering', 'created_asc');
		$limit 	       = $params->get( 'limit_items', 4 );
		$ordering      = str_replace( '_', '  ', $ordering );
		if( trim($ordering) == 'rand' ){ $ordering = ' RAND() '; }
		$my 	       = &JFactory::getUser();
		$aid	       = $my->get( 'aid', 0 );
		$thumbWidth    = (int)$params->get( 'thumbnail_width', 60 );
		$thumbHeight   = (int)$params->get( 'thumbnail_height', 60 );
		$imageHeight   = (int)$params->get( 'main_height', 300 ) ;
		$imageWidth    = (int)$params->get( 'main_width', 900 ) ;
		$excludeIds    = $params->get( 'exclude_article_ids', '' );
		
		$db	    = &JFactory::getDBO();
		$date   =& JFactory::getDate();
		$now    = $date->toMySQL();
		
		
		//
		$overrideLinks = array();
		if( $tmp = $params->get('override_links', '' ) ){
				$tmp = is_array($tmp)?$tmp:array($tmp);
				foreach( $tmp as $titem ){
					$link  = explode("@", $titem );	
					if( count($link) > 1 ){
						$overrideLinks[(int)trim(strtolower($link[0]))]=$link[1];
					}
				}
			}
		// make sql query
		$query 	= 'SELECT a.*,cc.description as catdesc, cc.title as category_title, cc.title as cattitle,s.description as secdesc, s.title as sectitle,' 
				. ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'
				. ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":",cc.id,cc.alias) ELSE cc.id END as catslug,'
				. ' CASE WHEN CHAR_LENGTH(s.alias) THEN CONCAT_WS(":", s.id, s.alias) ELSE s.id END as secslug'
				. "\n FROM #__content AS a"
				. ' INNER JOIN #__categories AS cc ON cc.id = a.catid' 
				. ' INNER JOIN #__sections AS s ON s.id = a.sectionid'
				. "\n WHERE a.state = 1"
				. "\n AND ( a.publish_up = " . $db->Quote( $db->getNullDate() ) . " OR a.publish_up <= " . $db->Quote( $now  ) . " )"
				. "\n AND ( a.publish_down = " . $db->Quote( $db->getNullDate() ) . " OR a.publish_down >= " . $db->Quote( $now  ) . " )"
				. ( ( !$mainframe->getCfg( 'shownoauth' ) ) ? "\n AND a.access <= " . (int) $aid : '' )
		;
		// frontpage articles
		switch( $params->get('frontpage','without_fp') ){
			case 'only_fp':
				$query .= "  AND a.id  IN (SELECT content_id FROM #__content_frontpage ) ";
				break;
			case 'without_fp':
				$query .= "  AND a.id NOT IN(SELECT content_id FROM #__content_frontpage ) ";
				break;
		}
		if( trim($excludeIds) ){
			$excludeIds = explode(",",$excludeIds);
			$aids = array();
			foreach( $excludeIds as $tmpId ){
				$aids[] = (int)$tmpId; 
			}
		
			$query .= '  AND a.id NOT IN ("'.implode('","',$aids).'") ';
		}
		
		$query .=  $condition . ' ORDER BY ' . $ordering;
		$query .=  $limit ? ' LIMIT ' . $limit : '';
		$db->setQuery($query);
		$data = $db->loadObjectlist();
		if( empty($data) ) return array();
		
		foreach( $data as $key => $item ){	
			if($item->access <= $aid ) {
				$data[$key]->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug, $item->sectionid));
			} else {
				$data[$key]->link = JRoute::_('index.php?option=com_user&view=login');
			}
			if( array_key_exists($item->id,$overrideLinks) ){
				$data[$key]->link=$overrideLinks[$item->id];
			}
			$item->date = JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC2')); 
			$item->catlink = JRoute::_(ContentHelperRoute::getCategoryRoute($item->catslug, $item->sectionid));
			$item->subtitle = self::substring( $item->title, $titleMaxChars );
			$item->description = self::substring( $item->introtext,
																$descriptionMaxChars );
																
			self::parseImages( $item );
			if( $item->mainImage &&  $image=self::renderThumb($item->mainImage, $imageWidth, $imageHeight, $item->title, $isThumb ) ){
				$item->mainImage = $image;
			}
			
			if( $item->thumbnail &&  $image = self::renderThumb($item->thumbnail, $thumbWidth, $thumbHeight, $item->title, $isThumb ) ){
				$item->thumbnail = $image;
			}
		}
		return $data;	
	}
	
	/**
	 * build condition query base parameter  
	 * 
	 * @param JParameter $params;
	 * @return string.
	 */
	public static  function buildConditionQuery( $params ){
		$source = trim($params->get( 'source', 'category' ) );
		if( $source == 'category' ){
			$catids = $params->get( 'category','');
			
			if( !$catids ){
				return '';
			}
			$catids = !is_array($catids) ? $catids : '"'.implode('","',$catids).'"';
			$condition = ' AND  a.catid IN( '.$catids.' )';
		} else {
			$ids = preg_split('/,/',$params->get( 'article_ids',''));	
			$tmp = array();
			foreach( $ids as $id ){
				$tmp[] = (int) trim($id);
			}
			$condition = " AND a.id IN('". implode( "','", $tmp ) ."')";
		}
		return $condition;
	}
	
	/**
	 * parser a custom tag in the content of article to get the image setting.
	 * 
	 * @param string $text
	 * @return array if maching.
	 */
	public static function parserCustomTag( $text ){ 
		if( preg_match("#{lofimg(.*)}#s", $text, $matches, PREG_OFFSET_CAPTURE) ){ 
			return $matches;
		}	
		return null;
	}
	
	/**
	 *  check the folder is existed, if not make a directory and set permission is 755
	 *
	 *
	 * @param array $path
	 * @access public,
	 * @return boolean.
	 */
	public static function makeDir( $path ){
		$folders = explode ( '/',  ( $path ) );
		$tmppath =  JPATH_SITE.DS.'images'.DS.'lofthumbs'.DS;
		if( !file_exists($tmppath) ) {
			JFolder::create( $tmppath, 0755 );
		}; 
		for( $i = 0; $i < count ( $folders ) - 1; $i ++) {
			if (! file_exists ( $tmppath . $folders [$i] ) && ! JFolder::create( $tmppath . $folders [$i], 0755) ) {
				return false;
			}	
			$tmppath = $tmppath . $folders [$i] . DS;
		}		
		return true;
	}
	
	/**
	 *  check the folder is existed, if not make a directory and set permission is 755
	 *
	 *
	 * @param array $path
	 * @access public,
	 * @return boolean.
	 */
	public static function renderThumb( $path, $width=100, $height=100, $title='', $isThumb=true ){
		
		if( $isThumb ){
			$path = str_replace( JURI::base(), '', $path );
			$imagSource = JPATH_SITE.DS. str_replace( '/', DS,  $path );
			if( file_exists($imagSource)  ) {
				$path =  $width."x".$height.'/'.$path;
				$thumbPath = JPATH_SITE.DS.'images'.DS.'lofthumbs'.DS. str_replace( '/', DS,  $path );
				if( !file_exists($thumbPath) ) {
					$thumb = PhpThumbFactory::create( $imagSource  );  
					if( !self::makeDir( $path ) ) {
							return '';
					}		
					$thumb->adaptiveResize( $width, $height);
					 
					$thumb->save( $thumbPath  ); 
				}
				$path = JURI::base().'images/lofthumbs/'.$path;
			} 
		}
		return '<img src="'.$path.'" title="'.$title.'" >';
	}
	
	/**
	 * get parameters from configuration string.
	 *
	 * @param string $string;
	 * @return array.
	 */
	public static function parseParams( $string ) {
		$string = html_entity_decode($string, ENT_QUOTES);
		$regex = "/\s*([^=\s]+)\s*=\s*('([^']*)'|\"([^\"]*)\"|([^\s]*))/";
		 $params = null;
		 if(preg_match_all($regex, $string, $matches) ){
				for ($i=0;$i<count($matches[1]);$i++){ 
				  $key 	 = $matches[1][$i];
				  $value = $matches[3][$i]?$matches[3][$i]:($matches[4][$i]?$matches[4][$i]:$matches[5][$i]);
				  $params[$key] = $value;
				}
		  }
		  return $params;
	}
	
	/**
	 * parser a image in the content of article.
	 *
	 * @param.
	 * @return
	 */
	public static function parseImages( &$row ){
		$text =  $row->introtext.$row->fulltext;
		$data = self::parserCustomTag( $text );
		if( isset($data[1][0]) ){
			$tmp = self::parseParams( $data[1][0] );
			$row->mainImage = isset($tmp['src']) ? $tmp['src']:'';
			$row->thumbnail = $row->mainImage ;// isset($tmp['thumb']) ?$tmp['thumb']:'';	
		} else {
			$regex = "/\<img.+src\s*=\s*\"([^\"]*)\"[^\>]*\>/";
			preg_match ($regex, $text, $matches); 
			$images = (count($matches)) ? $matches : array();
			if (count($images)){
				$row->mainImage = $images[1];
				$row->thumbnail = $images[1];
			} else {
				$row->thumbnail = '';
				$row->mainImage = '';	
			}
		}
	}
	
	/**
	 * load css - javascript file.
	 * 
	 * @param JParameter $params;
	 * @param JModule $module
	 * @return void.
	 */
	public static function loadMediaFiles( $params, $module ){
		// if the verion is equal 1.6.x
		$app = JFactory::getApplication();
		if( (float)$app->get ('MooToolsVersion')>='1.2' ) {
			JHTML::script( $module->module.'_jl16x.js','modules/'.$module->module.'/assets/');
		} else {
			JHTML::script( $module->module.'_jl15x.js','modules/'.$module->module.'/assets/');
		}
		JHTML::stylesheet( $module->module.'.css','modules/'.$module->module.'/assets/' );	
	}
	
	/**
	 * get a subtring with the max length setting.
	 * 
	 * @param string $text;
	 * @param int $length limit characters showing;
	 * @param string $replacer;
	 * @return tring;
	 */
	public static function substring( $text, $length = 100, $replacer='...' ){
		$string = strip_tags( $text );
		return JString::strlen( $string ) > $length ? JString::substr( $string, 0, $length ).$replacer: $string;
	}
}
?>