<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 12/24/2015
 * Time: 4:10 PM
 */

namespace TopFloor\Cds\CdsPages;


class CompareCdsPage extends CdsPage {
  public function initialize() {
    $urlHandler = $this->service->getUrlHandler();

    $productUrlTemplate = $urlHandler->construct(array(
      'page' => 'product',
      'id' => '%PRODUCT%',
      'cid' => 'product'
    ));

    $this->pageVars = array(
      'containerId' => 'cds-product-compare-container',
    );

    $this->dependencies->setting('Compare', array(
      'productUrlTemplate' => $productUrlTemplate,
      'containerId' => $this->pageVars['containerId'],
    ));

    parent::initialize();
  }
}
