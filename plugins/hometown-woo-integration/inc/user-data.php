<?php

// WE WANT ONLY LOGGED IN USERS TO BE ALLOWED TO DO THIS
add_action( 'wp_ajax_hometown_save_user_meta', 'hometown_save_user_meta' );
function hometown_save_user_meta() {

  $productID = $_POST['product_id'];
  $variationID= $_POST['variation_id'];
  $itemID = $productID . '_' . $variationID;
  $user_id = get_current_user_id();

  foreach($_POST['sizes'] as $key => $meta_value) {
    $meta_key = 'shirt_sizes-' . $itemID . '-' . $key;
    $prev_value = get_user_meta(get_current_user_id(), 'shirt_sizes-' . $itemID . '-' . $key, true);
    update_user_meta( $user_id, $meta_key, $meta_value, $prev_value );
  }

  hometown_save_custom_order_data();

  return true;

}
