<?php

/**
* @package HOMETOWN
* @version 0.1a
*/
/*
Plugin Name: Woo Ajax Lightbox Quick View
Description: Asynchronously loads woocommerce product content in a lightbox.
Author: Grayson Erhard
Version: 1
Author URI: http://graysonerhard.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Include JS Files
 */

function walqv_adding_scripts() {
  wp_enqueue_style( 'walqv-fancybox', plugins_url( 'assets/css/jquery.fancybox.min.css', __FILE__) );
  wp_enqueue_style('walqv-magnific-popup', plugins_url('assets/css/magnific-popup.css', __FILE__),'','1.0');
  wp_enqueue_style('walqv-main', plugins_url('assets/css/main.css', __FILE__),'','1.0');
  wp_enqueue_style('walqv-custom', plugins_url('assets/css/custom.css', __FILE__),'','1.0');
  wp_enqueue_style('font-awesome', 'https://use.fontawesome.com/releases/v5.0.3/css/all.css');

  wp_enqueue_script( 'walqv-fancybox', plugins_url( 'assets/js/jquery.fancybox.min.js', __FILE__ ), array( 'jquery' ), '3.1.6', true );
  wp_enqueue_script('walqv-magnific-popup', plugins_url('assets/js/jquery.magnific-popup.min.js', __FILE__),array('jquery'),'1.0', false);
  wp_enqueue_script('walqv-plugin-main', plugins_url('assets/js/woo-quickview.js', __FILE__),array('jquery'),'1.0', true);
}
add_action( 'wp_enqueue_scripts', 'walqv_adding_scripts' );


/* Requred files */
require_once dirname( __FILE__ ) . '/inc/hooks.php';