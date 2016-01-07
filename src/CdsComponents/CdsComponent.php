<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 12/24/2015
 * Time: 12:25 AM
 */

namespace TopFloor\Cds\CdsComponents;


use TopFloor\Cds\CdsPages\CdsPageInterface;
use TopFloor\Cds\CdsService;
use TopFloor\Cds\Collections\CdsDependencyCollection;

abstract class CdsComponent implements CdsComponentInterface {
  protected $service;

  protected $page;

  protected $dependencies;

  protected $parameters = array();

  protected $defaultParameters = array();

  protected $enabled = true;

  protected $view;

  protected $jsFile;

  protected $jsDir;

  protected $viewVars = array();

  protected $slug;

  protected $name;

  protected $settings = array();

  public function __construct(CdsService $service, CdsPageInterface $page = null, $settings = array()) {
    $this->service = $service;
    $this->page = $page;

    $this->configure($settings);

    $this->name = $this->getName();
    $this->slug = $this->service->slugify($this->name);

    $this->view = $this->slug;
    $this->dependencies = new CdsDependencyCollection();
    $this->jsDir = dirname(dirname(dirname(__FILE__))) . '/dist/js/';

    $this->initialize();
  }

  public function getName() {
    $name = get_class($this);

    $name = substr($name, strrpos($name, '\\') + 1);

    $name = substr($name, 0, strlen($name) - strlen('CdsComponent'));

    return $name;
  }

  protected function configure($settings) {
    // Extract parameters
    if (array_key_exists('parameters', $settings)) {
      $this->setParameters($settings['parameters']);

      unset($settings['parameters']);
    }

    $this->settings = $settings;
  }

  protected function initialize() {
    // Override this if there are any dependencies to declare or other setup actions to perform.

    $this->defaultJs();
  }

  protected function defaultJs() {
    $subDir = 'components/';

    if (substr($this->jsDir, -1) != '/') {
      $this->jsDir .= '/';
    }

    if (!isset($this->jsFile)) {
      $jsFile = $subDir . $this->slug . '.js';
      $jsPath = $this->jsDir . $jsFile;

      $this->jsFile = (file_exists($jsPath)) ? $jsFile : false;
    }

    if ($this->jsFile) {
      $this->dependencies->js('page-' . $this->slug, $this->jsFile);
    }
  }

  public function getDependencies() {
    $dependencies = new CdsDependencyCollection();
    $dependencies->addDependencies($this->dependencies->getDependencies());

    return $dependencies;
  }

  public function setParameters($parameters = array()) {
    $this->parameters = $parameters;
  }

  public function getParameters($encode = false) {
    $parameters = $this->parameters + $this->defaultParameters;

    if ($encode) {
      $parameters = json_encode($parameters);
    }

    return $parameters;
  }

  public function getParameter($name) {
    $parameters = $this->getParameters();

    return isset($parameters[$name]) ? $parameters[$name] : '';
  }

  public function setParameter($name, $value) {
    $this->parameters[$name] = $value;
  }

  public function execute() {
    $parent = 'TopFloor.Cds.Components.' . $this->name;

    $function = $parent . '.initialize';

    $template = "if (typeof %s != 'undefined' && typeof %s == 'function') { %s(%s) }";

    return sprintf($template, $parent, $function, $function, $this->getParameters(true));
  }

  public function output() {
    $output = '';

    if ($this->isEnabled() && isset($this->view)) {
      $output = $this->service->getRenderer()->render('components/' . $this->view, $this->viewVars);
    }

    return $output;
  }

  /**
   * Override to add custom logic here
   *
   * @return bool
   */
  public function isEnabled() {
    return $this->enabled;
  }
}
