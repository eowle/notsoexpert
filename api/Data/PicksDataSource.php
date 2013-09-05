<?php
/**
 * Class PicksDataSource
 *
 * Data layer for the Picks API
 */
class PicksDataSource extends DataSource
{
  /**
   * Get all picks for the given week
   *
   * @param int $week
   * @return array
   */
  public function getPicksByWeek($week)
  {
    $proc = "CALL get_picks_by_week_with_game_time(?)";
    $this->db->prepare($proc);
    $this->db->bind('i', array($week));

    if($this->db->execute())
    {
      $this->db->bindResults(array('game_time', 'game_id', 'user_id', 'pick'));
      $picks = array();

      while($this->db->fetch())
      {
        $result = $this->db->createObjectFromResult();

        if(strtotime($result->game_time) > time() && LoginHelper::isLoggedInUser($result->user_id) === false)
        {
          $result->pick = true;
        }

        array_push($picks, $result);
      }

      $this->db->close();
      return $picks;
    }

    return array("success" => false);
  }

  /**
   * Get picks for the given week and user id
   *
   * @param int $week
   * @param int $user_id
   * @return array
   */
  public function getPicksByWeekAndUserId($week, $user_id)
  {
    $proc = "CALL get_picks_by_week_and_user_with_game_time(?,?)";
    $this->db->prepare($proc);
    $this->db->bind('ii', array($week, $user_id));

    if($this->db->execute())
    {
      $this->db->bindResults(array('game_time', 'game_id', 'user_id', 'pick'));
      $picks = array();

      while($this->db->fetch())
      {
        $result = $this->db->createObjectFromResult();
        if(strtotime($result->game_time) > time() && LoginHelper::isLoggedInUser($user_id) === false)
        {

          $result->pick = true;
        }

        array_push($picks, $result);
      }

      $this->db->close();
      return $picks;
    }

    return array('success' => false);
  }

  /**
   * Set the pick for the given user_id and game_id
   *
   * @param int $user_id
   * @param int $game_id
   * @param string $pick
   * @return bool
   */
  public function setPickByUserIdAndGameId($user_id, $game_id, $pick)
  {
    $proc = "CALL add_or_update_pick(?,?,?)";
    $this->db->prepare($proc);
    $this->db->bind('iis', array($game_id, $user_id, $pick));
    $success = $this->db->execute();
    return $success;
  }

  /**
   * Given an integer user_id and array of picks formatted as [game_id: pick], add or update each
   *
   * @param int $user_id
   * @param array $picks
   * @return bool
   */
  public function setPicksByUserIdAndGameIds($user_id, array $picks)
  {
    $success = false;

    foreach($picks as $game_id => $pick)
    {
      $success = ($this->setPickByUserIdAndGameId($user_id, $game_id, $pick) === true) ? $success : false;
    }

    return $success;
  }
}