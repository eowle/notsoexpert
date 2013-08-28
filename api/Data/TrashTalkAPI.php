<?php
/**
 * Class TrashTalkAPI
 *
 * API for retrieving and adding trash talk. Implements the GET and
 * POST methods.
 */
class TrashTalkAPI extends NotSoExpertAPI
{
  private $data_source;

  /**
   * Set up the data source
   */
  public function __construct()
  {
    parent::__construct();
    $this->data_source = new TrashTalkDataSource();
  }

  /**
   * Retrieve the TrashTalk for the given week.  Which should be
   * in the 'week' field of the $params array
   *
   * @param array $params
   * @return array
   */
  public function doGet($params = null)
  {
    if(!isset($params['week']))
    {
      return array();
    }

    $trash_talk = $this->data_source->getTrashTalkForWeek($params['week']);
    $response = array('week' => $params['week'],
                      'trash_talk' => $trash_talk);

    return $response;
  }

  /**
   * The POST method adds trash talk to the data source.
   * The $params array should have a 'message' and 'week' field.
   * All other data can be retrieved from session or generated
   * pre-insertion.
   *
   * @param array $params
   * @return array
   */
  public function doPost($params = null)
  {
    $user_id = $params['user_id'];

    if((int)$user_id > 0 && $this->validateMember($user_id))
    {
      $week = $params['week'];
      $message = TrashHelper::processMessage($params['message']);
      $result = $this->data_source->addTrashTalk($user_id, $week, $message);

      if($result)
      {
        return array('success' => true);
      }
      else
      {
        return array('success' => false, 'error_message' => "Error adding trash talk");
      }
    }
    else
    {
      return array('success' => false, 'error_message' => "Invalid user specified");
    }
  }
}