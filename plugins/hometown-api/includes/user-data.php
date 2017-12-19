<?php

// WE WANT ONLY LOGGED IN USERS TO BE ALLOWED TO DO THIS
add_action( 'wp_ajax_hometown_save_user_meta', 'hometown_save_user_meta' );
function hometown_save_user_meta() {

  $product_id = $_POST['product_id'];
//  $variation_id = $_POST['variation_id'];
  $user_id = get_current_user_id();

  foreach($_POST['sizes'] as $key => $meta_value) {
    $meta_key = 'shirt_sizes-' . $product_id . '-' . $key;
    $prev_value = get_user_meta(get_current_user_id(), 'shirt_sizes-' . $product_id . '-' . $key, true);
    var_dump(update_user_meta( $user_id, $meta_key, $meta_value, $prev_value ));
  }

  return true;

}