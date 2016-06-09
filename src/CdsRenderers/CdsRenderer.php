<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 2/3/2016
 * Time: 6:45 PM
 */

namespace TopFloor\Cds\CdsRenderers;


use TopFloor\Cds\CdsEntities\CdsEntity;
use TopFloor\Cds\CdsService;

abstract class CdsRenderer implements CdsRendererInterface {
  protected $service;

  public function __construct(CdsService $service) {
    $this->service = $service;
  }

  public function render(CdsEntity $entity) {
    return $this->_render($entity);
  }

  public abstract function _render(CdsEntity $entity);
}
