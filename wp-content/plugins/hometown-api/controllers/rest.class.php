<?
// Specify domains from which requests are allowed
header('Access-Control-Allow-Origin: *');

// Specify which request methods are allowed
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

// Additional headers which may be sent along with the CORS request
// The X-Requested-With header allows jQuery requests to go through
header('Access-Control-Allow-Headers: X-Requested-With');

// Set the age to 1 day to improve speed/caching.
header('Access-Control-Max-Age: 86400');

require_once('base.class.php');

define("DEBUG_ON", true);

//!  The ReST class the does most of it
/*!
  This class is used to handle all ReST requests to http as well as a tool for code. Calls to /rest/{method} work as well as calls to $rest->{method} where method can be a table or a pseudofunction.
*/

class rest extends base implements ArrayAccess
{
  /**
   * Fields accessible by the ReST class.
   *
   * These are tables that are available via ReST. Usually I show all columns here however if any are password hashes, etc I remove those columns. Additionally you can use the _checkSecurity method to modify fields based on security.
   */
  var $allowedFieldsForTables = array();
  /**
   * Used for grouping fields. Currently unused
   */
  var $groupFieldsForTables = array();

  /**
   * A place to create deafult table indexes
   */
  var $tableIndexes = array();

  /**
   * A place we will store the last result of the rest
   */
  var $lastResult;

  /**
   * A place we will store the last table accessed by rest
   */
  var $lastTable;

  /**
   * The call that happens if no defined or pseudo call is made
   *
   * This call responds to get, post, put and delete. The functions are not perfectly documented but if you make a http request of any of those types it will respond with the corresponding method's response.
   *
   * \param method
   * Know your php and __call methods or google it
   *
   * \param arguments
   * Additional arguments usually not called via http
   */
  function __call($method, $arguments)
  {

    global $wpdb;

    // Table list
    $tables = $wpdb->get_results("show tables");
    $allTables = array();
    foreach ($tables as $index => $table) {
      $allTables[] = current($table);
    }

    if (substr($method, 0, 3) == REST_PREFIX . "_")
      $arguments = array($method, $arguments);
    else
      $arguments = array(REST_PREFIX . "_" . $method, $arguments);

    (isset($_GET['udid'])) ? $udid = $_GET['udid'] : $udid = false;

    if ($udid) {
      $methods = openssl_get_cipher_methods();

      $return = call_user_func_array(array($this, "_" . strtolower($_SERVER['REQUEST_METHOD'])), $arguments);

      return openssl_encrypt(json_encode($return), "AES-256-OFB", md5(md5($udid) . "Lyfespark12#3"));
    } else {
      return call_user_func_array(array($this, "_" . strtolower($_SERVER['REQUEST_METHOD'])), $arguments);
    }
  }

  function __get($field)
  {
    if (!$this->lastResult)
      return;

    $returnData = array();
    foreach ($this->lastResult as $row) {
      $returnData[] = $row->{$field};
    }

    return $returnData;
  }

  function __set($field, $value)
  {
    if (!$this->lastResult)
      return;

    $returnData = array();
    foreach ($this->lastResult as &$row) {
      $row->$field = $value;
    }
  }

  /**
   * Abstract Methods
   *
   * The following methods are used to allow this object to be accessed like an array
   */
  function offsetExists($offset)
  {
    if ($this->lastResult[$offset])
      return true;

    return false;
  }

  function offsetGet($offset)
  {
    if ($this->lastResult[$offset])
      return $this->lastResult[$offset];

    return false;
  }

  function offsetSet($offset, $value)
  {
    if (!$this->lastResult)
      $this->lastResult[$offset] = $value;
  }

  function offsetUnset($offset)
  {
    unset($this->lastResult[$offset]);
  }

