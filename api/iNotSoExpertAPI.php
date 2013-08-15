<?php
interface iNotSoExpertAPI
{
  public function doGet($params = null);
  public function doPost($params = null);
  public function doDelete();
  public function doPut();
}