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

function hometown_get_product_variant_images() {

  $_POST['ajaxImageSwapNonce'] = wp_create_nonce('_wc_additional_variation_images_nonce');
  $_POST['variation_id'] = 352;
  $_POST['post_id'] = 340;


  $nonce = $_POST['ajaxImageSwapNonce'];

  // Bail if nonce don't check out.
  if ( ! wp_verify_nonce( $nonce, '_wc_additional_variation_images_nonce' ) ) {
    die( 'error' );
  }

  // Sanitize.
  $post_id = absint( $_POST['post_id'] );

  $variation_id = '';

  if ( ! isset( $_POST['variation_id'] ) ) {
    $image_ids = get_post_thumbnail_id( $post_id ) . ',' . get_post_meta( $post_id, '_product_image_gallery', true );
  } else {
    $variation_id = absint( $_POST['variation_id'] );

    $image_ids = get_post_meta( $variation_id, '_wc_additional_variation_images', true );
  }

  $image_ids = array_filter( explode( ',', $image_ids ) );

  $product = wc_get_product( $variation_id );

  // If we're selecting the "Choose an Option" (i.e. no variation), product may not be set.
  if ( $product ) {
    $main_image_id = $product->get_image_id();

    if ( ! empty( $main_image_id ) ) {
      array_unshift( $image_ids, $main_image_id );
    }
  }

  $main_images = '<div class="woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-' . apply_filters( 'woocommerce_product_thumbnails_columns', 4 ) . ' images" data-columns="' . apply_filters( 'woocommerce_product_thumbnails_columns', 4 ) . '"><figure class="woocommerce-product-gallery__wrapper">';

  $loop = 0;

  if ( 0 < count( $image_ids ) ) {
    // Build html.
    foreach ( $image_ids as $id ) {
      $image_title     = esc_attr( get_the_title( $id ) );
      $full_size_image = wp_get_attachment_image_src( $id, 'full' );
      $thumbnail       = wp_get_attachment_image_src( $id, 'shop_thumbnail' );
      $attributes = array(
          'title'                   => $image_title,
          'data-large_image'        => $full_size_image[0],
          'data-large_image_width'  => $full_size_image[1],
          'data-large_image_height' => $full_size_image[2],
      );


      $filename = end(explode('/', parse_url($full_size_image[0])['path']));

      $filenameParts = explode('-', $filename);
      $shirtGender = $filenameParts[0];
      $shirtType = $filenameParts[1];
      $shirtColor = $filenameParts[2];
      $shirtOrientation = $filenameParts[3];
      $shirtBranding = $filenameParts[4];

      if ($shirtOrientation === 'front') {
        $html  = '<figure data-thumb="' . esc_url( $thumbnail[0] ) . '" class="woocommerce-product-gallery__image flex-active-slide shirt-front-design">';
      } else if ($shirtOrientation === 'back') {
        $html  = '<figure data-thumb="' . esc_url( $thumbnail[0] ) . '" class="woocommerce-product-gallery__image flex-active-slide shirt-back-design">';
      } else if (($shirtOrientation === 'sleeve') || ($shirtOrientation === 'side')) {
        $html  = '<figure data-thumb="' . esc_url( $thumbnail[0] ) . '" class="woocommerce-product-gallery__image flex-active-slide shirt-sleeve-design">';
      } else {
        $html  = '<figure data-thumb="' . esc_url( $thumbnail[0] ) . '" class="woocommerce-product-gallery__image flex-active-slide shirt-no-design">';
      }

      $html .= wp_get_attachment_image( $id, 'shop_single', false, $attributes );
      $html .= '</figure>';

      $main_images .= apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $id );


      // Build the list of variations as main images in case a custom
      // theme has flexslider type lightbox.
//      $main_images .= apply_filters( 'woocommerce_single_product_image_html', sprintf( '<figure data-thumb="%s" class="woocommerce-product-gallery__image flex-active-slide">%s</figure>', esc_url( $thumbnail[0] ), wp_get_attachment_image( $id, 'shop_single', false, $attributes ) ), $post_id );

      $loop++;
    }
  }

  $main_images .= '</figure></div>';

  echo $main_images;
//  echo $html;

//  echo json_encode( array( 'main_images' => $main_images ) );
  exit;
}




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