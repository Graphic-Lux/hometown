<?php

add_action( 'wp_ajax_hometown_save_imprint_artwork', 'hometown_save_imprint_artwork' );
/**
 * Save the imprint artwork url and color to the database
 *
 * return @void
 */
function hometown_save_imprint_artwork() {

	$uniqueIdentifier = $_POST['variation_id'];

	$frontURL    = ( isset( $_POST['frontURL'] ) ) ? 'FrontURL=' . $_POST['frontURL'] : '';
	$frontColor  = ( isset( $_POST['frontColor'] ) ) ? 'FrontColor=' . $_POST['frontColor'] : '';
	$backURL     = ( isset( $_POST['backURL'] ) ) ? 'BackURL=' . $_POST['backURL'] : '';
	$backColor   = ( isset( $_POST['backColor'] ) ) ? 'BackColor=' . $_POST['backColor'] : '';
	$sleeveURL   = ( isset( $_POST['sleeveURL'] ) ) ? 'SleeveURL=' . $_POST['sleeveURL'] : '';
	$sleeveColor = ( isset( $_POST['sleeveColor'] ) ) ? 'SleeveColor=' . $_POST['sleeveColor'] : '';

	$meta_key   = 'imprint_artwork-' . $uniqueIdentifier;
	$imprintCSV = $frontURL . ',' . $frontColor . ',' . $backURL . ',' . $backColor . ',' . $sleeveURL . ',' . $sleeveColor;

	$prev_value = get_user_meta( get_current_user_id(), $meta_key, true );
	$newID = update_user_meta( get_current_user_id(), $meta_key, $imprintCSV, $prev_value );

	wp_send_json( array(
		'action' => 'hometown_save_imprint_artwork',
		'result' => ( $newID ),
		'newID'  => $newID
	) );
	wp_die();

}


function hometown_get_imprint_artwork($variationID) {

  $uniqueIdentifier = $variationID;
  $meta_key   = 'imprint_artwork-' . $uniqueIdentifier;

  $imprintArtworkData = explode(',', get_user_meta( get_current_user_id(), $meta_key, true ));

  $imprintDataArray = array();

  foreach ($imprintArtworkData as $data) {

    $keyValuePair = explode('=', $data);
    $key = $keyValuePair[0];
    $value = $keyValuePair[1];

    if (strpos($key, 'Front') !== false) {
      if (strpos($key, 'Color') !== false) {
        $imprintDataArray['Front']['color'] = $value;
      } else {
        $imprintDataArray['Front']['url'] = $value;
      }
    } else if (strpos($key, 'Back') !== false) {
      if (strpos($key, 'Color') !== false) {
        $imprintDataArray['Back']['color'] = $value;
      } else {
        $imprintDataArray['Back']['url'] = $value;
      }
    } else if (strpos($key, 'Sleeve') !== false) {
      if (strpos($key, 'Color') !== false) {
        $imprintDataArray['Sleeve']['color'] = $value;
      } else {
        $imprintDataArray['Sleeve']['url'] = $value;
      }
    }

  }

  return $imprintDataArray;

}


function hometown_get_artwork_price($url) {

  global $wpdb;

  $query = "SELECT ID,post_type FROM `wp_posts` where guid like '%s'";
  $sql = $wpdb->prepare($query, array($url));
  $results = $wpdb->get_results($sql);

  foreach($results as $result) {
    if ($result->post_type === 'attachment') {
      $parentID = wp_get_post_parent_id( $result->ID );
      $query = "SELECT meta_value FROM `wp_postmeta` where post_id = %d and meta_key = 'hamf_artwork_price'";
      $sql = $wpdb->prepare($query, array($parentID));
      $priceResults = $wpdb->get_results($sql);
      foreach ($priceResults as $priceResult) {
        return $priceResult->meta_value;
      }
    }
  }

}