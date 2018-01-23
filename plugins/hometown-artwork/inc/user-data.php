<?php

add_action( 'wp_ajax_hometown_save_imprint_artwork', 'hometown_save_imprint_artwork' );
/**
 * Save the imprint artwork to the database
 *
 * return @void
 */
function hometown_save_imprint_artwork() {
  echo 'asdf';

//  $uniqueIdentifier = $_POST['variation_id'];
//
//  $front = (isset($_POST['front'])) ? 'Front='.$_POST['front'] : '';
//  $back = (isset($_POST['back'])) ? 'Back='.$_POST['back'] : '';
//  $sleeve = (isset($_POST['sleeve'])) ? 'Sleeve='.$_POST['sleeve'] : '';
//
//  $meta_key = 'imprint_locations-' . $uniqueIdentifier;
//  $imprintCSV = $front.','.$back.','.$sleeve;
//
//  $prev_value = get_user_meta(get_current_user_id(), $meta_key, true);
//
//  wp_send_json(array(
//      'action' => 'save_imprint_data',
//      'result' => (update_user_meta( get_current_user_id(), $meta_key, $imprintCSV, $prev_value )),
//      'newID'  => update_user_meta( get_current_user_id(), $meta_key, $imprintCSV, $prev_value )
//  ));
//  wp_die();

}