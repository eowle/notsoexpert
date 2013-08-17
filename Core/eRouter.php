<?php
/**
 * Class eRouter
 *
 * User: eowle
 * Date: 8/17/13
 * Time: 10:40 AM
 *
 * Generic Router
 */

class eRouter {
  /**
   * Parsed and loaded config
   *
   * @var array
   */
  private $config;

  /**
   * Singleton implementation
   *
   * @return eRouter
   */
  public static function getInstance()
  {
    static $instance = null;

    if($instance === null)
    {
      $instance = new eRouter();
    }

    return $instance;
  }

  /**
   * Set up our file handle and read in our config
   */
  public function __construct()
  {
    $config_file = '../config/Routes.yaml';
    $fh = fopen($config_file, "r");
    $yaml = fread($fh, filesize($config_file));
    $parsed = yaml_parse($yaml);

    $this->config = $parsed['Routes'];
  }

  /**
   * Get the class/parameter config for the current URL
   *
   * @return array ['class' => string, 'id_field' => string]
   */
  public function getRoute()
  {
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
    $api = (isset($_GET['api'])) ? $_GET['api'] : false;

    /**
     * We remove these values from our $_GET array so that we can easily append the rest of it as extra parameters to pass on
     * to the API
     */
    unset($_GET['category'], $_GET['api']);

    /**
     * Here, we check the category/api to see if we have a route for them. Once we've found the API we're looking for, load
     * up its config into $api_config
     */
    if($category)
    {
      if($api)
      {
        $api_config = $this->config[$category][$api];
      }
      else
      {
        if(isset($this->config[$category]))
        {
          $api_config = $this->config[$category];
        }
      }
    }

    return $api_config;
  }
}