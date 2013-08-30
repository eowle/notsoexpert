<?php
/**
 * Class HashHelper
 *
 * I got tired of typing out the entire hash() function
 */
class HashHelper
{
  /**
   * Get the salted SHA512 encrypted version of a string
   *
   * @param string $data
   * @return string
   */
  public static function hash($data)
  {
    return hash('sha512', $data . __SALT, false);
  }
}