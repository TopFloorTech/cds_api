<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/9/2016
 * Time: 3:47 PM
 */

namespace TopFloor\Cds\CdsReferences;


use TopFloor\Cds\Exceptions\CdsServiceException;

class ProductCdsReference extends CacheableCdsReference {
  protected function _render() {
    // TODO: Implement _render() method.
    throw new CdsServiceException('Method not implemented.');
  }

  protected function _getUrl() {
    $urlHandler = $this->service->getUrlHandler();

    return $urlHandler->construct(array(
      'page' => 'products',
      'id' => $this->cdsId,
    ));
  }

  protected function _getLabel() {
    $request = $this->service->productRequest($this->cdsId);

    $product = $request->process();

    return $product['description'];
  }
}
