<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/9/2016
 * Time: 10:51 PM
 */

namespace TopFloor\Cds\CdsRequests;


class ProductsCdsRequest extends CdsRequest {
  protected $categoryId = 'root';

  protected $productsPerPage = 15;

  protected $page = 0;

  public function setCategory($categoryId) {
    $this->categoryId = $categoryId;
  }

  public function getCategory() {
    return $this->categoryId;
  }

  public function getProductsPerPage() {
    return $this->productsPerPage;
  }

  public function setProductsPerPage($productsPerPage) {
    $this->productsPerPage = $productsPerPage;
  }

  public function getPage() {
    return $this->page;
  }

  public function setPage($page) {
    $this->page = $page;
  }

  public function getResource() {
    $config = $this->service->getConfig();
    $domain = $config->domain();
    $unitSystem = $config->unitSystem();

    $template = '/catalog3/service?o=fsearch&d=%s&cid=%s&unit=%s&page=%s&ppp=%s';

    return sprintf($template, $domain, $this->getCategory(), $unitSystem,
      $this->getPage(), $this->getProductsPerPage());
  }
}
