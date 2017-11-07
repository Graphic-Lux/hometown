<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
global $product;
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
?>

<?php
/**
 * woocommerce_before_single_product hook
 *
 * @hooked wc_print_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form();
    return;
}
         $thumbnail_id = get_post_thumbnail_id();
         $thumbnail = wp_get_attachment_image_src($thumbnail_id,'full');
         $thumbnail_link = '';
         if(isset($thumbnail[0])) :
              $thumbnail_link = $thumbnail[0];
         endif;
         
         $productTitle = get_the_title();
?>


<div class="product_page_content">

    <div class="pathway_box">
        <?php breadcrumb_trail('echo=1&separator='); ?>
    </div>

    <div class="product_page_title mobile">
        <h2 class="product_page_sidebar_title">
            <?php the_title(); ?>
            <?php

    ?>
    <a class="pin_it_link" target="_blank" href="//www.pinterest.com/pin/create/button/?url=<?php echo esc_url(get_permalink()); ?>&media=<?php echo $thumbnail_link; ?>&description=<?php echo esc_attr(get_the_title()); ?>">pinterest</a>
        </h2>

        <span class="prod_sb_price"><?php echo $product->get_price_html(); ?></span>
    </div>
    <?php
    /**
     * woocommerce_before_single_product_summary hook
     *
     * @hooked woocommerce_show_product_sale_flash - 10
     * @hooked woocommerce_show_product_images - 20
     */
    do_action('woocommerce_before_single_product_summary');
    ?>
	<div style="clear: both;"></div>
	

</div>




<div class="product_page_sidebar">


    <?php do_action( 'woocommerce_single_product_summary' ); ?>

    <div class="prod_sb_shipping_info">
        <?php

        if(get_field('free_shipping_text', 'option')){
            echo '<h4>'.get_field('free_shipping_text', 'option').'</h4>';
        }

// 		echo "<h4>FREE SHIPPING ON ALL ORDERS $25+</h4>";

        echo wpautop($product->post->post_content); 
        ?>
    </div>
	
	<div class="returns_box">
        <a href="#" id="question" class="returns_tab"><?php _e('ASK A QUESTION', 'am'); ?></a>
<!--         <div class="returns_content_box"> -->
<!--             <div id="question" class="have-a-question related_box">
			<h4><span style="text-transform: uppercase">HAVE A QUESTION ABOUT  <?php the_title(); ?> ?</span></h4>
			<?php echo do_shortcode( '[contact-form-7 id="6481" title="Single Product Form - Have A Question?"]' ); ?>
			</div> -->
<!--
		    <div class="related_box recently_viewed desktop_only">
		        <h4><span><?php _e('RECENTLY VIEWED PIECES', 'am'); ?></span></h4>
		        <?php echo am_woocommerce_recently_viewed_products(true); ?>
		    </div>
--><!--/related_box-->
<!--         </div> -->
    </div>
    <div class="returns_box">
        <a href="#" class="returns_tab"><?php _e('JEWELRY CARE', 'am'); ?></a>
        <div class="returns_content_box">
            <?php echo wpautop(get_field('jewelry_care', 'option')); ?>
        </div>
    </div>
    <div class="returns_box">
        <a href="#" class="returns_tab"><?php _e('RETURNS', 'am'); ?></a>
        <div class="returns_content_box">
            <?php echo wpautop(get_field('returns', 'option')); ?>
        </div>
    </div>

    <ul class="prod_sb_media">
        <li><a href="http://www.facebook.com/sharer.php?u=<?php echo get_permalink($product->post->ID); ?>" class="prod_sb_fb" target="_blank"></a></li>
