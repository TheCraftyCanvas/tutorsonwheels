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
abstract class modLofArticlesSrollerHelper {
	
	/**
	 * get list articles
	 */
	public static function getList( $params ){
		if( JVersion::isCompatible('1.6.0') ) {	
			return self::getListJLOneSix( $params );
		} 
		return self::getListJLOneFive( $params );
	}
	
	/**
	 * get the list of articles, using for joomla 1.6.x
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
		$my 	       = &JFactory::getUser();
		$aid	       = $my->get( 'aid', 0 );
		$thumbWidth    = (int)$params->get( 'thumbnail_width', 180 );
		$thumbHeight   = (int)$params->get( 'thumbnail_height', 60 );
		$thumbnailAlignment = $params->get( 'thumbnail_alignment', '' );
		$showThumbnail = $params->get( 'show_thumbnail', '1' );
		
		$db	    = &JFactory::getDBO();
		$date   =& JFactory::getDate();
		$now    = $date->toMySQL();
		
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
			$item->date = JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC2')); 
			
			$item->subtitle = self::substring( $item->title, $titleMaxChars );
			$item->description = self::substring( $item->introtext,
																$descriptionMaxChars );
				
			if( $showThumbnail ){	
				self::parseImages( $item );				
				if( $item->thumbnail &&  $image = self::renderThumb($item->thumbnail, $thumbWidth, $thumbHeight, $thumbnailAlignment, $item->title, $isThumb ) ){
					$item->thumbnail = $image;
				} 
			} else {
				$item->thumbnail ='';
			}
		}
		return $data;	
	}
	
	/**
	 * get the list of articles, using for joomla 1.6.x
	 * 
	 * @param JParameter $params;
	 * @return array;
	 */
	public static function getListJLOneSix( &$params )	{
		$formatter = $params->get( 'style_displaying', 'title' );
		$titleMaxChars = $params->get( 'title_max_chars', '100' );
		$descriptionMaxChars = $params->get( 'description_max_chars', 100 );
		$thumbWidth    = (int)$params->get( 'thumbnail_width', 180 );
		$thumbHeight   = (int)$params->get( 'thumbnail_height', 60 );
		$isThumb       = $params->get( 'auto_renderthumb',1);
		$thumbnailAlignment = $params->get( 'thumbnail_alignment', '' );
		$showThumbnail = $params->get( 'show_thumbnail', '1' );
		
		JModel::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_content'.DS.'models');
		// Get an instance of the generic articles model
		$model = JModel::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
		// Set application parameters in model
		$appParams = JFactory::getApplication()->getParams();
		$model->setState('params', $appParams);
		$openTarget = $params->get( 'open_target', 'parent' );
		// Set the filters based on the module params
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('limit_items', 5));
		$model->setState('filter.published', 1);

		// Access filter
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$model->setState('filter.access', $access);

		// Category filter
		if ($catid = $params->get('category')) {
			if( count ($catid) == 1 && !$catid[0] ) {
				$model->setState('filter.category_id', null);	
			}
			else {
				$model->setState('filter.category_id', $catid);
			}
		}

		// User filter
		$userId = JFactory::getUser()->get('id');

		$ordering  = $params->get('ordering', 'created_asc');
		$limit 	   = $params->get( 'limit_items', 4 );
		$ordering = split( '_', $ordering );

		$model->setState('list.ordering', $ordering[0]);
		$model->setState('list.direction', $ordering[1]);

		$items = $model->getItems();

		foreach ($items as $key => &$item) {
			$item->slug = $item->id.':'.$item->alias;
			$item->catslug = $item->catid.':'.$item->category_alias;

			if ( $access || in_array($item->access, $authorised) ) {		
				$item->link = JRoute::_(ContentRoute::article($item->slug, $item->catslug));
			}
			
			else {
				$item->link = JRoute::_('index.php?option=com_user&view=login');
			}
			$item->date = JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC2')); 
		
			if( $showThumbnail ) {		
				self::parseImages( $item );
				
				if( $item->thumbnail &&  $image = self::renderThumb($item->thumbnail, $thumbWidth, $thumbHeight, $thumbnailAlignment, $item->title ,  $isThumb ) ){
					$item->thumbnail = $image;
				}
			} else {
				$item->thumbnail = '';
			}
			$item->introtext = JHtml::_('content.prepare', $item->introtext);
			$item->subtitle = self::substring( $item->title, $titleMaxChars );
			$item->description = self::substring( $item->introtext, $descriptionMaxChars );
		
		}
		return $items;
	}

	/**
	 * build condition query base parameter  
	 * 
	 * @param JParameter $params;
	 * @return string.
	 */
	public static  function buildConditionQuery( $params ){
		
		$catids = $params->get('category','');
		if( !$catids ){
			return '';
		}
		$catids = !is_array($catids) ? $catids : '"'.implode('","',$catids).'"';
		$condition = ' AND  a.catid IN( '.$catids.' )';
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
	public static function renderThumb( $path, $width=100, $height=100, $thumbnailAlignment='', $title='', $isThumb=true ){
		
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
		return '<img src="'.$path.'" title="'.$title.'" alt="'.$title.'" class="lof-image '.$thumbnailAlignment.'" height="'.$height.'" width="'.$width.'">';
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
		global $mainframe;
		// if the verion is equal 1.6.x
		if( JVersion::isCompatible('1.6.0') ) {	
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
		if( function_exists('mb_substr') ){
			if(  $length > mb_strlen($string) ) return $string; 
			return (preg_match('/^(.*)\W.*$/', 
						mb_substr($string, 0, $length+1), $matches) 
							? $matches[1] : mb_substr($string, 0, $length)) . $replacer; 
		} 
		if(  $length > strlen($string) ) return $string; 
		return (preg_match('/^(.*)\W.*$/', 
					substr($string, 0, $length+1), $matches) 
						? $matches[1] : substr($string, 0, $length)) . $replacer; 
	}
}
?>