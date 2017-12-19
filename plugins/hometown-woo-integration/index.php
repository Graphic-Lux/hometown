<?php
/**
 * @package HOMETOWN
 * @version 0.1a
 */
/*
Plugin Name: Hometown Apparel WooCommerce Integration
Description: Integrates the WooCommerce plugins with custom code to allow the creation of custom t-shirts via web browser.
Author: Graphic Lux, Grayson Erhard
Version: 1
Author URI: http://graysonerhard.com
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Defines
define("HAWI_PROJECT_TITLE", "Hometown Apparel");
date_default_timezone_set ("America/Denver");
define('HAWI_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('HAWI_PLUGIN_URL', plugin_dir_url( __FILE__ ));

function ha_load_scripts() {

  $data = array(
      'apply_coupon_nonce'            => wp_create_nonce('apply-coupon'),
      'remove_coupon_nonce'           => wp_create_nonce('remove-coupon'),
      'update_order_nonce'            => wp_create_nonce('update-order-review'),
      'remove_order_item'             => wp_create_nonce('order-item'),
      'update_total_price_nonce'	    => wp_create_nonce('update_total_price'),
      'update_shipping_method_nonce'	=> wp_create_nonce('update-shipping-method'),
      'update_order_review'	          => wp_create_nonce('update-order-review'),
      'process_checkout'              => wp_create_nonce('woocommerce-process_checkout'),
      'search_products'               =>  wp_create_nonce('search-products'),
      'ajaxurl'                       => admin_url( 'admin-ajax.php' )
  );


  if (!is_product()) {
    wp_enqueue_style('hometown', HAWI_PLUGIN_URL . 'assets/css/hometown.css');
    wp_enqueue_style('swiper', HAWI_PLUGIN_URL . 'assets/js/Swiper-3.4.2/dist/css/swiper.min.css');

    wp_register_script('swiper', HAWI_PLUGIN_URL . 'assets/js/Swiper-3.4.2/dist/js/swiper.jquery.min.js', array('jquery'), '1', false);
    wp_register_script('hometown', HAWI_PLUGIN_URL . 'assets/js/hometown.js', array('jquery'), '1', false);

    wp_localize_script('hometown', 'ha_localized_config', $data);

    wp_enqueue_script('swiper');
    wp_enqueue_script('hometown');
  } else {
    wp_register_script('hometown-single-product-page', HAWI_PLUGIN_URL . 'assets/js/single-product-page.js', array('jquery'), '1', false);
    wp_localize_script('hometown-single-product-page', 'ha_localized_config', $data);
    wp_enqueue_script('hometown-single-product-page');
    wp_enqueue_script( 'wc-single-product' );
  }

}
add_action('wp_enqueue_scripts', 'ha_load_scripts');

// Requires
require_once('inc/admin.php');
require_once('inc/frontend.php');
require_once('inc/shortcode-controller.php');


// PLUGINS THAT WERE CUSTOMIZED
require_once('inc/woocommerce-colororimage-variation-select/woocommerce-colororimage-variation-select.php');