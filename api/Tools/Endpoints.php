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
  const BASE_URL = 'http://www.notsoexpert.com/api/';
  const ALL_PICKS = 'all_picks';
  const PICKS_FOR_USER = 'picks_for_user';
  const SCHEDULE = 'schedule';
  const TRASH_TALK = 'trash_talk';
  const RESULTS = 'results';
  const MEMBERS = 'members';

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
    self::MEMBERS => 'members'
  );

  /**
   * Get the sprintf-formatted URL for an enpoint
   *
   * @param string $endpoint - should be accessed by one of the above constants Endpoints::ALL_PICKS
   * @param string $base_url - can be overridden for testing purposes, but defaults to the above constant
   * @return string - sprintf formatted URL
   */
  public static function getEndpoint($endpoint, $base_url = self::BASE_URL)
  {
    return $base_url . self::$endpoint_urls[$endpoint];
  }
}