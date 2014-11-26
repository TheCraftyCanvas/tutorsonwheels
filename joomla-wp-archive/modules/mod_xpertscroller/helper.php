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
defined( '_JEXEC' ) or die('Restricted access');

class modXpertScrollerHelper{
    
    var $settings = array(); //hold all settins 
    var $moduleId = 0; //hold module unique id
    var $content = ''; //hold all content
    
    /*Get all params and assign them to a array */
    function init($module , $params){        
        foreach($params->_registry['_default']['data'] as $key=>$val){
            $this->settings[$key] = $val;
        }
        //set moduleid
        $this->moduleId = $module->id;
        //assign module unique id
        $this->settings['module_unique_id'] = ($this->settings['auto_module_id'] == 1) ? 'xs_'.$this->moduleId : $this->settings['module_unique_id'];
    }
    
    /*get items from core class*/
    function get_items(){
        if($this->settings['content_source'] == 'joomla'){
            if(!class_exists('XSJoomlaSource')) require_once (dirname(__FILE__).DS.'core'.DS.'xs.joomla.source.php');
            $xs_joomla = new XSJoomlaSource();
            $this->content = $xs_joomla->get_articles($this->settings);
        }
        elseif($this->settings['content_source'] == 'k2'){
            if(!class_exists('XSK2Source')) require_once (dirname(__FILE__).DS.'core'.DS.'xs.k2.source.php');
            $xs_k2 = new XSK2Source();
            $this->content = $xs_k2->get_items($this->settings);
        }
        elseif($this->settings['content_source'] ==  'vm'){
            if(!class_exists('XSVmSource')) require_once (dirname(__FILE__). DS . 'core' . DS . 'xs.vm.source.php');
            $xs_vm = new XSVmSource($this->settings);
            $this->content = $xs_vm->get_items();
            
        }
        
    }
    
    function render(){
        
        //compare total items with max amount to show. assign the lower value to maxItems variable.
        $totalItems = count($this->content);
        if( (int)$this->settings['max_article'] <= $totalItems ) $maxItems = (int)$this->settings['max_article'];
        else $maxItems = $totalItems;
        
        //number of pan.
        $totalPane = $maxItems / (int)$this->settings['col_amount'];
        
        //get this image position name and ues it as class
        $imagePosition = $this->settings['image_position'];
        
        //loop through full content object and prepare it according article settings
        foreach($this->content as $item){
            if($this->settings['content_source'] == 'joomla' or $this->settings['content_source'] == 'k2' ){
                //check image link
                if ( $this->settings['image_link'] === '1' ){
                    //check browser navigation
                    $item->image = ($this->settings['browser_nav'] === 'parent') ? "<a href=\"$item->link\"><img class=\"$imagePosition\" src=\"$item->image\" alt=\"$item->title\"></a>" : "<a target=\"_blank\" href=\"$item->link\"><img class=\"$imagePosition\" src=\"$item->image\" alt=\"$item->title\"></a>";
                }
                else  $item->image = "<img class=\"$imagePosition\" src=\"$item->image\" alt=\"$item->title\">";
                //check title publish 
                if ( $this->settings['article_title'] ==='1' ){
                    //if title publish then check is it linkable or not if linkable then check browser nav status.
                    if ( $this->settings['title_link'] === '1' ) 
                        $item->title = ($this->settings['browser_nav'] === 'parent') ? '<a href="'.$item->link.'">'.$item->title.'</a>' : '<a target="_blank" href="'.$item->link.'">'.$item->title.'</a>' ;    
                }
            }
        }
        //if content source is joomla or k2 then apply multiple layout option.
        if( $this->settings['content_source'] ==  'joomla' OR $this->settings['content_source'] ==  'k2' )
        {
            if ( $this->settings['scroller_layout'] == 'basic_h' 
            OR $this->settings['scroller_layout'] == 'basic_v' )
            {
                require( JModuleHelper::getLayoutPath('mod_xpertscroller','basic') );
            }   
        }
        
        //virtuemart will only load basic style
        if( $this->settings['content_source'] ==  'vm' ){
            require( JModuleHelper::getLayoutPath('mod_xpertscroller','vm') );
        }
        //item will load first then load script and style
        modXpertScrollerHelper::load_script();
        modXpertScrollerHelper::load_style();
        
    }
    
    /*
    * Load add script settings 
    * and push it to document head
    */
    function load_script(){    
        $doc =& JFactory::getDocument();    
        //Load jQuery 
        modXpertScrollerHelper::load_jquery();
        //modXpertScrollerHelper::load_scroller();
        
        $animationMode = ($this->settings['animation_style'] == 'animation_h') ? 'false' : 'true';
        $speed = (int)$this->settings['animation_speed'];
        $repeat = ( (int)$this->settings['repeat'] ) ? 'true' : 'false';
        $keyboardNav = ( (int)$this->settings['keyboard_navigation'] ) ? 'true' : 'false';
        
        $autoScroll = '';
        if ( (int)$this->settings['auto_play'] ){
            $autoPlay   = ( (int)$this->settings['auto_play'] ) ? 'true' : 'false';
            $interval   = (int)$this->settings['interval'];
            $autoPause  = ( (int)$this->settings['auto_pause'] ) ? 'true' : 'false';
            
            $autoScroll = ".autoscroll({ autoplay: {$autoPlay} , interval: {$interval}, autopause:{$autoPause} })";
        }
        //navigator plugin config
        $navigator='';
        if($this->settings['navigator']) $navigator = ".navigator()";
        
        $js = "
            jQuery(document).ready(function(){
                jQuery('#{$this->settings['module_unique_id']}').scrollable({ 
                    vertical: {$animationMode},
                    speed: {$speed},
                    circular: {$repeat},
                    keyboard: {$keyboardNav}
                }){$autoScroll}{$navigator};
            });
        ";
        $doc->addScriptDeclaration($js);
    }
    
