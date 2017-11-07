$=jQuery;

var amazonElement;
var paypalElement;

$(document).ready(function() {
	
	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////// ADDED TO CART JS
	
	if ($('.woocommerce-message').length > 0) {
		
		$('.woocommerce-message').fadeOut();
		
		var $button = $('.widget_shopping_mini_cart_content').parent();
		var $popup = $('.dropdown', $button);
		
		if(!$popup.is(':visible')){
		
		    $popup.removeClass('drop-left')
		        .removeClass('drop-bottom');
		
		    // get width/height
		    $popup.show();
		    var $width = $popup.width();
		    var $height = $popup.height();
		    var $button_offset = $button.get(0).getBoundingClientRect();
		    $popup.hide();
		
		    var $left = $button_offset.right - $width;
		    var $right = $(window).width() - ($button_offset.left + $width);
		    var $top = $button_offset.bottom - $height;
		    var $bottom = $(window).height() - ($button_offset.bottom + $height);
		
		    if($left < 10 && $right > 0){
		        $popup.addClass('drop-left');
		    }
		
		    if($bottom < 10 && $top > 0){
		        $popup.addClass('drop-bottom');
		    }
		
		    $popup.slideDown();
		}
	}
	
	
	
	
	
	
	
	$('.big_img_title').css({'background-image': 'url(' + $('.big_img_title img').attr('src') + ')'});
	
	$('#question').unbind().click(function() {
		$('html, body').animate({
	        scrollTop: $('#question_form').offset().top - 100
	    }, 500);
	});
	
	

	// slider
	list = $('.cdx-theme-list-view ul li');
	var youllLoveTheseCount = list.length;
	
	list = $('.rc_wc_rvp_product_list_widget li');
	var recentlyViewedCount = list.length;
	
// 	console.log('recents: '+recentlyViewedCount);
// 	console.log('youll love: '+youllLoveTheseCount);

	if (recentlyViewedCount == 0) {
		$('.recently_viewed').remove();
		$('.recently_viewed #bullet-1').remove();
		$('.recently_viewed #bullet-3').remove();
		$('.recently_viewed #bullet-2').remove();
		$('.recently_viewed #bullet-1').remove();
	} else if (recentlyViewedCount < 4) { 
		$('.recently_viewed #bullet-3').remove();
		$('.recently_viewed #bullet-2').remove();
	} else if (recentlyViewedCount < 7) {
		$('.recently_viewed #bullet-3').remove();
	}
	
	if (youllLoveTheseCount == 0) {
		$('.youll_love_these').remove();
// 		$('.recently_viewed').css({'border': 'none', 'width': '100%'});
		$('.youll_love_these #bullet-1').remove();
		$('.youll_love_these #bullet-3').remove();
		$('.youll_love_these #bullet-2').remove();
	} else if (youllLoveTheseCount < 4) { 
		$('.youll_love_these #bullet-3').remove();
		$('.youll_love_these #bullet-2').remove();
		$('.youll_love_these #bullet-1').remove();
	} else if (youllLoveTheseCount < 7) {
		$('.youll_love_these #bullet-3').remove();
	}
	
	
	
	if ($(window).width() < 768) {
		
		// slider
		list = $('.cdx-theme-list-view ul li');
		var youllLoveTheseCount = list.length
		
		for(var i = 0; i < list.length; i+=2) {
		  list.slice(i, i+2).wrapAll("<div id='slide-"+i+"' class='product_slide'></div>");
		}
		
		list = $('.rc_wc_rvp_product_list_widget li');
		var recentlyViewedCount = list.length
		
		for(var i = 0; i < list.length; i+=2) {
		  list.slice(i, i+2).wrapAll("<div id='slide-"+i+"' class='product_slide'></div>");
		}
		
		
		$('.recently_viewed #slide-0').show();
		$('.recently_viewed #slide-2').hide();
		$('.recently_viewed #slide-4').hide();
		$('.youll_love_these #slide-0').show();
		$('.youll_love_these #slide-2').hide();
		$('.youll_love_these #slide-4').hide();
		
		
		
		$('.recently_viewed #bullet-1').click(function(e) {
			e.preventDefault();
			$('.recently_viewed #bullet-1').css({'color': '#111'});
			$('.recently_viewed #bullet-2').css({'color': '#f37f93'});
			$('.recently_viewed #bullet-3').css({'color': '#f37f93'});
			$('.recently_viewed #slide-0').fadeIn();
			$('.recently_viewed #slide-2').hide();
			$('.recently_viewed #slide-4').hide()
		});
		
		$('.recently_viewed #bullet-2').click(function(e) {
			e.preventDefault();
			$('.recently_viewed #bullet-2').css({'color': '#111'});
			$('.recently_viewed #bullet-1').css({'color': '#f37f93'});
			$('.recently_viewed #bullet-3').css({'color': '#f37f93'});
			$('.recently_viewed #slide-0').hide();
			$('.recently_viewed #slide-2').fadeIn();
			$('.recently_viewed #slide-4').hide()
		});
		
		$('.recently_viewed #bullet-3').click(function(e) {
			e.preventDefault();
			$('.recently_viewed #bullet-3').css({'color': '#111'});
			$('.recently_viewed #bullet-1').css({'color': '#f37f93'});
			$('.recently_viewed #bullet-2').css({'color': '#f37f93'});
			$('.recently_viewed #slide-0').hide();
			$('.recently_viewed #slide-2').hide();
			$('.recently_viewed #slide-4').fadeIn()
		});
		
		$('.youll_love_these #bullet-1').click(function(e) {
			e.preventDefault();
			$('.youll_love_these #bullet-1').css({'color': '#111'});
			$('.youll_love_these #bullet-2').css({'color': '#f37f93'});
			$('.youll_love_these #bullet-3').css({'color': '#f37f93'});
			$('.youll_love_these #slide-0').fadeIn();
			$('.youll_love_these #slide-2').hide();
			$('.youll_love_these #slide-4').hide()
		});
		
		$('.youll_love_these #bullet-2').click(function(e) {
			e.preventDefault();
			$('.youll_love_these #bullet-2').css({'color': '#111'});
			$('.youll_love_these #bullet-1').css({'color': '#f37f93'});
			$('.youll_love_these #bullet-3').css({'color': '#f37f93'});
			$('.youll_love_these #slide-0').hide();
			$('.youll_love_these #slide-2').fadeIn();
			$('.youll_love_these #slide-4').hide()
		});
		
		$('.youll_love_these #bullet-3').click(function(e) {
			e.preventDefault();
			$('.youll_love_these #bullet-3').css({'color': '#111'});
			$('.youll_love_these #bullet-2').css({'color': '#f37f93'});
			$('.youll_love_these #bullet-1').css({'color': '#f37f93'});
			$('.youll_love_these #slide-0').hide();
			$('.youll_love_these #slide-2').hide();
			$('.youll_love_these #slide-4').fadeIn()
		});
		
		
		if (recentlyViewedCount < 5) {
			$('.recently_viewed #bullet-3').remove();
		} else if (recentlyViewedCount < 3) {
			$('.recently_viewed #bullet-3').remove();
			$('.recently_viewed #bullet-2').remove();
		} else if (recentlyViewedCount == 0) {
			$('.recently_viewed #bullet-1').remove();
			$('.recently_viewed #bullet-3').remove();
			$('.recently_viewed #bullet-2').remove();
		}
		
		if (youllLoveTheseCount < 5) {
			$('.youll_love_these #bullet-3').remove();
		} else if (youllLoveTheseCount < 3) {
			$('.youll_love_these #bullet-3').remove();
			$('.youll_love_these #bullet-2').remove();
		} else if (youllLoveTheseCount == 0) {
			$('.youll_love_these #bullet-1').remove();
			$('.youll_love_these #bullet-3').remove();
			$('.youll_love_these #bullet-2').remove();
		}
		
		$(".product_list_widget").on("swipe",function(swipe){
			
			var directionValue = swipe.swipestart.coords[0] - swipe.swipestop.coords[0];
			
			if (directionValue > 0) {
				var direction = 'left';
			} else {
				var direction = 'right';
			}
			
			var slideNumber = 0;
			
			$(this).children().each(function(e) {
			
			    if (!$(this).is(":hidden")) {
					slideNumber = $(this).attr('id').split('slide-')[1];
			    }
			
			});
		  
/*
		  	console.log(direction);
		  	console.log(slideNumber);
*/
		  
			if (slideNumber == 0) {
						
				if (direction == 'left') {
					if (youllLoveTheseCount > 2) {
						$('.youll_love_these #bullet-2').css({'color': '#111'});
						$('.youll_love_these #bullet-1').css({'color': '#f37f93'});
						$('.youll_love_these #bullet-3').css({'color': '#f37f93'});
						$('.youll_love_these #slide-0').hide();
						$('.youll_love_these #slide-2').fadeIn();
						$('.youll_love_these #slide-4').hide();
					}
					
				} else {
					return false;
				}
					
			} else if (slideNumber == 2) {
				
				if (direction == 'left') {
					if (youllLoveTheseCount > 4) {
						$('.youll_love_these #bullet-3').css({'color': '#111'});
						$('.youll_love_these #bullet-2').css({'color': '#f37f93'});
						$('.youll_love_these #bullet-1').css({'color': '#f37f93'});
						$('.youll_love_these #slide-0').hide();
						$('.youll_love_these #slide-2').hide();
						$('.youll_love_these #slide-4').fadeIn();
					}
				} else {
					$('.youll_love_these #bullet-1').css({'color': '#111'});
					$('.youll_love_these #bullet-2').css({'color': '#f37f93'});
					$('.youll_love_these #bullet-3').css({'color': '#f37f93'});
					$('.youll_love_these #slide-0').fadeIn();
					$('.youll_love_these #slide-2').hide();
					$('.youll_love_these #slide-4').hide()
				}
				
			} else if (slideNumber == 4) {
				
				if (direction == 'left') {
					return false;
				} else {
					$('.youll_love_these #bullet-3').css({'color': '#111'});
					$('.youll_love_these #bullet-2').css({'color': '#f37f93'});
					$('.youll_love_these #bullet-1').css({'color': '#f37f93'});
					$('.youll_love_these #slide-0').hide();
					$('.youll_love_these #slide-2').hide();
					$('.youll_love_these #slide-4').fadeIn();
				}
				
			}
		  
		});
		
		
		
		$(".rc_wc_rvp_product_list_widget").on("swipe",function(swipe){
			
			var directionValue = swipe.swipestart.coords[0] - swipe.swipestop.coords[0];
			
			if (directionValue > 0) {
				var direction = 'left';
			} else {
				var direction = 'right';
			}
			
			var slideNumber = 0;
			
			$(this).children().each(function(e) {
			
			    if (!$(this).is(":hidden")) {
					slideNumber = $(this).attr('id').split('slide-')[1];
			    }
			
			});
		  
/*
		  	console.log(direction);
		  	console.log(slideNumber);
*/
		  
			if (slideNumber == 0) {
						
				if (direction == 'left') {
					if (recentlyViewedCount > 2) {
						$('.recently_viewed #bullet-2').css({'color': '#111'});
						$('.recently_viewed #bullet-1').css({'color': '#f37f93'});
						$('.recently_viewed #bullet-3').css({'color': '#f37f93'});
						$('.recently_viewed #slide-0').hide();
						$('.recently_viewed #slide-2').fadeIn();
						$('.recently_viewed #slide-4').hide();
					}
				} else {
					return false;
				}
					
			} else if (slideNumber == 2) {
				
				if (direction == 'left') {
					if (youllLoveTheseCount > 4) {
						$('.recently_viewed #bullet-3').css({'color': '#111'});
						$('.recently_viewed #bullet-2').css({'color': '#f37f93'});
						$('.recently_viewed #bullet-1').css({'color': '#f37f93'});
						$('.recently_viewed #slide-0').hide();
						$('.recently_viewed #slide-2').hide();
						$('.recently_viewed #slide-4').fadeIn();
					}
				} else {
					$('.recently_viewed #bullet-1').css({'color': '#111'});
					$('.recently_viewed #bullet-2').css({'color': '#f37f93'});
					$('.recently_viewed #bullet-3').css({'color': '#f37f93'});
					$('.recently_viewed #slide-0').fadeIn();
					$('.recently_viewed #slide-2').hide();
					$('.recently_viewed #slide-4').hide()
				}
				
			} else if (slideNumber == 4) {
				
				if (direction == 'left') {
					return false;
				} else {
					$('.recently_viewed #bullet-3').css({'color': '#111'});
					$('.recently_viewed #bullet-2').css({'color': '#f37f93'});
					$('.recently_viewed #bullet-1').css({'color': '#f37f93'});
					$('.recently_viewed #slide-0').hide();
					$('.recently_viewed #slide-2').hide();
					$('.recently_viewed #slide-4').fadeIn();
				}
				
			}
		  
		});
		
		
		
	} else {
		
		// slider
		list = $('.cdx-theme-list-view ul li');
		var youllLoveTheseCount = list.length
		
		for(var i = 0; i < list.length; i+=3) {
		  list.slice(i, i+3).wrapAll("<div id='slide-"+i+"' class='product_slide'></div>");
		}
		
		list = $('.rc_wc_rvp_product_list_widget li');
		var recentlyViewedCount = list.length
		
		for(var i = 0; i < list.length; i+=3) {
		  list.slice(i, i+3).wrapAll("<div id='slide-"+i+"' class='product_slide'></div>");
		}
		
		
		$('.recently_viewed #slide-0').show();
		$('.recently_viewed #slide-3').hide();
		$('.recently_viewed #slide-6').hide()
		$('.youll_love_these #slide-0').show();
		$('.youll_love_these #slide-3').hide();
		$('.youll_love_these #slide-6').hide()
		
		
		
		$('.recently_viewed #bullet-1').click(function(e) {
			e.preventDefault();
			$('.recently_viewed #bullet-1').css({'color': '#111'});
			$('.recently_viewed #bullet-2').css({'color': '#f37f93'});
			$('.recently_viewed #bullet-3').css({'color': '#f37f93'});
			$('.recently_viewed #slide-0').fadeIn();
			$('.recently_viewed #slide-3').hide();
			$('.recently_viewed #slide-6').hide()
		});
		
		$('.recently_viewed #bullet-2').click(function(e) {
			e.preventDefault();
			$('.recently_viewed #bullet-2').css({'color': '#111'});
			$('.recently_viewed #bullet-1').css({'color': '#f37f93'});
			$('.recently_viewed #bullet-3').css({'color': '#f37f93'});
			$('.recently_viewed #slide-0').hide();
			$('.recently_viewed #slide-3').fadeIn();
			$('.recently_viewed #slide-6').hide()
		});
		
		$('.recently_viewed #bullet-3').click(function(e) {
			e.preventDefault();
			$('.recently_viewed #bullet-3').css({'color': '#111'});
			$('.recently_viewed #bullet-1').css({'color': '#f37f93'});
			$('.recently_viewed #bullet-2').css({'color': '#f37f93'});
			$('.recently_viewed #slide-0').hide();
			$('.recently_viewed #slide-3').hide();
			$('.recently_viewed #slide-6').fadeIn()
		});
		
		$('.youll_love_these #bullet-1').click(function(e) {
			e.preventDefault();
			$('.youll_love_these #bullet-1').css({'color': '#111'});
			$('.youll_love_these #bullet-2').css({'color': '#f37f93'});
			$('.youll_love_these #bullet-3').css({'color': '#f37f93'});
			$('.youll_love_these #slide-0').fadeIn();
			$('.youll_love_these #slide-3').hide();
			$('.youll_love_these #slide-6').hide()
		});
		
		$('.youll_love_these #bullet-2').click(function(e) {
			e.preventDefault();
			$('.youll_love_these #bullet-2').css({'color': '#111'});
			$('.youll_love_these #bullet-1').css({'color': '#f37f93'});
			$('.youll_love_these #bullet-3').css({'color': '#f37f93'});
			$('.youll_love_these #slide-0').hide();
			$('.youll_love_these #slide-3').fadeIn();
			$('.youll_love_these #slide-6').hide()
		});
		
		$('.youll_love_these #bullet-3').click(function(e) {
			e.preventDefault();
			$('.youll_love_these #bullet-3').css({'color': '#111'});
			$('.youll_love_these #bullet-2').css({'color': '#f37f93'});
			$('.youll_love_these #bullet-1').css({'color': '#f37f93'});
			$('.youll_love_these #slide-0').hide();
			$('.youll_love_these #slide-3').hide();
			$('.youll_love_these #slide-6').fadeIn()
		});
				
	}
	
	
	
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////// CHECKOUT CUSTOM JS
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////// IMPORTANT!!! - PAYMENT JS MUST BE PROCESSED IN PAYMENT.PHP
	
	$("p .checkout_edit").unwrap();
	
	// HIDE BEFORE PAGE LOAD
	hideShipping();
	// hidePayment() is getting called in /wp-content/themes/KDJ/woocommerce/checkout/payment.php
	hideReviewAndPurchase();
	
	
	
	// CONTINUE BUTTON CLICKS
	$('#billing_continue').unbind().click(function() {

		hideBilling();
		showShipping();
        
        // ADD EDIT BUTTON
        $('#billing_edit').fadeIn();
        
        // ADD FIELD REVIEW AREA
        var billingFirstName = '<p>' + $('#billing_first_name').val() + '</p>';
        var billingEmail = '<p>' + $('#billing_email').val() + '</p>';
        
        var billingFieldReviewWrapper = $('#billing_field_review');
        
        $(billingFirstName).appendTo(billingFieldReviewWrapper);
        $(billingEmail).appendTo(billingFieldReviewWrapper);
        $('#billing_field_review').fadeIn();
        
        // HELPFUL FUNCTIONALITY
//         $('#shipping_first_name').val($('#billing_first_name').val());
        
        
        
        $('html,body').animate({
        	scrollTop: $("#customer_details .col-1").offset().top - 50},
        'slow');
        
	});
	
	
	
	$('#shipping_continue').unbind().click(function() {

		hideShipping();
		showPayment();
		
		$('html,body').animate({
        	scrollTop: $("#payment").offset().top},
        'slow');
        
        // ADD EDIT BUTTON
        $('#shipping_edit').fadeIn();
        
        $('#customer_details .col-2').css({'height': 'auto'});
        
        var shippingFieldReviewWrapper = $('#shipping_field_review');
        
        // ADD FIELD REVIEW AREA
        var shippingName = '<p>' + $('#shipping_first_name').val() + ' ' + $('#shipping_last_name').val() + '</p>';
        var shippingAddress1 = '<p>' + $('#shipping_address_1').val() + '</p>';
        var shippingAddress2 = '<p>' + $('#shipping_address_2').val() + '</p>';
        var shippingCityStateAndZip = '<p>' + $('#shipping_city').val() + ', ' + $('#shipping_state').val() + ' ' + $('#shipping_postcode').val() + '</p>';
        var shippingCountry = '<p>' + $('#shipping_country').val() + '</p>';
        
        var shippingOption = '<br><br><p>' + $('#shipping_method_0 option:selected' ).text() + '</p>';
        
        if (shippingAddress2 == '<p></p>') {
	        var shippingReviewFields = shippingName + shippingAddress1 + shippingCityStateAndZip + shippingCountry + shippingOption;
        } else {
	        var shippingReviewFields = shippingName + shippingAddress1 + shippingAddress2 + shippingCityStateAndZip + shippingCountry + shippingOption;
        }
        
        $(shippingReviewFields).appendTo(shippingFieldReviewWrapper);
        $('#shipping_field_review').fadeIn();
		
	});
	
	
	
	$('#place_order').unbind().click(function() {

		showBilling();
		showShipping();
		showPayment();
		
		if ($(".woocommerce_error").length > 0) {
			$('html,body').animate({
	        	scrollTop: $("#customer_details").offset().top - 50},
	        'slow');
		}
		
	});
	
	
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////// BILLING EDIT
	$('#billing_edit').unbind().click(function(e) {

		e.preventDefault();

		showBilling();
		hideShipping();
		hidePayment();
		hideReviewAndPurchase();
		
		$('html,body').animate({
        	scrollTop: $("#customer_details .col-1").offset().top},
        'slow');
        
        // REMOVE EDIT BUTTON
        $('#billing_edit').fadeOut();
        $('#billing_field_review').fadeOut();
        $('#billing_field_review').empty();
        $('#shipping_field_review').empty();
	    $('.payment_review_fields_left').empty();
	    $('.payment_review_fields_right').empty();
        
		
	});
	
	$('#shipping_edit').unbind().click(function(e) {

		e.preventDefault();

		showShipping();
		hideBilling();
		hidePayment();
		hideReviewAndPurchase();
		
		$('html,body').animate({
        	scrollTop: $("#customer_details .col-2").offset().top},
        'slow');
        
        // REMOVE EDIT BUTTON
        $('#shipping_edit').fadeOut();
        $('#shipping_field_review').fadeOut();
        $('#shipping_field_review').empty();
	    $('.payment_review_fields_left').empty();
	    $('.payment_review_fields_right').empty();
        
		
	});
	
	
	// DISABLE FORM SUBMIT ON ENTER
	$('.woocommerce-checkout').on('keyup keypress', function(e) {
	  var keyCode = e.keyCode || e.which;
	  if (keyCode === 13) { 
	    e.preventDefault();
	    return false;
	  }
	});
	
	
	
	
	
	
		
});