  /**
   * This is generally a request for information.
   *
   * You are getting information. About a table generally called by /rest/{table} or $rest->table()
   *
   * \param table
   * The table you called via ReST or inline.
   *
   * \param arguments
   * Additional arguments usually not called via http
   */
  function _get($table, $arguments)
  {
    $_GET['table'] = $table;
    global $wpdb;

    if (!$this->allowedFieldsForTables[$table]) {
      $actualColumns = array();
      $columns = $wpdb->get_results("show columns from " . $table);
      foreach ($columns as $column) {
        $actualColumns[] = $column->Field;
      }

    } else {
      $actualColumns = $this->allowedFieldsForTables[$table];
    }


    $searchByIndex = false;
    if (count($arguments) > 0)
      if (count($this->tableIndexes[$table]))
        if ($arguments[0] != 'count' && $arguments[0] != 'sum' && $arguments[0] != 'avg') {
          $_GET['search']['eq'][$this->tableIndexes[$table]] = $arguments[0];
          $searchByIndex = true;
        }

    // Create a query from any requests
    $security = $this->_checkSecurity();
    $query = $this->_createQuery();
    $sort = $this->_createSort();
    $limit = $this->_createLimit();
    $group = $this->_createGroup();

    if ($arguments[0] == 'count' || $arguments[0] == 'sum' || $arguments[0] == 'avg') {
      $result = $wpdb->get_results("select " . $arguments[0] . "(" . (($arguments[0] == 'count') ? "*" : $arguments[1]) . ") as " . $arguments[0] . " from `" . $table . "`" . $query . $group);
      if ($_GET['show-query'])
        echo "select " . $arguments[0] . "(" . (($arguments[0] == 'count') ? "*" : $arguments[1]) . ") as " . $arguments[0] . " from `" . $table . "`" . $query . $group;
      return $result;
    } else {
      if (!$group) {
        $result = $wpdb->get_results("select `" . implode("`, `", $actualColumns) . "` from `" . $table . "`" . $query . $group . $sort . $limit);
        if ($_GET['show-query'])
          echo "select `" . implode("`, `", $actualColumns) . "` from `" . $table . "`" . $query . $group . $sort . $limit . "\n";
      } else {
        $result = $wpdb->get_results("select " . $this->groupFieldsForTables[$table] . " from `" . $table . "`" . $query . $group . $sort . $limit);
        if ($_GET['show-query'])
          echo "select " . $this->groupFieldsForTables[$table] . " from `" . $table . "`" . $query . $group . $sort . $limit . "\n";
      }
    }

    $newReturn = array();
    foreach ($result as $row) {
      $thisRow = cast('item', $row);
      $thisRow->table = $table;
      $newReturn[] = $thisRow;
    }


    if (!$security) {
      if (!$_GET['skip-dig'])
        $this->_extraFunctionCalls($table, $result);
      $this->lastResult = $newReturn;
      $this->lastTable = $table;

      return $newReturn;
    } else
      return $security;
  }

