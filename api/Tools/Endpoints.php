<?php
/**
 * Class Endpoints
 *
 * An enumeration of available endpoints
 */
class Endpoints
{
  /**
   * Constants for easy access
   */
  const BASE_URL = 'http://localhost/notsoexpert/api/';
  const ALL_PICKS = 'all_picks';
  const PICKS_FOR_USER = 'picks_for_user';
  const SCHEDULE = 'schedule';
  const TRASH_TALK = 'trash_talk';
  const RESULTS = 'results';
  const MEMBERS = 'members';
  const STANDINGS = 'standings';

  /**
   * Array of said constants => endpoint URIs
   *
   * @var array
   */
  protected static $endpoint_urls = array(
    self::ALL_PICKS => 'picks/%d',
    self::PICKS_FOR_USER => 'picks/%d?user_id=%d',
    self::SCHEDULE => 'schedule/%d',
    self::TRASH_TALK => 'trashtalk/%d',
    self::RESULTS => 'results/%d',
    self::MEMBERS => 'members',
    self::STANDINGS => 'standings'
  );

  /**
   * Get the sprintf-formatted URL for an enpoint
   *
   * @return string - sprintf formatted URL
   */
  public static function getEndpoint()
  {
    $params = func_get_args();
    $endpoint = self::$endpoint_urls[array_shift($params)];
    if(count($params) > 0)
    {
      $endpoint = vsprintf($endpoint, $params);
    }
    return self::BASE_URL . $endpoint;
  }
}