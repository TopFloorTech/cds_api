<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 2/3/2016
 * Time: 6:56 PM
 */

namespace TopFloor\Cds\CdsEntities;


use CDS;
use TopFloor\Cds\CdsService;
use TopFloor\Cds\CdsUrlHandlers\EnvironmentBasedCdsUrlHandler;
use TopFloor\Cds\CustomCds;
use TopFloor\Cds\CustomCdsUrlManager;

abstract class CdsEntity implements CdsEntityInterface {
  /**
   * @var CdsService
   */
  protected $service;

  protected $type;

  protected $id;

  protected $parameters;

  protected $cds;

  protected $basePath;

  public static $initCallback = null;

  public static $preRenderCallback = null;

  public function __construct(CdsService $service, $type, $id = null, $parameters = array(), $basePath = null) {
    $this->service = $service;
    $this->type = $type;
    $this->id = $id;
    $this->parameters = $parameters;
    $this->basePath = $basePath;

    $this->initialize();

    if (is_callable(self::$initCallback)) {
      call_user_func(self::$initCallback, $this);
    }
  }

  public function getType() {
    return $this->type;
  }

  public function getId() {
    return $this->id;
  }

  public function getParameters() {
    return $this->parameters + $this->getDefaultParameters();
  }

  protected function getDefaultParameters() {
    $parameters = array();

    if (isset($_REQUEST['unit'])) {
      $parameters['unit'] = $_REQUEST['unit'];
    }

    return $parameters;
  }

  public function getParameter($name, $default = false) {
    $parameters = $this->getParameters();

    if (isset($parameters[$name])) {
      return $parameters[$name];
    }

    return $default;
  }

  protected function initialize() {
    $this->getCds();
  }

  public function render($display = 'full') {
    $renderer = $this->service->getRenderer($display);

    if (is_callable(self::$preRenderCallback)) {
      call_user_func(self::$preRenderCallback, $this, $display);
    }

    return $renderer->render($this);
  }

  public function shouldShowLink() {
    return ($this->getUrl());
  }

  public function getUrl()
  {
    $urlHandler = $this->service->getUrlHandler();

    return $urlHandler->construct($this->getParameters(), null, $this->basePath);
  }

  public function getCds() {
    if (isset($this->cds)) {
      return $this->cds;
    }

    $path = $this->service->getConfig()->cdsPath();

    if (file_exists($path . 'cds.php')) {
      require_once($path . 'cds.php');
      require_once($path . 'CDSUrlManager.php');

      $cds = new CustomCds($this->service, $this->service->getConfig()->cdsPath(), $this->getParameters());

      $this->cds = $cds;

      return $cds;
    }

    return false;
  }

  public abstract function getLabel();

  public function shouldCache() {
    return true;
  }

  public function cacheKey() {
    $key = $this->getType();

    $id = $this->getId();
    if (!empty($id)) {
      $key .= "-$id";
    }

    $unit = $this->getParameter('unit');
    if (!empty($unit)) {
      $key .= "-$unit";
    }

    return $key;
  }
}