  /**
   * This is usually a update or create.
   *
   * Posting information with an save, update or create flag. This is a little untraditional but still functions properly. Definitely needs some fixes though.
   *
   * \todo _POST['save'] to _put as _post is currently the only place to do so. _put is kinda broken.
   * \param table
   * The table you called via ReST or inline.
   *
   * \param arguments
   * Additional arguments usually not called via http
   */
  function _post($table, $arguments = array())
  {
    global $wpdb;

    $index = $wpdb->get_results('show index from `' . $table . '`');

    $postData = (array)json_decode(file_get_contents("php://input"));
    if (count($postData) <= 0) {
      parse_str(urldecode(stripslashes(file_get_contents("php://input"))), $postData);
      $postData = (array)$postData;
    }

    /**
     * \ todo I really want to fix the way that this works. It is pretty broken but close to good.
     */

    if (isset($_GET['debug'])) { $debug = $_GET['debug']; }

    if ($debug) {
      echo "<br>Arguments: ";
      print_r($arguments);
      echo '<br>postData: ';
      print_r($postData);
// 			die();
    }

    (isset($_POST['save'])) ? $postSave = $_POST['save'] : $postSave = false;
    (isset($_POST['update'])) ? $postUpdate = $_POST['save'] : $postUpdate = false;

    if ($postSave) {
      $postData = $postSave;
    } else if (count($index) > 0) {
      if ($postUpdate)
        $postData = $postUpdate;

      $search = '';

      foreach ($index as $theIndex) {
// 					echo "<br>the index: "; print_r($theIndex);
        if (array_key_exists($theIndex->Column_name, $postData)) {
          $arguments['where'][$theIndex->Column_name] = $postData[$theIndex->Column_name];

          $search .= "`" . $theIndex->Column_name . "` = '" . $postData[$theIndex->Column_name] . "' and ";
        }
      }


      /*
            if ($debug) {
              echo 'arguments 3: ';var_dump($arguments);
              print_r($postData);
              die();
              echo '<br>rest select count';
              var_dump(substr($search, 0, -5));
            }
      */

      if ($arguments[0] == 'update') {
        $exists = $wpdb->get_results("select count(*) as count from `" . $table . "` where " . substr($search, 0, -5));
        if ($exists[0]->count > 0)
          $arguments['update'] = 1;
        else
          $arguments['create'] = 1;
      }

    }


// 			echo '<br>Arguments 4: ';	print_r($arguments);


    if (count($postData) <= 0)
      $this->_extraPostValues($table, $arguments, $postData);

    if ($arguments['update'] || count($_POST['update']) > 0) {
      if ($wpdb->update($table, $postData, $arguments['where'])) {
        $result = array("success" => "1", "action" => "updated", "where" => $arguments['where'], "update" => $postData);
      } else {
        $result = array("success" => "0", "action" => "updated", "where" => $arguments['where'], "update" => $postData);
      }
    } else {
      if ($wpdb->insert($table, $postData)) {
        $result = array("success" => "1", "action" => "inserted", "newId" => $wpdb->insert_id, "insert" => $postData);
      } else {
        $result = array("success" => "0", "action" => "inserted", "newId" => $wpdb->insert_id, "insert" => $postData);
      }
      $wpdb->hide_errors();
    }

// 		print_r($result);

    $this->_postSaveFunction($table, $arguments, $postData, $result);

    return $result;
  }

  /**
   * A potential "put" operation via ReST
   *
   * Kind of embarrassing right now. I've not used the put lately but it should work if someome sorts it out...
   *
   * \todo Redo this whole method
   */
  function _put($table, $arguments = array())
  {
    global $wpdb;
    $data = file_get_contents("php://input");
    $postData = (array)json_decode($data);
    if (count($postData) <= 0)
      parse_str(urldecode(stripslashes($data)), $postData);

    if (count($postData) == 1)
      $postData = (array)$postData[0];

    if (count($postData) <= 0)
      $postData = $_GET['insert'];

    $this->_extraPutValues($table, $arguments, $postData);
    if ($arguments['error'])
      return array("success" => 0, "error" => $arguments['message']);

    $insert = $wpdb->insert(REST_PREFIX . "_" . $table, $postData);
    if ($insert) {
      return array("success" => "1", "data" => $postData, "insert" => $wpdb->insert_id);
    }

    $update = $wpdb->replace(REST_PREFIX . "_" . $table, $postData);
    return array("success" => ($update ? "1" : "0"), "data" => $postData);
  }

