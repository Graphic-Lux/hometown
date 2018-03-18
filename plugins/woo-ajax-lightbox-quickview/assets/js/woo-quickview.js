

function woocommerce_ajax_lightbox_quickview() {
  let popup = ".mfp-content";
  (function($) {

    $('.ajax-popup-link').magnificPopup({
      type: 'ajax',
      modal: true,
      // settings: {
      //   cache: true
      // },
      callbacks: {
        open: function() {
          $('.mfp-content').append('<div id="fb-root"><button title="Close (Esc)" type="button" class="mfp-close">×</button></div>');
        },
        close: function () {
          $('.ajax-popup-link').unbind();

          $(popup).removeClass("fade-in");
        },
        ajaxContentAdded: function() {
          // Ajax content is loaded and appended to DOM
          $('.pswp').remove();
          $(popup).css('opacity', '0');
          setTimeout(function(){
             $(popup).addClass("fade-in");
             $(popup).css("opacity", "1");
          }, 1500);

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

}

function refresh_lightbox_arrow_func() {

  $('.lightbox_nav').unbind().click(function(e) {

    $('.mfp-close').click();

    e.preventDefault();

    console.log('pre');

    $(this).magnificPopup({
      type: 'ajax',
      modal: true,
      // settings: {
      //   cache: true
      // },
      callbacks: {
        open: function() {
          $('.mfp-content').append('<div id="fb-root"><button title="Close (Esc)" type="button" class="mfp-close">×</button></div>');
        },
        close: function () {
          $('.ajax-popup-link').unbind();
        },
        ajaxContentAdded: function() {
          // Ajax content is loaded and appended to DOM
          $('.pswp').remove();
        }
      }
    }).magnificPopup('open');

    console.log('post');


  });

}
woocommerce_ajax_lightbox_quickview();


