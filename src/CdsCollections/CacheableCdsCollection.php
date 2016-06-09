<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/10/2016
 * Time: 12:05 AM
 */

namespace TopFloor\Cds\CdsCollections;


abstract class CacheableCdsCollection extends CdsCollection {
  protected $permanent = false;

  protected $cache = true;

  public function getItems() {
    $cache = $this->service->getCache();

    $cacheKey = $this->getCacheKey();

    if ($this->cache && $cache->exists($cacheKey, $this->permanent)) {
      $options = $cache->get($cacheKey, $this->permanent);
    } else {
      $options = parent::getItems();

      if ($this->cache && !empty($options)) {
        $cache->set($cacheKey, $options, $this->permanent);
      }
    }

    return $options;
  }

  public abstract function getCacheKey();
}
