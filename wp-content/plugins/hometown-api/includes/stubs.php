<?
// Activation Hook
register_activation_hook(__file__, 'rest_activate');

// Rewrite rule filters
add_filter('rewrite_rules_array', 'rest_create_rewrite_rules');
add_filter('query_vars', 'rest_add_query_vars');

// Other filters
add_filter('admin_init', 'rest_flush_rewrite_rules');
add_action('template_redirect', 'rest_template_redirect_intercept');


function rest_activate()
{
  global $wp_rewrite;
  rest_flush_rewrite_rules();
}

// GET ALL MODELS
$modelFileDirectory = scandir(REST_PLUGIN_PATH . "models");

// unset "." and ".." values
unset($modelFileDirectory[0]);
unset($modelFileDirectory[1]);
$models = array();

foreach ($modelFileDirectory as $modelFile) {
  $models[] = explode('.', $modelFile)[0];
}


function rest_create_rewrite_rules($rules)
{
  global $wp_rewrite;
  global $models;

  $newRule = array(
      'rest/(.*)' => 'index.php?rest=' . $wp_rewrite->preg_index(1),
  );

  $dir = REST_PLUGIN_PATH . "views";

  if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
      while (($file = readdir($dh)) !== false) {
        if ($file != 'admin') {
          $file = explode('.', $file)[0];
          if ($file != '')
            $newRule[$file . "/(.*)"] = "index.php?" . $file . "=" . $wp_rewrite->preg_index(1);
// 				        $newRule[$file . "/(.*)/(.*)"] = "index.php?" . $file . "=" . $wp_rewrite->preg_index(1) . "&sessionID=" . $wp_rewrite->preg_index(2); // ERRORS OUT BY MAKING THE FILE 'PAGENAME'!
        }
      }
    }
  }

  /*
    foreach ($models as $model) {
      $model = explode('.', $model)[0];
      if ($model != '')
        $newRule["api/" . $model . "/(.*)"] = "index.php?" . $model . "=" . $wp_rewrite->preg_index(1);
    }
  */

  $newRules = $newRule + $rules;
  return $newRules;
}


function rest_add_query_vars($qvars)
{

  global $models;

  $qvars[] = 'rest';
// 	$qvars[] = 'api';

  $dir = REST_PLUGIN_PATH . "views";

  if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
      while (($file = readdir($dh)) !== false) {
        if ($file != 'admin') {
          $file = explode('.', $file)[0];
          if ((!in_array($file, $qvars)) && ($file != ''))
            $qvars[] = $file;
        }
      }
    }
  }

  foreach ($models as $model) {

    if ((!in_array($model, $qvars)) && ($model != ''))
      $qvars[] = $model;

  }

  if (isset($_GET['debug'])) {
// 		print_r($qvars);
  }

  return $qvars;
}


function rest_flush_rewrite_rules()
{
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
}


function rest_template_redirect_intercept()
{
  global $wp_query;
  global $models;

  if (isset($_GET['debug'])) {
    global $wp_rewrite;
// 		echo "<br> wp_query:";print_r($wp_query);
// 		print_r($wp_rewrite);
  }

  if ($wp_query->get('rest')) {
    rest_pushoutput($wp_query->get('rest'));
    exit;
  }

  $dir = REST_PLUGIN_PATH . "views";
  if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
      while (($file = readdir($dh)) !== false) {
        if ($file != 'admin') {
          $file = explode('.', $file)[0];
          if (isset($_GET['debug'])) {
 				    	echo "<br>file: $file";
          }

          if ($wp_query->get($file)) {
            rest_template($wp_query->get($file), $file);
          }
//						echo '<br>after rest_template()';

        }
      }
      closedir($dh);
    }
  }

//	echo '<br>after rest_template() if statement';

  foreach ($models as $model) {
    if ($wp_query->get($model)) {
      output($wp_query->get($model), $model);
      exit;
    }
  }
}

function rest_template($message, $folder)
{
  if (isset($_GET['debug'])) {
    echo "<br>rest_template() message: " . $message;
  }


  $args = explode("/", $message);

  if ($folder) {
    require_once(REST_PLUGIN_PATH . "views/" . $folder . "/" . $args[0] . ".php");
  } else {
    require_once(REST_PLUGIN_PATH . "views/" . $args[0] . ".php");
  }

  if (isset($_GET['debug'])) {
    echo "<br>before rest_template() exit";
  }

  exit;
}

function output($message, $model, $custom1 = false, $custom2 = false)
{


  if (isset($_GET['debug'])) {
    echo "<br>output() message: " . $message;
  }

  $initGet = $_GET;
  header('Cache-Control: no-cache, must-revalidate', false, 200);
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT', false, 200);

  if (isset($_GET['debug']))
    header('Content-type: application/json', false, 200);
  else
    header('Content-type: text/plain', false, 200);

  require_once(REST_PLUGIN_PATH . "controllers/rest.class.php");
  require_once(REST_PLUGIN_PATH . "models/" . $model . ".class.php");


  $class = "ha_$model";
  $obj = new $class();
  $args = explode("/", $message);
  $data = call_user_func_array(array($obj, array_shift($args)), $args);
  //$data['timestamp'] = time();

  $_GET = $initGet;
  if (!$_GET['debug'])
    echo json_encode($data->lastResult);
  else
    echo print_r($data, true);

}

function rest_pushoutput($message)
{

// 	var_dump($message);

  $initGet = $_GET;
  header('Cache-Control: no-cache, must-revalidate', false, 200);
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT', false, 200);

  if (isset($_GET['debug'])) { $debug = $_GET['debug']; }

  if (!$debug)
    header('Content-type: application/json', false, 200);
  else
    header('Content-type: text/plain', false, 200);

  require_once(REST_PLUGIN_PATH . 'controllers/rest.class.php');
  $obj = new rest();
  $args = explode("/", $message);
  $data = call_user_func_array(array($obj, array_shift($args)), $args);
  $data['timestamp'] = time();

// 	print_r($data);
  $_GET = $initGet;
  if (!$debug)
// 		echo json_encode($data->lastResult);
    echo json_encode($data);
  else
    echo print_r($data, true);
}


// A couple quick helpers
function decode($content)
{
  // Woot, let's get it
  $response = $content;

  if ($response != FALSE) {
    if ($response == "bnVsbA==") {
      return array();
    }
    // Well we got this far, let's decode that stuffs
    $decoded = json_decode(stripslashes(base64_decode($response)), true); // Yup we have to do all that stuff to get the content.

    // Make sure we got something worthwhile
    if (!is_array($decoded)) {
      return false;
    }

    // Individual catches
    if ($type == 'title') {
      if ($decoded['title'] == '') {
        return false;
      }
    }

    return $decoded;
  } else {
    return false;
  }
}
