$=jQuery;

// THIS FUNCTION GETS INITIALIZED AT THE END OF CART.PHP and form-checkout.php

function hometown_cart_init() {

  disableUnavailableSizes();

  $('.size_qty').unbind().change(function() {
    setSizeData($(this).data('unique-cart-key'));
  });

}

function disableUnavailableSizes() {

  $('.cart_item table').each(function() {

    let productID = parseInt($(this).attr('id'));

    let data = {
      'action': 'hometown_get_available_sizes',
      'product_id': productID
    };

    $.get(wc_add_to_cart_params.ajax_url, data, function(availableSizes) {

      let sizes = Object.keys(availableSizes);

      sizes.forEach((size) => {

        let uppercasedSize = size.toUpperCase();

        if (uppercasedSize === 'XXXL') {
          uppercasedSize = '3XL';
        } else if (uppercasedSize === 'XXXXL') {
          uppercasedSize = '4XL';
        }


        if (availableSizes[size] === 'yes') {
          $('input[name="'+uppercasedSize+'"][data-product-id="'+productID+'"]').removeAttr("disabled");
          $('input[name="'+uppercasedSize+'"][data-product-id="'+productID+'"]').closest('td').prev('td').css({'color': 'black'});
        } else {
          console.log(availableSizes[size], $('input[name="'+uppercasedSize+'"][data-product-id="'+productID+'"]').val());
          $('input[name="'+uppercasedSize+'"][data-product-id="'+productID+'"]').prop("disabled", true);
          $('input[name="'+uppercasedSize+'"][data-product-id="'+productID+'"]').closest('td').prev('td').css({'color': 'red'});
        }


      });

    });

  });

}