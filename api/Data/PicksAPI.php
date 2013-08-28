<?php
/**
 * Class PicksAPI
 *
 * Handles retrieving and setting picks.  Implements GET and POST methods.
 */
class PicksAPI extends NotSoExpertAPI
{
  /**
   * Instance of PicksDataSource
   *
   * @var PicksDataSource
   */
  private $data_source;

  /**
   * Set up our data source
   */
  public function __construct()
  {
    parent::__construct();
    $this->data_source = new PicksDataSource();
  }

  /**
   * @param array $params
   * @return array
   */
  public function doGet($params = null)
  {
    if(!isset($params['week']))
    {
      return array();
    }

    if($params['user_id'] === 'all')
    {
      $picks = $this->data_source->getPicksByWeek($params['week']);
    }
    else
    {
      $picks = $this->data_source->getPicksByWeekAndUserId($params['week'], $params['user_id']);
    }

    return $picks;
  }

  /**
   * Add/update picks
   *
   * The 'picks' field should be a json encoded object of <game_id>:<pick>, for example:
   *  {'1':'DEN', '2':'TEN',...}
   *
   * @param array $params
   * @return array
   */
  public function doPost($params = null)
  {
    if($this->validateMember($params['user_id']))
    {
      if(isset($params['user_id'], $params['picks']))
      {
        $picks = (array)json_decode($params['picks']);
        if($this->data_source->setPicksByUserIdAndGameIds($params['user_id'], $picks))
        {
          return array("success" => true);
        }

        return array("success" => false, "error" => "Unable to add picks to datasource");
      }
      else
      {
        return array("success" => false, "error" => "Invalid picks data provided");
      }
    }
    else
    {
      return array("success" => false, "error" => "Invalid member provided");
    }
  }
}