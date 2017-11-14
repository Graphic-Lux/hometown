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



