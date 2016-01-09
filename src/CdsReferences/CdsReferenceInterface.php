<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/9/2016
 * Time: 1:01 PM
 */

namespace TopFloor\Cds\CdsReferences;


interface CdsReferenceInterface {
  static function getReference($referenceType, $cdsId);
}