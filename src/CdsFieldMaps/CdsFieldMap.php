<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 2/10/2016
 * Time: 5:35 PM
 */

namespace TopFloor\Cds\CdsFieldMaps;

use TopFloor\Cds\CdsService;

abstract class CdsFieldMap implements CdsFieldMapInterface
{
    protected $service;

    protected $cdsId;

    protected $cdsType;

    protected $fields = array();

    protected $fieldMap = array();

    public function __construct(CdsService $service, $cdsId, $cdsType) {
        $this->service = $service;
        $this->cdsId = $cdsId;
        $this->cdsType = $cdsType;

        $this->populateFields();
    }

    public function getFieldMap() {
        return $this->fieldMap;
    }

    public function getFields() {
        return $this->fields;
    }

    protected abstract function populateFields();

    public function createFieldMap($cdsId, $cdsType) {
        $className = get_class($this);

        return new $className($this->service, $cdsId, $cdsType);
    }

    public function hasContent($fieldName) {
        $fieldName = $this->getMappedName($fieldName);

        return (!empty($this->fields[$fieldName]));
    }

    public function getContent($fieldName) {
        $fieldName = $this->getMappedName($fieldName);

        return isset($this->fields[$fieldName]) ? $this->fields[$fieldName] : '';
    }

    public function getMappedName($fieldName) {
        return (isset($this->fieldMap[$fieldName])) ? $this->fieldMap[$fieldName] : $fieldName;
    }

    public function isEmpty() {
        return empty($this->fields);
    }
}