    /*Load jQuery with scroller js engine*/
    function load_jquery(){
        $doc =& JFactory::getDocument();
        $app =& JFactory::getApplication(); //application object
        static $jqLoaded;
        
        if ($jqLoaded) {
            return;
        }   
             
        //js loader
        if($this->settings['load_jquery'] == '1' AND !$app->get('jQuery')){
            if($this->settings['jquery_source'] == 'local'){
                $jqueryLocation = JURI::base().'modules/mod_xpertscroller/admin/jquery-1.4.4.js';
            }
            elseif($this->settings['jquery_source'] == 'google_cdn'){
              $jqueryLocation = 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js';
            }
            $app->set('jQuery',1.4);
            $doc->addScript($jqueryLocation);
            $doc->addScriptDeclaration("jQuery.noConflict();");
            
            $jqLoaded = TRUE;
        }
        if(!defined('SCROLLER')){
            //add scroller js file
            $doc->addScript(JURI::base().'modules/mod_xpertscroller/interface/js/xpertscroller.js');
            define('SCROLLER',1);
        }
    }
    
    /*
    * Load necesery style.
    * take all css settings and push it on document head
    */
    
    function load_style(){
        $doc =& JFactory::getDocument();  
        $selectorId = '#'.$this->settings['module_unique_id'];
        $scrollerLayout = $this->settings['scroller_layout'];
        
        /*
        * module unique id will only assign on horizontl style. 
        * this unique class will only use for navigation arrow styling
        * vertical style will auto adjuct arrow position to middle using css file.
        */
        $selectorClass = ($scrollerLayout == 'basic_h') ? '.' . $this->settings['module_unique_id'] : '';
        
        //scroller wrapper widtha nd height. this width and height will effect on .pane class also.
        $moduleWidth = $paneWidth = (int)$this->settings['module_width'];
        $moduleHeight = (int)$this->settings['module_height'];
        
        /*
        * In horizontal style item width will calculated by persentage value
        * In vertical style item height will calculate on module height and num of columns
        */
        if($scrollerLayout == 'basic_h') $itemDimensions = 'width:'. 100 / (int)$this->settings['col_amount'] . '%';
        else $itemDimensions = 'width: 100%; height:' . $moduleHeight / $this->settings['col_amount'] .'px' ;
        
        $controlMargin = $this->settings['control_margin'];
        
        //items div always higher value thats why we will check animatin style and determine the proper css property
        $animationStyle = ($this->settings['animation_style'] == 'animation_h') ? 'width' : 'height';
        
        //resize image forcefully when image resize settings is turn on. later version will add auto thums generator engine.
        $imgWidth = NULL;
        $imgHeight = NULL;
        if ( ($this->settings['image_resize'] && $this->settings['content_source'] == 'joomla') || ($this->settings['k2_image_resize'] && $this->settings['content_source'] == 'k2') ){
            $imgWidth   = (int)$this->settings['image_width'];
            $imgHeight  = (int)$this->settings['image_height'];
        }
        //preaper all css settings
        $css = "
            {$selectorId} {width: {$moduleWidth}px; height: {$moduleHeight}px;}
            {$selectorId} .pane {width: {$moduleWidth}px }
            {$selectorId} .items { {$animationStyle}:20000em; }
            {$selectorId} .pane .item{{$itemDimensions}; overflow:hidden; }
            {$selectorId} .item img{width: {$imgWidth}px; height: {$imgHeight}px;}
            {$selectorClass} a.browse{ margin:{$controlMargin}; }
            
        ";
        //push this css on document head
        $doc->addStyleDeclaration($css);
        
        //load stylesheet
        modXpertScrollerHelper::load_stylesheet();
        
    }
    /*
    * Load Stylesheet
    * 
    */    
    function load_stylesheet(){
        global $mainframe;
        $doc =& JFactory::getDocument();
        static $loadedBasicStyle;
        
        if ($loadedBasicStyle){
            return;
        }
        
        if($this->settings['scroller_layout'] == 'basic_h' || $this->settings['scroller_layout'] == 'basic_v'){
            if (file_exists(JPATH_SITE.DS.'templates'.DS.$mainframe->getTemplate().'/css/xpertscroller-basic.css')) {
               $doc->addStyleSheet(JURI::base().'templates/'.$mainframe->getTemplate().'/css/xpertscroller-basic.css'); 
            }    
            else {
                $doc->addStyleSheet(JURI::base().'modules/mod_xpertscroller/interface/css/xpertscroller-basic.css');
            }
            
            $loadedBasicStyle = TRUE;
        }
    }
    
    //printing array for debuging purpose
    function _print($att){
        echo "<pre>";
        print_r($att);
        echo "</pre>";
    }
    
}