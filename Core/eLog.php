<?php
/**
 * Class eLog
 *
 * Basic logging functionality, defines different logs for type of log
 */
class eLog 
{
  /**
   * Collection of log filepaths
   *
   * @var string
   */
  public static $LOGTYPE_DB = 'logs/eLog.db.log';
  public static $LOGTYPE_ERROR = 'logs/eLog.error.log';
  public static $LOGTYPE_INFO = 'logs/eLog.info.log';
  public static $LOGTYPE_PERFORMANCE = 'logs/eLog.performance.log';

  /**
   * Handle to the file we're writing to
   *
   * @var resource
   */
  private $log;

  /**
   * Array of timestamps used to track against
   *
   * @var array
   */
  private $startTime = array();

  /**
   * Array of logged data
   *
   * @var array
   */
  private $logData = array();

  /**
   * Simple date format string to be used
   * @var string
   */
  private $dateFormat = 'm/d/Y h:i:s A';

  /**
   * Set our timezone, get a handle to the logType we're using
   *
   * @param $logType
   */
  function __construct( $logType )
  {
    date_default_timezone_set( 'America/New_York' );
    $this->log = fopen( $logType, "a+" ); 
  }

  /**
   * Helper method to hold a DB connection start
   *
   */
  public function logConnectStart()
  {
    $this->startTime['dbConnect'] = microtime( true );
  }

  /**
   * Helper method to log a DB connection time
   */
  public function logConnectEnd()
  {
    fwrite( $this->log, date( $this->dateFormat ) . ' || Connection finished in ' . ( microtime( true ) - $this->startTime['dbConnect'] ) . " seconds\n" );
  }

  /**
   * Helper method to hold the start of a given $query
   *
   * @param $query
   */
  public function logQueryStart( $query )
  {
    $this->logData['query'] = $query;
    $this->startTime['queryExec'] = microtime( true );
  }

  /**
   * Helper method to log how long the set query took
   *
   */
  public function logQueryEnd( )
  {
    fwrite( $this->log, date( $this->dateFormat ) . " || Query: '" . $this->logData['query'] . "' finished in: " . ( microtime( true ) - $this->startTime['queryExec'] ) . " seconds\n" );
  }

  /**
   * Magic method to log any error received
   *
   */
  public function logError( )
  {
    $error_info = func_get_args();
    foreach( $error_info as $info )
    {
      fwrite( $this->log, date( $this->dateFormat ) .  " || ERROR: " . $info . "\n" );
    }
  }

  /**
   * Generic log functionality
   *
   */
  public function logMessage( )
  {
    $message_info = func_get_args( );

    foreach( $message_info as $message )
    {
      fwrite( $this->log, date( $this->dateFormat ). ' || MESSAGE: ' . $message . "\n" );
    }
  }
}
