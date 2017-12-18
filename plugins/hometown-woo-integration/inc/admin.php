<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

///////////////////////////// CUSTOM VARIATION PRICE XXL+ INPUT FIELD ///////////////////////////////////

// Add Variation Settings
add_action( 'woocommerce_product_after_variable_attributes', 'variation_settings_fields', 10, 3 );
// Save Variation Settings
add_action( 'woocommerce_save_product_variation', 'save_variation_settings_fields', 10, 2 );
/**
 * Create new fields for variations
 *
 */
function variation_settings_fields( $loop, $variation_data, $variation ) {


  echo '<div class="options_group">';

  // Number Field
  woocommerce_wp_text_input(
      array(
          'id'          => '_xxl_pricing[' . $variation->ID . ']',
          'label'       => __( 'XXL+ Pricing', 'woocommerce' ),
          'desc_tip'    => 'true',
          'placeholder' => '19.95',
          'description' => __( 'Enter the price of XXL+ t-shirts that will be more expensive than the XS-XL shirts.', 'woocommerce' ),
          'value'       => get_post_meta( $variation->ID, '_xxl_pricing', true )
      )
  );

  echo '</div>';

}
/**
 * Save new fields for variations
 *
 */
function save_variation_settings_fields( $post_id ) {
  // Text Field
  $text_field = $_POST['_xxl_pricing'][ $post_id ];
  if( ! empty( $text_field ) ) {
    update_post_meta( $post_id, '_xxl_pricing', esc_attr( $text_field ) );
  }
}


///////////////////////////// CUSTOM PRODUCT CHECKBOX ///////////////////////////////////


// Display Fields
add_action('woocommerce_product_options_general_product_data', 'hometown_woocommerce_product_custom_fields');

// Save Fields
add_action('woocommerce_process_product_meta', 'hometown_woocommerce_product_custom_fields_save');


function hometown_woocommerce_product_custom_fields()
{
  global $woocommerce, $post;
  echo '<div class="product_custom_field">';
  woocommerce_wp_checkbox(
      array(
          'id' => '_custom_product',
          'placeholder' => 'Custom Product',
          'label' => __('Custom Product', 'woocommerce')
      )
  );
  echo '</div>';

}

function hometown_woocommerce_product_custom_fields_save($post_id)
{
  // Custom Product Text Field
  $woocommerce_custom_product_text_field = $_POST['_custom_product'];
  update_post_meta($post_id, '_custom_product', esc_attr($woocommerce_custom_product_text_field));
}