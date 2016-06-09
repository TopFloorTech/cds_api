<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 2/10/2016
 * Time: 8:57 PM
 */

namespace TopFloor\Cds\CdsFieldMaps;


use TopFloor\Cds\CdsService;

interface CdsFieldMapInterface
{
    public function __construct(CdsService $service, $cdsId, $cdsType);

    public function getFieldMap();

    public function getFields();

    /**
     * @param $cdsId
     * @param $cdsType
     * @return CdsFieldMapInterface
     */
    public function createFieldMap($cdsId, $cdsType);

    public function hasContent($fieldName);

    public function getContent($fieldName);

    public function isEmpty();

    public function getMappedName($fieldName);
}
