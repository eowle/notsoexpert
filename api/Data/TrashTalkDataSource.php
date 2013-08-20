<?php
/**
 * Class TrashTalkDataSource
 *
 * Data provider for the TrashTalk API
 */
class TrashTalkDataSource extends DataSource
{
  /**
   * Get the trash talk for the given week
   *
   * @param int $week
   * @return array
   */
  public function getTrashTalkForWeek($week)
  {
    $proc = "CALL get_trash_talk(?)";
    $this->db->prepare($proc);
    $this->db->bind('i', array($week));

    if($this->db->execute())
    {
      $this->db->bindResults(array('trash_id',
                                   'name',
                                   'message',
                                   'post_time',
                                   'user_id'));

      $trash_talk = array();

      while($this->db->fetch())
      {
        $trash = $this->db->createObjectFromResult();
        array_push($trash_talk, $trash);
      }

      $this->db->close();
    }

    return $trash_talk;
  }

  public function addTrashTalk($user_id, $week, $message)
  {
    $proc = "CALL add_trash_talk_by_id(?,?,?)";
    $this->db->prepare($proc);
    $this->db->bind('iis', array($user_id, $week, $message));
    return $this->db->execute();
  }
}