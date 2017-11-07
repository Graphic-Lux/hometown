<?
require_once(LS_PLUGIN_PATH.'/controllers/rest.class.php');
class hometown extends rest
{
  function cool($a, $b)
  {
    print_r($a);
    print_r($b);
    die();
  }
}