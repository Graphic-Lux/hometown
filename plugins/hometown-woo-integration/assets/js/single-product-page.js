$=jQuery;

let pathname = window.location.pathname;

$(document).ready(function() {

  $('.more_sizes').unbind().click(function() {
    $('.bigger_sizes').slideToggle();
  });

  console.log(pathname);

  if (pathname.indexOf('create') > 0) {
    $('.single-product-summary .all_shirt_sizes').remove();
  }

});

function hometown_reload_add_to_cart_actions() {

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

      let data = {
        'action':         'hometown_save_user_meta',
        'product_id':     product_id,
        // 'variation_id':   variation_id
      };

      data.sizes = {};

      $('.sizing_inputs').each(function() {
        let name = $(this).find('input').attr('name');
        data.sizes[name] = $(this).find('input').val();
      });

      hometown_set_user_size_options(data);

    } else if (pathname.indexOf('custom/create')) {

      // console.log(product_id, variation_id);

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

  $.post(ha_localized_config.ajaxurl, data).done(function(searchResults) {

    console.log(searchResults);
    $('.shirt_positions').html(searchResults).fadeIn();

  });

}

function hometown_set_user_size_options(data) {
  $.post(ha_localized_config.ajaxurl, data).done(function(userMetaResults) {

    console.log(userMetaResults);

    $.post('?wc-ajax=add_to_cart', {product_id : data.product_id, quantity: 1}).done(function(addToCartResults) {
      window.location.replace('checkout');
    });

  });
}