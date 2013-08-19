<?php
/**
 * Class ResultsDataSource
 *
 * Data provider for ResultsAPI
 */
class ResultsDataSource extends DataSource
{
  /**
   * Get the game winners for the given week.
   *
   * @param int $week
   * @return array [[game_id: int, winner: string],...]
   */
  public function getResultsForWeek($week)
  {
    $qry = "CALL get_results_by_week(?)";
    $this->db->prepare($qry);
    $this->db->bind('i', array($week));

    if($this->db->execute())
    {
      $this->db->bindResults(array('game_id', 'winner'));
      $results = array();

      while($this->db->fetch())
      {
        $result = $this->db->createObjectFromResult();
        array_push($results, $result);
      }

      $this->db->close();
    }

    return $results;
  }
}