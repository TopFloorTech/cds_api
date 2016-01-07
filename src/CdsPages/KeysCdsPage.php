<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 12/24/2015
 * Time: 4:56 PM
 */

namespace TopFloor\Cds\CdsPages;


class KeysCdsPage extends CdsPage {
  protected $pageTitle = 'Search';

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

    $this->pageVars = array(
      'containerId' => 'cds-keys-results',
      'progressImageSrc' => 'http://' . $this->service->getHost() . '/catalog3/images/progress_animation.gif',
    );

    $this->defaultParameters = array(
      'queryParameter' => 's',
      'productUrlTemplate' => $productUrlTemplate,
      'categoryUrlTemplate' => $categoryUrlTemplate,
      'containerId' => $this->pageVars['containerId'],
      'attributeLabel' => 'Attribute',
      'valueLabel' => 'Value',

    );

    parent::initialize();
  }
}
