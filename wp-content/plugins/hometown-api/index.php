<?php
/**
 * @package HOMETOWN
 * @version 0.1a
 */
/*
Plugin Name: Hometown Apparel API
Description: Allows the creation of custom t-shirts via web browser as well as built-in API functionality.
Author: Graphic Lux, Grayson Erhard
Version: 0.1a
Author URI: http://graysonerhard.com
*/

// Defines
define("PROJECT_TITLE", "Hometown Apparel");
define("REST_PLUGIN_ENABLED", true);
define("REST_PREFIX",   "ha");
define('REST_DATABASE', "");
date_default_timezone_set ("America/Denver");
define('REST_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('REST_PLUGIN_URL', plugin_dir_url( __FILE__ ));

//$upload_dir = wp_upload_dir();
//define('TF_SESSION_FOLDER_PATH', $upload_dir['basedir']  . '/user-mp3s');

// Requires
require_once('includes/functions.php');
require_once('includes/stubs.php');
// require_once('includes/posttype.php');
require_once('includes/menus.php');

require_once('controllers/rest.class.php');
require_once('controllers/object.class.php');
require_once('controllers/item.class.php');
