let magPop;

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
        },
        updateStatus: function(data) {
          if(data.status === 'ready') {
            magPop = $(this);
            hometown_reload_add_to_cart_actions();
          }
        }
      }
    });

  })(jQuery);
}

woocommerce_ajax_lightbox_quickview();