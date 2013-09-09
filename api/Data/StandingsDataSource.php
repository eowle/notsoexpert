<?php
/**
 * Class StandingsDataSource
 *
 * Data provider for the Standings API
 */
class StandingsDataSource extends DataSource
{
  /**
   * Get the current season standings
   *
   * @return array
   */
  public function getStandings()
  {
    $proc = "CALL get_season_standings()";
    $this->db->prepare($proc);
    $standings = array();

    if($this->db->execute())
    {
      $this->db->bindResults(array('wins', 'losses', 'first_name'));
      $position = 1;
      while($this->db->fetch())
      {
        $standing = $this->db->createObjectFromResult();

        if($position === 1)
        {
          $top_wins = $standing->wins;
          $standing->games_back = 0;
        }
        else
        {
          $standing->games_back = $top_wins - $standing->wins;
        }

        array_push($standings, $standing);
        $position++;
      }
    }

    return $standings;
  }
}
