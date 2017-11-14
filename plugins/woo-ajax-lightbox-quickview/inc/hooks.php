<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_filter( 'body_class','more_woocommerce_body_classes' );
function more_woocommerce_body_classes( $classes ) {

  $classes[] = 'custom-create-page';
  $classes[] = 'product-template-default';
  $classes[] = 'single';
  $classes[] = 'single-product';
  $classes[] = 'woocommerce';
  $classes[] = 'woocommerce-page';


  return $classes;

}


add_action( 'woocommerce_after_shop_loop_item','walqv_hook_quickview_link', 11);
function walqv_hook_quickview_link(){
  global $post;
//  echo '<div class="wpb_wl_preview_area"><a class="wpb_wl_preview open-popup-link walqv_product_preview" href="#wpb_wl_quick_view_'.get_the_id().'" data-effect="mfp-zoom-in" data-url="'.get_permalink($post->ID).'" data-id="'.get_the_id().'">'.__( 'Quick View','woocommerce-lightbox' ).'</a></div>';
  echo '<div class="wpb_wl_preview_area"><a class="ajax-popup-link wpb_wl_preview open-popup-link walqv_product_preview" href="'.get_permalink($post->ID).'" data-effect="mfp-zoom-in"  data-id="'.get_the_id().'">'.__( 'Quick View','woocommerce-lightbox' ).'</a></div>';
}

//add_action( 'woocommerce_after_shop_loop_item','walqv_hook_quickview_content' );
function walqv_hook_quickview_content(){
  ?>
  <div id="wpb_wl_quick_view_<?php echo get_the_id(); ?>" class="mfp-hide mfp-with-anim wpb_wl_quick_view_content wpb_wl_clearfix product">
    <div class="walqv_container"></div>
  </div>
  <?php
}
