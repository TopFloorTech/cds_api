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

  static function referenceTypeExists($referenceType);

  public function hasLink();

  public function getUrl();

  public function getLabel();

  public function render();
}