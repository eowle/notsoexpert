<?php
class NotSoExpertAPI implements iNotSoExpertAPI
{
  protected $session, $db;

  public function __construct()
  {
    $this->session = eSession::getInstance();
    $this->db = eDB::getInstance();
  }

  /**
   * Stubbed out here to satisfy the interface, should be implemented by child classes
   * However, if we're here that means the invoked API doesn't implement it, so throw a 501
   *
   */
  public function doPost($params = null)
  {
    http_response_code(501);
    return;
  }

  /**
   * Stubbed out here to satisfy the interface, should be implemented by child classes
   * However, if we're here that means the invoked API doesn't implement it, so throw a 501
   *
   */
  public function doGet($params = null)
  {
    http_response_code(501);
    return;
  }

  /**
   * Validate that the user session matches the requested member
   *
   * @param int $user_id
   * @return bool
   * @throws Exception
   */
  final public function validateMember($user_id)
  {
    if($this->session->get('user_id') === $user_id)
    {
      return true;
    }

    http_response_code(401);
    throw new Exception('Requested user_id does not match logged in member');
  }

  /**
   * DELETE is not implemented on any API, throw 501/Method Not Implemented
   *
   */
  final public function doDelete()
  {
    http_response_code(501);
    return;
  }

  /**
   * PUT is not implemented on any API, throw 501/Method Not Implemented
   *
   */
  final public function doPut()
  {
    http_response_code(501);
    return;
  }
}