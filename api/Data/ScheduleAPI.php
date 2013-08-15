<?php
class ScheduleAPI extends NotSoExpertAPI
{
  public function __construct()
  {
    parent::__construct();
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

    $proc = "CALL get_schedule_by_week(?)";
    $this->db->prepare($proc);
    $this->db->bind('i', array($params['week']));

    if($this->db->execute())
    {
      $this->db->bindResults(array('game_id', 'home_team', 'away_team', 'game_time'));
      $schedule = array();

      while($this->db->fetch() !== null)
      {
        $schedule[] = $this->db->result;
      }
    }
  }
}