<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 12/24/2015
 * Time: 9:38 PM
 */

namespace TopFloor\Cds\CdsComponents;

class KeysCdsComponent extends CdsComponent {
  protected $enabled = true;

  public function initialize() {
    $urlHandler = $this->service->getUrlHandler();

    $productUrlTemplate = $urlHandler->construct(array(
      'page' => 'product',
      'id' => '%PRODUCT%',
      'cid' => 'product',
    ));

    $categoryUrlTemplate = $urlHandler->construct(array(
      'page' => 'search',
      'cid' => '%CATEGORY%',
    ));

    $this->viewVars = array(
      'containerId' => 'cds-keys-results',
      'progressImageSrc' => 'http://' . $this->service->getHost() . '/catalog3/images/progress_animation.gif',
    );

    $this->defaultParameters = array(
      'containerId' => $this->viewVars['containerId'],
      'productUrlTemplate' => $productUrlTemplate,
      'categoryUrlTemplate' => $categoryUrlTemplate,
      'attributeLabel' => 'Attribute',
      'valueLabel' => 'Value',
      'queryParameter' => 's',
    );

    parent::initialize();
  }
}
