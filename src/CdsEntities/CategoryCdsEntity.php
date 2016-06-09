<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 2/3/2016
 * Time: 8:31 PM
 */

namespace TopFloor\Cds\CdsEntities;


use TopFloor\Cds\CdsService;

class CategoryCdsEntity extends CdsEntity {
  protected $baseCategory;

  public function __construct(CdsService $service, $type, $id = null, $parameters = array(), $basePath = null, $baseCategory = null)
  {
    $this->baseCategory = $baseCategory;

    parent::__construct($service, $type, $id, $parameters, $basePath);
  }

  public function setBaseCategory($baseCategory) {
    $this->baseCategory = $baseCategory;
  }

  public function getUrl()
  {
    if (!is_null($this->baseCategory) && !is_null($this->basePath) && ($this->getId() == $this->baseCategory)) {
      return '/' . $this->basePath;
    }

    return parent::getUrl();
  }

  protected function initialize()
  {
    if (empty($this->id)) {
      $this->id = 'root';
    }

    $this->parameters += array(
        'page' => 'search',
        'cid' => $this->id,
    );

    parent::initialize();
  }

  public function getLabel() {
    $request = $this->service->categoryRequest($this->getId());

    $category = $request->process();

    return $category['label'];
  }
}
