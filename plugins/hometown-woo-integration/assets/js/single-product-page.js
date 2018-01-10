$=jQuery;

let pathname = window.location.pathname;
let graphic_lux_subdirectory = '/home';

$(document).ready(function() {single_product_page_init();});


function single_product_page_init() {

  if (pathname.indexOf('predesigned') > 0) {
    getSizes();
  }

  $('.more_sizes').unbind().click(function() {
    $('.bigger_sizes').slideToggle();
  });

  if (pathname.indexOf('create') > 0) {
    $('.single-product-summary .all_shirt_sizes').remove();
  }

  let continueButton = $('.single_add_to_cart_button').detach();
  continueButton.appendTo('.single-product-summary');

}


function hometown_reload_add_to_cart_actions() {


  $('.wcvasquare').unbind().click(function() {
    if (!$(this).hasClass('selectedswatch')) {
      getSizes();
    }
  });


  $('.single_add_to_cart_button').unbind().click(function(e) {

    e.preventDefault();

    let product_id = $('input[name="product_id"]').val();
    let variation_id = null;

    if (typeof product_id === 'undefined') {
      product_id = $('button[name="add-to-cart"]').val();
    } else {
      variation_id = $('input[name="variation_id"]').val();
    }



    if (pathname.indexOf('predesigned')  > 0) {

      setSizeData(product_id, variation_id);

    } else if (pathname.indexOf('custom/create')) {

      $('.step_1_close').slideUp();

      $('.step_1 .step-holder .custom_step').addClass('done');

      setAddToCartData(product_id, variation_id);

      let data = {
        'action': 'hometown_get_product_variant_images',
        'product_id': product_id,
        'variation_id': variation_id
      };

      $('.mfp-close').click();

      $('.product .post-'+product_id).addClass('shirt_selected');

      hometown_get_product_variant_images(data);

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
  $.post(ha_localized_config.ajaxurl, data).done(function(userMetaResults) {

    // console.log(userMetaResults);
    if (pathname.indexOf('predesigned') > 0) {
      $.post('?wc-ajax=add_to_cart', {product_id : data.product_id, quantity: 1}).done(function(addToCartResults) {
        window.location.replace(graphic_lux_subdirectory+'/checkout');
      });
    }

  });
}

function setSizeData(product_id, variation_id) {

  let data = {
    'action':         'hometown_save_user_meta',
    'product_id':     product_id,
    'variation_id':   variation_id
  };

  data.sizes = {};

  $('.sizing_inputs').each(function() {
    let name = $(this).find('input').attr('name');
    data.sizes[name] = $(this).find('input').val();
  });

  hometown_set_user_size_options(data);

}



function setAddToCartData(product_id, variation_id) {

  $('#continue_3').attr('data-product-id', product_id);
  $('#continue_3').attr('data-product-variant-id', variation_id);
  $('#continue_3').attr('data-product-variation', $('.selectedswatch').data('option'));

}