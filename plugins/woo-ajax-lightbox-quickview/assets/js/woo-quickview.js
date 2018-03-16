function woocommerce_ajax_lightbox_quickview() {
  (function($) {

    $('.ajax-popup-link').magnificPopup({
      type: 'ajax',
      modal: true,
      settings: {
        cache: true
      },
      callbacks: {
        open: function() {
          $('.mfp-content').append('<div id="fb-root"><button title="Close (Esc)" type="button" class="mfp-close">×</button></div>');
        },
        close: function () {
          $('.ajax-popup-link').unbind();
        }
      }
    });

  })(jQuery);
}

function lightbox_arrows() {

  let currentProductID = parseInt($('div.product.type-product').attr('id').split('-')[1]);

  let nextProductHref = $('li.post-'+currentProductID).next().find('.walqv_product_preview').attr('href');
  let prevProductHref = $('li.post-'+currentProductID).prev().find('.walqv_product_preview').attr('href');

  if (typeof nextProductHref !== 'undefined') {
    $('.lightbox_nav.right').attr('href', nextProductHref);
    $('.lightbox_nav.right').fadeIn();
  }

  if (typeof prevProductHref !== 'undefined') {
    $('.lightbox_nav.left').attr('href', prevProductHref);
    $('.lightbox_nav.left').fadeIn();
  }

  if ($('.mfp-wrap').length > 1) {
    let oldWrap = $('.mfp-wrap')[0];

    $(oldWrap).remove();
  }



}

woocommerce_ajax_lightbox_quickview();


// $('.lightbox_nav').unbind().click(function() {
//   $('.mfp-close').click();
//   woocommerce_ajax_lightbox_quickview();
// });