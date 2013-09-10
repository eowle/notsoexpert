<?php
class CurlHelper
{
  /**
   * @var string
   */
  protected $url;

  /**
   * For a multi-exec pattern, this is a collection of handles to use
   * @var array
   */
  protected $multi_handles = array();

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
   * @param string $url;
   * @return void
   */
  public function setUrl($url)
  {
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

  /**
   * Add a handle to the multi handler array given a url
   *
   * @param string $handler_identifier
   * @param string $url
   * @return void
   */
  public function addHandler($handler_identifier, $url)
  {
    $h = curl_init();
    curl_setopt($h, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($h, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($h, CURLOPT_HEADER, false);
    curl_setopt($h, CURLOPT_URL, $url);
    $this->multi_handles[$handler_identifier] = $h;
  }

  /**
   * Execute the multi-get
   *
   * @return array
   */
  public function multiGet()
  {
    $return = array();
    $mh = curl_multi_init();

    foreach($this->multi_handles as $handle)
    {
      curl_multi_add_handle($mh, $handle);
    }

    do {
      curl_multi_exec($mh, $running);
      curl_multi_select($mh);
    }while($running > 0);

    foreach($this->multi_handles as $k => $handle)
    {
      $return[$k] = json_decode(curl_multi_getcontent($handle));
      curl_multi_remove_handle($mh, $handle);
    }

    curl_multi_close($mh);
    return $return;
  }
}
