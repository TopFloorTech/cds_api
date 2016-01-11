<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/9/2016
 * Time: 10:51 PM
 */

namespace TopFloor\Cds\CdsRequests;


class CategoryCdsRequest extends CdsRequest {
  protected $categoryId = 'root';

  public function setCategory($categoryId) {
    $this->categoryId = $categoryId;
  }

  public function getCategory() {
    return $this->categoryId;
  }

  public function getResource() {
    $config = $this->service->getConfig();
    $domain = $config->domain();
    $unitSystem = $config->unitSystem();

    $template = '/catalog3/service?o=category&d=%s&cid=%s&unit=%s';

    return sprintf($template, $domain, $this->getCategory(), $unitSystem);
  }
}
