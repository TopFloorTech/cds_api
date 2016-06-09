<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 2/3/2016
 * Time: 6:47 PM
 */

namespace TopFloor\Cds\CdsEntities;


use TopFloor\Cds\CdsRenderers\CdsRenderer;

interface CdsEntityInterface {
  public function getType();

  public function getId();

  public function getUrl();

  public function getLabel();

  public function shouldShowLink();

  public function getParameters();

  public function getCds();

  public function shouldCache();

  public function cacheKey();

  public function render($display = 'full');
}
