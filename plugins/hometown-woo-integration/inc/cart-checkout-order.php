<?php

add_action( 'wp_ajax_hometown_woocommerce_add_to_cart_variation', 'hometown_woocommerce_add_to_cart_variation' );

function hometown_woocommerce_add_to_cart_variation() {

  ob_start();

  $productID = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
  $quantity = 1;
  $variation_id = $_POST['variation_id'];
//  $variation  = array('color' => $_POST['variation']);
  $variation = false;
  $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $productID, $quantity );

//  var_dump($variation);

  if ( $passed_validation && WC()->cart->add_to_cart( $productID, $quantity, $variation_id, $variation  ) ) {

    $return = array(
        'action'       => 'add to cart',
        'result'       => true
    );

    wp_send_json($return);

  } else {

    $return = array(
        'action'       => 'add to cart',
        'result'       => false
    );

    wp_send_json($return);

  }

  wp_die();
}




//add_filter('woocommerce_checkout_cart_item_quantity','hometown_add_user_custom_option_from_session_into_cart',1,3);
add_filter('woocommerce_cart_item_name','hometown_add_user_sizes_and_imprint_data_into_cart_name',1,3);
if(!function_exists('hometown_add_user_sizes_and_imprint_data_into_cart_name')) {
  function hometown_add_user_sizes_and_imprint_data_into_cart_name($product_name, $values, $cart_item_key ) {

    if (is_checkout()) {
      $product = wc_get_product( $values['product_id'] );
      $variationID = hometown_get_variation_id($values['variation_id'],$values['product_id']);
      $uniqueIdentifier = $values['unique_key'];

      $output = $product_name . "<dl class='variation'>";
      $output .= hometown_display_user_meta($product, $variationID, 'cart', $uniqueIdentifier);
      $output .= "</dl>";

      return $output;
    } else {
      return $product_name . "<dl class='variation'>";
    }

  }

}


add_filter('woocommerce_cart_item_quantity','hometown_add_user_sizes_and_imprint_data_into_cart_quantity',1,3);
function hometown_add_user_sizes_and_imprint_data_into_cart_quantity($qty, $cart_item_key, $values ) {

  $product = wc_get_product( $values['product_id'] );
  $variationID = hometown_get_variation_id($values['variation_id'],$values['product_id']);
  $uniqueIdentifier = $values['unique_key'];

  $output = "<dl class='variation'>";
  $output .= hometown_display_user_meta($product, $variationID, 'cart', $uniqueIdentifier);
  $output .= "</dl>";

  return $output;

}


add_filter('woocommerce_display_item_meta','hometown_customize_woo_order_item_meta',1,3);
function hometown_customize_woo_order_item_meta($html, $item, $args) {

  $output = "<dl class='variation'>";

  foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
    $output .= $meta->value;
  }

  $output .= "</dl>";

  return $output;

}

add_action('woocommerce_before_order_itemmeta', 'hometown_customize_woo_admin_order_screen');

function hometown_customize_woo_admin_order_screen($meta_id) {

  echo wc_get_order_item_meta($meta_id, 'Sizes and Artwork', true);

}




add_action( 'woocommerce_before_calculate_totals', 'hometown_calculate_price', 10 );
function hometown_calculate_price( $cart_object ) {

  global $isProcessed;

  if( !WC()->session->__isset( "reload_checkout" )) {


    foreach ( $cart_object->get_cart() as $key => $item ) {

      $product_subtotal = 0;

      $product = wc_get_product( $item['product_id'] );
      $variationID = hometown_get_variation_id($item['variation_id'], $item['product_id']);
      $uniqueIdentifier = $item['unique_key'];

      $sizeData = hometown_get_size_data($uniqueIdentifier);
      $productSizeData = $sizeData[$uniqueIdentifier];
      $price = hometown_get_price($product, $variationID);

      // GET IMPRINT AND ARTWORK DATA
      $imprintArray = hometown_get_imprint_data($uniqueIdentifier);
      $artworkDataArray = hometown_get_imprint_artwork($uniqueIdentifier);

      $artworkPrices = array();

      if ($artworkDataArray) {
        // SET ARTWORK PRICE
        foreach ($imprintArray[$uniqueIdentifier] as $orientation => $location) {
          if ($location != '') {
            $artworkPrices[$orientation] = (float) number_format(hometown_get_artwork_price($artworkDataArray[$orientation]['id']), 2);
          }
        }

        $artTotal = (float) 0.00;
        $artPriceOutput = '';

        foreach ($artworkPrices as $orientation => $artPrice) {
//          $artPriceOutput .= '<br>+ ' . $orientation . ' artwork Price: $' . number_format($artPrice, 2);
          $artTotal += (float) $artPrice;
        }
      } else {
        $artTotal = (float) 0.00;
      }

      foreach ($productSizeData as $size => $qty) {

        if (($size === 'XXL') || ($size === '3XL') || ($size === '4XL') || ($size === '5XL')) {

          $xxlPricing = (float) get_post_meta( $variationID, '_xxl_pricing', true );
          $lineSubtotal = $qty * ($xxlPricing + $artTotal);

        } else {

          $lineSubtotal = $qty * ($price + $artTotal);

        }

        $product_subtotal += $lineSubtotal;

      }



      $item['data']->set_price((float) $product_subtotal);

    }

    $isProcessed = true;

  }

}






