<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 2/3/2016
 * Time: 2:10 PM
 */

namespace TopFloor\Cds\CdsEntities;


use TopFloor\Cds\CdsService;
use TopFloor\Cds\Exceptions\CdsServiceException;

class CdsEntityFactory {
  protected static $entityTypes = array(
    'product' => '\\TopFloor\\Cds\\CdsEntities\\ProductCdsEntity',
    'category' => '\\TopFloor\\Cds\\CdsEntities\\CategoryCdsEntity',
    'utility' => '\\TopFloor\\Cds\\CdsEntities\\UtilityCdsEntity',
  );

  /**
   * @param \TopFloor\Cds\CdsService $service
   * @param $entityType
   * @param $cdsId
   * @param array $parameters
   * @param null $basePath
   * @return CdsEntity
   * @throws CdsServiceException
   */
  public static function create(CdsService $service, $entityType, $cdsId, $parameters = array(), $basePath = null) {
    if (!self::entityTypeExists($entityType)) {
      throw new CdsServiceException("CDS entity type $entityType is not defined.");
    }

    $class = self::$entityTypes[$entityType];

    return new $class($service, $entityType, $cdsId, $parameters, $basePath);
  }

  public static function entityTypeExists($entityType) {
    return (array_key_exists($entityType, self::$entityTypes));
  }
}
