<?php
/**
 * Class ProfileAPI
 *
 * API that handles retrieving, updating, and creating profiles.
 * Implements POST and GET methods
 */
class ProfileAPI extends NotSoExpertAPI
{
  private static $valid_fields = array('first_name', 'email', 'image');

  /**
   * Profile Data Provider
   *
   * @var ProfileDataSource
   */
  private $data_source;

  /**
   * Set up our data source
   */
  public function __construct()
  {
    parent::__construct();
    $this->data_source = new ProfileDataSource();
  }

  /**
   * Get the requested fields for the given user
   *
   * @param array $params
   * @return array
   */
  public function doGet($params = null)
  {
    $user_id = (int)$params['user_id'];

    if($user_id > 0)
    {
      if(isset($params['fields']))
      {
        $fields = $this->validateFields(explode(',', $params['fields']));
        $valid_fields = $fields['valid_fields'];
        $invalid_fields = array('invalid_fields' => implode(',', $fields['invalid_fields']));
      }
      else
      {
        $valid_fields = 'all';
        $invalid_fields = array('invalid_fields' => '');
      }

      $profile_data = $this->data_source->getProfileData($user_id, $valid_fields);
      return array_merge($profile_data, $invalid_fields);
    }

    return array('error' => 'Invalid member supplied');
  }

  /**
   *
   * @param array $params
   * @return array
   */
  public function doPost($params = null)
  {
    $user_id = (int)$params['user_id'];

    if($user_id > 0 && $this->validateMember($user_id))
    {
      $email = $params['email'];
      $first_name = $params['first_name'];
      $this->data_source->updateUserNameAndEmail($user_id, $email, $first_name);

      if(isset($params['user_image']))
      {
        $this->data_source->updateUserImage($user_id, $params['user_image']);
      }

      //TODO: Add password update here
    }

    return array('error' => 'Invalid member supplied');
  }

  /**
   * Validate the requested fields, remove any invalid fields requested
   *
   * @param array $fields
   * @return array
   */
  protected function validateFields(array $fields)
  {
    $validated_fields = array('valid_fields' => array(), 'invalid_fields' => array());

    foreach($fields as $index => $field)
    {
      if(!in_array($field, self::$valid_fields))
      {
        array_push($validated_fields['invalid_fields'], $field);
      }
      else
      {
        array_push($validated_fields['valid_fields'], $field);
      }
    }

    return $validated_fields;
  }
}