/*** tccTutorTheme2012.js 
* tccTutorTheme2012 custom theme Javascript
* written by Anson Han, @thecraftycanvas
*
*/

jQuery.noConflict();
jQuery(document).ready( function($) {

  // add chalkboard ledge bottom border image for any tutoring service divisions  
  var BorderHTML = "";
  BorderHTML = '<div class="chalkboard-ledge"></div>';
  $(BorderHTML).insertAfter('table.views-view-grid');

  var currentPageURL = window.location.pathname;
  var setActiveMobileLink = function() {
    $('#mobile-menu').children('select').children('option').each(function() {
      if( (currentPageURL == "/index") && ($(this).attr('value') == "/") ) {
        $(this).attr('selected',true);
      } else {
        if( $(this).attr('value') == currentPageURL ) {
          $(this).attr('selected',true);
        } else {
          $(this).attr('selected',false);
        }
      }
    });
  };
  
  
  setActiveMobileLink();

  $('#mobile-nav').change( function() {
    window.location.href = $('#mobile-nav').find('option:selected').attr('value');
  });
  
  $('#content-wrapper .container').find('iframe').each(function() {
    if($(this).attr('src').indexOf('youtube') > -1) {
      $(this).wrap('<div class="video-container"></div>');
    }
  });

});
