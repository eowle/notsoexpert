<?php
/**
 * Class ScheduleDataSource
 *
 * Data source layer for the Schedule API
 */
class ScheduleDataSource extends DataSource
{
  /**
   * Get the schedule for the given $week
   *
   * @param int $week
   * @return array [[game_id: int, home_team: string, away_team: string, game_time: string], ...]
   */
  public function getScheduleForWeek($week)
  {
    $proc = "CALL get_schedule_by_week(?)";
    $this->db->prepare($proc);
    $this->db->bind('i', array($week));

    if($this->db->execute())
    {
      $this->db->bindResults(array('game_id', 'home_team', 'away_team', 'game_time'));
      $schedule = array();

      while($this->db->fetch())
      {
        $result = $this->db->createObjectFromResult();
        array_push($schedule, $result);
      }
    }

    return $schedule;
  }
}