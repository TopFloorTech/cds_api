<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 2/8/2016
 * Time: 10:24 PM
 */

namespace TopFloor\Cds\CdsOverrides;


use TopFloor\Cds\CdsEntities\CdsEntity;

interface CdsOverrideInterface
{
    public function override(CdsEntity $entity, $viewMode = 'full');
}