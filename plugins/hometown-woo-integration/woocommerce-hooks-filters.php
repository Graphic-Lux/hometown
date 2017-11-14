<?php

add_action( 'wp_ajax_nopriv_woocommerce_json_search_products', array( 'WC_AJAX', 'json_search_products') );
add_action( 'wc_ajax_json_search_products', array( 'WC_AJAX', 'json_search_products') );



// WE WANT ONLY LOGGED IN USERS TO BE ALLOWED TO DO THIS
add_action( 'wp_ajax_hometown_get_products_by_category', 'hometown_get_products_by_category' );

function hometown_get_products_by_category() {

  if ((isset($_GET['style'])) && (isset($_GET['type']))) {
    $style = preg_replace('/\PL/u', '', $_GET['style']);
    $type = preg_replace('/\PL/u', '', $_GET['type']);

    // TODO: FIX META_QUERY
    $args2 = array(
        'post_type'             => 'product',
        'post_status'           => 'publish',
        'ignore_sticky_posts'   => 1,
        'posts_per_page'        => '12',
//        'meta_query'            => array(
//            array(
//                'key'           => '_visibility',
//                'value'         => array('catalog', 'visible'),
//                'compare'       => 'IN'
//            )
//        ),
        'tax_query'             => array(
            array(
                'taxonomy'      => 'product_cat',
//                'field'         => 'term_id', //This is optional, as it defaults to 'term_id'
                'field'         => 'slug',
//                'terms'         => array($categoryIDType, $categoryIDStyle),
                'terms'         => array($style, $type),
                'operator'      => 'AND' // Possible values are 'IN', 'NOT IN', 'AND'.
            )
        )
    );

    ?>
    <ul class="products">
      <?php
      $loop = new WP_Query( $args2 );
      if ( $loop->have_posts() ) {
        while ( $loop->have_posts() ) : $loop->the_post();
          wc_get_template_part( 'content', 'product' );
        endwhile;
      } else {
        echo __( 'No products found' );
      }
      wp_reset_postdata();
      ?>
    </ul><!--.products-->
    <?php
    wp_die();

  }

}





//add_action( 'wp_ajax_hometown_get_product_variant_images', 'hometown_get_product_variant_images' );

//function hometown_get_product_variant_images() {
//
//  global $woocommerce;
//
//  if ((isset($_GET['product_id'])) && (isset($_GET['variant_id']))) {
//    $productID = preg_replace('/\PL/u', '', $_GET['product_id']);
//    $variantID = preg_replace('/\PL/u', '', $_GET['variant_id']);
//
//    $productID = 184;
//    $variantID = 149;
//
////    echo $productID;
//
//    $product = wc_get_product( $productID );
//
//    $variations = $product->get_available_variations();
//    foreach ( $variations as $variation ) {
//      echo $variation['image_src'];
//    }
//
//  }
//
//
//}





// REMOVE AVIA ADD TO CART AND SELECT OPTION ON SINGLE PRODUCTS
add_action('init','remove_woocommerce_config');
function remove_woocommerce_config(){

  // remove add to cart button on single product
  remove_action( 'woocommerce_after_shop_loop_item', 'avia_add_cart_button', 16);

  // CREATES SINGLE PRODUCT PAGE BUG WITH THIS UNCOMMENTED
//    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

}



remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );

add_action ( 'woocommerce_before_shop_loop_item', 'enable_only_lightbox_product_view_function', 20 );
function enable_only_lightbox_product_view_function() {
//  echo '<a class="wpb_wl_preview open-popup-link woocommerce-LoopProduct-link woocommerce-loop-product__link" href="#wpb_wl_quick_view_' . get_the_ID() . '" data-effect="mfp-zoom-in">';
  echo '<a class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';

}


function display_product_page($postID) {
  echo do_shortcode("[product_page id='$postID']");
}


//remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 11 );
//remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 16 );
//remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 21 );