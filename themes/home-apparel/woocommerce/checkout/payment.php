<?php
/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.5.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
?>
<script type="text/javascript">
	// MOVE THE AMAZON AND PAYPAL BUTTONS VIA SUBPAR WAY BECAUSE I COULDN'T FIGURE OUT HOW TO HOOK INTO THEM
	var amazonElement = $('.wc-amazon-checkout-message');
	var paypalElement = $('.express_checkout_button_chekout_page');

	amazonElement.detach();
	paypalElement.detach();

	$('#billing_header').prepend(paypalElement);
	$('#billing_header').prepend(amazonElement);
	
	$('.wc-amazon-checkout-message').fadeIn();
	$('.express_checkout_button_chekout_page').fadeIn();
</script>	
<div id="payment" class="woocommerce-checkout-payment">
	
	<h3 id="payment_header"><?= _e( '3. Payment', 'woocommerce' )?></h3>
	<a href="#" id="payment_edit" class="checkout_edit">EDIT</a>
	<?
	$cardLogoPath = '/wp-content/themes/KDJ/woocommerce/checkout/payment_logos.png';
	echo "<img src='$cardLogoPath' class='payment_logos' />";
	?>
	<div style="clear:both"></div>
	<div class="field_review" id="payment_field_review">
		<div class="payment_review_fields_left"></div>
		<div class="payment_review_fields_right"></div>
	</div>
	<?php if ( WC()->cart->needs_payment() ) : ?>
		<ul class="wc_payment_methods payment_methods methods">
			<?php
				if ( ! empty( $available_gateways ) ) {
					foreach ( $available_gateways as $gateway ) {
						wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
					}
				} else {
					echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? __( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : __( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>';
				}
			?>
		</ul>
	<?php endif; ?>
	
	<h3 id="billing_header">Billing Information</h3>
	
	<div id="same_as_shipping_wrapper">
		<input type="checkbox" value="1" id="same_as_shipping" /> <span id="same_as_shipping_text">SAME AS SHIPPING</span>
	</div>
	
	<div id="billing_info">
	
	<?php foreach ( $checkout->get_checkout_fields( 'billing' ) as $key => $field ) : ?>
		<? if ($field['placeholder'] != "Email") { ?>
			<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
		<? } ?>
	<?php endforeach; ?>

	</div>
	
	
	<a href="#" id="payment_continue" class="continue">Continue</a>
	
	<div class="form-row place-order">
		<noscript>
			<?php _e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ); ?>
			<br/><input type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>" />
		</noscript>

		<?php wc_get_template( 'checkout/terms.php' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

		<?php //echo apply_filters( 'woocommerce_order_button_html', '<input type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '" />' ); ?>

		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout' ); ?>
	</div>
	<script src="/wp-content/themes/KDJ/grayson/payment.js" type="text/javascript"></script>
</div>
<?php
if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
?>