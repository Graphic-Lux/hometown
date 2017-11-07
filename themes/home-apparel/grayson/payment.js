hidePayment();




$('#stripe-card-number').attr("placeholder", "Card Number");

// SAME AS SHIPPING
$("#same_as_shipping").change(function() {
    if(this.checked) {
        $('#payment #billing_first_name').val($('#shipping_first_name').val());
        $('#billing_last_name').val($('#shipping_last_name').val());
        $('#billing_address_1').val($('#shipping_address_1').val());
        $('#billing_address_2').val($('#shipping_address_2').val());
        $('#billing_city').val($('#shipping_city').val());
        $('#billing_state').val($('#shipping_state').val());
        $('#billing_postcode').val($('#shipping_postcode').val());
        $('#billing_country').val($('#shipping_country').val());
        $('#billing_phone').val($('#shipping_phone').val());
        
        $('#billing_info').slideUp();
    } else {
	    $('#payment #billing_first_name').val('');
        $('#billing_last_name').val('');
        $('#billing_address_1').val('');
        $('#billing_address_2').val('');
        $('#billing_city').val('');
        $('#billing_state').val('');
        $('#billing_postcode').val('');
        $('#billing_country').val('');
        $('#billing_phone').val('');
        
        $('#billing_info').slideDown();
    }
});




$('#payment_continue').unbind().click(function() {

	if (($('#stripe-card-number').val() != "") && ($('#stripe-card-expiry').val() != "") && ($('#stripe-card-csv').val() != "")) {
		
		hidePayment();
		showReviewAndPurchase();
		
	    // ADD EDIT BUTTON
	    $('#payment_edit').fadeIn();
	    
	    $('#payment').css({'height': 'auto'});
	    
	    // ADD FIELD REVIEW AREA
	    var billingName = '<p>' + $('#payment #billing_first_name').val() + ' ' + $('#payment #billing_last_name').val() + '</p>';
	    var billingAddress1 = '<p>' + $('#billing_address_1').val() + '</p>';
	    var billingAddress2 = '<p>' + $('#billing_address_2').val() + '</p>';
	    var billingCityStateAndZip = '<p>' + $('#billing_city').val() + ', ' + $('#billing_state').val() + ' ' + $('#billing_postcode').val() + '</p>';
	    var billingCountry = '<p>' + $('#billing_country').val() + '</p>';
	    
	    if (billingAddress2 == '<p></p>') {
	        var paymentReviewFieldsLeft = billingName + billingAddress1 + billingCityStateAndZip + billingCountry;
	    } else {
	        var paymentReviewFieldsLeft = billingName + billingAddress1 + billingAddress2 + billingCityStateAndZip + billingCountry;
	    }
	    
	    var billingCardNumber = '<p>' + $('#stripe-card-number').val() + '</p>';
	    
	    if (typeof document.getElementById('stripe-card-number').className.split(/\s+/)[3] != 'undefined') {
		    
		    if ($('#stripe-card-expiry').val().substr(0, 2) == 42) {
			    var billingCardCompany = "<p>" + document.getElementById('stripe-card-number').className.split(/\s+/)[2].ucfirst() + "</p>";
		    } else {
			    var billingCardCompany = "<p>" + document.getElementById('stripe-card-number').className.split(/\s+/)[3].ucfirst() + "</p>";
		    }
		    
	    } else {
		    var billingCardCompany = "<p></p>";
	    }
	    
	    var billingCardExp = '<p>Exp. ' + $('#stripe-card-expiry').val() + '</p>';
	    
	    var paymentReviewFieldsRight = billingCardNumber + billingCardCompany + billingCardExp;
	    
	    var paymentFieldReviewWrapper = $('#payment_field_review');
	    
	    $(paymentReviewFieldsLeft).appendTo($('.payment_review_fields_left'));
	    $(paymentReviewFieldsRight).appendTo($('.payment_review_fields_right'));
	    
	    $('html,body').animate({
	    	scrollTop: $(".review_and_purchase").offset().top},
	    'slow');
	    
	    $('#payment_field_review').fadeIn();

	} else {
		
		alert('Please finish filling out your billing information before you can proceed.');
		
		$('html,body').animate({
	    	scrollTop: $("#payment").offset().top - 50},
	    'slow');
		
	}
		
});


$('#payment_edit').unbind().click(function(e) {

	e.preventDefault();

	showPayment();
	hideBilling();
	hideShipping();
	hideReviewAndPurchase();
	
	$('html,body').animate({
    	scrollTop: $("#payment").offset().top},
    'slow');
    
    // REMOVE EDIT BUTTON
    $('#payment_edit').fadeOut();
    $('#payment_field_review').fadeOut();
    $('.payment_review_fields_left').empty();
    $('.payment_review_fields_right').empty();
    
	
});



String.prototype.ucfirst = function()
{
    return this.charAt(0).toUpperCase() + this.substr(1);
}

