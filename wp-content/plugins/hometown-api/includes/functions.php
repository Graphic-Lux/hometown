<?
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

spl_autoload_register(function ($class_name) {
	/*if (file_exists(REST_PLUGIN_PATH . "models/" . $class_name . ".class.php"))
	{
		require_once(REST_PLUGIN_PATH . "models/" . $class_name . ".class.php");
	} else */if (file_exists(REST_PLUGIN_PATH . "models/" . str_replace("tf_", "", $class_name) . ".class.php")) {
		require_once(REST_PLUGIN_PATH . "models/" . str_replace("tf_", "", $class_name) . ".class.php");
	}
});

function getView($path, $echo = false)
{
	if (file_exists(REST_PLUGIN_PATH . "views/" . $path . ".php"))
	{
		if ($echo)
			require(REST_PLUGIN_PATH . "views/" . $path . ".php");
		else
			return file_get_contents(REST_PLUGIN_PATH . "views/" . $path . ".php");
	} else if (file_exists(REST_PLUGIN_PATH . "views/" . $path . ".html"))
	{
		if ($echo)
			require(REST_PLUGIN_PATH . "views/" . $path . ".html");
		else
			return file_get_contents(REST_PLUGIN_PATH . "views/" . $path . ".html");
	} else {
		return false;
	}
}

/**
 * Class casting
 *
 * @param string|object $destination
 * @param object $sourceObject
 * @return object
 */
function cast($destination, $sourceObject)
{
    if (is_string($destination)) {
        $destination = new $destination();
    }
    $sourceReflection = new ReflectionObject($sourceObject);
    $destinationReflection = new ReflectionObject($destination);
    $sourceProperties = $sourceReflection->getProperties();
    foreach ($sourceProperties as $sourceProperty) {
        $sourceProperty->setAccessible(true);
        $name = $sourceProperty->getName();
        $value = $sourceProperty->getValue($sourceObject);
        if ($destinationReflection->hasProperty($name)) {
            $propDest = $destinationReflection->getProperty($name);
            $propDest->setAccessible(true);
            $propDest->setValue($destination,$value);
        } else {
            $destination->$name = $value;
        }
    }
    return $destination;
}


//function ha_load_scripts() {
//
//  wp_enqueue_style('tunefuze', REST_PLUGIN_URL . 'assets/css/tunefuze.css');
//
//  wp_enqueue_style('sumner', REST_PLUGIN_URL . 'assets/css/sumner.css');
//
//  wp_enqueue_style('tooltipster', REST_PLUGIN_URL . 'assets/css/tooltipster.bundle.min.css');
//
//  wp_enqueue_style('font-awesome', REST_PLUGIN_URL . 'assets/css/ionicons.min.css');
//
//  wp_enqueue_style('boostrap_slider', REST_PLUGIN_URL . 'assets/css/bootstrap-slider.min.css');
//
//  wp_register_script('sumner-js', REST_PLUGIN_URL . 'assets/js/sumner.js', array('jquery', 'tf-functions'), '0.2.0', false);
//
//  wp_register_script('tooltipster-js', REST_PLUGIN_URL . 'assets/js/tooltipster.bundle.min.js', array('jquery', 'tf-functions'), '0.2.0', false);
//
//  wp_register_script('boostrap-slider-js', REST_PLUGIN_URL . 'assets/js/bootstrap-slider.min.js', array('jquery', 'tf-functions'), '0.2.0', false);
//
//  wp_register_script('tf-functions', REST_PLUGIN_URL . 'assets/js/functions.js', array('jquery'), '0.2.0', false);
//  wp_register_script('audio-js', REST_PLUGIN_URL . 'assets/js/custom-t/audio.js', array('jquery', 'tf-functions'), '0.2.0', false);
////  wp_register_script('audio-js', REST_PLUGIN_URL . 'assets/js/custom-t/audio.js', array('jquery', 'tf-functions'), '0.2.0', false);
//  wp_register_script('mp3-visual-js', REST_PLUGIN_URL . 'assets/js/custom-t/visual.js', array('jquery', 'tf-functions'), '0.2.0', false);
//
//  wp_enqueue_script('tf-functions');
//  wp_enqueue_script('audio-js');
//  wp_enqueue_script('mp3-visual-js');
//  wp_enqueue_script('sumner-js');
//  wp_enqueue_script('tooltipster-js');
//  wp_enqueue_script('boostrap-slider-js');
//
//  $data = array(
//    'upload_url' => admin_url('async-upload.php'),
//    'ajax_url'	 => admin_url('admin-ajax.php'),
//    'nonce'		 => wp_create_nonce('media-form'),
//    'site_url'	 => get_site_url(),
//    'sessionID'	 => get_session_id(),
//    'userID'	 => get_current_user_id(),
//    'plugin_url' => REST_PLUGIN_URL
//  );
//
//  $localized = wp_localize_script( 'tf-functions', 'localized_config', $data );
//
//}
//add_action('wp_enqueue_scripts', 'ha_load_scripts');



