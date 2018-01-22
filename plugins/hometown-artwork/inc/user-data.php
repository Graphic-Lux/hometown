<?php
/**
 * Created by PhpStorm.
 * User: gerhard
 * Date: 1/22/18
 * Time: 11:02 AM
 */

add_action( 'wp_ajax_hometown_save_imprint_artwork', 'hometown_save_imprint_artwork' );
// Add Data in a Custom Session, on ‘Add to Cart’ Button Click
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