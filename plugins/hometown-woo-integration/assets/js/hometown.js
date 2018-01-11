$=jQuery;

let pathname = window.location.pathname;
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

    $('.step_2 .step-holder .custom_step').addClass('done');

    $('.artwork_selection').slideUp();
    $('.step_2_shirt_designs').fadeTo(100, 1);
    $('.product_image_wrap.subtype').fadeIn();

    getSizes();

  });

  $("#continue_3").unbind().click(function(e) {
    e.preventDefault();
    finalizeCustomOrder();
  });



  $('.edit_heading').unbind().click(function() {

    let step = parseInt($(this).data('step'));

    if (step === 1) {

      $('.step_1_close').slideDown();

      $('.step_1 .step-holder .custom_step').removeClass('done');

    } else if (step === 2) {

      $('.step_2 .step-holder .custom_step').removeClass('done');

      $('.artwork_selection').slideDown();
      // $('.step_2_shirt_designs').fadeTo(100, 1);
      // $('.product_image_wrap.subtype').fadeIn();

    } else if (step === 3) {

    }



  });



}




function getSizes() {

  console.log(pathname);
  console.log(pathname.indexOf('create') > 0);

  let sizeData = {};
  sizeData.action = 'hometown_display_sizes';

  if (pathname.indexOf('create') > 0) {
    sizeData.product_id = $("#continue_3").data('product-id');
    sizeData.variation_id = $("#continue_3").data('product-variant-id');
  } else {
    sizeData.product_id = $("input[name='product_id']").val();
    sizeData.variation_id = $("input[name='variation_id']").val();
  }


  $.post( wc_add_to_cart_params.ajax_url, sizeData, function( response ) {

    if (pathname.indexOf('create') > 0) {
      $(".shirt_sizes_wrap").html(response).fadeIn();
      single_product_page_init();
    } else {
      $('.product_meta').html(response).fadeIn();
      $('.more_sizes').unbind().click(function() {
        $('.bigger_sizes').slideToggle();
      });
    }

  });

}




function finalizeCustomOrder() {

  add_variation_to_cart();

  // MUST BE DONE AFTER CART BECAUSE IT ADDS DATA TO WOOCOMMERCE SESSION
  save_imprint_location();

  setSizeData($("#continue_3").data('product-id'), $("#continue_3").data('product-variant-id'));

}




function save_imprint_location() {

  let data = {
    action: 'hometown_save_imprint_data',
    product_id: $("#continue_3").data('product-id'),
    variation_id: $("#continue_3").data('product-variant-id'),
    front: $('#front-imprint_location option[value="'+$('#front-imprint_location').val()+'"]').text(),
    back: $('#back-imprint_location option[value="'+$('#back-imprint_location').val()+'"]').text(),
    sleeve: $('#sleeve-imprint_location option[value="'+$('#sleeve-imprint_location').val()+'"]').text()
  };

  $.post( wc_add_to_cart_params.ajax_url, data, function( response ) {
    console.log(response);
  });

}




function add_variation_to_cart() {

  $('.step_3 .step-holder .custom_step').addClass('done');

  let data = {
    action: 'hometown_woocommerce_add_to_cart_variable',
    product_id: $("#continue_3").data('product-id'),
    variation_id: $("#continue_3").data('product-variant-id'),
    variation: $("#continue_3").data('product-variation')
  };

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

