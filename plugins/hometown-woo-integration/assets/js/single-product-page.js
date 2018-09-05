$=jQuery;

$(document).ready(function() {single_product_page_init();});

function single_product_page_init() {
  console.log('single product page init');

  $('.more_sizes').unbind().click(function() {
    $('.bigger_sizes').slideToggle();
  });

  if (window.location.pathname.indexOf('create') > 0) {
    $('.single-product-summary .all_shirt_sizes').remove();
  }

  let continueButton = $('.single_add_to_cart_button').detach();
  continueButton.appendTo('.single-product-summary');

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

}


function hometown_reload_add_to_cart_actions() {

  lightbox_arrows();

  // continue_1
  $('.single_add_to_cart_button').unbind().click(function(e) {

    e.preventDefault();

    if (!$('.variations .wcvasquare').hasClass('selectedswatch')) {
      $('.variations .swatchinput').bounce({
        interval: 100,
        distance: 10,
        times: 5
      });
      // console.log('bouncing');
      return false;
    }


    let product_id = $('input[name="product_id"]').val();
    let variation_id = null;

    if (typeof product_id === 'undefined') {
      product_id = $('button[name="add-to-cart"]').val();
    } else {
      variation_id = $('input[name="variation_id"]').val();
    }



    if (window.location.pathname.indexOf('predesigned')  > 0) {

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
}