function hideBilling() {
	$('#billing_slide').slideUp();
// 	$('#customer_details .col-1').css({'height': '80px'});
}

function hideShipping() {
	$('.woocommerce-shipping-fields__field-wrapper').slideUp();
	$('.woocommerce-checkout-review-shipping-table').slideUp();
	$('#shipping_continue').slideUp();
	$('#customer_details .col-2').delay(5000).css({'height': '80px'});
}

function hidePayment() {
	$('.wc_payment_methods').slideUp();
	$('#billing_header').slideUp();
	$('#same_as_shipping').slideUp();
	$('#same_as_shipping_text').slideUp();
	$('#billing_info').slideUp();
	$('#payment_continue').slideUp();
	$('.payment_logos').fadeOut('fast');
	
	$('#payment').delay(5000).css({'height': '80px'});
}

function hideReviewAndPurchase() {
	$('.review_and_purchase p').slideUp();
	$('.review_and_purchase').css({'height': '80px'});
}

function showBilling() {
	$('#billing_slide').slideDown();
}

function showShipping() {
	$('.woocommerce-shipping-fields__field-wrapper').slideDown();
	$('.woocommerce-checkout-review-shipping-table').slideDown();
	$('#shipping_continue').slideDown();
	$('#customer_details .col-2').delay(5000).css({'height': 'auto'});
}

function showPayment() {
	$('.wc_payment_methods').slideDown();
	$('#billing_header').slideDown();
	$('#same_as_shipping').slideDown();
	$('#same_as_shipping_text').slideDown();
	$('#billing_info').slideDown();
	$('#payment_continue').slideDown();
	$('.payment_logos').fadeIn('fast');
	
	$('#payment').delay(5000).css({'height': 'auto'});
}

function showReviewAndPurchase() {
	
	$('.review_and_purchase').delay(5000).css({'height': 'auto'});
	$('.review_and_purchase p').slideDown();
	
}