<?php

/*
* Add your own functions here. You can also copy some of the theme functions into this file. 
* Wordpress will use those functions instead of the original functions then.
*/

add_theme_support('deactivate_layerslider');
add_action('after_setup_theme', 'remove_portfolio');
	function remove_portfolio() {
	remove_action('init', 'portfolio_register');
}

function is_login_page() {
    if ( $GLOBALS['pagenow'] === 'wp-login.php' && ! empty( $_REQUEST['action'] ) && $_REQUEST['action'] === 'register' )
        return true;
    return false;
}

function my_redirect() {  
    //if you have the page id of landing. I would tell you to use if( is_page('page id here') instead
    //Don't redirect if user is logged in or user is trying to sign up or sign in
    if( !is_login_page() && !is_admin() && !is_user_logged_in() && !is_page( [17, 73] )){
        //$page_id is the page id of landing page
        if( !is_page('2') ){
            wp_redirect( get_permalink('2') );
            exit;
        }
    }
}
add_action( 'template_redirect', 'my_redirect' );

add_action('template_redirect', 'redirect_user_role'); function redirect_user_role(){ 
	if(current_user_can('administrator') && is_page('2')) { wp_redirect('http://dev.hostsites.cloud/home/landing-page/'); } }

function mytheme_custom_scripts(){
    if ( is_home() || is_front_page()) {
            $scriptSrc = get_stylesheet_directory_uri() . '/js/custom.js';
            wp_enqueue_script( 'myhandle', $scriptSrc , array(), '1.0',  false );
    }
}
add_action( 'wp_enqueue_scripts', 'mytheme_custom_scripts' );


//  Product Slider 6 Columns //

add_filter('avia_load_shortcodes', 'avia_include_shortcode_template', 15, 1);
function avia_include_shortcode_template($paths)
{
	$template_url = get_stylesheet_directory();
    	array_unshift($paths, $template_url.'/shortcodes/');

	return $paths;
}


function woo_debug_top() {
	echo 'top';
}

function woo_debug_bottom() {
	echo 'bottom';
}

add_action( 'woocommerce_before_checkout_form', 'woo_debug_top', 5 );
add_action( 'woocommerce_before_checkout_billing_form', 'woo_debug_bottom', 5 );


/** Add Custom Tab In Account Area */

function custom_wc_end_point() {
	if(class_exists('WooCommerce')){
    add_rewrite_endpoint( 'contact', EP_ROOT | EP_PAGES );
}
}
add_action( 'init', 'custom_wc_end_point' );
function custom_endpoint_query_vars( $vars ) {
    $vars[] = 'contact-us';
    return $vars;
}
add_filter( 'query_vars', 'custom_endpoint_query_vars', 0 );
function ac_custom_flush_rewrite_rules() {
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'ac_custom_flush_rewrite_rules' );
// add the custom endpoint in the my account nav items
function custom_endpoint_acct_menu_item( $items ) {
   
    $logout = $items['customer-logout'];
    unset( $items['customer-logout'] );
	$items['contact-us'] = __( 'Contact', 'woocommerce' ); // replace videos with your endpoint name
	$items['customer-logout'] = $logout;
        return $items;
}
add_filter( 'woocommerce_account_menu_items', 'custom_endpoint_acct_menu_item' );
// fetch content from your source page (in this case video page)
function fetch_content_custom_endpoint() {
    global $post;
    $id = "73"; // your video page id
    ob_start();
    $output = apply_filters('the_content', get_post_field('post_content', $id));
    $output .= ob_get_contents();
    ob_end_clean();
    echo $output;
}
add_action( 'woocommerce_account_videos_endpoint', 'fetch_content_custom_endpoint' );

add_theme_support( 'wc-product-gallery-zoom' );
add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-slider' );


function hometown_redirect_after_login() {
  return $_SERVER['HTTP_REFERER'].'/landing-page';
}

add_filter('login_redirect', 'hometown_redirect_after_login');