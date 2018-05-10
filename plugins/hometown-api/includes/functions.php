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