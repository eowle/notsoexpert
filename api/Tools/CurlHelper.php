<?php
class CurlHelper
{
  /**
   * @var string
   */
  protected $url;

  /**
   * If an instance of CurlHelper is created with arguments, set the URL with whatever was given
   */
  public function __construct()
  {
    if(count(func_get_args()) > 0)
    {
      $this->setUrl(func_get_args());
    }
  }

  /**
   * Set the target URL and optional parameters.  This method uses func_get_args to apply them, but args[0] should be
   * the URL of the target.
   *
   * @return void
   */
  public function setUrl()
  {
    $params = func_get_args();
    $url = array_shift($params);

    if(count($params) > 0)
    {
      $url = vsprintf($url, $params);
    }

    $this->url = $url;
  }

  /**
   *  Execute a GET request on the URL that was set
   *
   * @return mixed
   */
  public function get()
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $this->url);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
  }

  /**
   * Execute a POST request on the URL that was set, given the data provided
   *
   * @param array $post_data
   * @return mixed
   */
  public function post(array $post_data)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_URL, $this->url);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
  }
}