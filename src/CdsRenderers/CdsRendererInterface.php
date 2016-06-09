<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 2/3/2016
 * Time: 2:49 PM
 */

namespace TopFloor\Cds\CdsRenderers;


use TopFloor\Cds\CdsEntities\CdsEntity;

interface CdsRendererInterface {
  public function render(CdsEntity $entity);
}
