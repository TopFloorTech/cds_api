<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/10/2016
 * Time: 12:05 AM
 */

namespace TopFloor\Cds\SelectOptions;


abstract class CacheableCdsSelectOptions extends CdsSelectOptions {
  public function getOptions() {
    $cache = $this->service->getCache();

    $cacheKey = $this->getCacheKey();

    if ($cache->exists($cacheKey)) {
      $options = $cache->get($cacheKey);
    } else {
      $options = parent::getOptions();

      $cache->set($cacheKey, $options);
    }

    return $options;
  }

  public abstract function getCacheKey();
}