//function get_session_id() {
//
//	global $wp_query;
//
////	print_r($wp_query);
//
//	if ($wp_query->get('sessions')) {
//
//		if (strpos($wp_query->get('sessions'), 'edit') == 0) {
//
//      $sessions = new ha_sessions();
//			$sessionID = explode('edit/',$wp_query->get('sessions'))[1];
//			$session = $sessions->get($sessionID)->lastResult[0];
//
//      if (get_current_user_id() != $session->user_id) {
//        unset($session);
//        wp_redirect('/');
//        exit;
//      }
//
//      return $sessionID;
//
//		}
//
//
//	} else if (isset($_REQUEST['sessionID'])) {
//
//	  return $_REQUEST['sessionID'];
//
//	} else {
//
//	  return 0;
//
//  }
//
//
//
//}
//
//
//
//function ha_allow_subscriber_to_uploads() {
//	$subscriber = get_role('subscriber');
//
//	if ( ! $subscriber->has_cap('upload_files') ) {
//		$subscriber->add_cap('upload_files');
//	}
//}
//add_action('admin_init', 'ha_allow_subscriber_to_uploads');



// FILTER, SANITIZE AND VALIDATE
//function ha_mp3_submission_cb() {
//	check_ajax_referer('mp3-submission');
//}
//add_action('wp_ajax_mp3_submission', 'ha_mp3_submission_cb');






// HOOK TO CHANGE UPLOAD DIRECTORY OF MP3s

//add_filter('wp_handle_upload_prefilter', 'ha_handle_upload_prefilter');
//add_filter('wp_handle_upload', 'ha_handle_upload');
//
//function ha_handle_upload_prefilter( $file )
//{
//	add_filter('upload_dir', 'ha_custom_upload_dir');
//	return $file;
//}
//
//function ha_handle_upload( $fileinfo )
//{
//	remove_filter('upload_dir', 'ha_custom_upload_dir');
//	return $fileinfo;
//}
//
//function ha_custom_upload_dir($path)
//{
//	// Determines if uploading from inside a post/page/cpt
//	// If not, default Upload folder is used
//	$use_default_dir = (isset($_REQUEST['post_id'] ) && $_REQUEST['post_id'] == 0 ) ? true : false;
//	$use_default_dir = (isset($_GET['action']) && ($_GET['action'] == 'upload-plugin')) ? true : false;
//
//	if (!is_admin()) {
//		if( !empty( $path['error'] ) || $use_default_dir ) {
//			 echo 'error or not uploading from a post/page/cpt.';
//			 return $path; //error or uploading not from a post/page/cpt
//		}
//	}
//
//
//
//	 // Save uploads in FILETYPE based folders. When using this method,
//	 // you may want to change the check for $use_default_dir
//	 $extension = substr( strrchr( $_POST['name'], '.' ), 1 );
//
//	 if ($extension == 'mp3') {
//
//		$customDir = '/user-mp3s/'. get_current_user_id() . '/' . get_session_id(); // /user-mp3s/<user_id>/<session_id>
//
//	 } else {
//
//		 echo 'not mp3';die();
//
//	 }
//
//	//remove default subdir (year/month)
//	$path['path']	 = str_replace($path['subdir'], '', $path['path']);
//	$path['url']	 = str_replace($path['subdir'], '', $path['url']);
//
//	$path['subdir']	 = $customDir;
//	$path['path']	.= $customDir;
//	$path['url']	.= $customDir;
//
//	return $path;
//}


/*
 * php delete function that deals with directories recursively
 */
function delete_files($target) {
  if(is_dir($target)){
    $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

    foreach( $files as $file )
    {
      delete_files( $file );
    }

    rmdir( $target );
  } elseif(is_file($target)) {
    unlink( $target );
  }
}



@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );