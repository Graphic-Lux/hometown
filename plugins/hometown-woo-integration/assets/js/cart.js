$=jQuery;

// THIS FUNCTION GETS INITIALIZED AT THE END OF CART.PHP and form-checkout.php

function hometown_cart_init() {

  console.log('cart.js');

  $('.size_qty').unbind().change(function() {
    console.log('here');
    setSizeData($(this).attr('data-product-id'), $(this).attr('data-product-variant-id'));
  });

}