<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 12/24/2015
 * Time: 9:38 PM
 */

namespace TopFloor\Cds\CdsComponents;

class ProductCartCdsComponent extends CdsComponent {
  protected $enabled = true;

  public function initialize() {
    $listPrice = $this->settings['listPrice'];

    $urlHandler = $this->service->getUrlHandler();

    $this->viewVars = array(
      'containerId' => 'cds-product-cart',
      'productPriceId' => 'cds-product-price',
      'productListPriceId' => 'cds-product-list-price',
      'listPrice' => $listPrice,
      'qtyInputId' => 'cds-add-to-cart-quantity',
      'addToCartButtonId' => 'cds-add-to-cart-button',
      'addToCartLabel' => 'Add to Cart',
    );

    // Set defaults
    $this->defaultParameters = array(
      'productId' => $urlHandler->get('id'),
      'productLabel' => '',
      'productDescription' => '',
      'productImageUrl' => '',
      'cartUrl' => '',
    );

    parent::initialize();
  }
}
