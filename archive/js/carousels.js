$(function(){
  // vars for testimonials carousel
  var divwidth=$("#testimonials").width();
  $("#testimonials .carousel-wrap ul li").css('width', divwidth);
//alert(divwidth);
  var $txtcarousel = $('#testimonial-list');
  var txtcount = $txtcarousel.children().length;
  var wrapwidth = (txtcount * divwidth) + divwidth; // 400px width for each testimonial item
  //alert(wrapwidth);
  $txtcarousel.css('width',wrapwidth);
  var animtime = 1200; // milliseconds for clients carousel
 
 
 var rotating = true;
  var clientspeed = 5000;
  var seeclients = setInterval(rotateClients, clientspeed);
  
  $(document).on({
    mouseenter: function(){
      rotating = false; // turn off rotation when hovering
    },
    mouseleave: function(){
      rotating = true;
    }
  }, '#testimonials');
  
  function rotateClients() {
    if(rotating != false) {
      var $first = $('#testimonial-list li:first');
      $first.animate({ 'margin-right': -divwidth }, 2000, function() {
        $first.remove().css({ 'margin-right': '0px' });
        $('#testimonial-list li:last').before($first);  
      });
    }
  }

});