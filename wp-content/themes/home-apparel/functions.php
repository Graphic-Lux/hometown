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

// Register Style
function custom_styles() {

	wp_register_style( 'grayson', get_stylesheet_directory_uri() . '/grayson/grayson.css', false, '1' );
	
	wp_register_script( 'grayson', get_stylesheet_directory_uri() . '/grayson/grayson.js', false, '1' );
	
	wp_register_script( 'plugins', get_stylesheet_directory_uri() . '/grayson/jquery.plugin.min.js', false, '1' );
	
	wp_register_script( 'jquery-mobile', 'https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js', array('jQuery'), false, true);

	wp_register_style( 'sumner', get_stylesheet_directory_uri() . '/grayson/sumner/sumner.css', false, '1' );
	
	wp_register_script( 'grayson-payment', get_stylesheet_directory_uri() . '/grayson/payment.js', false, true );
/*
	wp_register_style( 'easyzoom', get_stylesheet_directory_uri() . '/grayson/EasyZoom/css/easyzoom.css', false, '1' );
	
	wp_register_script( 'easyzoom', get_stylesheet_directory_uri() . '/grayson/EasyZoom/dist/easyzoom.js', false, '1' );
*/

	wp_enqueue_style( 'grayson' );
	wp_enqueue_script('plugins');
	wp_enqueue_script( 'grayson', array('jquery'), '', true );
	wp_enqueue_script('jquery-mobile');
	wp_enqueue_style( 'sumner' );
 	wp_enqueue_script( 'app', get_stylesheet_directory_uri() . '/includes/js/app.js', array('jquery'), '', true);
/*
	wp_enqueue_style( 'easyzoom' );
	wp_enqueue_script( 'easyzoom' );
*/

}
add_action( 'wp_enqueue_scripts', 'custom_styles' );

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// WOOCOMMERCE CUSTOM HOOKS ////////////////////////////////////////////////



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// CUSTOM PHONE FIELD & CHANGE PLACEHOLDERS
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
     
     unset($fields['billing']);
     unset($fields['shipping']['shipping_country']);
     
     $fields['billing']['billing_first_name'] = array(
	    'placeholder'   => _x('First Name', 'placeholder', 'woocommerce'),
	    'priority'		=> 10
     );
     
     $fields['billing']['billing_last_name'] = array(
	    'placeholder'   => _x('Last Name', 'placeholder', 'woocommerce'),
	    'priority'		=> 20
     );
     
     $fields['billing']['billing_email'] = array(
	    'placeholder'   => _x('Email', 'placeholder', 'woocommerce'),
	    'priority'		=> 30
     );
     
     $fields['billing']['billing_address_1'] = array(
	    'placeholder'   => _x('Street Address 1', 'placeholder', 'woocommerce'),
	    'priority'		=> 40
     );
     
     $fields['billing']['billing_address_2'] = array(
	    'placeholder'   => _x('Street Address 2', 'placeholder', 'woocommerce'),
	    'priority'		=> 50
     );
     
     $fields['billing']['billing_country'] = array(
	    'type'			=> 'country',
	    'placeholder'   => _x('Country', 'placeholder', 'woocommerce'),
	    'priority'		=> 60
     );
     
     $fields['billing']['billing_city'] = array(
	    'type'			=> 'text',
	    'placeholder'   => _x('City', 'placeholder', 'woocommerce'),
	    'priority'		=> 70
     );
     
     $fields['billing']['billing_state'] = array(
	    'placeholder'   => _x('State', 'placeholder', 'woocommerce'),
	    'type'   => 'state',
	    'priority'		=> 80
     );
     
     $fields['billing']['billing_postcode'] = array(
	    'type'			=> 'text',
	    'placeholder'   => _x('Zip', 'placeholder', 'woocommerce'),
	    'priority'		=> 90
     );
     
     $fields['billing']['billing_phone'] = array(
	    'placeholder'   => _x('Phone', 'placeholder', 'woocommerce'),
	    'required'  => false,
	    'class'     => array('form-row-wide'),
	    'clear'     => true,
	    'priority'	=> 95
     );
     
     
     $fields['shipping']['shipping_first_name'] = array(
	    'placeholder'   => _x('First Name', 'placeholder', 'woocommerce'),
	    'priority' 		=> 10
     );
     
     $fields['shipping']['shipping_last_name'] = array(
	    'placeholder'   => _x('Last Name', 'placeholder', 'woocommerce'),
	    'priority' 		=> 20
     );
     
     $fields['shipping']['shipping_address_1'] = array(
	    'placeholder'   => _x('Street Address 1', 'placeholder', 'woocommerce'),
	    'priority' 		=> 30
     );
     
     $fields['shipping']['shipping_address_2'] = array(
	    'placeholder'   => _x('Street Address 2', 'placeholder', 'woocommerce'),
	    'priority' 		=> 40
     );
     
     $fields['shipping']['shipping_country'] = array(
	     'type'			=> 'country',
	    'placeholder'   => _x('Country', 'placeholder', 'woocommerce'),
	    'priority' 		=> 60
     );
     
     $fields['shipping']['shipping_postcode'] = array(
	    'type'			=> 'text',
	    'placeholder'   => _x('Zip', 'placeholder', 'woocommerce'),
     );
     
     $fields['shipping']['shipping_city'] = array(
	    'type'			=> 'text',
	    'placeholder'   => _x('City', 'placeholder', 'woocommerce'),
     );
     
     $fields['shipping']['shipping_state'] = array(
	    'type'			=> 'state',
	    'placeholder'   => _x('State', 'placeholder', 'woocommerce'),
     );
     
     $fields['shipping']['shipping_phone'] = array(
	    'placeholder'   => _x('Phone', 'placeholder', 'woocommerce'),
	    'required'  => false,
	    'class'     => array('form-row-wide'),
	    'clear'     => true,
	    'priority'	=> 90
     );
     
     unset($fields['order']['order_comments']); 

     return $fields;
}

