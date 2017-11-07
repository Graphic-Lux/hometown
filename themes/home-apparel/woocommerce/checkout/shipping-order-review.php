<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

	<div class="shop_table woocommerce-checkout-review-shipping-table">
	
		<?php // do_action( 'woocommerce_review_order_before_shipping' ); ?>
		
			<h3 class="shop_table">Choose Shipping Option:</h3>
			<div id="shipping_option_select">
				<?php wc_cart_totals_shipping_html(); ?>
			</div>
			
		<?php // do_action( 'woocommerce_review_order_after_shipping' ); ?>
	
	</div>

<? endif; ?>