<?php
/**
 * Class eSession
 *
 * Simple PHP Session wrapper
 */
class eSession
{
  /**
   * Can't do shit without session if it ain't started
   */
  function __construct( )
  {
    session_start();
  }

  /**
   * Singleton implementation
   *
   * @return eSession
   */
  public static function getInstance( )
  {
    static $instance = null;

    if ( $instance === null )
    {
      $instance = new eSession( );
    }

    return $instance;
  }

  /**
   * Set the given $varName to $value in session, and return it to ensure that it gets set successfully
   *
   * @param string $varName
   * @param mixed $value
   * @return mixed
   */
  function set( $varName, $value )
  {
    $_SESSION[ $varName ] = $value;
    return $_SESSION[ $varName ];
  }

  /**
   * Get the given $varName from session
   *
   * @param string $varName
   * @return mixed
   */
  function get( $varName )
  {
    return $_SESSION[ $varName ];
  }

  /**
   * Increment a counter in session and return its new value
   *
   * @param string $varName
   * @param int $count
   * @return mixed
   */
  function increment( $varName, $count = 1 )
  {
    $_SESSION[ $varName ] += $count;
    return $_SESSION[ $varName ];
  }

}
