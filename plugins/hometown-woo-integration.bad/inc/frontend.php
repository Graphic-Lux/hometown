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
        'posts_per_page'        => -1,
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
        ),
        'orderby'         =>  'menu_order',
        'order'           =>  'ASC',
    );

    ?>


      <ul class="products shirt_style_options swiper-wrapper">
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


      $fullSizeImage = parse_url($full_size_image[0]);
      $filename = end(explode('/', $fullSizeImage['path']));

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

          $leftHTML .= "<span class='click_to_edit_product'>CLICK TO EDIT PRODUCT</span>";

          // CREATE DROPDOWN
          $leftHTML_dropdown = "<select name='front-imprint_location' class='imprint_location_dropdown' id='front-imprint_location'>";
            $leftHTML_dropdown .= "<option value='0'>Choose Front Imprint Location</option>";
            if ($shirtType === 'polo') {
              $leftHTML_dropdown .= "<option value='pocket'>Left Chest / Pocket</option>";
            } else if ($shirtType === 'hat') {
              $leftHTML_dropdown .= "<option value='full_front'>Full Front</option>";
            } else {
              $leftHTML_dropdown .= "<option value='full_front'>Full Front</option>";
              $leftHTML_dropdown .= "<option value='pocket'>Left Chest / Pocket</option>";
              $leftHTML_dropdown .= "<option value='centered'>Centered</option>";
            }
          $leftHTML_dropdown .= "</select>";

          // PUT IMAGE INTO WOOCOMMERCE HTML FILTER
//          $main_images_left .= apply_filters( 'woocommerce_single_product_image_thumbnail_html', $leftHTML, $id );
          $main_images_left .= $leftHTML;
          $main_images_left .= $leftHTML_dropdown;
        $main_images_left .= "</div>";

      } else if ($shirtOrientation === 'back') {

        if (($shirtType === 'longsleeve') || ($shirtType === 'tee') || ($shirtType === 'tank') || ($shirtType === 'polo')) {

          $main_images_middle = "<div class='step_2_shirt_designs'>";
          $middleHTML = '<figure id="' . $shirtOrientation . '"data-thumb="' . esc_url($thumbnail[0]) . '" class="shirt_design woocommerce-product-gallery__image flex-active-slide ">';
          $middleHTML .= wp_get_attachment_image($id, 'shop_single', false, $attributes);
          $middleHTML .= '</figure>';

          $middleHTML .= "<span class='click_to_edit_product'>CLICK TO EDIT PRODUCT</span>";

          $middleHTML_dropdown = "<select name='back-imprint_location' class='imprint_location_dropdown' id='back-imprint_location'>";
          if ($shirtType === 'polo') {
            $middleHTML_dropdown .= "<option value='0'>No Imprint Available</option>";
          } else {
            $middleHTML_dropdown .= "<option value='0'>Choose Back Imprint Location</option>";
            $middleHTML_dropdown .= "<option value='full_back'>Full Back</option>";
          }
          $middleHTML_dropdown .= "</select>";

//          $main_images_middle .= apply_filters('woocommerce_single_product_image_thumbnail_html', $middleHTML, $id);
          $main_images_middle .= $middleHTML;
          $main_images_middle .= $middleHTML_dropdown;
          $main_images_middle .= "</div>";

        }

      } else if ($shirtOrientation === 'sleeve') {

        if (($shirtType === 'longsleeve') || ($shirtType === 'hoodie')) {

          $main_images_right = "<div class='step_2_shirt_designs'>";
            $rightHTML  = '<figure id="'.$shirtOrientation.'" data-thumb="' . esc_url( $thumbnail[0] ) . '" class="shirt_design woocommerce-product-gallery__image flex-active-slide">';
            $rightHTML .= wp_get_attachment_image( $id, 'shop_single', false, $attributes );
            $rightHTML .= '</figure>';

            $rightHTML .= "<span class='click_to_edit_product'>CLICK TO EDIT PRODUCT</span>";

            if (($shirtType === 'longsleeve') || ($shirtType === 'hoodie')) {
              $rightHTML_dropdown = "<select name='sleeve-imprint_location' class='imprint_location_dropdown' id='sleeve-imprint_location'>";
                $rightHTML_dropdown .= "<option value='0'>Choose Side Imprint Location</option>";
                $rightHTML_dropdown .= "<option value='left_sleeve'>Left Arm</option>";
                $rightHTML_dropdown .= "<option value='right_sleeve'>Right Arm</option>";
//                $rightHTML_dropdown .= "<option value='both'>Both Sleeves</option>";
              $rightHTML_dropdown .= "</select>";
            }


//            $main_images_right .= apply_filters( 'woocommerce_single_product_image_thumbnail_html', $rightHTML, $id );
            $main_images_right .= $rightHTML;
            $main_images_right .= $rightHTML_dropdown;
          $main_images_right .= "</div>";
          }

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

  return __( 'SELECT THIS PRODUCT', 'woocommerce' );

}