<!--         <li><a href="http://twitter.com/share?url=<?php echo get_permalink($product->post->ID); ?>&text=<?php echo $product->post->post_title; ?>" class="prod_sb_tw" target="_blank"></a></li> -->
<!--         <li><a href="//www.pinterest.com/pin/create/button/?url=<?php echo esc_url(get_permalink()); ?>&media=<?php echo $thumbnail_link; ?>&description=<?php echo esc_attr(get_the_title()); ?>" class="prod_sb_instagram" target="_blank"></a></li> -->
        <li><a href="http://pinterest.com/pin/create/button/?url=<script?php echo get_permalink($product->post->ID); ?>" class="prod_sb_pinterest" target="_blank"></a></li>
        <li><a href="mailto:?Subject=<?php echo $product->post->post_title; ?>&Body=<?php echo get_permalink($product->post->ID); ?>" class="prod_sb_email" target="_blank"></a></li>
    </ul>

</div><!--/product_page_sidebar-->

<!--
<div class="related_box recently_viewed mobile">
    <h4><span><?php _e('RECENTLY VIEWED', 'am'); ?></span></h4>
    <?php echo am_woocommerce_recently_viewed_products(true); ?>
</div>
-->

<?php
/**
 * woocommerce_after_single_product_summary hook
 *
 * @hooked woocommerce_output_product_data_tabs - 10
 * @hooked woocommerce_upsell_display - 15
 * @hooked woocommerce_output_related_products - 20
 */
//do_action( 'woocommerce_after_single_product_summary' );
?>

<meta itemprop="url" content="<?php the_permalink(); ?>" />

<div class="after_products">
	
	<div class="recently_viewed">
	
	<h4>RECENTLY VIEWED</h4>
	
	<?= do_shortcode('[woocommerce_recently_viewed_products per_page=”9″] ')?>
	<div class="slider_bullets">
		<a class="slider_bullet" id="bullet-1" href="#slide-0">&bull;</a><a class="slider_bullet" id="bullet-2" href="#slide-3">&bull;</a><a class="slider_bullet" id="bullet-3" href="#slide-6">&bull;</a>
	</div>
	</div>
	
	<div class="youll_love_these">
	
	<h4>YOU'LL LOVE THESE</h4>
	<? $productID = $product->get_id() ?>
	<?= do_shortcode("[cdxwoocs pid='$productID' theme=”theme-list-view”]")?>
	<div class="slider_bullets">
		<a class="slider_bullet" id="bullet-1" href="#slide-0">&bull;</a><a class="slider_bullet" id="bullet-2" href="#slide-3">&bull;</a><a class="slider_bullet" id="bullet-3" href="#slide-6">&bull;</a>
	</div>
	</div>
	
</div>

<div class="product_email_subscribe">
	
	<h3>Get <em>20%</em> Off Your First Order</h3>
	<span class="girlgang_description">Join the #girlgang at Katie Dean Jewelry today, and the hardest thing you'll have to do is pick what pieces you'd like to get for 20% OFF.</span>
    <?php
    if ($form_title) {
        ?>
        <h3><?php echo str_replace(array('<p>', '</p>'), "", wpautop($form_title)); ?></h3>
        <?php
    }
    ?>
	<form method="post" action="//katiedeanjewelry.us13.list-manage.com/subscribe/post?u=e8ba99d857a0300f3d6a4479f&amp;id=aa18b05827" target="_blank" class="product_email_subscribe_form" >
	<input type="text" placeholder="First Name" name="FNAME"></label>
	<input type="text" placeholder="Email" name="EMAIL"></label>
	<input type="submit" value="JOIN NOW">
	</form>

    <?php
    if ($form_text) {
        ?>
        <h4><?php echo str_replace(array('<p>', '</p>'), "", wpautop($form_text)); ?></h4>
        <?php
    }
    ?>
</div><!--/email_subscribe_box-->

<div class="product_question" id="question_form">
	<h3>Ask Us About: <?=$productTitle?>
<?=do_shortcode('[contact-form-7 id="6485"]')?>
</div>
<?php //do_action( 'woocommerce_after_single_product' ); ?>
