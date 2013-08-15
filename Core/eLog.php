<?php
class eLog 
{
  public static $LOGTYPE_DB = 'logs/eLog.db.log';
  public static $LOGTYPE_ERROR = 'logs/eLog.error.log';
  public static $LOGTYPE_INFO = 'logs/eLog.info.log';
  public static $LOGTYPE_PERFORMANCE = 'logs/eLog.performance.log';
  private $log, $startTime = array(), $endTime;
  private $logData = array();
  private $dateFormat = 'm/d/Y h:i:s A';
  function __construct( $logType )
  {
    date_default_timezone_set( 'America/New_York' );
    $this->log = fopen( $logType, "a+" ); 
  }

  public function logConnectStart()
  {
    $this->startTime['dbConnect'] = microtime( true );
  }

  public function logConnectEnd()
  {
    fwrite( $this->log, date( $this->dateFormat ) . ' || Connection finished in ' . ( microtime( true ) - $this->startTime['dbConnect'] ) . " seconds\n" );
  }

  public function logQueryStart( $query )
  {
    $this->logData['query'] = $query;
    $this->startTime['queryExec'] = microtime( true );
  }

  public function logQueryEnd( )
  {
    fwrite( $this->log, date( $this->dateFormat ) . " || Query: '" . $this->logData['query'] . "' finished in: " . ( microtime( true ) - $this->startTime['queryExec'] ) . " seconds\n" );
  }

  public function logError( )
  {
    $error_info = func_get_args();
    foreach( $error_info as $info )
    {
      fwrite( $this->log, date( $this->dateFormat ) .  " || ERROR: " . $info . "\n" );
    }
  }

  public function logMessage( )
  {
    $message_info = func_get_args( );

    foreach( $message_info as $message )
    {
      fwrite( $this->log, date( $this->dateFormat ). ' || MESSAGE: ' . $message . "\n" );
    }
  }
}
?>
