<?php

add_action( 'wp_ajax_hometown_woocommerce_add_to_cart_variation', 'hometown_woocommerce_add_to_cart_variation' );

function hometown_woocommerce_add_to_cart_variation() {

  ob_start();

  $product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
  $quantity = 1;
  $variation_id = $_POST['variation_id'];
//  $variation  = array('color' => $_POST['variation']);
  $variation = false;
  $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

//  var_dump($variation);

  if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation  ) ) {

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

  die();
}




//add_filter('woocommerce_checkout_cart_item_quantity','hometown_add_user_custom_option_from_session_into_cart',1,3);
add_filter('woocommerce_cart_item_name','hometown_add_user_custom_option_from_session_into_cart',1,3);
if(!function_exists('hometown_add_user_custom_option_from_session_into_cart')) {
  function hometown_add_user_custom_option_from_session_into_cart($product_name, $values, $cart_item_key ) {

    $product = wc_get_product( $values['product_id'] );

    $variationID = hometown_get_variation_id($values);

    $output = $product_name . "</a><dl class='variation'>";

    $output .= '<div class="container">';
    $output .= '<table class="table table-borderless wdm_options_table" id="' . $values['product_id'] . '">';
    $output .= '<thead>
                  <tr>
                    <th>Shirt Orientation</th>
                    <th>Imprint Location</th>
                    <th>Artwork</th>
                  </tr>
                </thead>
                <tbody>';

    $imprintArray = hometown_get_imprint_data($variationID);

    if (count($imprintArray[$variationID]) > 0) {
      foreach ($imprintArray[$variationID] as $imprintLocation => $imprintValue) {
        if ($imprintValue != '') {
          $output .= "<tr class='preview_imprint_locations'>";
          $output .= "<td>" . $imprintLocation . "</td>";
          $output .= "<td>" . $imprintValue . "</td>";
          $output .= "</tr>";
        }
      }
    }

    $output .= '</tbody>
                </table>
              </div>';

    $sizeData = hometown_get_size_data($variationID);

    /*code to add custom data on Cart & checkout Page*/
    if(count($sizeData[$variationID]) > 0)
    {

      $output .= '<table class="table table-borderless wdm_options_table" id="' . $values['product_id'] . '">';
      $output .= '<thead>
                  <tr>
                    <th></th>
                    <th>QTY</th>
                    <th>Each</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>';

      $price = hometown_get_price($product, $variationID);

      $miniCartProcessed = false;

      foreach($sizeData[$variationID] as $size => $qty) {

        if (($size === 'XXL') || ($size === '3XL') || ($size === '4XL')) {

          $xxlPricing = '$'.number_format(get_post_meta( $variationID, '_xxl_pricing', true ), 2);
          $lineSubtotal = '$'.number_format($qty * $xxlPricing, 2);

          $output .= "<tr class='preview_sizes'>";
            $output .= '<td>' . $size . '</td>';

            // ESCAPE MINI CART
            if ($miniCartProcessed) {
              $output .= '<td>' . $qty . '</td>';
            } else {
              $output .= '<td><input type="text" name="'.$size.'" data-product-id="'.$values['product_id'].'" data-product-variant-id="'.$variationID.'" class="size_qty" value="' . $qty . '" /></td>';
            }

            $output .= '<td>' . $xxlPricing . '</td>';
            $output .= '<td>' . $lineSubtotal . '</td>';
          $output .= '</tr>';

        } else {
          $lineSubtotal = '$'.number_format($qty * $price, 2);
          $shirtPriceOutput = '$'.number_format($price, 2);

          $output .= "<tr class='preview_sizes'>";
            $output .= '<td>' . $size . '</td>';

            // ESCAPE MINI CART
            if ($miniCartProcessed) {
              $output .= '<td>' . $qty . '</td>';
            } else {
              $output .= '<td><input type="text" name="'.$size.'" data-product-id="'.$values['product_id'].'" data-product-variant-id="'.$variationID.'" class="size_qty" value="' . $qty . '" /></td>';
            }

            $output .= '<td>' . $shirtPriceOutput . '</td>';
            $output .= '<td>' . $lineSubtotal . '</td>';
          $output .= '</tr>';

        }
      }

      $output .= '</tbody>
                </table>
              </div>';

    } else {
      return $product_name;
    }


    $output .= "</dl>";
    return $output;

  }

}








add_action( 'woocommerce_before_calculate_totals', 'hometown_custom_prices', 100 );
function hometown_custom_prices( $cart_object ) {

  global $isProcessed;

  if( !WC()->session->__isset( "reload_checkout" )) {


    foreach ( $cart_object->get_cart() as $key => $item ) {

      $product_subtotal = 0;

      $product = wc_get_product( $item['product_id'] );
      $variationID = hometown_get_variation_id($item);

      $sizeData = hometown_get_size_data($variationID);
      $productSizeData = $sizeData[$variationID];
      $price = hometown_get_price($product, $variationID);


      foreach ($productSizeData as $size => $qty) {

        if (($size === 'XXL') || ($size === '3XL') || ($size === '4XL')) {
          $xxlPricing = (float) get_post_meta( $variationID, '_xxl_pricing', true );
          $lineSubtotal = (float) $qty * $xxlPricing;
        } else {
          $lineSubtotal = (float) $qty * $price;
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