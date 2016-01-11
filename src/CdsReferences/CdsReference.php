<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/9/2016
 * Time: 3:40 PM
 */

namespace TopFloor\Cds\CdsReferences;


use TopFloor\Cds\CdsService;
use TopFloor\Cds\Exceptions\CdsServiceException;

class CdsReference implements CdsReferenceInterface {

  protected static $referenceTypes = array(
    'product' => '\\TopFloor\\Cds\\CdsReferences\\ProductCdsReference',
    'category' => '\\TopFloor\\Cds\\CdsReferences\\CategoryCdsReference',
  );

  protected $cdsId;
  protected $service;


  protected function __construct($cdsId, CdsService $service) {
    $this->cdsId = $cdsId;
    $this->service = $service;

    $this->initialize();
  }

  protected function initialize() {
    // Override if desired
  }

  /**
   * @param $referenceType
   * @param $cdsId
   * @return CdsReference
   * @throws \TopFloor\Cds\Exceptions\CdsServiceException
   */
  public static function getReference($referenceType, $cdsId) {
    if (!self::referenceTypeExists($referenceType)) {
      throw new CdsServiceException("Reference type $referenceType is not defined.");
    }

    $class = self::$referenceTypes[$referenceType];

    return new $class($cdsId);
  }

  public static function referenceTypeExists($referenceType) {
    return (array_key_exists($referenceType, self::$referenceTypes));
  }

  public function getUrl() {
    return $this->url;
  }

  public function getLabel() {
    return $this->label;
  }

  public function hasLink() {
    return ($this->getUrl());
  }

  public function render() {
    return '';
  }
}