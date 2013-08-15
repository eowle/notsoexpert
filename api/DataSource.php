<?php
class DataSource
{
  protected $db;

  public function __construct()
  {
    $this->db = eDB::getInstance();
  }
}