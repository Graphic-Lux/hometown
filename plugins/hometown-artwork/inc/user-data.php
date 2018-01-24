<?php

add_action( 'wp_ajax_hometown_save_imprint_artwork', 'hometown_save_imprint_artwork' );
/**
 * Save the imprint artwork url and color to the database
 *
 * return @void
 */
function hometown_save_imprint_artwork() {
  echo 'asdf'; die();
	$uniqueIdentifier = $_POST['variation_id'];

	$frontURL    = ( isset( $_POST['frontURL'] ) ) ? 'FrontURL=' . $_POST['frontURL'] : '';
	$frontColor  = ( isset( $_POST['frontColor'] ) ) ? 'FrontColor=' . $_POST['frontColor'] : '';
	$backURL     = ( isset( $_POST['backURL'] ) ) ? 'BackURL=' . $_POST['backURL'] : '';
	$backColor   = ( isset( $_POST['backColor'] ) ) ? 'BackColor=' . $_POST['backColor'] : '';
	$sleeveURL   = ( isset( $_POST['sleeveURL'] ) ) ? 'SleeveURL=' . $_POST['sleeveURL'] : '';
	$sleeveColor = ( isset( $_POST['sleeveColor'] ) ) ? 'SleeveColor=' . $_POST['sleeveColor'] : '';

	$meta_key   = 'imprint_artwork-' . $uniqueIdentifier;
	$imprintCSV = $frontURL . ',' . $frontColor . ',' . $backURL . ',' . $backColor . ',' . $sleeveURL . ',' . $sleeveColor;

	echo $imprintCSV;die();

	$prev_value = get_user_meta( get_current_user_id(), $meta_key, true );

	$newID = update_user_meta( get_current_user_id(), $meta_key, $imprintCSV, $prev_value );

	wp_send_json( array(
		'action' => 'hometown_save_imprint_artwork',
		'result' => ( $newID ),
		'newID'  => $newID
	) );
	wp_die();

}