function hometown_get_price($product, $variationID) {
  if( $product->is_on_sale() ) {
    $sale_price = ($product->get_sale_price() == 0) ? get_post_meta($variationID, '_sale_price', true) : $product->get_sale_price();
    return $sale_price;
  }
  $regular_price = ($product->get_regular_price() == 0) ? get_post_meta($variationID, '_regular_price', true) : $product->get_regular_price();
  return $regular_price;
}






// AJAX REFRESH MINI CART
function hometown_ajax_refresh_cart() {
  echo do_shortcode('[woocommerce_cart]');
  exit;
}
add_filter( 'wp_ajax_hometown_ajax_refresh_cart', 'hometown_ajax_refresh_cart' );





function hometown_display_imprint_data($productID, $variationID, $uniqueIdentifier) {

  $imprintArray = hometown_get_imprint_data($uniqueIdentifier);

  if (count($imprintArray[$uniqueIdentifier]) > 0) {

    $output = '<table class="table table-borderless wdm_options_table" id="' . $productID . '">';
    $output .= '<thead>
                    <tr>
                      <th>Shirt Orientation</th>
                      <th>Imprint Location</th>
                      <th>Artwork</th>
                    </tr>
                  </thead>
                  <tbody>';

    $artworkDataArray = hometown_get_imprint_artwork($uniqueIdentifier);

    foreach ($imprintArray[$uniqueIdentifier] as $orientation => $location) {
      if ($location != '') {
        $output .= "<tr class='preview_imprint_locations'>";
        $output .= "<td>" . $orientation . "</td>";
        $output .= "<td>" . $location . "</td>";
        $output .= "<td>";
          $color = ($artworkDataArray[$orientation]['color'] === 'No custom color') ? '' : $artworkDataArray[$orientation]['color'];
          $output .= "<img src='" . $artworkDataArray[$orientation]['url'] . "' class='force-inline-svg' data-color='".$color."'/>";
        $output .= "</td>";
//        $output .= "<td>$" . number_format(hometown_get_artwork_price($artworkDataArray[$orientation]['id']), 2) . "/shirt</td>";
        $output .= "</tr>";
      }
    }

    $output .= '</tbody>
                </table>';

    return $output;

  } else {
    return '';
  }

}




