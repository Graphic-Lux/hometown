$=jQuery;

// THIS FUNCTION GETS INITIALIZED AT THE END OF CART.PHP

function hometown_cart_init() {

  $('.size_qty').unbind().change(function() {
    console.log('here');
    setSizeData($(this).attr('data-product-id'), $(this).attr('data-product-variant-id'));
  });

  // if ($('#menu-item-shop').length > 0) {
  //   $('#menu-item-shop').remove();
  // }

}