<?php
$include_path = __DIR__ . '/' . PATH_SEPARATOR
                . __DIR__ . '/Data' . PATH_SEPARATOR
                . __DIR__ . '/Member' . PATH_SEPARATOR
                . realpath('../Core');

set_include_path(get_include_path() . PATH_SEPARATOR . $include_path);

function __autoload($class_name)
{
  require_once($class_name . '.php');
}

$config_file = "config/Routes.yaml";
$fh = fopen($config_file, "r");
$yaml = fread($fh, filesize($config_file));
$parsed = yaml_parse($yaml);
$routes = $parsed['Routes'];


$api = (isset($_GET['api'])) ? $_GET['api'] : false;
$id = (isset($_GET['id'])) ? $_GET['id'] : false;
$sub = (isset($_GET['sub'])) ? $_GET['sub'] : false;

if($api)
{
  if($sub)
  {

  }
  else
  {
    if(isset($routes[$api]['class']))
    {
      $instance = new $routes[$api]['class'];
      $instance->doGet(array('week' => 1));
    }
  }
}