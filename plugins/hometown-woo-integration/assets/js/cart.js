$=jQuery;

// THIS FUNCTION GETS INITIALIZED AT THE END OF CART.PHP and form-checkout.php

function hometown_cart_init() {

  $('.size_qty').unbind().change(function() {
    setSizeData($(this).data('unique-cart-key'));
  });

}