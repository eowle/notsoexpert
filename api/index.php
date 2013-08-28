<?php
/**
 * Every application defined as an API should return a json-encodable object
 * So we set the Content-Type to application/json
 */
header("Content-Type: application/json");

/**
 * Our include paths for the API are pretty simple, they are our current directory, directories of the APIs, and
 * the Core directory that includes DB, Session, and Log handlers
 */
$include_path = __DIR__ . '/' . PATH_SEPARATOR
                . __DIR__ . '/Data' . PATH_SEPARATOR
                . __DIR__ . '/Member' . PATH_SEPARATOR
                . __DIR__ . '/Tools' . PATH_SEPARATOR
                . realpath('../Core');

set_include_path(get_include_path() . PATH_SEPARATOR . $include_path);


/**
 * Automagic class loader.  Since we've already set the include path above, this function gets invoked when
 * we try to instantiate a class that hasn't been loaded, this should catch it and require it.
 *
 * @param $class_name
 */
function __autoload($class_name)
{
  require_once($class_name . '.php');
}

$router = eRouter::getInstance();
$route_data = $router->getRoute();

$id = (isset($_GET['id'])) ? $_GET['id'] : false;
unset($_GET['id']);

/**
 * If an id_field is set in the API config, that means it REQUIRES this field to be passed in.  Outside of that,
 * we merge in parameters that are additionally set in our $_GET and $_POST arrays to pass down.
 */
$extra_params = array();

if(isset($route_data['id_field']) && $id)
{
  $extra_params[$route_data['id_field']] = $id;
}

$extra_params = array_merge($extra_params, $_GET, $_POST, $_FILES);

/**
 * The 'class' field of the Routes config specifies which class this request should invoke.  Once we have that,
 * we can use it to instantiate our instance.
 *
 * TODO: Add handling for an invalid class name specified
 */
$instance = new $route_data['class'];

/**
 * This switch translates our request method (GET|POST|DELETE|PUT) into the respective method in our class.
 * If the user requests something outside of these actions, throw a 501/Method Not Implemented
 */
try
{
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
}
catch(Exception $e)
{
  $output = array('error' => $e->getMessage());
}

/**
 * We're expecting a json encodable object from our APIs, so all we need to do is encode it and send it back to the
 * requesting party.
 */
echo json_encode($output);