<?
class object extends rest
{
	var $tableName;
	var $singleClass;
	
	function __construct($id = 0)
	{		
		if ($id != 0)
			$this->get($id);
	}
	
	/**
	 * This method allows /api/object/get/id and $object->get($id)
	 *
	 * This gets an individual item from the object it is called from
	 *
	 * \param id
	 * The id of the object you are looking for
	 */
	function get($id = 0, $data = NULL, &$curl = NULL, $header = '')
	{
		if (strlen($this->tableName) == 0)
			$class       = get_class($this);
		else
			$class       = $this->tableName;
		
		if (strlen($this->singleClass) == 0)
			$this->singleClass = substr(get_class($this), 0, -1);
		$parts       = explode("_", $this->singleClass);
		$classSingle = end($parts);
		
		$oldget    = $_GET;
		$oldpost   = $_POST;
		$oldmethod = $_SERVER['REQUEST_METHOD'];
		$_SERVER['REQUEST_METHOD'] = "GET";
		unset($_GET, $_POST);
    //$_GET['show-query'] = 1;

		if (is_numeric($id))
		{
			$_GET['search']['eq']['id'] = $id;
		} else if (is_array($id)) {
			$_GET['search']['eq'] = $id;
		}
		
		$response = call_user_func_array(array($this, $class), array());
		
		$_GET = $oldget;
		$_POST = $oldpost;
		$_SERVER['REQUEST_METHOD'] = $oldmethod;
		
		return $this;
	}
	
	/**
	 * This method allows /api/object/me and $object->me()
	 *
	 * This returns all items of this object that belong to you
	 */
	function me()
	{
		if (strlen($this->tableName) == 0)
			$class       = get_class($this);
		else
			$class       = $this->tableName;
		
		if (strlen($this->singleClass) == 0)
			$this->singleClass = substr(get_class($this), 0, -1);
		$parts       = explode("_", $this->singleClass);
		$classSingle = end($parts);
		
		global $wpdb;
		$user = wp_get_current_user();
		
		$oldget    = $_GET;
		$oldpost   = $_POST;
		$oldmethod = $_SERVER['REQUEST_METHOD'];
		$_SERVER['REQUEST_METHOD'] = "GET";
		unset($_GET, $_POST);

		$_GET['search']['eq'][$classSingle . '_user'] = $user->data->ID;
		$response = call_user_func_array(array($this, $class), array());
		$_GET = $oldget;
		$_POST = $oldpost;
		$_SERVER['REQUEST_METHOD'] = $oldmethod;

		return $this;
	}
	
	/**
	 * This method allows /api/object/all and $object->all()
	 *
	 * This returns all items of this object
	 */
	function all()
	{
		if (strlen($this->tableName) == 0)
			$class       = get_class($this);
		else
			$class       = $this->tableName;
		
		if (strlen($this->singleClass) == 0)
			$this->singleClass = substr(get_class($this), 0, -1);
		$parts       = explode("_", $this->singleClass);
		$classSingle = end($parts);
		
		$oldget    = $_GET;
		$oldpost   = $_POST;
		$oldmethod = $_SERVER['REQUEST_METHOD'];
		$_SERVER['REQUEST_METHOD'] = "GET";
		unset($_GET, $_POST);
		
		$response = call_user_func_array(array($this, $class), array());
		
		$_GET = $oldget;
		$_POST = $oldpost;
		$_SERVER['REQUEST_METHOD'] = $oldmethod;
		
		return $this;
	}
		
	/**
	 * This method allows /api/object/user/id and $object->user($id)
	 *
	 * This returns all items of this object that belong to the specified user
	 */
	function user($userId)
	{
		if (strlen($this->tableName) == 0)
			$class       = get_class($this);
		else
			$class       = $this->tableName;
		
		if (strlen($this->singleClass) == 0)
			$this->singleClass = substr(get_class($this), 0, -1);
		$parts       = explode("_", $this->singleClass);
		$classSingle = end($parts);
		
		$oldget    = $_GET;
		$oldpost   = $_POST;
		$oldmethod = $_SERVER['REQUEST_METHOD'];
		$_SERVER['REQUEST_METHOD'] = "GET";
		unset($_GET, $_POST);

		$_GET['search']['eq'][$classSingle . '_user'] = $userId;
		$response = call_user_func_array(array($this, $class), array());
		
		$_GET = $oldget;
		$_POST = $oldpost;
		$_SERVER['REQUEST_METHOD'] = $oldmethod;
		
		return $response;
	}
	
