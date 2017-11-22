$=jQuery;

$(document).ready(function () {hometown_init();});

function hometown_reload_scripts() {
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



function hometown_init() {

  console.log('hometown init');

  // OPEN LIGHTBOX BY CLICKING COLOR SWATCH
  $('.wcvaswatchinput').unbind().click(function(e) {

    e.preventDefault();

    var colorElement = $(this);
    var colorLink = $(colorElement)[0].href;
    var color = colorLink.split('color=');
    color = color[1];
    var quickViewButton = $(this).parent().parent().parent().parent().parent().find('.wpb_wl_preview_area a');
    quickViewButton = $(quickViewButton)[0];
    var lightboxAnchor = quickViewButton.href;
    var lightboxID = lightboxAnchor.split('#');
    lightboxID = lightboxID[1];

    $(quickViewButton).click();

    $('#'+lightboxID+' .wcvasquare').removeClass('selectedswatch');
    $('#'+lightboxID+' .wcvasquare').addClass('wcvaswatchlabel');
    $('#'+lightboxID+' .attribute_pa_color_'+color).removeClass('wcvaswatchlabel');
    $('#'+lightboxID+' .attribute_pa_color_'+color).addClass('selectedswatch');
    $('#'+lightboxID+' .wcva-single-select').val(color);

  });


  //initialize swiper when document ready
  var mySwiper = new Swiper ('.swiper-container', {
    // Optional parameters
    direction: 'horizontal',
    loop: false,
    slidesPerView: 5,
    autoResize: true
  });



  // STEP 1
  // SLIDER VIEWING

  $('.shirt-slider').each(function() {
    $(this).hide();
  });

  $('.type a').unbind().click(function (e) {

    $('.type a').removeClass('hovered');
    $(this).addClass("hovered");

    e.preventDefault();
    let type = $(this).html().toLowerCase();
    let sliderClass = '.'+type+'-slider';

    $('.shirt-slider').each(function() {
      $(this).fadeOut('fast');
    }).promise().done(function () {
      if (type === 'unisex') {
        $('.mens-slider').fadeIn();
      } else {
        $(sliderClass).fadeIn();
      }
    });

    $('.subtype.shirt_type').fadeIn();

    // PULLING PRODUCT AFTER
    $('.single_shirt').unbind().click(function () {

      $('.single_shirt').removeClass('selected_single_shirt');
      $(this).addClass('selected_single_shirt');

      $('.product_grid_wrap').fadeOut().empty();
      $('.product_slider_wrap').fadeOut().empty();

      var style = $(this).attr('id');
      var type = $(this).data('type');

      var data = {
        'action': 'hometown_get_products_by_category',
        'style': style,
        'type': type
      };

      $.get(ha_localized_config.ajaxurl, data).done(function(searchResults) {
        // console.log(searchResults);
        $('.product_grid_wrap').html(searchResults).fadeIn();
        // hometown_reload_scripts();
        // hometown_init();
        woocommerce_ajax_lightbox_quickview(sliderClass);
        // $( document.body ).trigger( 'post-load' );
        $(sliderClass).fadeIn();
        $('.subtype.product').fadeIn();
      });

    });

  });


  $('#continue_1').unbind().click(function(e) {

    e.preventDefault();

    if ($(this).data('product-id') && $(this).data('product-variant-id') !== '') {
      hometown_get_product_variant_images($(this).data('product-id'), $(this).data('product-variant-id'));
    }
    
  });


}



function hometown_get_product_variant_images(productID, variantID) {

  var data = {
    'action': 'hometown_get_product_variant_images',
    'product_id': productID,
    'variant_id': variantID
  };

  console.log(data);

  // $.wc_additional_variation_images_frontend.init();

  $.get(ha_localized_config.ajaxurl, data).done(function(searchResults) {
    // console.log(searchResults);
    $('.shirt_front').html(searchResults).fadeIn();
    hometown_reload_scripts();
    hometown_init();
    $( document.body ).trigger( 'post-load' );
  });

}