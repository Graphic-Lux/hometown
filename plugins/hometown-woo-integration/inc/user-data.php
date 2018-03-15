<?php

// WE WANT ONLY LOGGED IN USERS TO BE ALLOWED TO DO THIS
add_action( 'wp_ajax_hometown_save_user_sizes', 'hometown_save_user_sizes' );
function hometown_save_user_sizes() {

  $uniqueIdentifier = $_POST['variation_id'];
  $sizeCSV = '';

  foreach($_POST['sizes'] as $key => $meta_value) {
    $meta_value = preg_replace( '/[^0-9]/', '', $meta_value );
    $sizeCSV .= $key.'='.$meta_value.',';
  }

  $meta_key = 'shirt_sizes-' . $uniqueIdentifier;
  $prev_value = get_user_meta(get_current_user_id(), $meta_key, true);

  wp_send_json(array(
      'action' => 'save_size_data',
      'result' => (update_user_meta( get_current_user_id(), $meta_key, $sizeCSV, $prev_value )),
      'newID'  => update_user_meta( get_current_user_id(), $meta_key, $sizeCSV, $prev_value ),
      'value'  => $sizeCSV
  ));
  wp_die();
}




function hometown_get_size_data($variationID) {

  $uniqueIdentifier = $variationID;

  $sizeArray = hometown_get_size_array();
  $meta_key = 'shirt_sizes-' . $uniqueIdentifier;

  $sizeKeyValueCSV = get_user_meta(get_current_user_id(), $meta_key, true);
  $sizeKeyValues = explode(',', $sizeKeyValueCSV);

  if (in_array('5XL', $sizeArray)) {
    array_splice($sizeKeyValues, 9);
  } else {
    array_splice($sizeKeyValues, 8);
  }


  $sizeArrayData[$uniqueIdentifier] = array();

  foreach($sizeKeyValues as $data) {
    if ($data !== '') {
      $sizeData = explode('=', $data);
      $size = $sizeData[0];
      $quantity = ($sizeData[1] == '') ? 0 : $sizeData[1];

      $sizeArrayData[$uniqueIdentifier][$size] = $quantity;
    } else {
      foreach ($sizeArray as $size) {
        $sizeArrayData[$uniqueIdentifier][$size] = 0;
      }
      break;
    }

  }
//  print_r($sizeArrayData);
  return $sizeArrayData;

}




function hometown_get_size_array() {
  return array('XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL', '4XL');
}




add_action( 'wp_ajax_hometown_save_imprint_data', 'hometown_save_imprint_data' );
// Add Data in a Custom Session, on ‘Add to Cart’ Button Click
function hometown_save_imprint_data() {

  $uniqueIdentifier = $_POST['variation_id'];

  $front = (isset($_POST['front'])) ? 'Front='.$_POST['front'] : '';
  $back = (isset($_POST['back'])) ? 'Back='.$_POST['back'] : '';
  $sleeve = (isset($_POST['sleeve'])) ? 'Sleeve='.$_POST['sleeve'] : '';

  $meta_key = 'imprint_locations-' . $uniqueIdentifier;
  $imprintCSV = $front.','.$back.','.$sleeve;

  $prev_value = get_user_meta(get_current_user_id(), $meta_key, true);

  $newID = update_user_meta( get_current_user_id(), $meta_key, $imprintCSV, $prev_value );

  wp_send_json(array(
      'action' => 'save_imprint_data',
      'result' => ($newID),
      'newID'  => $newID
  ));
  wp_die();

}



function hometown_get_imprint_data($variationID) {

  $uniqueIdentifier = $variationID;
  $meta_key = 'imprint_locations-' . $uniqueIdentifier;

  $imprintCSV = get_user_meta(get_current_user_id(), $meta_key, true);

  $imprintDataKeyValues = explode(',', $imprintCSV);

  $imprintArray[$uniqueIdentifier] = array();

  foreach ($imprintDataKeyValues as $imprintKeyValue) {
    if ($imprintKeyValue !== '') {
      $imprintData = explode('=', $imprintKeyValue);
      $key = $imprintData[0];
      $value = $imprintData[1];
      $imprintArray[$uniqueIdentifier][$key] = $value;
    }
  }

  return $imprintArray;

}



function hometown_woocommerce_order_status_completed( $order_id ) {

  error_log( "Order complete for order $order_id", 0 );

  $order = new WC_Order( $order_id );
  $items = $order->get_items();

  hometown_delete_all_user_meta($items);

}
//add_action( 'woocommerce_thankyou', 'hometown_woocommerce_order_status_completed', 10, 1 );





function hometown_after_remove_product($cart_item_key) {

  global $woocommerce;
  $items = $woocommerce->cart->get_cart();

  foreach ($items as $item) {

    if ($item['key'] === $cart_item_key) {

      $variationID = hometown_get_variation_id($item['variation_id'], $item['product_id']);
      $uniqueIdentifier = $variationID;

      $imprintMetaKey = 'imprint_locations-' . $uniqueIdentifier;
      $imprintArtworkMetaKey = 'imprint_artwork-' . $uniqueIdentifier;
      $shirtSizesMetaKey = 'shirt_sizes-' . $uniqueIdentifier;

      delete_user_meta(get_current_user_id(), $imprintMetaKey);
      delete_user_meta(get_current_user_id(), $imprintArtworkMetaKey);
      delete_user_meta(get_current_user_id(), $shirtSizesMetaKey);

    }

  }

}
add_action( 'woocommerce_remove_cart_item', 'hometown_after_remove_product' );




function hometown_delete_all_user_meta($items) {

  foreach ($items as $item) {

    $variationID = hometown_get_variation_id($item['variation_id'], $item['product_id']);
    $uniqueIdentifier = $variationID;

    $imprintMetaKey = 'imprint_locations-' . $uniqueIdentifier;
    $imprintArtworkMetaKey = 'imprint_artwork-' . $uniqueIdentifier;
    $shirtSizesMetaKey = 'shirt_sizes-' . $uniqueIdentifier;

    delete_user_meta(get_current_user_id(), $imprintMetaKey);
    delete_user_meta(get_current_user_id(), $imprintArtworkMetaKey);
    delete_user_meta(get_current_user_id(), $shirtSizesMetaKey);

  }

}




function hometown_get_variation_id($variationID, $productID) {

  $product = wc_get_product( $productID );

  if ($variationID == 0) {
    $productChild = $product->get_children();
    return $productChild[0];
  } else {
    return $variationID;
  }

}