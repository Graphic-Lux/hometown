<?
add_action( 'admin_menu', 'rest_plugin_menu' );

function rest_plugin_menu() {
	add_menu_page(    PROJECT_TITLE, PROJECT_TITLE, 'manage_options',     REST_PREFIX . '-handle',      'rest_dashboard', '', 3);
	add_submenu_page( REST_PREFIX . '-handle', 'Dashboard',  'Dasboard', 'manage_options', REST_PREFIX . '-handle',      'rest_dashboard');
	
	$dir = REST_PLUGIN_PATH . "views/admin";
	
	if (is_dir($dir)) {
	    if ($dh = opendir($dir)) {
	        while (($file = readdir($dh)) !== false) {
		        $lowerCaseFileName = strtolower($file);
		        $explodedLowerCaseFileName = explode(".", $lowerCaseFileName);
		        if (end($explodedLowerCaseFileName) == "php" && $lowerCaseFileName != "dashboard.php")
		        {
			        $file = str_replace(".php", "", $file);
			        add_submenu_page( REST_PREFIX . '-handle', ucwords(str_replace("_", " ", strtolower($file))),  ucwords(str_replace("_", " ", $file)), 'manage_options', REST_PREFIX . '-' . str_replace("_", "-", strtolower($file)), 'rest_function');
		        }
	        }
	        closedir($dh);
	    }
	}
}

function rest_dashboard()
{
	require_once(REST_PLUGIN_PATH . "/views/admin/dashboard.php");
}

function rest_function()
{
	if ($_GET['page'] != str_replace(REST_PREFIX . "-", "", $_GET['page']))
	{
		require_once(REST_PLUGIN_PATH . "/views/admin/" . str_replace(REST_PREFIX . "-", "", $_GET['page']) . ".php");
	}
}