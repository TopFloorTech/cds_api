<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/9/2016
 * Time: 11:37 PM
 */

namespace TopFloor\Cds\CdsRequests;


abstract class CacheableCdsRequest extends CdsRequest {
  public function process() {
    $cacheKey = 'request-' . $this->getResource();

    $cache = $this->service->getCache();

    if ($cache->exists($cacheKey)) {
      $result = $cache->get($cacheKey);
    } else {
      $result = parent::process();

      $cache->set($cacheKey, $result);
    }

    return $result;
  }
}
