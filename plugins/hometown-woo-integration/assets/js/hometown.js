$=jQuery;

let graphic_lux_subdirectory = '/home';

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




  $("#continue_2").unbind().click(function() {

    $('.artwork_selection').slideUp();
    $('.step_2_shirt_designs').fadeTo(100, 1);
    $('.product_image_wrap.subtype').fadeIn();

    let data = {
      action: 'hometown_display_sizes',
      product_id: $("#continue_3").data('product-id')
    };

    $.post( wc_add_to_cart_params.ajax_url, data, function( response ) {
      $(".shirt_sizes_wrap").html(response).fadeIn();
    });

  });

  $("#continue_3").unbind().click(function(e) {
    e.preventDefault();
    finalizeCustomOrder();
  });



}





function finalizeCustomOrder() {

  let data = {
    action: 'hometown_woocommerce_add_to_cart_variable',
    product_id: $("#continue_3").data('product-id'),
    variation_id: $("#continue_3").data('product-variant-id'),
    variation: $("#continue_3").data('product-variation')
  };

  setSizeData(data.product_id);

  $.post( wc_add_to_cart_params.ajax_url, data, function( response ) {
    console.log(response);
    if (response.result) {
      window.location.replace(graphic_lux_subdirectory+'/checkout');
    }
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

