<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 12/24/2015
 * Time: 1:36 AM
 */

namespace TopFloor\Cds\CdsComponents;


class CadRequesterCdsComponent extends CdsComponent {
  public function initialize() {
    $urlHandler = $this->service->getUrlHandler();

    $this->viewVars = array(
      'containerId' => 'cds-product-cad-container',
      'containerClass' => 'cds-product-cad-container',
      'downloadButtonId' => 'cds-cad-download-button',
      'formatSelectId' => 'cds-cad-download-formats',
      'view3dButtonId' => 'cds-cad-view-3D-button',
      'view2dButtonId' => 'cds-cad-view-2D-button',
      'disclaimerId' => 'cds-product-cad-view-disclaimer',
      'disclaimerTarget' => 'cds-help',
      'helpHref' => '//www.product-config.net/catalog3/help/3dviewerhelp.html',
    );

    $this->dependencies->setting('CadRequester', array(
      'containerId' => $this->viewVars['containerId'],
      'downloadButtonId' => $this->viewVars['downloadButtonId'],
      'formatSelectId' => $this->viewVars['formatSelectId'],
      'view2dButtonId' => $this->viewVars['view2dButtonId'],
      'view3dButtonId' => $this->viewVars['view3dButtonId'],
    ));

    $this->defaultParameters = array(
      'productId' => $urlHandler->get('id'),
    );

    parent::initialize();
  }
}