  /**
   * A pretty basic delete method via ReST
   *
   * If you send delete as the request_method to the server and you have access that item will be deleted. I'm honestly not sure if this method has any security in this iteration. Perhaps it is something we should look into.
   *
   * \todo Check security on the delete method. It looks terrible.
   */
  function _delete($table, $arguments = array())
  {

    global $wpdb;
    if (count($arguments[0]) > 1) {
      $deleteData = $arguments[0];
    } else if (is_numeric($arguments[0])) {
      $deleteData = $this->_extraDeleteValues($table, $arguments);
    } else {
      $deleteData = (array)json_decode(file_get_contents("php://input"));
    }

    if (count($deleteData) === 0) {
      $deleteData = array(explode('=',file_get_contents("php://input"))[0] => explode('=',file_get_contents("php://input"))[1]);
    }

//    var_dump($deleteData);

    $query = '';
    $isOr = false;

    foreach ($deleteData as $field => $value) {
      if (count($deleteData) > 1) {
        // process array of values (bulk delete)
        $isOr = true;
        foreach ((array)$value as $subfield => $subvalue) {
          $query[] = "`" . $subfield . "` = '" . addslashes($subvalue) . "'";
        }
      } else {
        // process single values
        $query[] = "`$field` = '" . addslashes($value) . "'";
      }
    }

    if ($isOr) {
      if ($wpdb->query("delete from `" . $table . "` where " . implode(" or ", $query))) {
        $postDeleteResult = $this->_postSaveFunction($table, $arguments, $deleteData, array("success" => "1"));
        if ($postDeleteResult) {
          return array("success" => "1", "object" => $deleteData);
        }
      }
    } else {
      if ($wpdb->query("delete from `" . $table . "` where " . implode(" and ", $query))) {
        $postDeleteResult = $this->_postSaveFunction($table, $arguments, $deleteData, array("success" => "1"));
        if ($postDeleteResult) {
          return array("success" => "1", "object" => $deleteData);
        }
      }
    }

    return array("success" => "0", "object" => $deleteData);

  }

  /**
   * This method creates the actual query
   *
   * There are a lot of things to know about this method.
   * - _GET['search']['li']['{field}']
   * <b>If the string is like the value (on both sides)</b>
   * - _GET['search']['eq']['{field}']
   * <b>If the string equals the value</b>
   * - _GET['search']['neq']['{field}']
   * <b>If the string doesn't equal the value</b>
   * - _GET['search']['bla']['{field}']
   * <b>If the string is blank</b>
   * - _GET['search']['gt']['{field}']
   * <b>If the string (int) is greater than the value</b>
   * - _GET['search']['lt']['{field}']
   * <b>If the string (int) is lesser than the value</b>
   */
  function _createQuery()
  {
    $query = array();
    if (is_array($_GET['search']))
      foreach ($_GET['search'] as $typeOfSearch => $fields) {
        switch ($typeOfSearch) {
          case 'li':
            foreach ($fields as $field => $value) {
              $value = urldecode($value);
              $query[] = "(`$field` like BINARY '%$value%' or `$field` like BINARY '$value%' or `$field` like BINARY '%$value' or `$field` like '%$value%' or `$field` like '$value%' or `$field` like '%$value') ";
            }
            break;

          case 'eq':
            foreach ($fields as $field => $value) {
              if (!is_object($value) && !is_array($value)) {
                $value = urldecode($value);
                $query[] = "(`$field` = BINARY '$value' or `$field` = '$value') ";
              } else if (is_array($value)) {
                foreach ($value as $valueSet) {
                  $valueSet = urldecode($valueSet);
                  $query[] = "(`$field` = BINARY '$valueSet' or `$field` = '$valueSet') ";
                }
              }
            }
            break;

          case 'neq':
            foreach ($fields as $field => $value) {
              $value = urldecode($value);
              $query[] = "(`$field` != BINARY '$value' or `$field` != '$value') ";
            }
            break;

          case 'bla':
            foreach ($fields as $field => $value) {
              $value = urldecode($value);
              $query[] = "`$field` != '' ";
            }
            break;

          case 'gt':
            foreach ($fields as $field => $value) {
              $value = urldecode($value);
              $query[] = "`$field` > '$value' ";
            }
            break;

          case 'lt':
            foreach ($fields as $field => $value) {
              $value = urldecode($value);
              $query[] = "`$field` < '$value' ";
            }
            break;
        }
      }

//    (isset($_GET['searchType'])) ? $searchType = $_GET['searchType'] : $searchType = false;
    if (isset($_GET['searchType'])) { $searchType = $_GET['searchType']; }

    if (count($query) > 0)
      if ($_GET['where'])
        return " where " . implode(" and ", $query);
      else if (!$searchType)
        return " having " . implode(" and ", $query);
      else
        return " having " . implode(" or ", $query);

    return "";
  }

