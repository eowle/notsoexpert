<?php
/**
 * Class MembersDataSource
 *
 * Datasource to provide all members for the league
 */
class MembersDataSource extends DataSource
{
  /**
   * Get all members for the league
   *
   * @return array
   */
  public function getMembers()
  {
    $proc = "CALL get_league_players()";
    $this->db->prepare($proc);
    $members = array();

    if($this->db->execute())
    {
      $this->db->bindResults(array('id', 'username', 'email', 'image', 'first_name'));

      while($this->db->fetch())
      {
        $member = $this->db->createObjectFromResult();
        unset($member->email, $member->username);
        array_push($members, $member);
      }
    }

    $this->db->close();

    return $members;
  }
}