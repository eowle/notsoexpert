<?php
/**
 * Class ProfileDataSource
 *
 * Data provider for ProfileAPI
 */
class ProfileDataSource extends DataSource
{
  /**
   * Image upload directory
   */
  CONST IMAGE_UPLOAD_DIR = '/var/app/current/Assets/images/user_images/';

  /**
   * Allowed image types
   *
   * @var array
   */
  private static $valid_image_types = array( 'image/jpeg' => '.jpg',
                                    'image/gif' => '.gif',
                                    'image/png' => '.png',
                                    'image/bmp' => '.bmp' );
  /**
   * Get the requested user info for the given user id
   *
   * @param int $user_id
   * @param array|string $fields
   * @return array
   */
  public function getProfileData($user_id, $fields)
  {
    $proc = "CALL get_user_info(?)";
    $this->db->prepare($proc);
    $this->db->bind('i', array($user_id));
    if($this->db->execute())
    {
      $this->db->bindResults(array('password', 'email', 'image', 'first_name'));
      $this->db->fetch();
      $profile_data = $this->db->createObjectFromResult();
      unset($profile_data->password);
      $trimmed_data = array();

      if($fields === 'all')
      {
        $trimmed_data = (array)$profile_data;
      }
      else
      {
        foreach($fields as $field)
        {
          $trimmed_data[$field] = $profile_data->$field;
        }
      }

      return $trimmed_data;
    }
  }

  /**
   * Update a user's first name and email address
   *
   * @param int $user_id
   * @param string $first_name
   * @param string $email
   * @return bool
   */
  public function updateUserNameAndEmail($user_id, $first_name, $email)
  {
    $proc = "CALL update_user_profile(?,?,?)";
    $this->db->prepare($proc);
    $this->db->bind('iss', array($user_id, $email, $first_name));
    $result = $this->db->execute();
    $this->db->close();
    return $result;
  }

  /**
   * Update a user's image
   *
   * @param int $user_id
   * @param array $user_image
   * @return bool
   */
  public function updateUserImage($user_id, $user_image)
  {
    $result = false;
    if(array_key_exists($user_image['type'], self::$valid_image_types))
    {
      $current_user_images = glob(self::IMAGE_UPLOAD_DIR . $user_id . '-*');
      if(!empty($current_user_images))
      {
        foreach($current_user_images as $current_user_image)
        {
          unlink($current_user_image);
        }
      }

      $new_filename = $user_id . '-' . time() . '.' . self::$valid_image_types[$user_image['type']];
      $file_path = self::IMAGE_UPLOAD_DIR . $new_filename;
      if(move_uploaded_file($user_image['tmp_name'], $file_path))
      {
        $proc = "CALL update_user_image(?,?)";
        $this->db->prepare($proc);
        $this->db->bind('is', array($user_id, $new_filename));
        $result = $this->db->execute();
        $this->db->close();
      }
    }
    return $result;
  }

  /**
   * Update a user's password
   *
   * @param int $user_id
   * @param string $old_password
   * @param string $new_password
   * @return bool
   */
  public function updateUserPassword($user_id, $old_password, $new_password)
  {
    $result = false;
    $proc = "CALL get_user_password(?)";
    $this->db->prepare($proc);
    $this->db->bind('i', array($user_id));
    if($this->db->execute())
    {
      $this->db->bindResults(array('pass_hash'));
      $this->db->fetch();
      $data = $this->db->createObjectFromResult();
      $this->db->close();
      if(hash('sha512', $old_password . __SALT, false) === $data->pass_hash && strlen($new_password) > 4)
      {
        $new_pass_hash = hash('sha512', $new_password . __SALT, false);
        $proc = "CALL update_pass_hash(?,?)";
        $this->db->prepare($proc);
        $this->db->bind('is', array($user_id, $new_pass_hash));
        $result = $this->db->execute();
      }
    }
    return $result;
  }
}