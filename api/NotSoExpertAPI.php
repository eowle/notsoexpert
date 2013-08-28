<?php
/**
 * Class NotSoExpertAPI
 *
 * Base definition that all APIs should extend.
 */
class NotSoExpertAPI implements iNotSoExpertAPI
{
  protected $session;

  /**
   * Fire up our session as soon as an API invoked
   */
  public function __construct()
  {
    $this->session = eSession::getInstance();
  }

  /**
   * Stubbed out here to satisfy the interface, should be implemented by child classes
   * However, if we're here that means the invoked API doesn't implement it, so throw a 501
   *
   */
  public function doPost($params = null)
  {
    header("HTTP/1.0 501 Method Not Implemented");
    return;
  }

  /**
   * Stubbed out here to satisfy the interface, should be implemented by child classes
   * However, if we're here that means the invoked API doesn't implement it, so throw a 501
   *
   */
  public function doGet($params = null)
  {
    header("HTTP/1.0 501 Method Not Implemented");
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

    header("HTTP/1.0 401 Not Authorized");
    throw new Exception('Requested user id does not match logged in member');
  }

  /**
   * DELETE is not implemented on any API, throw 501/Method Not Implemented
   *
   */
  final public function doDelete()
  {
    header("HTTP/1.0 501 Method Not Implemented");
    return;
  }

  /**
   * PUT is not implemented on any API, throw 501/Method Not Implemented
   *
   */
  final public function doPut()
  {
    header("HTTP/1.0 501 Method Not Implemented");
    return;
  }
}