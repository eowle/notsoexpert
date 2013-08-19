<?php
/**
 * Class ResultsAPI
 *
 * API for retrieving game results for a given week.  Only implements
 * the GET method
 */
class ResultsAPI extends NotSoExpertAPI
{
  private $data_source;

  /**
   * Build our data provider
   */
  public function __construct()
  {
    $this->data_source = new ResultsDataSource();
  }

  /**
   * Process GET request, retrieve the results for the given week.
   * Should be in the 'week' field of the params array
   *
   * @param array $params
   * @return array
   */
  public function doGet($params = null)
  {
    if(!isset($params['week']))
    {
      return array();
    }

    $results = $this->data_source->getResultsForWeek($params['week']);
    $response = array("week" => $params['week'],
                      "results" => $results);

    return $response;
  }
}