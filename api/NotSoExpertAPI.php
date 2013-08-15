<?php
class NotSoExpertAPI implements iNotSoExpertAPI
{
  /**
   * Stubbed out here to satisfy the interface, should be implemented by child classes
   * However, if we're here that means the invoked API doesn't implement it, so throw a 501
   *
   */
  public function doPost()
  {
    http_response_code(501);
    return;
  }

  /**
   * Stubbed out here to satisfy the interface, should be implemented by child classes
   * However, if we're here that means the invoked API doesn't implement it, so throw a 501
   *
   */
  public function doGet()
  {
    http_response_code(501);
    return;
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