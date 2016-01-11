<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/9/2016
 * Time: 11:05 PM
 */

namespace TopFloor\Cds\CdsConfigs;


interface CdsConfigInterface {
  public function get($key);

  public function host();

  public function domain();

  public function unitSystem();

  public function cdsPath();
}