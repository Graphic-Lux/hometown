<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

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
          if (get_post_meta(get_the_ID(),'_custom_product', true) === 'yes') {
            wc_get_template_part( 'content', 'product' );
          }
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





add_action( 'wp_ajax_hometown_get_product_variant_images', 'hometown_get_product_variant_images' );

function hometown_get_product_variant_images() {

  // Sanitize.
  $post_id = absint( $_POST['product_id'] );

  if ( $_POST['variation_id'] === null ) {
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

        $main_images_left = "<div class='step_2_shirt_designs'>";

          // GRAB SHIRT IMAGE
          $leftHTML  = '<figure id="'.$shirtOrientation.'" data-thumb="' . esc_url( $thumbnail[0] ) . '" class="shirt_design woocommerce-product-gallery__image flex-active-slide">';
          $leftHTML .= wp_get_attachment_image( $id, 'shop_single', false, $attributes );
          $leftHTML .= '</figure>';

          // CREATE DROPDOWN
          $leftHTML_dropdown = "<select name='front-imprint_location' class='imprint_location_dropdown' id='front-imprint_location'>";
            $leftHTML_dropdown .= "<option value='0'>Choose Front Imprint Location</option>";
            $leftHTML_dropdown .= "<option value='full_front'>Full Front</option>";
            $leftHTML_dropdown .= "<option value='mid_chest'>Mid Chest</option>";
            $leftHTML_dropdown .= "<option value='pocket'>Left Chest / Pocket</option>";
          $leftHTML_dropdown .= "</select>";

          // PUT IMAGE INTO WOOCOMMERCE HTML FILTER
          $main_images_left .= apply_filters( 'woocommerce_single_product_image_thumbnail_html', $leftHTML, $id );
          $main_images_left .= $leftHTML_dropdown;
        $main_images_left .= "</div>";

      } else if ($shirtOrientation === 'back') {

        $main_images_middle = "<div class='step_2_shirt_designs'>";
          $middleHTML  = '<figure id="'.$shirtOrientation.'"data-thumb="' . esc_url( $thumbnail[0] ) . '" class="shirt_design woocommerce-product-gallery__image flex-active-slide ">';
          $middleHTML .= wp_get_attachment_image( $id, 'shop_single', false, $attributes );
          $middleHTML .= '</figure>';

          $middleHTML_dropdown = "<select name='back-imprint_location' class='imprint_location_dropdown' id='back-imprint_location'>";
            $middleHTML_dropdown .= "<option value='0'>Choose Back Imprint Location</option>";
            $middleHTML_dropdown .= "<option value='full_back'>CENTERED - Full Imprint</option>";
            $middleHTML_dropdown .= "<option value='upper_back'>Upper Back</option>";
            $middleHTML_dropdown .= "<option value='lower_back'>Lower Back</option>";
          $middleHTML_dropdown .= "</select>";

          $main_images_middle .= apply_filters( 'woocommerce_single_product_image_thumbnail_html', $middleHTML, $id );
          $main_images_middle .= $middleHTML_dropdown;
        $main_images_middle .= "</div>";

      } else if ($shirtOrientation === 'sleeve') {

        $main_images_right = "<div class='step_2_shirt_designs'>";
          $rightHTML  = '<figure id="'.$shirtOrientation.'" data-thumb="' . esc_url( $thumbnail[0] ) . '" class="shirt_design woocommerce-product-gallery__image flex-active-slide">';
          $rightHTML .= wp_get_attachment_image( $id, 'shop_single', false, $attributes );
          $rightHTML .= '</figure>';

          if (($shirtType === 'longsleeve') || ($shirtType === 'hoodie')) {
            $rightHTML_dropdown = "<select name='sleeve-imprint_location' class='imprint_location_dropdown' id='sleeve-imprint_location'>";
              $rightHTML_dropdown .= "<option value='0'>Choose Side Imprint Location</option>";
              $rightHTML_dropdown .= "<option value='left_sleeve'>Left Sleeve</option>";
              $rightHTML_dropdown .= "<option value='right_sleeve'>Right Sleeve</option>";
              $rightHTML_dropdown .= "<option value='both'>Both Sleeves</option>";
            $rightHTML_dropdown .= "</select>";
          }



          $main_images_right .= apply_filters( 'woocommerce_single_product_image_thumbnail_html', $rightHTML, $id );

          if (($shirtType === 'longsleeve') || ($shirtType === 'hoodie')) {
            $main_images_right .= $rightHTML_dropdown;
          }

        $main_images_right .= "</div>";
      }


      $loop++;

    }

    $main_images .= $main_images_left . $main_images_middle . $main_images_right;

  }

  $main_images .= '</figure></div>';

  echo $main_images;
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


// CHANGE ADD TO CART TEXT -> CONTINUE
add_filter( 'add_to_cart_text', 'woo_custom_single_add_to_cart_text' );                // < 2.1
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_single_add_to_cart_text' );  // 2.1 +

