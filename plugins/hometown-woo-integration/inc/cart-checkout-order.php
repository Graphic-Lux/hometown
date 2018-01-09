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



add_action( 'wp_ajax_hometown_save_custom_order_data', 'hometown_save_custom_order_data' );
// Add Data in a Custom Session, on ‘Add to Cart’ Button Click
function hometown_save_custom_order_data() {

  //Custom data - Sent Via AJAX post method
  $product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
  $variation_id = $_POST['variation_id'];

  session_start();

  if (isset($_POST['front'])) {
    $front = $_POST['front'];
    $back = $_POST['back'];
    $sleeve = $_POST['sleeve'];

    //This is User custom value sent via AJAX
    $_SESSION['wdm_user_custom_data'] = array(
        "Front"  =>  $front,
        "Back"   =>  $back,
        "Sleeve" =>  $sleeve
    );
  }

  if (isset($_POST['sizes'])) {
    $_SESSION['wdm_user_custom_data']['sizes'] = $_POST['sizes'];
//    print_r($_SESSION);
  }

  die();

}


add_filter('woocommerce_add_cart_item_data','wdm_add_item_data',1,2);

if(!function_exists('wdm_add_item_data'))
{
  function wdm_add_item_data($cart_item_data,$product_id)
  {
    /*Here, We are adding item in WooCommerce session with, wdm_user_custom_data_value name*/
    global $woocommerce;
    session_start();
    if (isset($_SESSION['wdm_user_custom_data'])) {
      $option = $_SESSION['wdm_user_custom_data'];
      $new_value = array('wdm_user_custom_data_value' => $option);
    }
    if(empty($option))
      return $cart_item_data;
    else
    {
      if(empty($cart_item_data))
        return $new_value;
      else
        return array_merge($cart_item_data,$new_value);
    }
    unset($_SESSION['wdm_user_custom_data']);
    //Unset our custom session variable, as it is no longer needed.
  }
}




add_filter('woocommerce_get_cart_item_from_session', 'wdm_get_cart_items_from_session', 1, 3 );
if(!function_exists('wdm_get_cart_items_from_session'))
{
  function wdm_get_cart_items_from_session($item,$values,$key)
  {
    if (array_key_exists( 'wdm_user_custom_data_value', $values ) )
    {
      $item['wdm_user_custom_data_value'] = $values['wdm_user_custom_data_value'];
    }
    return $item;
  }
}





add_filter('woocommerce_checkout_cart_item_quantity','wdm_add_user_custom_option_from_session_into_cart',1,3);
add_filter('woocommerce_cart_item_price','wdm_add_user_custom_option_from_session_into_cart',1,3);
if(!function_exists('wdm_add_user_custom_option_from_session_into_cart')) {
  function wdm_add_user_custom_option_from_session_into_cart($product_name, $values, $cart_item_key ) {

//    print_r( $values );

    /*code to add custom data on Cart & checkout Page*/
    if(count($values['wdm_user_custom_data_value']) > 0)
    {
      $output = $product_name . "</a><dl class='variation'>";
      $output .= "<ul class='wdm_options_table' id='" . $values['product_id'] . "'>";

      if ($values['variation_id'] == 0) {
        $product = wc_get_product( $values['product_id'] );
        $variationID = $product->get_children()[0];
      } else {
        $variationID = $values['variation_id'];
      }

      foreach($values['wdm_user_custom_data_value'] as $key => $value) {

        $value = ($value === '') ? 'Not selected' : $value;

        if (($key === 'Front') || ($key === 'Back') || ($key === 'Sleeve')) {
          $output .= "<li class='preview_imprint_locations'>" . $key . " imprint location: " . $value . "</li>";
        } else {
          foreach ($value as $size => $sizeValue) {
            if (($size === 'XXL') || ($size === '3XL') || ($size === '4XL')) {
              $xxlPricing = (float) get_post_meta( $variationID, '_xxl_pricing', true );
              $lineSubtotal = (float) $sizeValue * $xxlPricing;
              $output .= "<li class='preview_sizes'>" . $size . ": " . $sizeValue . " * $" . $xxlPricing . "/shirt = $" . $lineSubtotal . "</li>";
            } else {
              $lineSubtotal = (float) $sizeValue * $_SESSION['initial_price'][$variationID];
              $output .= "<li class='preview_sizes'>" . $size . ": " . $sizeValue . " * $" . $_SESSION['initial_price'][$variationID] . "/shirt = $" . $lineSubtotal . "</li>";
            }
          }
        }

      }

      $output .= "</ul></dl>";

      return $output;
    } else {
      return $product_name;
    }

  }

}





add_action('woocommerce_before_cart_item_quantity_zero','wdm_remove_user_custom_data_options_from_cart',1,1);
if(!function_exists('wdm_remove_user_custom_data_options_from_cart'))
{
  function wdm_remove_user_custom_data_options_from_cart($cart_item_key)
  {
    global $woocommerce;
    // Get cart
    $cart = $woocommerce->cart->get_cart();
    // For each item in cart, if item is upsell of deleted product, delete it
    foreach( $cart as $key => $values)
    {
      if ( $values['wdm_user_custom_data_value'] == $cart_item_key )
        unset( $woocommerce->cart->cart_contents[ $key ] );
    }
  }
}









add_action( 'woocommerce_before_calculate_totals', 'hometown_custom_prices', 100 );
function hometown_custom_prices( $cart_object ) {

  global $isProcessed;

  if( !WC()->session->__isset( "reload_checkout" )) {

    if (!isset($_SESSION)) {
      session_start();
    }

    foreach ( $cart_object->get_cart() as $key => $item ) {

      $product_subtotal = 0;

      // SET VARIATION ID
      if ($item['variation_id'] == 0) {
        $product = wc_get_product( $item['product_id'] );
        $variationID = $product->get_children()[0];
      } else {
        $variationID = $item['variation_id'];
      }

      // SET INITIAL PRICE HERE
      if (!isset($_SESSION['initial_price'][$variationID])) {

        $_SESSION['initial_price'][$variationID] = $item['data']->get_price();

      }



      foreach($item['wdm_user_custom_data_value'] as $itemKey => $customValue) {

        if (($itemKey !== 'Front') && ($itemKey !== 'Back') && ($itemKey !== 'Sleeve')) {

          foreach ($customValue as $size => $sizeValue) {

            if (($size === 'XXL') || ($size === '3XL') || ($size === '4XL')) {
              $xxlPricing = (float) get_post_meta( $variationID, '_xxl_pricing', true );
              $lineSubtotal = (float) $sizeValue * $xxlPricing;
            } else {
              $lineSubtotal = (float) $sizeValue * $_SESSION['initial_price'][$variationID ];
            }

            $product_subtotal += $lineSubtotal;

          }

        }
      }


      $item['data']->set_price((float) $product_subtotal);
//      $item['data']->set_price((float) 0);
    }

    $isProcessed = true;

  }

}