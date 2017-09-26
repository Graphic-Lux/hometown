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
      var style = $(this).attr('id');
      var type = $(this).data('type');

      // var terms = type+' '+style;
      var terms = 'ninja';

      $.get('?wc-ajax=json_search_products', {security: localized_config.search_products, term: terms}).done(function(searchResults) {
        console.log(searchResults);
      });


      console.log(type,style);
    });






  });


});