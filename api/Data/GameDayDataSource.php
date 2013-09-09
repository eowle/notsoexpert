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
   * TODO: Create StandingsAPI and insert it here
   *
   * @param int $week
   * @return array
   */
  public function getDataForWeek($week)
  {
    $return = array();

    $request_start = microtime(true);
    $results = $this->getResultsForWeek($week);
    $members = $this->getMembers();
    $picks = $this->getPicksForWeek($week);
    $trash_talk = $this->getTrashTalkForWeek($week);
    $schedule = $this->getScheduleForWeek($week);
    $standings = $this->getStandings();
    $request_end = microtime(true);

    $assembly_start = microtime(true);
    $return['week'] = $week;
    $return['members'] = $this->mergeMemberPicksData($members, $picks->picks, $results->results);
    $return['schedule'] = $schedule->schedule;
    $return['trash_talk'] = $trash_talk->trash_talk;
    $return['standings'] = $standings;
    $assembly_end = microtime(true);

    $return['request_time'] = ($request_end - $request_start) * 1000;
    $return['assembly_time'] = ($assembly_end - $assembly_start) * 1000;
    return $return;
  }

  /**
   * Merge the Member's data with Schedule, Picks, And Results
   *
   * @param array $members
   * @param array $picks
   * @param array $results
   * @return array
   */
  public function mergeMemberPicksData($members, $picks, $results)
  {
    $members = $this->keyArrayByField($members, 'id');
    $results = $this->keyArrayByField($results, 'game_id');

    foreach($picks as $pick)
    {
      if(!isset($members[$pick->user_id]->picks))
      {
        $members[$pick->user_id]->picks = array();
        $members[$pick->user_id]->record = array('wins' => 0, 'losses' => 0);
      }

      $game_finished = isset($results[$pick->game_id]);
      $is_win = $game_finished && ($pick->pick === $results[$pick->game_id]->winner);

      $members[$pick->user_id]->picks[$pick->game_id] = array(
        'pick' => $pick->pick,
        'game_finished' => $game_finished,
        'win' => $is_win
      );

      if($game_finished)
      {
        if($is_win)
        {
          $members[$pick->user_id]->record['wins']++;
        }
        else
        {
          $members[$pick->user_id]->record['losses']++;
        }
      }
    }

    return $members;
  }

  /**
   * Re-key an array so that the given field is now the index of each entry
   *
   * @param array $arr
   * @param string $field
   * @return array
   */
  protected function keyArrayByField(array $arr, $field)
  {
    $keyed = array();

    foreach($arr as $item)
    {
      $keyed[$item->$field] = $item;
    }

    return $keyed;
  }

  /**
   * Get the league standings
   *
   * @return array
   */
  public function getStandings()
  {
    $this->curl_helper->setUrl(Endpoints::getEndpoint(Endpoints::STANDINGS));
    $standings = $this->curl_helper->get();
    return $standings;
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

  /**
   * Get the list of members
   *
   * @return array
   */
  public function getMembers()
  {
    $this->curl_helper->setUrl(Endpoints::getEndpoint(Endpoints::MEMBERS));
    $members = $this->curl_helper->get();
    return $members;
  }

  /**
   * Get this week's picks
   *
   * @param int $week
   * @return array
   */
  public function getPicksForWeek($week)
  {
    $this->curl_helper->setUrl(Endpoints::getEndpoint(Endpoints::ALL_PICKS), $week);
    $picks = $this->curl_helper->get();
    return $picks;
  }

  /**
   * Get this week's trash talk
   *
   * @param int $week
   * @return array
   */
  public function getTrashTalkForWeek($week)
  {
    $this->curl_helper->setUrl(Endpoints::getEndpoint(Endpoints::TRASH_TALK), $week);
    $trash = $this->curl_helper->get();
    return $trash;
  }

  /**
   * Get the schedule for this week
   *
   * @param int $week
   * @return array
   */
  public function getScheduleForWeek($week)
  {
    $this->curl_helper->setUrl(Endpoints::getEndpoint(Endpoints::SCHEDULE), $week);
    $schedule = $this->curl_helper->get();
    return $schedule;
  }
}