  /**
   * This method adds sort if specified
   *
   * - _GET['sort']['{field}'] = [asc|desc]
   * <b>asc or desc based on the ordering of the request</b>
   */
  function _createSort()
  {
    if (is_array($_GET['sort'])) {
      $sort = " order by ";
      foreach ($_GET['sort'] as $field => $ordering) {
        $sort .= "`$field` $ordering, ";
      }
    }
    return substr($sort, 0, -2);
  }

  /**
   * This method adds grouping if specified
   *
   * Not super important. I'm ignoring this right now.
   */
  function _createGroup()
  {
    if (is_array($_GET['group'])) {
      $group = " group by " . implode(", ", $_GET['group']);
    }
    return $group;
  }

  /**
   * This method adds limiting if specified
   *
   * - _GET['limit'] = {num}
   * <b>How many items should return</b>
   *
   * - _GET['start'] = {num}
   * <b>How many items in should we go?</b>
   */
  function _createLimit()
  {
    $limit = ($_GET['limit'] ? $_GET['limit'] : 100);
    $start = ($_GET['start'] ? $_GET['start'] : 0);
    return " limit $start, $limit";
  }

  /**
   * This method adds security. Kind of.
   *
   * The plan is for this method to check if the user has access to items in the database. The object.class.php does that pretty well but we should use this as a backup. You can lock tables to ensure they are not accessed via api still. This method referenced the UUID of mobile devices and I'm not removing that right now as it isn't causing issues.
   *
   * \todo Make this method more useful.
   */
  function _checkSecurity()
  {
    if (is_array($_GET['table'])) {
      if (in_array($_GET['table'], $lockedTables)) {
        if (!$_GET['uuid'])
          return array("success" => "0", "response" => "You do not have access to this data.");
        else {
          //$_GET['search']['eq']['deviceId'] = $_GET['uuid'];
          //return false;
        }
      } else {
        return false;
      }
    }
  }

  /**
   * This method is for adding to the get call
   *
   * Inside of this method we are able to append additional data from other tables based on queries. This could clearly be moved into joins for optimization but for right now it makes sense and isn't hurting anything. It's kind of nice for some ReST calls.
   *
   * \todo Move this to joins
   */
  function _extraFunctionCalls($table, &$data)
  {

  }

  /**
   * This method is for adding to the put call
   *
   * This method is used for all put calls. We can modify the values before put.
   */
  function _extraPutValues($table, &$arguments, &$postData)
  {

  }

  /**
   * This method is for adding to the put response
   *
   * This method is used for all put calls. We can modify the values that are returned after the put.
   */
  function _extraPutAdditions($table, &$putData)
  {

  }

  /**
   * This method is for adding to the post call
   *
   * This method is used for all post calls. We can modify the values before post
   */
  function _extraPostValues($table, &$arguments, &$postData)
  {

  }

  /**
   * This method is for adding to the delete return
   *
   * This method is used for all delete calls. We can modify the values before delete
   */
  function _extraDeleteValues($table, &$arguments)
  {

  }

  /**
   * This method is for adding to the post return
   *
   * This method is used for all post calls. We can modify the values after the post
   */
  function _postSaveFunction($table, &$arguments, &$data, $result = array())
  {

    return true;

  }


  /*******************************************
   *********** PSEUDO FUNCTIONS ***************
   ********************************************/

  function me()
  {
    global $wpdb;
    $me = (array)wp_get_current_user();
    return $me;
  }
}