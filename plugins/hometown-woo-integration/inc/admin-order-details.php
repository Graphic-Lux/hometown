<?php

//add_filter('woocommerce_admin_order_item_values', 'hometown_order_item_meta');
//
//function hometown_order_item_meta($item_id) {
//  echo 'fdsa';
//
//}


//// Add the the product custom field as item meta data in the order
//add_action( 'woocommerce_add_order_item_meta', 'pd_number_order_meta_data', 10, 3 );
//function pd_number_order_meta_data( $item_id, $cart_item, $cart_item_key ) {
//  // get the product custom field value
//  $pd_number = get_post_meta( $cart_item[ 'variation_id' ], 'shirt_sizes', true );
//
//  // Add the custom field value to order item meta
//  if( ! empty($pd_number) )
//    wc_update_order_item_meta( $item_id, '_pd_number', $pd_number );
//}
