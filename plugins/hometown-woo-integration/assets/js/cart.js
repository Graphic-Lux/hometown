$=jQuery;

$(document).ready(function() {
  if ($('#menu-item-shop').length > 0) {
    $('#menu-item-shop').remove();
  }
});

function hometown_cart_init() {

  $('.size_qty').unbind().change(function() {
    setSizeData($(this).attr('data-product-id'), $(this).attr('data-product-variant-id'));
  });

  if ($('#menu-item-shop').length > 0) {
    $('#menu-item-shop').remove();
  }

}