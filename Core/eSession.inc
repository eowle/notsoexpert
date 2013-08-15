<?php
class eSession
{
  function __construct( )
  {
  }

  public static function getInstance( )
  {
    static $instance = null;

    if ( $instance === null )
    {
      $instance = new eSession( );
    }

    return $instance;
  }

  function set( $varName, $value )
  {
    $_SESSION[ $varName ] = $value;
    return $_SESSION[ $varName ];
  }

  function get( $varName )
  {
    return $_SESSION[ $varName ];
  }

  function increment( $varName, $count = 1 )
  {
    $_SESSION[ $varName ] += $count;
    return $_SESSION[ $varName ];
  }

}
