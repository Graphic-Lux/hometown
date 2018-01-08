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

      foreach($values['wdm_user_custom_data_value'] as $key => $value) {

        $value = ($value === '') ? 'Not selected' : $value;

        if (($key === 'Front') || ($key === 'Back') || ($key === 'Sleeve')) {
          $output .= "<li class='preview_imprint_locations'>" . $key . " imprint location: " . $value . "</li>";
        } else {
          foreach ($value as $size => $sizeValue) {
            if (($size === 'XXL') || ($size === '3XL') || ($size === '4XL')) {
              $xxlPricing = (float) get_post_meta( $values['variation_id'], '_xxl_pricing', true );
              $lineSubtotal = (float) $sizeValue * $xxlPricing;
              $output .= "<li class='preview_sizes'>" . $size . ": " . $sizeValue . " * $" . $xxlPricing . "/shirt = $" . $lineSubtotal . "</li>";
            } else {
              $lineSubtotal = (float) $sizeValue * $values['line_subtotal'];
              $output .= "<li class='preview_sizes'>" . $size . ": " . $sizeValue . " * $" . $values['line_subtotal'] . "/shirt = $" . $lineSubtotal . "</li>";
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






// define the woocommerce_cart_item_subtotal callback
function filter_woocommerce_cart_item_subtotal( $wc, $cart_item, $cart_item_key ) {


  $product_subtotal = 0;
//  unset($_SESSION);
//  session_start();

  foreach($cart_item['wdm_user_custom_data_value'] as $key => $value) {


    if (($key !== 'Front') && ($key !== 'Back') && ($key !== 'Sleeve')) {

      foreach ($value as $size => $sizeValue) {

        if (($size === 'XXL') || ($size === '3XL') || ($size === '4XL')) {
          $xxlPricing = (float) get_post_meta( $cart_item['variation_id'], '_xxl_pricing', true );
          $lineSubtotal = (float) $sizeValue * $xxlPricing;

        } else {
          $lineSubtotal = (float) $sizeValue * $cart_item['line_subtotal'];
        }

        $product_subtotal = number_format($product_subtotal + $lineSubtotal, 2);


      }

    }
  }

  $product_id = $cart_item['product_id'];

  $_SESSION['new_prices'][$product_id] = $product_subtotal;

print_r($_SESSION);



  return (string) '$' . $product_subtotal;
};

// add the filter
add_filter( 'woocommerce_cart_item_subtotal', 'filter_woocommerce_cart_item_subtotal', 10, 3 );






//add_action( 'woocommerce_before_calculate_totals', 'add_custom_price', 10, 1);
function add_custom_price( $cart_object ) {

  if ( is_admin() && ! defined( 'DOING_AJAX' ) )
    return;

  $i=0;

  foreach ( $cart_object->get_cart() as $cart_item ) {
    ## Price calculation ##
    $price = $cart_item['data']->get_price();
//    echo $price;
//    if ($i=0) {
//      $product_subtotal = 0;
//    }
//
////    print_r($cart_item);
//
//    foreach($cart_item['wdm_user_custom_data_value'] as $key => $value) {
//
//      if (($key !== 'Front') && ($key !== 'Back') && ($key !== 'Sleeve')) {
//
//        foreach ($value as $size => $sizeValue) {
//
//          if (($size === 'XXL') || ($size === '3XL') || ($size === '4XL')) {
//            $xxlPricing = (float) get_post_meta( $cart_item['variation_id'], '_xxl_pricing', true );
//            $lineSubtotal = (float) $sizeValue * $xxlPricing;
//          } else {
//            echo $price;
//            $lineSubtotal = (float) $sizeValue * $price;
//          }
//
//          $product_subtotal = $product_subtotal + $lineSubtotal;
//
//
//        }
//
//      }
//
//    }
//
//
//
//    $cart_item['data']->set_price( $product_subtotal ); // WC 3.0+
  }

}






// CHANGE ORDER TOTAL VALUE WITH XXL+ SIZING
//add_action('woocommerce_cart_total', 'calculate_totals', 10, 1);
//
//function calculate_totals($wc_price){
////  $new_total = 0;
//  foreach ( WC()->cart->cart_contents as $key => $value ) {
////    echo print_r($value);
////    var_dump($value);
////    var_dump($key);
//  }
//
//  return wc_price($new_total);
//}







add_filter( 'woocommerce_get_discounted_price', 'hometown_edit_line_item_price', 10, 1 );
add_filter( 'woocommerce_adjust_non_base_location_prices', 'hometown_edit_line_item_price', 10, 1 );
//add_filter( 'woocommerce_get_price_excluding_tax', 'hometown_edit_line_item_price', 10, 1 );
//add_filter( 'woocommerce_get_price_including_tax', 'hometown_edit_line_item_price', 10, 1 );
//add_filter( 'woocommerce_tax_round', 'hometown_edit_line_item_price', 10, 1);
//add_filter( 'woocommerce_product_get_price', 'hometown_edit_line_item_price', 10, 1);

function hometown_edit_line_item_price($price) {

  $product_subtotal = 0;

//  print_r($_SESSION['wdm_user_custom_data']);

//
//  foreach($cart_item['wdm_user_custom_data_value'] as $key => $value) {
//
//
//    if (($key !== 'Front') && ($key !== 'Back') && ($key !== 'Sleeve')) {
//
//      foreach ($value as $size => $sizeValue) {
//
//        if (($size === 'XXL') || ($size === '3XL') || ($size === '4XL')) {
//          $xxlPricing = (float) get_post_meta( $cart_item['variation_id'], '_xxl_pricing', true );
//          $lineSubtotal = (float) $sizeValue * $xxlPricing;
//
//        } else {
//          $lineSubtotal = (float) $sizeValue * $cart_item['line_subtotal'];
//        }
//
//        $product_subtotal = $product_subtotal + $lineSubtotal;
//
//
//      }
//
//    }
//
//
//  }


//  return (string) '$' . number_format($product_subtotal, 2);


 return 1;
}