add_action( 'wp_ajax_hometown_display_sizes', 'hometown_display_sizes' );

function hometown_display_sizes() {

  ?>
  
  <div class="all_shirt_sizes">
	 
    <div class="standard_sizes">
      <?php
      if (hometownGetReferrer() === 'predesigned') {
        ?> <h2>Choose Your Sizes</h2> <?php
      }
      ?>
      <div class="sizing_inputs">
        <label for="XS">XS</label>
        <input name="XS" type="text" class="size_qty" value="0"/>
      </div>
      <div class="sizing_inputs">
        <label for="S">S</label>
        <input name="S" type="text" class="size_qty" value="0"/>
      </div>
      <div class="sizing_inputs">
        <label for="M">M</label>
        <input name="M" type="text" class="size_qty" value="0"/>
      </div>
      <div class="sizing_inputs">
        <label for="L">L</label>
        <input name="L" type="text" class="size_qty" value="0"/>
      </div>
      <div class="sizing_inputs">
        <label for="XL">XL</label>
        <input name="XL" type="text" class="size_qty" value="0"/>
      </div>
    </div>
    <a class="more_sizes">*Need bigger sizes?</a>
    <div class="bigger_sizes">
      <div class="sizing_inputs">
        <label for="XXL">XXL</label>
        <input name="XXL" type="text" class="size_qty" value="0"/>
      </div>
      <div class="sizing_inputs">
        <label for="3XL">3XL</label>
        <input name="3XL" type="text" class="size_qty" value="0"/>
      </div>
      <div class="sizing_inputs">
        <label for="4XL">4XL</label>
        <input name="4XL" type="text" class="size_qty" value="0"/>
      </div>
      <?php

      if (hometownGetReferrer() === 'predesigned') {

        $productID = get_the_ID();

        $product = wc_get_product( $productID );
        $productChild = $product->get_children();
        $variationID = $productChild[0];

        display_additional_sizes_price($variationID);

      }

      ?>
      <div id="pricing"></div>
    </div>
  </div>
  <?
//  wp_die();
}



add_action('wp_ajax_display_additional_sizes_price', 'display_additional_sizes_price');
function display_additional_sizes_price($variationID) {

  if ($variationID === '') {
    $uniqueID = $_POST['variation_id'];
  } else {
    $uniqueID = $variationID;
  }


  $additionalSizesPrice = get_post_meta( $uniqueID, '_xxl_pricing', true );

  if (isset($additionalSizesPrice)) {
    ?>
    <span id="xxl_pricing">* Pricing for XXL-4XL is <?= wc_price($additionalSizesPrice) ?>/shirt</span>
    <?php
  } else {
    ?>
    <span id="xxl_pricing">* Price may be more for shirts XXL-4XL.</span>
    <?php
  }

  if (hometownGetReferrer() === 'create') {
    wp_die();
  }

}




/**
* @desc Remove in all product type
*/
function wc_remove_all_quantity_fields( $return, $product ) {
  return true;
}
add_filter( 'woocommerce_is_sold_individually', 'wc_remove_all_quantity_fields', 10, 2 );

add_filter('woocommerce_return_to_shop_redirect', 'hometown_return_to_shop_link');
function hometown_return_to_shop_link() {
  return get_site_url() . '/landing-page';
}



add_filter( 'body_class','hometown_body_classes' );
function hometown_body_classes( $classes ) {

  $page = hometownGetPage();

  if ($page === 'create') {
    $classes[] = 'custom-create-page';
  } else if ($page === 'predesigned') {
    $classes[] = 'predesigned';
  }

  if (($page === 'create') || ($page === 'predesigned')) {
    $classes[] = 'product-template-default';
//  $classes[] = 'single';
    $classes[] = 'single-product';
    $classes[] = 'woocommerce';
    $classes[] = 'woocommerce-page';
  }


  return $classes;

}


function hometownGetPage()
{

  $pathname = $_SERVER['REQUEST_URI'];

  if (strpos($pathname, 'create')) {
    return 'create';
  } else if (strpos($pathname, 'predesigned')) {
    return 'predesigned';
  } else if (strpos($pathname, 'product')) {
    return 'product';
  } else if (strpos($pathname, 'admin-ajax')) {
    return 'create';
  }

}

function hometownGetReferrer() {
  $pathname = $_SERVER["HTTP_REFERER"];

  if (strpos($pathname, 'create')) {
    return 'create';
  } else if (strpos($pathname, 'predesigned')) {
    return 'predesigned';
  } else if (strpos($pathname, 'product')) {
    return 'product';
  } else if (strpos($pathname, 'admin-ajax')) {
    return 'create';
  }
}

