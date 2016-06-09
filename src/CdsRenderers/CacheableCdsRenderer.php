<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 2/3/2016
 * Time: 8:18 PM
 */

namespace TopFloor\Cds\CdsRenderers;

use TopFloor\Cds\CdsEntities\CdsEntity;

abstract class CacheableCdsRenderer extends CdsRenderer {
  public function render(CdsEntity $entity) {
    if (!$entity->shouldCache()) {
      return parent::render($entity);
    }

    $cache = $this->service->getCache();
    $cacheKey = $this->cacheKey($entity);

    if ($cache->exists($cacheKey)) {
      $output = $cache->get($cacheKey);
    } else {
      $output = parent::render($entity);
      $cache->set($cacheKey, $output);
    }

    return $output;
  }

  public function cacheKey(CdsEntity $entity) {
    $key = $entity->cacheKey();

    $className = get_class($this);

    $slashPos = strrpos($className, '\\');

    if ($slashPos !== FALSE) {
      $className = substr($className, $slashPos + 1);
    }

    $key .= "-$className";

    return $key;
  }
}
