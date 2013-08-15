<?php
header("Content-Type: application/json");
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

$category = (isset($_GET['category'])) ? $_GET['category'] : false;
$id = (isset($_GET['id'])) ? $_GET['id'] : false;
$api = (isset($_GET['api'])) ? $_GET['api'] : false;

unset($_GET['category'], $_GET['id'], $_GET['api']);

if($category)
{
  if($api)
  {
    $api_config = $routes[$category][$api];
  }
  else
  {
    if(isset($routes[$category]))
    {
      $api_config = $routes[$category];
    }
  }
}

if(isset($api_config['id_field']) && $id)
{
  $extra_params = array_merge(array($api_config['id_field'] => $id), $_GET, $_POST);
}

$instance = new $api_config['class'];

switch($_SERVER['REQUEST_METHOD'])
{
  case 'GET':
    $output = $instance->doGet($extra_params);
    break;
  case 'POST':
    $output = $instance->doPost($extra_params);
    break;
  case 'DELETE':
    $output = $instance->doDelete();
    break;
  case 'PUT':
    $output = $instance->doPut();
    break;
  default:
    http_response_code(501);
    break;
}

echo json_encode($output);