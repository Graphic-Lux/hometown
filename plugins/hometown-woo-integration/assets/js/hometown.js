$=jQuery;

$(document).ready(function () {

  //initialize swiper when document ready
  var mySwiper = new Swiper ('.swiper-container', {
    // Optional parameters
    direction: 'horizontal',
    loop: false,
    slidesPerView: 5,
    autoResize: true
  });

  hometown_init();

});



function hometown_init() {

  console.log('hometown init');

  // OPEN LIGHTBOX BY CLICKING COLOR SWATCH
  // $('.wcvaswatchinput').unbind().click(function(e) {
  //
  //   e.preventDefault();
  //
  //   let colorElement = $(this);
  //   let colorLink = $(colorElement)[0].href;
  //   let color = colorLink.split('color=');
  //   color = color[1];
  //   let quickViewButton = $(this).parent().parent().parent().parent().parent().find('.wpb_wl_preview_area a');
  //   quickViewButton = $(quickViewButton)[0];
  //   let lightboxAnchor = quickViewButton.href;
  //   let lightboxID = lightboxAnchor.split('#');
  //   lightboxID = lightboxID[1];
  //
  //   $(quickViewButton).click();
  //
  //   $('#'+lightboxID+' .wcvasquare').removeClass('selectedswatch');
  //   $('#'+lightboxID+' .wcvasquare').addClass('wcvaswatchlabel');
  //   $('#'+lightboxID+' .attribute_pa_color_'+color).removeClass('wcvaswatchlabel');
  //   $('#'+lightboxID+' .attribute_pa_color_'+color).addClass('selectedswatch');
  //   $('#'+lightboxID+' .wcva-single-select').val(color);
  //
  // });




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

      let style = $(this).attr('id');
      let type = $(this).data('type');

      let data = {
        'action': 'hometown_get_products_by_category',
        'style': style,
        'type': type
      };

      $.get(ha_localized_config.ajaxurl, data).done(function(searchResults) {

        $('.product_grid_wrap').html(searchResults).fadeIn();
        woocommerce_ajax_lightbox_quickview();
        $('.subtype.product').fadeIn();

      });

    });

  });





}


function hometown_reload_scripts() {
  $("body script").each(function(){
    let oldScript = this.getAttribute("src");
    if (oldScript !== 'null') {
      $(this).remove();
      let newScript;
      newScript = document.createElement('script');
      newScript.type = 'text/javascript';
      newScript.src = oldScript;
      document.getElementsByTagName("body")[0].appendChild(newScript);
    }
  });
}


function hometown_reload_add_to_cart_actions() {

  console.log('reload add to cart actions');

  $('.single_add_to_cart_button').unbind().click(function(e) {

    e.preventDefault();

    let data = {
      'action': 'hometown_get_product_variant_images',
      'product_id': $(this).parent().find('input[name="product_id"]').val(),
      'variation_id': $(this).parent().find('input[name="variation_id"]').val()
    };

    // console.log('magnific popup: ',magPop[0]);

    magPop[0].close();
    let magnificPopup = $.magnificPopup.instance; // save instance in magnificPopup variable
    magnificPopup.close(); // Close popup that is currently opened

    hometown_get_product_variant_images(data);

  });
}


function hometown_get_product_variant_images(data) {

  $.post(ha_localized_config.ajaxurl, data).done(function(searchResults) {

    // console.log(searchResults);
    $('.shirt_positions').html(searchResults).fadeIn();

  });

}