	/**
	 * This method allows /api/object/custom-t and $object->custom-t($array)
	 *
	 * This creates an object in the database from either data or json.
	 */
	function create($data = array())
	{
		
		if (strlen($this->tableName) == 0)
			$class       = get_class($this);
		else
			$class       = $this->tableName;
		
		if (strlen($this->singleClass) == 0)
			$this->singleClass = substr(get_class($this), 0, -1);
		$parts       = explode("_", $this->singleClass);
		$classSingle = end($parts);
		
		$uploadedFile = false;
		if (count($_FILES) > 0)
		{
			if ( ! function_exists( 'wp_handle_upload' ) ) {
			    require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
			
			$uploadedfile = $_FILES['image'];
			
			$upload_overrides = array( 'test_form' => false );
			
			$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
			if ( $movefile && ! isset( $movefile['error'] ) ) {
			    $uploadedFile = $movefile['url'];
			}
		}
		
		$oldget    = $_GET;
		$oldpost   = $_POST;
		$oldmethod = $_SERVER['REQUEST_METHOD'];
		$_SERVER['REQUEST_METHOD'] = "POST";
		unset($_GET, $_POST);

		$tableColumns = explode(", ", str_replace("`", "", $this->allowedFieldsForTables['bp_' . $class]));
		if ($data)
		{
			$_POST['save'] = $data;
			
			/*if (in_array($classSingle . '_user', $tableColumns))
				$_POST['save'][$classSingle . '_user'] = $bp->loggedin_user->id;*/
		} else {
			$_POST['save'] = $oldpost['save'];
		}
		
		if ($uploadedFile){
			if (in_array($classSingle . '_image', $tableColumns))
			{
				if (strlen($_POST['save'][$classSingle . '_image']) <= 0)
				{
					$_POST['save'][$classSingle . '_image'] = $uploadedFile;
				}
			}
		}
		
		$response = call_user_func_array(array($this, $class), array());
		
		$_GET = $oldget;
		$_POST = $oldpost;
		$_SERVER['REQUEST_METHOD'] = $oldmethod;
		
		return $response;
	}
	
	/**
	 * This method allows /api/object/update and $object->update($array)
	 *
	 * This updates an object in the database from either data or json.
	 */
	function update($data = array())
	{
		if (strlen($this->tableName) == 0)
			$class       = get_class($this);
		else
			$class       = $this->tableName;
		
		if (strlen($this->singleClass) == 0)
			$this->singleClass = substr(get_class($this), 0, -1);
		$parts       = explode("_", $this->singleClass);
		$classSingle = end($parts);	
		$oldget    = $_GET;
		$oldpost   = $_POST;
		$oldmethod = $_SERVER['REQUEST_METHOD'];
		$_SERVER['REQUEST_METHOD'] = "POST";
		unset($_GET, $_POST);

		if ($data)
		{
			$_POST['update'] = $data;
		}
		$response = call_user_func_array(array($this, $class), array());
		
		$_GET = $oldget;
		$_POST = $oldpost;
		$_SERVER['REQUEST_METHOD'] = $oldmethod;
		
		return $response;
	}
	
	function delete($data = array())
	{
//    echo 'here';die();
		if (strlen($this->tableName) == 0)
			$class       = get_class($this);
		else
			$class       = $this->tableName;

		if (strlen($this->singleClass) == 0)
			$this->singleClass = substr(get_class($this), 0, -1);
		$parts       = explode("_", $this->singleClass);
		$classSingle = end($parts);	
		$oldget    = $_GET;
		$oldpost   = $_POST;

		$oldmethod = $_SERVER['REQUEST_METHOD'];
		$_SERVER['REQUEST_METHOD'] = "DELETE";
		unset($_GET, $_POST);
    
		$response = call_user_func_array(array($this, $class), array($data));
		
		$_GET = $oldget;
		$_POST = $oldpost;
		$_SERVER['REQUEST_METHOD'] = $oldmethod;
		return $response;
	}
}