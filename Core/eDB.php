<?php
require_once('../config/Config.db.php');

/**
 * Class eDB
 *
 * Wrapper class for PHP's mysqli implementation
 */
class eDB
{
  /**
   * Database connection config, pulled in from Config.db.php
   *
   * @var array
   */
  private static $dbConfig = array (
      'username' => __DB_USER,
      'password' => __DB_PASS,
      'host' => __DB_HOST,
      'name' => __DB_NAME
  );

  /**
   * Don't know what this is used for
   *
   * TODO: Remove?
   * @var array
   */
  protected $query = array();

  /**
   * Instance of logging wrapper
   *
   * @var eLog
   */
  protected $eLog;

  /**
   * Currently active mysqli statement
   *
   * @var mysqli_stmt
   */
  protected $stmt;

  /**
   * Result fetched from the database
   *
   * @var mixed
   */
  public $result;

  /**
   * Instantiate logging, create database connection
   */
  function __construct()
  {
    $this->eLog = new eLog( eLog::$LOGTYPE_DB );
    $this->eLog->logConnectStart();
    $this->conn = new mysqli( self::$dbConfig['host'], self::$dbConfig['username'], self::$dbConfig['password'], self::$dbConfig['name'] );
    $this->eLog->logConnectEnd();
  }

  /**
   * Singleton implementation
   *
   * @return eDB
   */
  public static function getInstance()
  {
    static $instance = null;

    if($instance === null)
    {
      $instance = new eDB();
    }

    return $instance;
  }

  /**
   * Prepare a query for execution.
   *
   * @param string $qry
   */
  function prepare( $qry )
  {
  	$this->stmt = $this->conn->prepare( $qry );
  	if( $this->stmt == false )
    {
      $this->eLog->logError( "Unable to prepare query: " . $qry );
  	}
  }

  /**
   * Bind values (sprintf style) to a prepared query
   *
   * @param string $typelist
   * @param array $values
   */
  function bind($typelist, $values)
  {
    $toBind = array($typelist);

    foreach($values as $k => $v)
    {
      $toBind[] = &$values[$k];
    }

    call_user_func_array(array($this->stmt, "bind_param"), $toBind);
  }

  /**
   * Execute the current statement
   *
   * @return bool
   */
  function execute()
  {
  	return $this->stmt->execute() or die( $this->stmt->error );
  }

  /**
   * Bind the results fetched to the database to elements of $this->result
   *
   * @param array $varNames
   */
  function bindResults( $varNames )
  {
  	$this->result = new stdclass;
  	$resultArray = array();
  	foreach( $varNames as $var )
  	{
      $resultArray[] = &$this->result->$var;
  	}

  	call_user_func_array( array( $this->stmt, 'bind_result' ), $resultArray );
  }

  /**
   * Fetch the next available row from our result
   *
   * @return bool
   */
  function fetch()
  {
  	$fetchResult = $this->stmt->fetch();
  	return $fetchResult;
  }

  /**
   * Close the statement.  mysqli is unhappy if there are results that you haven't read yet
   * before trying to execute another query
   *
   */
  function close()
  {
  	$this->stmt->close();
  	while( $this->conn->next_result() ) {}	
  }

  /**
   * Helper method to create an as-value object for consumption.  Typically, the fetch results are returned as
   * a by-reference object, which makes it harder to deal with.
   *
   * @return stdClass
   */
  function createObjectFromResult()
  {
    $temp = new stdClass();

    foreach($this->result as $k => $v)
    {
      $temp->$k = $v;
    }

    return $temp;
  }
}
