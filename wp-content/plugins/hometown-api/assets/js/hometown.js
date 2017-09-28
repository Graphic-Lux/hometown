$=jQuery;

$(document).ready(function () {

  //initialize swiper when document ready
  var mySwiper = new Swiper ('.swiper-container', {
    // Optional parameters
    direction: 'horizontal',
    loop: false,
    slidesPerView: 5,
  });



  // STEP 1
  // SLIDER VIEWING

  $('.shirt-slider').each(function() {
    $(this).hide();
  });

  $('.type a').unbind().click(function (e) {

    e.preventDefault();
    var type = $(this).html().toLowerCase();
    var sliderClass = '.'+type+'-slider';

    $('.shirt-slider').each(function() {
      $(this).fadeOut('fast');
    }).promise().done(function () {
      if (type === 'unisex') {
        $('.mens-slider').fadeIn();
      } else {
        $(sliderClass).fadeIn();
      }
    });

  // PULLING PRODUCT AFTER
    $('.single_shirt').unbind().click(function () {

      $('.product_grid_wrap').fadeOut().empty();
      $('.product_slider_wrap').fadeOut().empty();

      var style = $(this).attr('id');
      var type = $(this).data('type');

      var data = {
        'action': 'hometown_get_products_by_category',
        'style': style,
        'type': type
      };

      console.log(type,style);

      $.get(localized_config.ajaxurl, data).done(function(searchResults) {
        console.log(searchResults);
        $('.product_grid_wrap').html(searchResults).fadeIn();
        hometown_reload_scripts();
        $( document.body ).trigger( 'post-load' );
      });

    });






  });


});

function hometown_reload_scripts() {
  // $("head script").each(function(){
  //   var oldScript = this.getAttribute("src");
  //   $(this).remove();
  //   var newScript;
  //   newScript = document.createElement('script');
  //   newScript.type = 'text/javascript';
  //   newScript.src = oldScript;
  //   document.getElementsByTagName("head")[0].appendChild(newScript);
  // });

  $("body script").each(function(){
    var oldScript = this.getAttribute("src");
    if (oldScript !== 'null') {
      $(this).remove();
      var newScript;
      newScript = document.createElement('script');
      newScript.type = 'text/javascript';
      newScript.src = oldScript;
      document.getElementsByTagName("body")[0].appendChild(newScript);
    }
  });
}