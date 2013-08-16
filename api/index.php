<?php
/**
 * index.php serves as the Router and output path for our API
 */

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

/**
 * This is the router section of index.  Load the config from Routes.yaml and parse it, this should give us easy
 * access to an array that will tell us which class handles which request, and what parameters it expects.
 */
$config_file = "config/Routes.yaml";
$fh = fopen($config_file, "r");
$yaml = fread($fh, filesize($config_file));
$parsed = yaml_parse($yaml);
$routes = $parsed['Routes'];

/**
 * When a request gets loaded, the exposed URL looks something like:
 *  http://www.example.com/api/schedule/3
 * Our .htaccess re-writes this as:
 *  http://www.example.com/api/index.php?category=schedule&id=3
 *
 * There are rules like the above to handle every expected API call, but they are always in the format:
 *  api/<category>/<id>/<api>
 */
$category = (isset($_GET['category'])) ? $_GET['category'] : false;
$id = (isset($_GET['id'])) ? $_GET['id'] : false;
$api = (isset($_GET['api'])) ? $_GET['api'] : false;

/**
 * We remove these values from our $_GET array so that we can easily append the rest of it as extra parameters to pass on
 * to the API
 */
unset($_GET['category'], $_GET['id'], $_GET['api']);


/**
 * Here, we check the category/api to see if we have a route for them. Once we've found the API we're looking for, load
 * up its config into $api_config
 */
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

/**
 * If an id_field is set in the API config, that means it REQUIRES this field to be passed in.  Outside of that,
 * we merge in parameters that are additionally set in our $_GET and $_POST arrays to pass down.
 */
$extra_params = array();

if(isset($api_config['id_field']) && $id)
{
  $extra_params[$api_config['id_field']] = $id;
}

$extra_params = array_merge($extra_params, $_GET, $_POST);

/**
 * The 'class' field of the Routes config specifies which class this request should invoke.  Once we have that,
 * we can use it to instantiate our instance.
 *
 * TODO: Add handling for an invalid class name specified
 */
$instance = new $api_config['class'];

/**
 * This switch translates our request method (GET|POST|DELETE|PUT) into the respective method in our class.
 * If the user requests something outside of these actions, throw a 501/Method Not Implemented
 */
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

/**
 * We're expecting a json encodable object from our APIs, so all we need to do is encode it and send it back to the
 * requesting party.
 */
echo json_encode($output);