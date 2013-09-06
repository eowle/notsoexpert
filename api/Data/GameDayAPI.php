<?php
/**
 * Class GameDayAPI
 *
 * Aggregate all the data necessary to build the main view of the application.
 * Only implements the GET method, and expects a week.
 */
class GameDayAPI extends NotSoExpertAPI
{
  /**
   * @var GameDayDataSource
   */
  private $data_source;

  /**
   * Build out dat datasource
   */
  public function __construct()
  {
    parent::__construct();
    $this->data_source = new GameDayDataSource();
  }

  /**
   * GET Implementation, kick off our data source processes, and munge the data
   *
   * @param array $params
   * @return array
   */
  public function doGet($params = null)
  {
    $return = array();

    if(isset($params['week']))
    {
      $return = $this->data_source->getDataForWeek($params['week']);
    }

    return $return;
  }
}