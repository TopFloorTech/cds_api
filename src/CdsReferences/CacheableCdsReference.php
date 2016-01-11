<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/10/2016
 * Time: 1:14 AM
 */

namespace TopFloor\Cds\CdsReferences;


abstract class CacheableCdsReference extends CdsReference {
  public function getUrl() {
    $cache = $this->service->getCache();

    if ($cache->exists($this->cacheKey('url'))) {
      $url = $cache->get($this->cacheKey('url'));
    } else {
      $url = $this->_getUrl();
    }

    return $url;
  }

  protected function cacheKey($key) {
    $cacheKey = 'reference-' . $this->cdsId . '-' . $key;

    return $cacheKey;
  }

  public function getLabel() {
    $cache = $this->service->getCache();

    if ($cache->exists($this->cacheKey('label'))) {
      $label = $cache->get($this->cacheKey('label'));
    } else {
      $label = $this->_getLabel();
    }

    return $label;
  }

  public function hasLink() {
    return ($this->getUrl());
  }

  public function render() {
    $cache = $this->service->getCache();

    if ($cache->exists($this->cacheKey('rendered'))) {
      $rendered = $cache->get($this->cacheKey('rendered'));
    } else {
      $rendered = $this->_render();
    }

    return $rendered;
  }

  protected abstract function _render();

  protected abstract function _getUrl();

  protected abstract function _getLabel();
}