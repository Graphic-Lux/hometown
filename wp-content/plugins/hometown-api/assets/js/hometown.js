$=jQuery;

$(document).ready(function () {

  //initialize swiper when document ready
  var mySwiper = new Swiper ('.swiper-container', {
    // Optional parameters
    direction: 'horizontal',
    loop: false,
    slidesPerView: 5,
  });



  // STEP 1
  // SLIDER VIEWING

  $('.shirt-slider').each(function() {
    $(this).hide();
  });

  $('.type a').unbind().click(function (e) {

    e.preventDefault();
    var type = $(this).html().toLowerCase();
    var sliderClass = '.'+type+'-slider';

    $('.shirt-slider').each(function() {
      $(this).fadeOut('fast');
    }).promise().done(function () {
      if (type === 'unisex') {
        $('.mens-slider').fadeIn();
      } else {
        $(sliderClass).fadeIn();
      }
    });

  // PULLING PRODUCT AFTER
    $('.single_shirt').unbind().click(function () {
      var style = $(this).attr('id');
      var type = $(this).data('type');


      // Fire our ajax request!
      // $.ajax({
      //   method: 'GET',
      //   // Here we supply the endpoint url, as opposed to the action in the data object with the admin-ajax method
      //   url: '/rest/wp_posts',
      //   data: data,
      //   beforeSend: function ( xhr ) {
      //     // Here we set a header 'X-WP-Nonce' with the nonce as opposed to the nonce in the data object with admin-ajax
      //     xhr.setRequestHeader( 'X-WP-Nonce', rest_object.api_nonce );
      //   },
      //   success : function( response ) {
      //     console.log(response);
      //     $( '#result' ).html(response.message);
      //   },
      //   fail : function( response ) {
      //     console.log(response);
      //     $( '#result' ).html(response.message);
      //   }
      // });

      console.log(type,style);
    });






  });


});