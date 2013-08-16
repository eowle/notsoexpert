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
}