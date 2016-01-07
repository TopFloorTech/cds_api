<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 12/24/2015
 * Time: 9:40 PM
 */

namespace TopFloor\Cds\CdsComponents;


class SpecSheetCdsComponent extends CdsComponent {
  public function initialize() {
    $urlHandler = $this->service->getUrlHandler();

    $this->viewVars = array(
      'containerClass' => 'cds-product-spec-sheet',
      'submitButtonId' => 'cds-product-spec-sheet-submit',
      'submitButtonText' => 'View Spec Sheet',
    );

    $this->defaultParameters = array(
      'unitSystem' => $this->service->getUnitSystem(),
      'productId' => $urlHandler->get('id'),
      'categoryId' => $urlHandler->get('cid'),
    );

    parent::initialize();
  }
}
