$=jQuery;

$(document).ready(function() {single_product_page_init();});

function single_product_page_init() {

  if (window.location.pathname.indexOf('create') > 0) {
    $('.single-product-summary .all_shirt_sizes').remove();
  }

  let continueButton = $('.single_add_to_cart_button').detach();
  continueButton.appendTo('.single-product-summary');

}


function hometown_reload_add_to_cart_actions() {

  $('.more_sizes').unbind().click(function() {
    $('.bigger_sizes').slideToggle();
  });

  lightbox_arrows();

  getAvailableSizes();

  // continue_1
  $('.single_add_to_cart_button').unbind().click(function(e) {

    if (!$('.variations .wcvasquare').hasClass('selectedswatch')) {
      $('.variations .swatchinput').bounce({
        interval: 100,
        distance: 10,
        times: 5
      });
      // console.log('bouncing');
      return false;
    }

    e.preventDefault();

    let product_id = $('input[name="product_id"]').val();
    let variation_id = null;

    if (typeof product_id === 'undefined') {
      product_id = $('button[name="add-to-cart"]').val();
    } else {
      variation_id = $('input[name="variation_id"]').val();
    }



    if ((window.location.pathname.indexOf('predesigned')  > 0) || (window.location.pathname.indexOf('pre-designed')  > 0)) {

      setSizeData(null);

    } else if (window.location.pathname.indexOf('custom/create')) {

      $('.step_1_close').slideUp();

      $('.artwork_selection').slideDown();

      $('.step_1 .step-holder .custom_step').addClass('done');

      $('.step_1 .step-holder .edit_heading').fadeIn();

      setAddToCartData(product_id, variation_id);

      let data = {
        'action': 'hometown_get_product_variant_images',
        'product_id': product_id,
        'variation_id': variation_id
      };

      $('.mfp-close').click();

      $('.product').removeClass('shirt_selected');
      $('.post-'+product_id).addClass('shirt_selected');

      hometown_get_product_variant_images(data);

    }

  });
}


function getAvailableSizes() {

  let product_id = $('input[name="product_id"]').val();

  if (typeof product_id === 'undefined') {
    product_id = $('button[name="add-to-cart"]').val();
  }

  let data = {
    'action': 'hometown_get_available_sizes',
    'product_id': product_id
  };

  $.get(wc_add_to_cart_params.ajax_url, data, function(availableSizes) {

    let sizes = Object.keys(availableSizes);

    sizes.forEach((size) => {

      let uppercasedSize = size.toUpperCase();

      console.log(uppercasedSize);

      if (uppercasedSize === 'XXXL') {
        uppercasedSize = '3XL';
      } else if (uppercasedSize === 'XXXXL') {
        uppercasedSize = '4XL';
      }

      if (availableSizes[size] === 'yes') {
        $('input[name="'+uppercasedSize+'"]').removeAttr("disabled");
        $('label[for="'+uppercasedSize+'"]').css({'color': 'black'});
      } else {
        $('input[name="'+uppercasedSize+'"]').prop("disabled", true);
        $('label[for="'+uppercasedSize+'"]').css({'color': 'red'});
      }

    });

  });

}