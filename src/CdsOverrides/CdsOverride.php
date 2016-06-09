<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 2/10/2016
 * Time: 11:16 AM
 */

namespace TopFloor\Cds\CdsOverrides;


use TopFloor\Cds\CdsEntities\CdsEntity;
use TopFloor\Cds\CdsFieldMaps\CdsFieldMapInterface;
use TopFloor\Cds\CdsService;

abstract class CdsOverride implements CdsOverrideInterface
{
    protected $fieldMap;

    protected $service;

    public function __construct(CdsService $service, CdsFieldMapInterface $fieldMap) {
        $this->service = $service;
        $this->fieldMap = $fieldMap;
    }

    public abstract function override(CdsEntity $entity, $viewMode = 'full');

    protected function overrideField($fieldName, &$cdsFields, $cdsFieldNames = array(), $fieldMap = null) {
        if (is_null($fieldMap)) {
            $fieldMap = $this->fieldMap;
        }

        if ($fieldMap->hasContent($fieldName)) {
            $content = $fieldMap->getContent($fieldName);

            if (empty($cdsFieldNames)) {
                $cdsFieldNames = array($fieldName);
            }

            foreach ((array) $cdsFieldNames as $cdsFieldName) {
                $cdsFields[$cdsFieldName] = $content;
            }
        }
    }
}
