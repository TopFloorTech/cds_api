<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 2/3/2016
 * Time: 8:30 PM
 */

namespace TopFloor\Cds\CdsEntities;


class ProductCdsEntity extends CdsEntity {

  protected function initialize()
  {
    $this->parameters += array(
        'page' => 'product',
        'id' => $this->getId(),
    );

    parent::initialize();
  }

  public function getLabel() {
    $request = $this->service->productRequest($this->getId());

    $product = $request->process();

    $label = (!empty($product['description'])) ? $product['description'] : $product['label'];

    return $label;
  }
}
