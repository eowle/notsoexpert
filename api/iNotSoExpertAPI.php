<?php
/**
 * Interface iNotSoExpertAPI
 *
 * Definition of our API.  do* functions should map to their respective HTTP Verbs
 */
interface iNotSoExpertAPI
{
  public function doGet($params = null);
  public function doPost($params = null);
  public function doDelete();
  public function doPut();
}