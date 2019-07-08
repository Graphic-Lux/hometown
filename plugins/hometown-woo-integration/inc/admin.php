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
          'placeholder' => 'Required!',
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

///////////////////////////// AVAILABLE SIZES CHECKBOX ///////////////////////////////////


// Display Fields
add_action('woocommerce_product_options_general_product_data', 'hometown_woocommerce_product_available_sizes');

// Save Fields
add_action('woocommerce_process_product_meta', 'hometown_woocommerce_product_available_sizes_save');


function hometown_woocommerce_product_available_sizes()
{
  global $woocommerce, $post;

//  $productID = $post->ID;

  echo "<hr>";
  echo '<div class="product_custom_field">';

  woocommerce_wp_checkbox(
      array(
          'id' => '_xs_available_sizes',
          'label' => __('XS Sizes Available', 'woocommerce'),
//          'value' => $value
      )
  );


  woocommerce_wp_checkbox(
      array(
          'id' => '_s_available_sizes',
          'label' => __('S Sizes Available', 'woocommerce'),
      )
  );

  woocommerce_wp_checkbox(
      array(
          'id' => '_m_available_sizes',
          'label' => __('M Sizes Available', 'woocommerce'),
      )
  );



  woocommerce_wp_checkbox(
      array(
          'id' => '_l_available_sizes',
          'label' => __('L Sizes Available', 'woocommerce'),
      )
  );

  woocommerce_wp_checkbox(
      array(
          'id' => '_xl_available_sizes',
          'label' => __('XL Sizes Available', 'woocommerce'),
      )
  );

  woocommerce_wp_checkbox(
      array(
          'id' => '_xxl_available_sizes',
          'label' => __('XXL Sizes Available', 'woocommerce')
      )
  );

  woocommerce_wp_checkbox(
      array(
          'id' => '_3xl_available_sizes',
          'label' => __('3XL Sizes Available', 'woocommerce')
      )
  );

  woocommerce_wp_checkbox(
      array(
          'id' => '_4xl_available_sizes',
          'label' => __('4XL Sizes Available', 'woocommerce')
      )
  );
  echo '</div>';

}

function hometown_woocommerce_product_available_sizes_save($post_id)
{
  // Custom Product Text Field
  $woocommerce_custom_product_text_field = $_POST['_xs_available_sizes'];
  update_post_meta($post_id, '_xs_available_sizes', esc_attr($woocommerce_custom_product_text_field));

  $woocommerce_custom_product_text_field = $_POST['_s_available_sizes'];
  update_post_meta($post_id, '_s_available_sizes', esc_attr($woocommerce_custom_product_text_field));

  $woocommerce_custom_product_text_field = $_POST['_m_available_sizes'];
  update_post_meta($post_id, '_m_available_sizes', esc_attr($woocommerce_custom_product_text_field));

  $woocommerce_custom_product_text_field = $_POST['_l_available_sizes'];
  update_post_meta($post_id, '_l_available_sizes', esc_attr($woocommerce_custom_product_text_field));

  $woocommerce_custom_product_text_field = $_POST['_xl_available_sizes'];
  update_post_meta($post_id, '_xl_available_sizes', esc_attr($woocommerce_custom_product_text_field));

  $woocommerce_custom_product_text_field = $_POST['_xxl_available_sizes'];
  update_post_meta($post_id, '_xxl_available_sizes', esc_attr($woocommerce_custom_product_text_field));

  $woocommerce_custom_product_text_field = $_POST['_3xl_available_sizes'];
  update_post_meta($post_id, '_3xl_available_sizes', esc_attr($woocommerce_custom_product_text_field));

  $woocommerce_custom_product_text_field = $_POST['_4xl_available_sizes'];
  update_post_meta($post_id, '_4xl_available_sizes', esc_attr($woocommerce_custom_product_text_field));
}