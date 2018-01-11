<?php

/**
 * @package HOMETOWN
 * @version 0.1a
 */
/*
Plugin Name: Hometown Apparel Artwork CPT
Description: Integrates the artwork into the custom t-shirt page.
Author: Graphic Lux, Grayson Erhard
Version: 1
Author URI: http://graysonerhard.com
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

define('HAA_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('HAA_PLUGIN_URL', plugin_dir_url( __FILE__ ));

add_action('wp_enqueue_scripts', 'ha_artwork_load_scripts');
function ha_artwork_load_scripts() {
  wp_enqueue_style('artwork', HAA_PLUGIN_URL . 'assets/css/artwork.css');
  wp_enqueue_script('artwork', HAA_PLUGIN_URL . 'assets/js/artwork.js', array('jquery'), false, true);

  // color picker plug-in
  wp_enqueue_style('wheel-color-picker', HAA_PLUGIN_URL . 'assets/jquery.wheelcolorpicker-2.2.0/css/wheelcolorpicker.css');
  wp_enqueue_script('wheel-color-picker', HAA_PLUGIN_URL . 'assets/jquery.wheelcolorpicker-2.2.0/jquery.wheelcolorpicker.js', array('jquery'), false, true);
}

require('admin/cpt.php');
require('frontend/frontend.php');