$=jQuery;

let pathname = window.location.pathname;
let graphic_lux_subdirectory = '/home';

$(document).ready(function () { hometown_init();});



function hometown_init() {

  //initialize swiper when document ready
  var mySwiper = new Swiper ('.swiper-container', {
    // Optional parameters
    direction: 'horizontal',
    loop: false,
    slidesPerView: 5,
    autoResize: true
  });

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

    $('.step_2 .step-holder .edit_heading').fadeIn();

    $('#continue_2').fadeOut();
    $('.artwork_selection').slideUp();
    $('.step_2_shirt_designs').fadeTo(100, 1);
    $('.product_image_wrap.subtype').fadeIn();
    $('#continue_3').fadeIn();

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


  if ($('.woocommerce-order-overview__order.order').length > 0) {
    var orderNumberClone = $('.woocommerce-order-overview__order.order').html();
    $('.avia_codeblock ').prepend(orderNumberClone+'<br>');
  }


}


function display_additional_sizes_price() {

  let sizeData = {};
  sizeData.action = 'display_additional_sizes_price';

  if (pathname.indexOf('create') > 0) {
    sizeData.product_id = $("#continue_3").attr('data-product-id');
    sizeData.variation_id = $("#continue_3").attr('data-product-variant-id');
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


function getSizes() {

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

  setSizeData(null, $('#continue_3').attr('data-product-id', product_id));

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
    // console.log(response);
    if (response.result) {
      window.location.replace(graphic_lux_subdirectory+'/cart');
    } else {
      confirm('Error adding product to cart. Cannot add the same t-shirt and color combination to the cart more than once.');
    }
  });
}









function hometown_get_product_variant_images(data) {

  // console.log(data);

  $.post(ha_localized_config.ajaxurl, data).done(function(searchResults) {

    $('.step_2_content_container.subtype').fadeIn();
    // console.log(searchResults);
    $('.shirt_positions').html(searchResults).fadeIn();

    artwork_init();

  });

}

function hometown_set_user_size_options(sizeData) {

  console.log(sizeData);


  let data = {
    action: 'hometown_woocommerce_add_to_cart_variation',
    product_id: $("input[name='product_id']").val(),
    variation_id: $("input[name='variation_id']").val()
  };

  $.post( wc_add_to_cart_params.ajax_url, data, function( addToCartResults ) {

    console.log(addToCartResults);

    let uniqueCartData = {
      'action': 'hometown_get_unique_cart_key'
    };

    // GET UNIQUE CART DATA
    $.post(ha_localized_config.ajaxurl, uniqueCartData).done(function(uniqueCartKey) {

      console.log(uniqueCartKey);

      sizeData.unique_cart_key = uniqueCartKey;

      // ADD SIZE DATA
      $.post(ha_localized_config.ajaxurl, sizeData).done(function(userMetaResults) {

        // console.log(userMetaResults);
        if (pathname.indexOf('predesigned') > 0) {

          window.location.replace(graphic_lux_subdirectory+'/cart');

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


    });


  });



}



function setSizeData(uniqueCartKey, productID) {

  console.log(uniqueCartKey);

  let sizeData = {};
  sizeData.sizes = {};

  if (uniqueCartKey !== null) {

    sizeData.action = 'hometown_save_user_sizes';
    sizeData.unique_cart_key = uniqueCartKey;

    $('.size_qty').each(function() {
      if ($(this).attr('data-unique-cart-key') === uniqueCartKey) {
        let name = $(this).attr('name');
        sizeData.sizes[name] = parseInt($(this).val());
      }
    });

    updateSizes(sizeData);

  } else {

    sizeData.action = 'hometown_save_user_sizes';
    $('.size_qty').each(function() {
      let name = $(this).attr('name');
      sizeData.sizes[name] = parseInt($(this).val());
    });

    hometown_set_user_size_options(sizeData, productID);

  }




}


function updateSizes(sizeData) {

  console.log('here');

  $.post(ha_localized_config.ajaxurl, sizeData).done(function(userMetaResults) {

    console.log ((pathname.indexOf('cart') || pathname.indexOf('checkout') >= 0));

    if (pathname.indexOf('cart') || pathname.indexOf('checkout') >= 0) {

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

