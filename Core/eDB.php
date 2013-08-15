<?php
class eDB
{
  private static $dbConfig = array (
      'username' => __DB_USER,
      'password' => __DB_PASS,
      'host' => __DB_HOST,
      'name' => __DB_NAME
  );

  protected $query = array();
  protected $eLog;
	protected $stmt; 
	public $result;

  function __construct()
  {
    $this->eLog = new eLog( eLog::$LOGTYPE_DB );
    $this->eLog->logConnectStart();
    $this->conn = new mysqli( self::$dbConfig['host'], self::$dbConfig['username'], self::$dbConfig['password'], self::$dbConfig['name'] );
    $this->eLog->logConnectEnd();
  }

  public static function getInstance()
  {
    static $instance = null;

    if($instance === null)
    {
      $instance = new eDB();
    }

    return $instance;
  }

  function prepare( $qry )
  {
  	$this->stmt = $this->conn->prepare( $qry );
  	if( $this->stmt == false )
    {
      $this->eLog->logError( "Unable to prepare query: " . $qry );
  	}
  }

  function bind($typelist, $values)
  {
    $toBind = array($typelist);

    foreach($values as $k => $v)
    {
      $toBind[] = &$values[$k];
    }

    call_user_func_array(array($this->stmt, "bind_param"), $toBind);
  }
  
  function execute()
  {
  	return $this->stmt->execute() or die( $this->stmt->error );
  }
  
  function bindResults( $varNames )
  {
  	$this->result = new stdclass;
  	$resultArray = array();
  	foreach( $varNames as $var )
  	{
  		$resultArray[] =& $this->result->$var;
  	}
  	call_user_func_array( array( $this->stmt, 'bind_result' ), $resultArray );
  }
  
  function fetch()
  {
  	$fetchResult = $this->stmt->fetch();
  	return $fetchResult;
  }

  
  function close()
  {
  	$this->stmt->close();
  	while( $this->conn->next_result() ) {}	
  }
}
