<?php
/**
 * Class LoginDataSource
 *
 * Provides data layer access for the LoginAPI
 */
class LoginDataSource extends DataSource
{
  /**
   * Attempt to find the user given a username and password
   *
   * @param string $username
   * @param string $password
   * @return int
   */
  public function getUserIdFromUsernameAndPassword($username, $password)
  {
    $user_id = 0;
    $pass_hash = HashHelper::hash($password);
    $proc = "CALL get_userid_from_username_and_hash(?, ?)";
    $this->db->prepare($proc);
    $this->db->bind('ss', array($username, $pass_hash));

    if($this->db->execute())
    {
      $this->db->bindResults(array('user_id', 'first_name'));
      $this->db->fetch();
      $result = $this->db->createObjectFromResult();
      $user_id = (int)$result->user_id;
    }

    $this->db->close();
    return $user_id;
  }
}