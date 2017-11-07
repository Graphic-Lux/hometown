<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
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
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="woocommerce_totals shop_table woocommerce-checkout-review-order-table">
	
	<? if ( WC()->cart->coupons_enabled() ) { ?>
		<div class="cart_discount_code skin">
			<div class="coupon">
<!-- 				<div class="woocommerce-info">Discount Code</div> -->
				<? do_action( 'woocommerce_before_checkout_coupon' ); ?>		
				<? do_action('woocommerce_after_checkout_coupon'); ?>
			</div>
		</div>
	<? } ?>
	
	<ul id="totals">
		<li class="cart-subtotal">
			<span><?php _e( 'Subtotal', 'woocommerce' ); ?></span><span><?php wc_cart_totals_subtotal_html(); ?></span>
		</li>
		
		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<li class="fee">
				<span><?php echo esc_html( $fee->name ); ?></span><span><?php wc_cart_totals_fee_html( $fee ); ?></span>
			</li>
		<?php endforeach; ?>
	
		<?php if ( wc_tax_enabled() && 'excl' === WC()->cart->tax_display_cart ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<li class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
						<span><?php echo esc_html( $tax->label ); ?></span><span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
					</li>
				<?php endforeach; ?>
			<?php else : ?>
				<li class="tax-total">
					<span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span><span><?php wc_cart_totals_taxes_total_html(); ?></span>
				</li>
			<?php endif; ?>
		<?php endif; ?>
		
		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
	
			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
	
			<li class="cart_review_totals_shipping">
				<span><?php _e( 'Shipping', 'woocommerce' ); ?></span><span class="shipping-amount"><?php echo WC()->cart->get_cart_shipping_total(); ?></span>
			</li>
	
			<?php //wc_cart_totals_shipping_html(); ?>
	
			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
	
		<?php endif; ?>
		
		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<li class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
			<? $amount = WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax ) * -1 ?>
			<span><?=$code?></span><span><?= wc_price($amount) ?></span>
			</li>
		<?php endforeach; ?>

	
		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
	
		<li class="order-total">
			<span><?php _e( 'Total', 'woocommerce' ); ?></span><span><?php wc_cart_totals_order_total_html(); ?></span>
		</li>
	
		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
	</ul>
		
	
	<?php do_action( 'woocommerce_review_order_before_cart_contents' );

	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
	
		if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
			?>
			<ul class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> checkout_tbl_row">
				<li class="check_prod_photo_cell">
	                <?php
	                $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
	
	                if (!$_product->is_visible())
	                    echo $thumbnail;
	                else
	                    printf('<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail);
	                ?>
	            </li>
	            <li class="check_prod_name_cell">
	                <h3>
	                <?php
	                if (!$_product->is_visible())
	                    echo apply_filters('woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key);
	                else
	                    echo apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title()), $cart_item, $cart_item_key);
	
					$product_color = ($cart_item['variation']['attribute_pa_color']) ? strtoupper($cart_item['variation']['attribute_pa_color']) : false;
					$product_size = ($cart_item['variation']['attribute_pa_size']) ? explode('size-', $cart_item['variation']['attribute_pa_size'])[1] : false;
					
					if (strlen($product_size) > 1) {
						$firstSizeNumber = substr($product_size, 0, 1);
						$product_size = substr_replace($product_size, $firstSizeNumber . "/", 0, 1);
					}
					
					$product_color = ($product_color) ? "COLOR : " . $product_color : '';
					$product_size = ($product_size) ? "<span class='size_txt'>SIZE</span> <span class='size_numbers'>" . $product_size . "</span>": '';
			
				
					
					
	
	        // Meta data
	        
	
	        // Backorder notification
	        if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity']))
	            echo '<p class="backorder_notification">' . __('Available on backorder', 'woocommerce') . '</p>';
	                ?></h3>
	                <ul>
	                    <?php echo WC()->cart->get_item_data($cart_item); ?>
	                    <li>$<?php echo $_product->get_price(); ?></li>
	                </ul>
	                <?=($product_color) ? '<span class="cart_product_color">'.$product_color.'</span>' : '';?>
	                    <?
	                echo apply_filters('woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="checkout_delete_link" title="%s">REMOVE</a>', esc_url(WC()->cart->get_remove_url($cart_item_key)), __('Remove this item', 'woocommerce')), $cart_item_key);
	                ?>
	            </li>
	            <li class="check_prod_style_cell">
	            	<?=($product_size) ? '<span class="cart_product_size">'.$product_size.'</span>' : '';?>
	            	<span class="qty_txt">QTY</span><span class="checkout_product_qty"><?=$cart_item['quantity']?></span>
	            </li>
			</ul>
			<?php
		}
	}
		
	do_action( 'woocommerce_review_order_after_cart_contents' ); ?>	
	
	
</div>



