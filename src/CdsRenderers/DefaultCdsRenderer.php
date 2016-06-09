<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 2/3/2016
 * Time: 8:12 PM
 */

namespace TopFloor\Cds\CdsRenderers;

use TopFloor\Cds\CdsEntities\CdsEntity;

class DefaultCdsRenderer extends CacheableCdsRenderer {
  public function _render(CdsEntity $entity) {
    return $entity->getCds()->getCatalogHTML();
  }
}
