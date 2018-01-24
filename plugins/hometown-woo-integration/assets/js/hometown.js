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

  // console.log('hometown init');


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

    $('#continue_2').fadeOut();
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
      $('#continue_2').fadeIn();
      // $('.step_2_shirt_designs').fadeTo(100, 1);
      // $('.product_image_wrap.subtype').fadeIn();

    } else if (step === 3) {

    }



  });


}




function getSizes() {

  // console.log(pathname);
  console.log(pathname.indexOf('create') > 0);

  let sizeData = {};
  sizeData.action = 'hometown_display_sizes';

  if (pathname.indexOf('create') > 0) {
    sizeData.product_id = $("#continue_3").attr('data-product-id');
    sizeData.variation_id = $("#continue_3").attr('data-product-variant-id');
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

  save_artwork_to_user_meta();

  setSizeData($("#continue_3").data('product-id'), $("#continue_3").data('product-variant-id'));

}




function save_imprint_location() {

  let frontImprintLocation = ($('#front-imprint_location option[value="'+$('#front-imprint_location').val()+'"]').val() == 0) ? null : $('#front-imprint_location option[value="'+$('#front-imprint_location').val()+'"]').text();

  let backImprintLocation = ($('#back-imprint_location option[value="'+$('#back-imprint_location').val()+'"]').val() == 0) ? null : $('#back-imprint_location option[value="'+$('#back-imprint_location').val()+'"]').text();

  let sleeveImprintLocation = ($('#sleeve-imprint_location option[value="'+$('#sleeve-imprint_location').val()+'"]').val() == 0) ? null : $('#sleeve-imprint_location option[value="'+$('#back-imprint_location').val()+'"]').text();


  let data = {
    action: 'hometown_save_imprint_data',
    product_id: $("#continue_3").data('product-id'),
    variation_id: $("#continue_3").data('product-variant-id'),
    front: frontImprintLocation,
    back: backImprintLocation,
    sleeve: sleeveImprintLocation
  };

  $.post( wc_add_to_cart_params.ajax_url, data, function( response ) {
    // console.log(response);
  });

}




function add_variation_to_cart() {

  $('.step_3 .step-holder .custom_step').addClass('done');

  let data = {
    action: 'hometown_woocommerce_add_to_cart_variation',
    product_id: $("#continue_3").data('product-id'),
    variation_id: $("#continue_3").data('product-variant-id'),
    variation: $("#continue_3").data('product-variation')
  };

  $.post( wc_add_to_cart_params.ajax_url, data, function( response ) {
    console.log(response);
    if (response.result) {
      // window.location.replace(graphic_lux_subdirectory+'/cart');
    } else {
      confirm('Error adding product to cart.');
    }
  });
}









function hometown_get_product_variant_images(data) {

  console.log(data);

  $.post(ha_localized_config.ajaxurl, data).done(function(searchResults) {

    $('.step_2_content_container.subtype').fadeIn();
    // console.log(searchResults);
    $('.shirt_positions').html(searchResults).fadeIn();

    artwork_init();

  });

}

function hometown_set_user_size_options(data) {

  // console.log(data);

  $.post(ha_localized_config.ajaxurl, data).done(function(userMetaResults) {

    // console.log(userMetaResults);
    if (pathname.indexOf('predesigned') > 0) {
      $.post('?wc-ajax=add_to_cart', {product_id : data.product_id, quantity: 1}).done(function(addToCartResults) {
        window.location.replace(graphic_lux_subdirectory+'/cart');
      });
    } else if (pathname.indexOf('cart') || pathname.indexOf('checkout') >= 0) {

      // UPDATE CART
      $.post(
          woocommerce_params.ajax_url,
          {'action': 'hometown_ajax_refresh_cart'},
          function(result) {
            if (pathname.indexOf('cart') >= 0) {
              $('.entry-content').html(result);
            } else if (pathname.indexOf('checkout') >= 0) {
              $(document.body).trigger("update_checkout");
            }

          }
      );

    }

  });

}

function setSizeData(product_id, variation_id) {

  let data = {
    'action':         'hometown_save_user_sizes',
    'product_id':     product_id,
    'variation_id':   variation_id
  };

  data.sizes = {};

  $('.size_qty').each(function() {
    if ($(this).attr('data-product-variant-id') === data.variation_id) {
      let name = $(this).attr('name');
      data.sizes[name] = $(this).val();
    }
  });

  hometown_set_user_size_options(data);

}



function setAddToCartData(product_id, variation_id) {

  $('#continue_3').attr('data-product-id', product_id);
  $('#continue_3').attr('data-product-variant-id', variation_id);
  $('#continue_3').attr('data-product-variation', $('.selectedswatch').data('option'));

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




