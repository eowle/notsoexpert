<?php
/**
 * Class StandingsAPI
 *
 * API to retrieve current standings
 */
class StandingsAPI extends NotSoExpertAPI
{
  /**
   * @var StandingsDataSource
   */
  private $data_source;

  /**
   * Build the data source
   */
  public function __construct()
  {
    $this->data_source = new StandingsDataSource();
  }
  /**
   * Get the current season standings
   *
   * @param null $params
   * @return array
   */
  public function doGet($params = null)
  {
    return $this->data_source->getStandings();
  }
}
