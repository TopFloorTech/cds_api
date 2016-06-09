<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/10/2016
 * Time: 12:00 AM
 */

namespace TopFloor\Cds\CdsCollections;


use TopFloor\Cds\CdsService;
use TopFloor\Cds\Exceptions\CdsServiceException;

class CdsCollection implements CdsCollectionInterface {
  protected $service;

  public static $types = array(
    'productOptions' => '\\TopFloor\\Cds\\CdsCollections\\ProductOptionsCdsCollection',
    'categories' => '\\TopFloor\\Cds\\CdsCollections\\CategoriesCdsCollection',
    'categoryOptions' => '\\TopFloor\\Cds\\CdsCollections\\CategoryOptionsCdsCollection',
  );

  /**
   * @param $type
   * @param \TopFloor\Cds\CdsService $service
   * @return CdsCollection
   * @throws \TopFloor\Cds\Exceptions\CdsServiceException
   */
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

  protected function __construct(CdsService $service) {
    $this->service = $service;

    $this->initialize();
  }

  protected function initialize() {
    // Override if needed
  }

  public function getItems() {
    $options = $this->loadData();

    return $options;
  }

  protected function loadData() {
    // Override this function
    return array();
  }
}