function woo_custom_single_add_to_cart_text() {

  return __( 'Continue', 'woocommerce' );

}





add_action( 'woocommerce_after_single_product_summary', 'hometown_sizing_fields', 0 );

function hometown_sizing_fields() {

  hometown_display_sizes();

  return true;

}

function hometown_display_sizes() {

  global $post;
  ?>
  <div class="all_shirt_sizes">
    <div class="standard_sizes">
      <div class="sizing_inputs">
        <?php $xsData = (get_user_meta(get_current_user_id(), 'shirt_sizes-' . $post->ID . '-' . 'XS', true)) ? get_user_meta(get_current_user_id(), 'shirt_sizes-' . $post->ID . '-' . 'XS', true) : 0;
        ?>
        <label for="XS">XS</label>
        <input name="XS" type="text" value="<?=$xsData?>"/>
      </div>
      <div class="sizing_inputs">
        <?php $sData = (get_user_meta(get_current_user_id(), 'shirt_sizes-' . $post->ID . '-' . 'S', true)) ? get_user_meta(get_current_user_id(), 'shirt_sizes-' . $post->ID . '-' . 'S', true) : 0; ?>
        <label for="S">S</label>
        <input name="S" type="text" value="<?=$sData?>"/>
      </div>
      <div class="sizing_inputs">
        <?php $mData = (get_user_meta(get_current_user_id(), 'shirt_sizes-' . $post->ID . '-' . 'M', true)) ? get_user_meta(get_current_user_id(), 'shirt_sizes-' . $post->ID . '-' . 'M', true) : 0; ?>
        <label for="M">M</label>
        <input name="M" type="text" value="<?=$mData?>"/>
      </div>
      <div class="sizing_inputs">
        <?php $lData = (get_user_meta(get_current_user_id(), 'shirt_sizes-' . $post->ID . '-' . 'L', true)) ? get_user_meta(get_current_user_id(), 'shirt_sizes-' . $post->ID . '-' . 'L', true) : 0; ?>
        <label for="L">L</label>
        <input name="L" type="text" value="<?=$lData?>"/>
      </div>
      <div class="sizing_inputs">
        <?php $xlData = (get_user_meta(get_current_user_id(), 'shirt_sizes-' . $post->ID . '-' . 'XL', true)) ? get_user_meta(get_current_user_id(), 'shirt_sizes-' . $post->ID . '-' . 'XL', true) : 0; ?>
        <label for="XL">XL</label>
        <input name="XL" type="text" value="<?=$xlData?>"/>
      </div>
    </div>
    <a class="more_sizes">Need bigger sizes?</a>
    <div class="bigger_sizes">
      <div class="sizing_inputs">
        <?php $xxlData = (get_user_meta(get_current_user_id(), 'shirt_sizes-' . $post->ID . '-' . 'XXL', true)) ? get_user_meta(get_current_user_id(), 'shirt_sizes-' . $post->ID . '-' . 'XXL', true) : 0; ?>
        <label for="XXL">XXL</label>
        <input name="XXL" type="text" value="<?=$xxlData?>"/>
      </div>
      <div class="sizing_inputs">
        <?php $xxxlData = (get_user_meta(get_current_user_id(), 'shirt_sizes-' . $post->ID . '-' . '3XL', true)) ? get_user_meta(get_current_user_id(), 'shirt_sizes-' . $post->ID . '-' . '3XL', true) : 0; ?>
        <label for="3XL">3XL</label>
        <input name="3XL" type="text" value="<?=$xxxlData?>"/>
      </div>
      <div class="sizing_inputs">
        <?php $xxxxlData = (get_user_meta(get_current_user_id(), 'shirt_sizes-' . $post->ID . '-' . '4XL', true)) ? get_user_meta(get_current_user_id(), 'shirt_sizes-' . $post->ID . '-' . '4XL', true) : 0; ?>
        <label for="4XL">4XL</label>
        <input name="4XL" type="text" value="<?=$xxxxlData?>"/>
      </div>
    </div>
  </div>
  <?

}


/**
* @desc Remove in all product type
*/
function wc_remove_all_quantity_fields( $return, $product ) {
  return true;
}
add_filter( 'woocommerce_is_sold_individually', 'wc_remove_all_quantity_fields', 10, 2 );



add_filter( 'body_class','hometown_body_classes' );
function hometown_body_classes( $classes ) {

  $pathname = $_SERVER['REQUEST_URI'];

  if (strpos($pathname, 'create')) {
    $classes[] = 'custom-create-page';
  } else if (strpos($pathname, 'predesigned')) {
    $classes[] = 'predesigned';
  }
  $classes[] = 'product-template-default';
//  $classes[] = 'single';
  $classes[] = 'single-product';
  $classes[] = 'woocommerce';
  $classes[] = 'woocommerce-page';

  return $classes;

}