<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/9/2016
 * Time: 11:08 PM
 */

namespace TopFloor\Cds\CdsConfigs;


abstract class CdsConfig implements CdsConfigInterface {

  public abstract function get($key);

  public function cdsPath() {
    $cdsPath = $this->get('cdsPath');

    if (is_null($cdsPath)) {
      $cdsPath = dirname(dirname(__FILE__)) . '/cds/';
    } elseif (substr($cdsPath, -1) !== '/') {
      $cdsPath .= '/';
    }

    return $cdsPath;
  }

  public function host() {
    return $this->get('cdsHost');
  }

  public function domain() {
    return $this->get('cdsDomain');
  }

  public function unitSystem() {
    return $this->get('unitSystem');
  }
}
