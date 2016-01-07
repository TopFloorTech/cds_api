<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 12/24/2015
 * Time: 1:23 AM
 */

namespace TopFloor\Cds\CdsPages;


use TopFloor\Cds\UrlHandlers\EnvironmentBasedUrlHandler;

class CartCdsPage extends CdsPage {
  public function initialize() {
    /** @var EnvironmentBasedUrlHandler $urlHandler */
    $urlHandler = $this->service->getUrlHandler();

    $this->pageVars = array(
      'id' => 'cds-cart-container',
      'returnUrl' => $urlHandler->getEnvironmentUri(),
    );

    $this->dependencies->setting('Cart', array(
      'containerId' => $this->pageVars['id'],
    ));

    parent::initialize();
  }
}