/*
remove_action('woocommerce_checkout_billing','woocommerce_checkout_billing');
add_action('woocommerce_billing_info','woocommerce_checkout_billing');
*/




////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  SHIPPING OPTION

remove_action('woocommerce_after_checkout_shipping_form', 'wc_cart_totals_shipping_html', 10);

// hook into the fragments in AJAX and add our new table to the group
add_filter('woocommerce_update_order_review_fragments', 'websites_depot_order_fragments_split_shipping', 10, 1);

function websites_depot_order_fragments_split_shipping($order_fragments) {

	ob_start();
	websites_depot_woocommerce_order_review_shipping_split();
	$websites_depot_woocommerce_order_review_shipping_split = ob_get_clean();

	$order_fragments['.websites-depot-checkout-review-shipping-table'] = $websites_depot_woocommerce_order_review_shipping_split;

	return $order_fragments;

}

// We'll get the template that just has the shipping options that we need for the new table
function websites_depot_woocommerce_order_review_shipping_split( $deprecated = false ) {
	wc_get_template( 'checkout/shipping-order-review.php', array( 'checkout' => WC()->checkout() ) );
}


add_action('woocommerce_after_checkout_shipping_form', 'websites_depot_move_new_shipping_table', 5);
function websites_depot_move_new_shipping_table() {
	echo '<div class="shop_table websites-depot-checkout-review-shipping-table"></div>';
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// KEEP SHOPPING LINK
add_action( 'woocommerce_before_checkout_form', 'add_keep_shopping_link' );
function add_keep_shopping_link() {
	echo '<a class="continue_shopping_link" href="/product-category/whats-new/">';
		_e( '&larr; KEEP SHOPPING', 'woocommerce' );
	echo '</a>';
}



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// BILLING
add_action( 'woocommerce_before_checkout_billing_form', 'add_billing_header' );
function add_billing_header() {
	echo '<h3>';
		_e( '1. Customer Information', 'woocommerce' );
	echo '</h3>';
	echo '<a href="#" id="billing_edit" class="checkout_edit">EDIT</a>';
	echo '<div class="field_review" id="billing_field_review"></div>';
}




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// SHIPPING
add_action( 'woocommerce_after_checkout_shipping_form', 'add_shipping_continue_button' );
function add_shipping_continue_button() {
	echo '<a href="#" class="continue" id="shipping_continue">Continue</a>';
}
add_action( 'woocommerce_before_checkout_shipping_form', 'add_shipping_header' );
function add_shipping_header() {
	echo '<h3>';
		_e( '2. Shipping', 'woocommerce' );
	echo '</h3>';
	echo '<a href="#" id="shipping_edit" class="checkout_edit">EDIT</a>';
	echo '<div class="field_review" id="shipping_field_review"></div>';
}




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// CHECKOUT - COUPON CODE
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
add_action( 'woocommerce_before_checkout_coupon', 'woocommerce_checkout_coupon_form' );

// rename the "Have a Coupon?" message on the checkout page
function woocommerce_rename_coupon_message_on_checkout() {
	return 'Discount Code';
}
add_filter( 'woocommerce_checkout_coupon_message', 'woocommerce_rename_coupon_message_on_checkout', 20 );

// rename the coupon field on the checkout page
function woocommerce_rename_coupon_field_on_checkout( $translated_text, $text, $text_domain ) {
	// bail if not modifying frontend woocommerce text
	if ( is_admin() || 'woocommerce' !== $text_domain ) {
		return $translated_text;
	}
	if ( 'Coupon code' === $text ) {
		$translated_text = 'Enter Code';
	
	} elseif ( 'Apply Coupon' === $text ) {
		$translated_text = 'APPLY';
	}
	return $translated_text;
}
add_filter( 'gettext', 'woocommerce_rename_coupon_field_on_checkout', 10, 3 );




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// PAYMENT
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
add_action( 'woocommerce_checkout_before_order_review', 'woocommerce_checkout_payment' );


// add_action( 'woocommerce_checkout_before_order_review', 'woocommerce_checkout_payment' );





//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// REVIEW
add_action( 'woocommerce_checkout_before_order_review', 'review_and_purchase' );
function review_and_purchase() {
	echo '<div class="review_and_purchase">';
	
		echo '<h3>';
			_e( '4. Review & Purchase', 'woocommerce' );
		echo '</h3>';
		
		echo '<span class="review_and_purchase_instructions">';
			echo "Click the purchase button below after you have reviewed your information.";
		echo '</span>';
		
		echo apply_filters( 'woocommerce_order_button_html', '<input type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="PURCHASE" data-value="PURCHASE" />' );
		
	echo '</div>';
}




// define the woocommerce_thankyou_order_received_text callback 
function filter_woocommerce_thankyou_order_received_text( $var, $order ) { 
    $var = '<h3>Your Order Has Been Placed!</h3>';
    return $var; 
}; 
         
// add the filter 
add_filter( 'woocommerce_thankyou_order_received_text', 'filter_woocommerce_thankyou_order_received_text', 10, 2 ); 
/*


function woo_debug_top() {
	echo 'top';
}

function woo_debug_bottom() {
	echo 'bottom';
}

add_action( 'woocommerce_before_checkout_form', 'woo_debug_top', 5 );
add_action( 'woocommerce_before_checkout_billing_form', 'woo_debug_bottom', 5 );
*/

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