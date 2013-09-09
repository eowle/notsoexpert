<?php
/**
 * Class MembersAPI
 *
 * API to get all members for the league, implements GET method
 */
class MembersAPI extends NotSoExpertAPI
{
  /**
   * @var MembersDataSource
   */
  private $data_source;

  /**
   * Build our data source yo
   */
  public function __construct()
  {
    parent::__construct();
    $this->data_source = new MembersDataSource();
  }

  /**
   * Get a list of public member fields and return them for output
   *
   * @param null $params
   * @return array
   */
  public function doGet($params = null)
  {
    return $this->data_source->getMembers();
  }
}