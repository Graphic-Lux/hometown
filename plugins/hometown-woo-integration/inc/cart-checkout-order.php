<?php

add_action( 'wp_ajax_hometown_woocommerce_add_to_cart_variable', 'hometown_woocommerce_add_to_cart_variable' );

function hometown_woocommerce_add_to_cart_variable() {

  ob_start();

  $product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
  $quantity = 1;
  $variation_id = $_POST['variation_id'];
  $variation  = array('color' => $_POST['variation']);
  $variation = false;
  $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

//  var_dump($variation);

  if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation  ) ) {

    $return = array(
        'result'       => true
    );

    wp_send_json($return);

  } else {

    $return = array(
        'result'       => false
    );

    wp_send_json($return);

  }

  die();
}





add_filter('woocommerce_checkout_cart_item_quantity','hometown_add_user_custom_option_from_session_into_cart',1,3);
add_filter('woocommerce_cart_item_price','hometown_add_user_custom_option_from_session_into_cart',1,3);
if(!function_exists('hometown_add_user_custom_option_from_session_into_cart')) {
  function hometown_add_user_custom_option_from_session_into_cart($product_name, $values, $cart_item_key ) {

    $product = wc_get_product( $values['product_id'] );

    $variationID = hometown_get_variation_id($values);

    $output = $product_name . "</a><dl class='variation'>";
    $output .= "<ul class='wdm_options_table' id='" . $values['product_id'] . "'>";

    $imprintArray = hometown_get_imprint_data($variationID);

    if (count($imprintArray[$variationID]) > 0) {
      foreach ($imprintArray[$variationID] as $imprintLocation => $imprintValue) {
        $output .= "<li class='preview_imprint_locations'>" . $imprintLocation . " imprint location: " . $imprintValue . "</li>";
      }
    }


    $sizeData = hometown_get_size_data($variationID);

    /*code to add custom data on Cart & checkout Page*/
    if(count($sizeData[$variationID]) > 0)
    {

      $price = hometown_get_price($product, $variationID);

      foreach($sizeData[$variationID] as $size => $sizeValue) {
        if (($size === 'XXL') || ($size === '3XL') || ($size === '4XL')) {
          $xxlPricing = (float) get_post_meta( $variationID, '_xxl_pricing', true );
          $lineSubtotal = (float) $sizeValue * $xxlPricing;
          $output .= "<li class='preview_sizes'>" . $size . ": " . $sizeValue . " x " . wc_price($xxlPricing) . "/shirt = " . wc_price($lineSubtotal) . "</li>";
        } else {
          $lineSubtotal = (float) $sizeValue * $price;
          $output .= "<li class='preview_sizes'>" . $size . ": " . $sizeValue . " x " . wc_price($price) . "/shirt = " . wc_price($lineSubtotal) . "</li>";
        }
      }

      $output .= "</ul></dl>";

      return $output;
    } else {
      return $product_name;
    }

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


      foreach ($productSizeData as $size => $sizeValue) {

        if (($size === 'XXL') || ($size === '3XL') || ($size === '4XL')) {
          $xxlPricing = (float) get_post_meta( $variationID, '_xxl_pricing', true );
          $lineSubtotal = (float) $sizeValue * $xxlPricing;
        } else {
          $lineSubtotal = (float) $sizeValue * $price;
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