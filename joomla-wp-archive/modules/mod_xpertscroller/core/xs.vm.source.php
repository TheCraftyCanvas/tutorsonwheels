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
require_once(JPATH_SITE.DS."components".DS."com_virtuemart".DS."virtuemart_parser.php");
require_once( CLASSPATH . 'ps_product.php');

class XSVmSource{
    
    var $config = array();
    var $productType ;
    var $ps_product;
    var $db;
    var $catId;
    var $max_items;
    var $show_price;
    var $show_addtocart;
    var $show_title;
    
    function __construct($config){
        //assign all config to config array;
        $this->config = $config;
        
        $this->productType = $config['product_type'];
        $this->ps_product = new ps_product();
        $this->db = new ps_DB();
        $this->catId = (int) $config['vm_cat_id'];
        $this->max_items = $config['max_article'];
        $this->show_price = $config['show_price'];
        $this->show_addtocart = $config['show_addtocart'];
        $this->show_title = $config['article_title'];
    }
    
    function get_items(){
        switch($this->productType){
            case 'random':
                $items = self::random_products($this->config);
                return $items;
            default:
                $items = self::latest_products($this->config);
                return $items;
        }
    }
    
    function latest_products($config){       
        
        $query  = "SELECT DISTINCT product_sku FROM #__{vm}_product, #__{vm}_product_category_xref, #__{vm}_category WHERE ";
        $query .= "product_parent_id=''";
        $query .= "AND #__{vm}_product.product_id=#__{vm}_product_category_xref.product_id ";
        $query .= "AND #__{vm}_category.category_id=#__{vm}_product_category_xref.category_id ";
        if( !empty( $this->catId ) ) {
            $query .= "AND #__{vm}_category.category_id='{$this->catId}' ";
        }
        if( CHECK_STOCK && PSHOP_SHOW_OUT_OF_STOCK_PRODUCTS != "1") {
            $query .= " AND product_in_stock > 0 ";
        }
        $query .= "AND #__{vm}_product.product_publish='Y' ";
        $query .= "ORDER BY #__{vm}_product.product_id DESC ";
        $query .= "LIMIT 0, {$this->max_items} ";
        
        $this->db->query($query);
        $rows = $this->db->record;
        
        $i=0;
        $lists    = array();
        foreach($rows as $row){
            $lists[$i]->items = $this->ps_product->product_snapshot($row->product_sku,$this->show_price,$this->show_addtocart,$this->show_title);
            $i++;
        }
        return $lists;

    }
    
    function random_products(){
        if ( $this->catId ) {
        $query  = "SELECT DISTINCT product_sku FROM #__{vm}_product, #__{vm}_product_category_xref, #__{vm}_category WHERE ";
        $query .= "product_parent_id=''";
        $query .= "AND #__{vm}_product.product_id=#__{vm}_product_category_xref.product_id ";
        $query .= "AND #__{vm}_category.category_id=#__{vm}_product_category_xref.category_id ";
        $query .= "AND #__{vm}_category.category_id='{$this->catId}'";
        $query .= "AND #__{vm}_product.product_publish='Y' ";
        if( CHECK_STOCK && PSHOP_SHOW_OUT_OF_STOCK_PRODUCTS != "1") {
            $query .= " AND product_in_stock > 0 ";
        }
      $query .= "ORDER BY product_name DESC";
    }
    else {
        $query  = "SELECT DISTINCT product_sku FROM #__{vm}_product WHERE ";
        $query .= "product_parent_id='' AND vendor_id='".$_SESSION['ps_vendor_id']."' ";
        $query .= "AND #__{vm}_product.product_publish='Y' ";
        if( CHECK_STOCK && PSHOP_SHOW_OUT_OF_STOCK_PRODUCTS != "1") {
            $query .= " AND product_in_stock > 0 ";
        }
        $query .= "ORDER BY product_name DESC";
    }
        $this->db->query($query);
        $rows = $this->db->record;
        
        $i=0;
        $lists    = array();
        foreach($rows as $row){
            $lists[$i]->items = $this->ps_product->product_snapshot($row->product_sku,$this->show_price,$this->show_addtocart,$this->show_title);
            $i++;
        }
        return $lists;

    }
}