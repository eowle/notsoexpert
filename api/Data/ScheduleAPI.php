<?php
class ScheduleAPI extends NotSoExpertAPI
{
  private $data_source;

  public function __construct()
  {
    parent::__construct();
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