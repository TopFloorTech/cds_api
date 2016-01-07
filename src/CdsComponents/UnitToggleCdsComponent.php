<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 12/24/2015
 * Time: 9:38 PM
 */

namespace TopFloor\Cds\CdsComponents;

class UnitToggleCdsComponent extends CdsComponent {
  protected $enabled = false;

  public function initialize() {
    $this->viewVars = array(
      'containerId' => 'cds-unit-toggle-container',
    );

    $this->defaultParameters = array(
      'containerId' => $this->viewVars['containerId'],
      'unitSystem' => $this->service->getUnitSystem(),
    );

    parent::initialize();
  }
}
