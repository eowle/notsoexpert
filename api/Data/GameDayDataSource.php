<?php
/**
 * Class GameDayDataSource
 *
 * Data source for the GameDay API
 */
class GameDayDataSource extends DataSource
{
  protected $curl_helper;

  public function __construct()
  {
    parent::__construct();
    $this->curl_helper = new CurlHelper();
  }
  /**
   * Get all the datas
   *
   * @param int $week
   */
  public function getDataForWeek($week)
  {
    $results = $this->getResultsForWeek($week);
    return $results;
  }

  /**
   * Get results for the given week
   *
   * @param $week
   * @return array
   */
  public function getResultsForWeek($week)
  {
    $this->curl_helper->setUrl(Endpoints::getEndpoint(Endpoints::RESULTS), $week);
    $results = $this->curl_helper->get();
    return $results;
  }
}