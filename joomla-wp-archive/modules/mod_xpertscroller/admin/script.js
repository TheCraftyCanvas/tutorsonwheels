/**
 * @package XpertScroller
 * @version 1.3
 * @author ThemeXpert http://www.themexpert.com
 * @copyright Copyright (C) 2009 - 2011 ThemeXpert
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

if ($defined(window.jQuery) && $type(jQuery.noConflict)=='function') {
    jQuery.noConflict();
}

jQuery(document).ready( function(){  
    jQuery('#param-page').hide();   

    //Set default open/close settings
    jQuery('.acc_container').hide(); //Hide/close all containers
    jQuery('.acc_trigger:first').addClass('active').next().show(); //Add "active" class to first trigger, then show/open the immediate next container

    //On Click
    jQuery('.acc_trigger').click(function(){
        if( jQuery(this).next().is(':hidden') ) { //If immediate next container is closed...
            jQuery('.acc_trigger').removeClass('active').next().slideUp(); //Remove all "active" state and slide up the immediate next container
            jQuery(this).toggleClass('active').next().slideDown(); //Add "active" state to clicked trigger and slide down the immediate next container
        }
        else{
            jQuery('.acc_trigger').removeClass('active');
            jQuery(this).next().slideUp();
        }
        return false; //Prevent the browser jump to the link anchor
    });
    
    //Redio button override with switcher style.
    jQuery(".tx-enable").click(function(){
        var parent = jQuery(this).parents('.paramlist_value');
        jQuery('.tx-disable',parent).removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery('.checkbox',parent).attr('checked', true);
    });
    jQuery(".tx-disable").click(function(){
        var parent = jQuery(this).parents('.paramlist_value');
        jQuery('.tx-enable',parent).removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery('.checkbox',parent).attr('checked', false);
    });
    
    //Joomla, k2, VM Accordion hide and show effect depend on content source.
    function showJoomla(){
        jQuery('h2.xs_joomla').show();
        jQuery('h2.xs_k2').hide();
        jQuery('h2.xs_vm').hide();
    }
    function showK2(){
        jQuery('h2.xs_joomla').hide();
        jQuery('h2.xs_vm').hide();
        jQuery('h2.xs_k2').show();
    }
    function showVm(){
        jQuery('h2.xs_vm').show();
        jQuery('h2.xs_k2').hide();
        jQuery('h2.xs_joomla').hide();
    }
    
    //determine which settings is enable in content source and show related container
    switch(jQuery('#paramscontent_source').val()){
        case 'joomla':
            showJoomla();
            break;
        case 'k2':
            showK2();
            break;
        case 'vm':
            showVm();
            break;
    }
    
    //change accordion realtime 
    jQuery('#paramscontent_source').change(function(){
        switch(jQuery('#paramscontent_source').val()){
        case 'joomla':
            showJoomla();
            break;
        case 'k2':
            showK2();
            break;
        case 'vm':
            showVm();
            break;
    }
    });
});