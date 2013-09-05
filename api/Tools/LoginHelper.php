<?php
/**
 * Class LoginHelper
 *
 * Helper methods dealing with logged in (and logging in) users
 */
class LoginHelper
{
  /**
   * Check to see if the provided user id matches the logged in user
   *
   * @param int $user_id
   * @return bool
   */
  public static function isLoggedInUser($user_id)
  {
    return ($user_id === (int)eSession::getInstance()->get('user_id'));
  }
}