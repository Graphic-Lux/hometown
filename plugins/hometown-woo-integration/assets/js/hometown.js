$=jQuery;

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


  // STEP 1
  // SLIDER VIEWING

  $('.shirt-slider').each(function() {
    $(this).hide();
  });


  // TOGGLE BETWEEN SLIDER AND GRID VIEW
  // $('.shirt_view').unbind().click(function (e) {
  //
  //   jQuery.fn.extend({
  //     toggleText: function (a, b){
  //       var that = this;
  //       if (that.text() != a && that.text() != b){
  //         that.text(a);
  //       }
  //       else
  //       if (that.text() == a){
  //         that.text(b);
  //       }
  //       else
  //       if (that.text() == b){
  //         that.text(a);
  //       }
  //       return this;
  //     }
  //   });
  //
  //   e.preventDefault();
  //
  //   $(".shirt_view").toggleText('SLIDER VIEW', 'GRID VIEW');
  //   $('.product_slider_wrap').toggle();
  //   $('.product_grid_wrap').toggle();
  //
  //
  // });



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
        $('.product_slider_wrap').html(searchResults);

        $('.product_grid_wrap ul').removeClass('swiper-wrapper');
        $('.product_grid_wrap ul li').removeClass('swiper-slide');


        var productSwiper = new Swiper ('.product_slider_wrap', {
          // Optional parameters
          direction: 'horizontal',
          loop: false,
          slidesPerView: 5,
          autoResize: true
        });

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
    $('.choose_sizes.subtype').fadeIn();
    $('#continue_3').fadeIn();

    display_additional_sizes_price();

    $('html, body').animate({
      scrollTop: $(".step_3").offset().top
    }, 2000);

  });



  $("#continue_3").unbind().click(function(e) {

    if ($(this).prop('disabled')) {
      return false;
    } else {
      $(this).prop('disabled', true);
    }

    e.preventDefault();

    let sizeQA = [];

    $('.size_qty').each(function() {

      if (parseInt($(this).val()) !== 0) {
        add_variation_to_cart();
        return false;
      } else {
        sizeQA.push($(this).val());
      }

    });

    // IF ALL SIZES ARE 0, BOUNCE SIZE BOXES FOR REMINDER
    if (sizeQA.length === 8) {
      $('.size_qty').bounce({
        interval: 100,
        distance: 8,
        times: 5
      });
      return false;
    }


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
  sizeData.product_id = $("#continue_3").attr('data-product-id');
  sizeData.variation_id = $("#continue_3").attr('data-product-variant-id');

  console.log(sizeData);

  $.post( wc_add_to_cart_params.ajax_url, sizeData, function( response ) {

      $("#pricing").html(response).fadeIn();
      single_product_page_init();

  });
}




function save_imprint_location(uniqueCartKey) {

  let frontImprintLocation = ($('#front-imprint_location option[value="'+$('#front-imprint_location').val()+'"]').val() == 0) ? null : $('#front-imprint_location option[value="'+$('#front-imprint_location').val()+'"]').text();

  let backImprintLocation = ($('#back-imprint_location option[value="'+$('#back-imprint_location').val()+'"]').val() == 0) ? null : $('#back-imprint_location option[value="'+$('#back-imprint_location').val()+'"]').text();

  let sleeveImprintLocation = ($('#sleeve-imprint_location option[value="'+$('#sleeve-imprint_location').val()+'"]').val() == 0) ? null : $('#sleeve-imprint_location option[value="'+$('#back-imprint_location').val()+'"]').text();


  let data = {
    action: 'hometown_save_imprint_data',
    product_id: $("#continue_3").data('product-id'),
    variation_id: $("#continue_3").data('product-variant-id'),
    unique_cart_key: uniqueCartKey,
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

      let uniqueCartData = {
        'action': 'hometown_get_unique_cart_key'
      };

      // GET UNIQUE CART DATA
      $.post(ha_localized_config.ajaxurl, uniqueCartData).done(function(uniqueCartKey) {

        save_imprint_location(uniqueCartKey);

        save_artwork_to_user_meta(uniqueCartKey);

        setSizeData(uniqueCartKey);

        window.location.replace(ha_localized_config.graphic_lux_subdirectory+'/cart');

      });

    } else {
      confirm('Error adding product to cart.');
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

    $('html, body').animate({
      scrollTop: $(".step_2").offset().top - 50
    }, 500);

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
        if (window.location.pathname.indexOf('predesigned') > 0) {

          window.location.replace(ha_localized_config.graphic_lux_subdirectory+'/cart');

        } else if (window.location.pathname.indexOf('cart') || window.location.pathname.indexOf('checkout') >= 0) {

          // UPDATE CART
          $.post(
              woocommerce_params.ajax_url,
              {'action': 'hometown_ajax_refresh_cart'},
              function(result) {
                if (window.location.pathname.indexOf('cart') >= 0) {
                  $('.entry-content').html(result);
                } else if (window.location.pathname.indexOf('checkout') >= 0) {
                  $(document.body).trigger("update_checkout");
                }

              }
          );

        }

      });


    });


  });



}



function setSizeData(uniqueCartKey) {

  // console.log(uniqueCartKey);

  let sizeData = {};
  sizeData.sizes = {};

  if (uniqueCartKey !== null) {

    if (window.location.pathname.indexOf('create') >= 0) {

      sizeData.action = 'hometown_save_user_sizes';
      sizeData.unique_cart_key = uniqueCartKey;
      $('.size_qty').each(function() {
        let name = $(this).attr('name');
        sizeData.sizes[name] = parseInt($(this).val());
      });

      updateSizes(sizeData);

    } else {
      sizeData.action = 'hometown_save_user_sizes';
      sizeData.unique_cart_key = uniqueCartKey;

      $('.size_qty').each(function() {
        if ($(this).attr('data-unique-cart-key') === uniqueCartKey) {
          let name = $(this).attr('name');
          sizeData.sizes[name] = parseInt($(this).val());
        }
      });

      updateSizes(sizeData);
    }



  } else {

    sizeData.action = 'hometown_save_user_sizes';
    $('.size_qty').each(function() {
      let name = $(this).attr('name');
      sizeData.sizes[name] = parseInt($(this).val());
    });

    hometown_set_user_size_options(sizeData);

  }

}




function updateSizes(sizeData) {

  $.post(ha_localized_config.ajaxurl, sizeData).done(function(userMetaResults) {

    if (window.location.pathname.indexOf('cart') || window.location.pathname.indexOf('checkout') >= 0) {

      // UPDATE CART
      $.post(
          woocommerce_params.ajax_url,
          {'action': 'hometown_ajax_refresh_cart'},
          function(result) {
            if (window.location.pathname.indexOf('cart') >= 0) {
              $('.entry-content').html(result);
            } else if (window.location.pathname.indexOf('checkout') >= 0) {
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


(function($){
  $.fn.bounce = function(settings) {
    if(typeof settings.interval == 'undefined'){
      settings.interval = 100;
    }

    if(typeof settings.distance == 'undefined'){
      settings.distance = 10;
    }

    if(typeof settings.times == 'undefined'){
      settings.times = 4;
    }

    if(typeof settings.complete == 'undefined'){
      settings.complete = function(){};
    }

    $(this).css('position','relative');

    for(var iter=0; iter<(settings.times+1); iter++){
      $(this).animate({ top:((iter%2 == 0 ? settings.distance : settings.distance * -1)) }, settings.interval);
    }

    $(this).animate({ top: 0}, settings.interval, settings.complete);
  };
})(jQuery);