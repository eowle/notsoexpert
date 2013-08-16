<?php
/**
 * Class ScheduleAPI
 *
 * API for retrieving the schedule for a given week.  Only implements the GET method.
 */
class ScheduleAPI extends NotSoExpertAPI
{
  /**
   * Schedule data provider
   *
   * @var ScheduleDataSource
   */
  private $data_source;

  /**
   * Build our data provider
   *
   */
  public function __construct()
  {
    $this->data_source = new ScheduleDataSource();
  }

  /**
   * Process the get request against the schedule api.  $params should have a 'week' field
   * that designates what week we'll be returning the schedule for.
   *
   * @param array|null $params
   * @return array
   */
  public function doGet($params = null)
  {
    if(!isset($params['week']))
    {
      return array();
    }

    $schedule = $this->data_source->getScheduleForWeek($params['week']);
    $response = array("week" => $params['week'], "schedule" => $schedule);

    return $response;
  }
}