function hometown_display_size_data($product, $productID, $variationID, $screen, $uniqueIdentifier) {

  $sizeData = hometown_get_size_data($uniqueIdentifier);

  /*code to add custom data on Cart & checkout Page*/
  if(count($sizeData[$uniqueIdentifier]) > 0)
  {

    $output = '<table class="table table-borderless wdm_options_table" id="' . $productID . '">';
    $output .= '<thead>
                  <tr>
                    <th>Size</th>
                    <th>QTY</th>
                    <th>Each</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>';

    $price = hometown_get_price($product, $variationID);

    // GET IMPRINT AND ARTWORK DATA
    $imprintArray = hometown_get_imprint_data($uniqueIdentifier);
    $artworkDataArray = hometown_get_imprint_artwork($uniqueIdentifier);


    if ($artworkDataArray) {

      $artworkPrices = array();

      // SET ARTWORK PRICE
      foreach ($imprintArray[$uniqueIdentifier] as $orientation => $location) {
        if ($location != '') {
          $artworkPrices[$orientation] = (float) number_format(hometown_get_artwork_price($artworkDataArray[$orientation]['id']), 2);
        }
      }

      $artTotal = (float) 0.00;
      $artPriceOutput = '';

      foreach ($artworkPrices as $orientation => $artPrice) {
//        $artPriceOutput .= '<br>+ ' . $orientation . ' artwork Price: $' . number_format($artPrice, 2);
        $artTotal += (float) $artPrice;
      }

    } else {
      $artTotal = (float) 0.00;
      $artPriceOutput = '';
    }

    foreach($sizeData[$uniqueIdentifier] as $size => $qty) {

      if (($size === 'XXL') || ($size === '3XL') || ($size === '4XL') || ($size === '5XL')) {

        $xxlPriceNumber = (float) number_format((float) get_post_meta( $variationID, '_xxl_pricing', true ), 2);
        $xxlPrice = '$'.$xxlPriceNumber;

        $output .= "<tr class='preview_sizes'>";
        $output .= '<td>' . $size . '</td>';

        if ($screen === 'cart') {
          $output .= '<td><input type="text" name="'.$size.'" data-product-id="'.$productID.'" data-product-variant-id="'.$variationID.'" data-unique-cart-key="'.$uniqueIdentifier.'" class="size_qty" value="' . $qty . '" /></td>';
        } else {
          $output .= '<td>'.$qty.'</td>';
        }

        $output .= '<td>';
        //$output .= 'Ind. shirt: ' . $xxlPrice;

        $output .= $artPriceOutput;

        $lineSubtotal = $qty * ($xxlPriceNumber+$artTotal);
        $shirtSubtotal = (float) $xxlPriceNumber + $artTotal;

        $output .= "$".number_format($shirtSubtotal, 2);

        $output .= '</td>';

        $output .= '<td>' . '$'.number_format($lineSubtotal, 2) . '</td>';
        $output .= '</tr>';

      } else {

        $shirtPriceOutput = '$'.number_format($price, 2);

        $output .= "<tr class='preview_sizes'>";
        $output .= '<td>' . $size . '</td>';

        if ($screen === 'cart') {
          $output .= '<td><input type="text" name="'.$size.'" data-product-id="'.$productID.'" data-product-variant-id="'.$variationID.'" data-unique-cart-key="'.$uniqueIdentifier.'" class="size_qty" value="' . $qty . '" /></td>';
        } else {
          $output .= '<td>'.$qty.'</td>';
        }

        $output .= '<td>';
        //$output .= 'Ind. shirt: ' . $shirtPriceOutput;

        $output .= $artPriceOutput;

        $lineSubtotal = $qty * ($price+$artTotal);

        $shirtSubtotal = (float) $price + $artTotal;

        $output .= "$".number_format($shirtSubtotal, 2);

        $output .= '</td>';
        $output .= '<td>' . '$'.number_format($lineSubtotal, 2) . '</td>';
        $output .= '</tr>';

      }
    }

    $output .= '</tbody>
                </table>
              </div>';

    return $output;

  } else {
    return $product->get_name();
  }

}





function hometown_display_user_meta($product, $variationID, $screen, $uniqueIdentifier) {

  $productID = $product->get_id();

  $output = '';
  $output .= hometown_display_imprint_data($productID, $variationID, $uniqueIdentifier);
  $output .= hometown_display_size_data($product, $productID, $variationID, $screen, $uniqueIdentifier);
  return $output;

}





add_action('woocommerce_add_order_item_meta','hometown_add_values_to_order_item_meta',1,2);
if(!function_exists('hometown_add_values_to_order_item_meta'))
{
  function hometown_add_values_to_order_item_meta($item_id, $values)
  {

    $user_custom_values = '<br>';

    $product =  wc_get_product( $values['data']->get_id());
    $user_custom_values .= hometown_display_user_meta($product, $values['variation_id'], 'order', $values['unique_key']);

    if(!empty($user_custom_values))
    {
      wc_add_order_item_meta($item_id,'Sizes and Artwork',$user_custom_values);
    }

  }
}



/*
 * @desc Force individual cart item
 */
function hometown_force_individual_cart_items( $cart_item_data, $product_id ){

  $unique_cart_item_key = md5( microtime().rand() );
  $cart_item_data['unique_key'] = $unique_cart_item_key;
  hometown_store_unique_cart_key($cart_item_data);

  return $cart_item_data;

}

add_filter( 'woocommerce_add_cart_item_data','hometown_force_individual_cart_items', 10, 2 );



/*
 * @desc Remove quantity selector in all product type
 */
function hometown_remove_all_quantity_fields( $return, $product ) {

  return true;

}

add_filter( 'woocommerce_is_sold_individually', 'hometown_remove_all_quantity_fields', 10, 2 );


add_action('woocommerce_before_checkout_form', 'add_continue_shopping_link');
function add_continue_shopping_link() {
  echo '<a class="continue_shopping_link" href="'.get_site_url().'/landing-page">&larr; Continue shopping</a>';
}