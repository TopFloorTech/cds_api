<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/10/2016
 * Time: 12:18 AM
 */

namespace TopFloor\Cds\CdsCollections;

class ProductOptionsCdsCollection extends CacheableCdsCollection {

  protected $permanent = true;

  public function getCacheKey() {
    return 'select-options-product';
  }

  public function loadData() {
    $options = array();

    $perPage = 250;
    $page = 0;

    $request = $this->service->productsRequest('root', $page, $perPage);

    $hasMorePages = true;

    while ($hasMorePages) {
      $request->setPage($page);
      $results = $request->process();

      if (!empty($results['products'])) {
        foreach ($results['products'] as $product) {
          $options[$product['id']] = $product['label'];
        }
      }

      if ($results['endRow'] < $results['rowCount']) {
        $page++;
      } else {
        $hasMorePages = false;
      }
    }

    return $options;
  }
}
