<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
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
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<script>
	function Timer(duration, display) 
{
    var timer = duration, hours, minutes, seconds;
    setInterval(function () {
        hours = parseInt((timer /3600)%24, 10)
        minutes = parseInt((timer / 60)%60, 10)
        seconds = parseInt(timer % 60, 10);
		hours = hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
		display.text(hours +":"+minutes + ":" + seconds);

				--timer;
    }, 1000);
}

jQuery(function ($) 
{
    var twentyFourHours = 24 * 60 * 60;
    var display = $('#remainingTime');
    Timer(twentyFourHours, display);
});
	
</script>
<div class="container">
	<div class="woocommerce-order">
	
		<?php if ( $order ) : ?>
	
			<?php if ( $order->has_status( 'failed' ) ) : ?>
	
				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>
	
				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
					<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
					<?php if ( is_user_logged_in() ) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My account', 'woocommerce' ); ?></a>
					<?php endif; ?>
				</p>
	
			<?php else : ?>
	
				<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); ?></p>
	
				<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
	
					<li class="woocommerce-order-overview__order order">
						<?php _e( 'YOUR ORDER NUMBER IS:', 'woocommerce' ); ?>
						<strong><?php echo $order->get_order_number(); ?></strong>
					</li>
	
		
				</ul>
	
			<?php endif; ?>
	
			
	
		<?php else : ?>
	
			<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>
	
		<?php endif; ?>
	
		<span id="email_reminder">A confirmation email has been sent to you. Please print out this page and keep the order number for your records.</span>
	
	</div>
	
	<?php
	$featuredImageURL = get_field('thank_you_featured_image', $post_id);
	$featuredText = get_field('thank_you_featured_text', $post_id);
	
	$featuredImageURL2 = get_field('thank_you_featured_image_2', $post_id);
	
	
	?>
	<div class="thank_you_image_wrap">
		<div class="thank_you_featured_image" style="background: url(<?=$featuredImageURL?>)">
			<h2 class="thank_you_featured_text"><?=$featuredText?></h2>
		</div>
	</div>
	
	<div class="timer_wrap">
		<div id="thankyou_timer" class="one-third">
			<div id="the-final-countdown">
				<span id="remainingTime">24:00:00</span>
			</div>	
		</div>
		<div id="thankyou_miss_something" class="one-third">
			<span>Miss something? We've got you! Order within the next 24 hours & get free shipping. <strong>USE CODE:</strong> JUSTONEMORE</span>
			<a href="/product-category/whats-new" class="cta">SHOP MORE</a>
		</div>
		<div id="thank_you_featured_image_2_wrap" class="one-third">
			<div class="thank_you_featured_image_2" style="background: url(<?=$featuredImageURL2?>)">
			</div>
		</div>
	</div>
</div>