<?php
/**
 * Class LoginAPI
 *
 * Class for logging a user in, and checking their login status
 * Implements GET and POST methods
 */
class LoginAPI extends NotSoExpertAPI
{
  private $data_source;

  /**
   * Set up our datasource
   */
  public function __construct()
  {
    parent::__construct();
    $this->data_source = new LoginDataSource();
  }
  /**
   * Check the current login status
   *
   * @param array $params
   * @return array
   */
  public function doGet($params = null)
  {
    $user_id = (int)$this->session->get('user_id');
    return array('logged_in' => ($user_id > 0), 'user_id' => $user_id);
  }

  /**
   * Attempt to log a user in
   *
   * @param array $params
   * @return array
   */
  public function doPost($params = null)
  {
    if(isset($params['username'], $params['password']))
    {
      $user_id = $this->data_source->getUserIdFromUsernameAndPassword($params['username'], $params['password']);

      if($user_id > 0)
      {
        $this->session->set('user_id', $user_id);
        return array('success' => true, 'user_id' => $user_id);
      }
    }

    return array('success' => false);
  }
}