<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/9/2016
 * Time: 10:51 PM
 */

namespace TopFloor\Cds\CdsRequests;


class CategoryCdsRequest extends CacheableCdsRequest {
  protected $categoryId = null;

  public function setCategory($categoryId) {
    $this->categoryId = $categoryId;
  }

  public function getCategory() {
    return $this->categoryId;
  }

  public function getResource() {
    $config = $this->service->getConfig();
    $domain = $config->domain();
    $unitSystem = $this->service->getUrlHandler()->getUnitSystem();

    $template = '/catalog3/service?o=category&d=%s&unit=%s';

    $output = sprintf($template, $domain, $unitSystem);

    $categoryId = $this->getCategory();

    if (!is_null($categoryId)) {
      $output .= '&cid=' . $categoryId;
    }

    return $output;
  }
}
