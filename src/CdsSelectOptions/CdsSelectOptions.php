<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/10/2016
 * Time: 12:00 AM
 */

namespace TopFloor\Cds\SelectOptions;


use TopFloor\Cds\CdsService;
use TopFloor\Cds\Exceptions\CdsServiceException;

class CdsSelectOptions implements CdsSelectOptionsInterface {
  protected $service;

  protected static $types = array(
    'product' => '\\TopFloor\\Cds\\SelectOptions\\ProductSelectOptions',
    'category' => '\\TopFloor\\Cds\\SelectOptions\\CategorySelectOptions',
  );

  public static function create($type, CdsService $service) {
    if (!self::typeExists($type)) {
      throw new CdsServiceException("Select options type $type doesn't exist.");
    }

    $type = self::$types[$type];

    return new $type($service);
  }

  public static function typeExists($type) {
    return (array_key_exists($type, self::$types));
  }

  public function __construct(CdsService $service) {
    $this->service = $service;
  }

  public function getOptions() {
    $options = $this->loadData();

    return $options;
  }

  protected function loadData() {
    // Override this function
    return array();
  }
}
