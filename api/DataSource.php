<?php
/**
 * Class DataSource
 *
 * Base class for the DataSource objects for each API, provides low level support to any available
 * data source.
 */
class DataSource
{
  /**
   * eDB instance
   *
   * @var eDB
   */
  protected $db;

  /**
   * Set up any available data sources
   */
  public function __construct()
  {
    $this->db = eDB::getInstance();
  }

  /**
   * Get an instance of the requested datasource
   *
   */
  public static function getInstance()
  {
    static $instance = array();

    $requested_class = get_called_class();

    if($instance[$requested_class] === null)
    {
      $instance[$requested_class] = new $requested_class();
    }

    return $instance[$requested_class];
  }
}