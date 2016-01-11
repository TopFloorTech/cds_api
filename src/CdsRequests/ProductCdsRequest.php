<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/9/2016
 * Time: 10:51 PM
 */

namespace TopFloor\Cds\CdsRequests;


class ProductCdsRequest extends CdsRequest {
  protected $productId;
  protected $categoryId = null;

  public function setCategory($categoryId) {
    $this->categoryId = $categoryId;
  }

  public function getCategory() {
    return $this->categoryId;
  }

  public function setProduct($productId) {
    $this->productId = $productId;
  }

  public function getProduct() {
    return $this->productId;
  }

  public function getResource() {
    $config = $this->service->getConfig();
    $domain = $config->domain();
    $category = $this->getCategory();
    $unitSystem = $config->unitSystem();

    $template = '/catalog3/service?o=product&d=%s&id=%s&unit=%s';
    if (!is_null($category)) {
      $template .= sprintf('&cid=%s', $category);
    }

    return sprintf($template, $domain, $this->getProduct(), $unitSystem);
  }
}