<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 12/23/2015
 * Time: 11:50 PM
 */

namespace TopFloor\Cds\CdsPages;


use TopFloor\Cds\CdsComponents\CdsComponent;
use TopFloor\Cds\CdsComponents\CdsComponentInterface;
use TopFloor\Cds\CdsService;
use TopFloor\Cds\Collections\CdsComponentCollection;
use TopFloor\Cds\Collections\CdsDependencyCollection;

class CdsPage implements CdsPageInterface {
  protected $service;

  protected $components;

  /** @var CdsDependencyCollection */
  protected $dependencies;

  protected $view;

  protected $jsDir;

  protected $jsFile;

  protected $sidebarView;

  protected $pageTitle;

  protected $pageVars = array();

  protected $name;

  protected $slug;

  protected $parameters = array();

  protected $defaultParameters = array();

  public function __construct(CdsService $service) {
    $this->service = $service;

    $this->name = $this->getName();
    $this->slug = $this->service->slugify($this->name);
    $this->pageTitle = $this->name;

    $this->dependencies = new CdsDependencyCollection();
    $this->components = new CdsComponentCollection();

    $this->jsDir = dirname(dirname(dirname(__FILE__))) . '/dist/js/';
    $this->view = $this->slug;
    $this->sidebarView = $this->slug;

    $this->initialize();
  }

  public function getName() {
    $name = get_class($this);

    $name = substr($name, strrpos($name, '\\') + 1);

    $name = substr($name, 0, strlen($name) - strlen('CdsPage'));

    return $name;
  }

  public function getDependencies() {
    $dependencies = new CdsDependencyCollection();

    $dependencies->addDependencies($this->components->getDependencies()->getDependencies());
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

  protected function initialize() {
    // Override this if there are any dependencies to declare or other setup actions to perform.
    // Make sure to call this parent function at the end of your initialize function

    $this->setupComponents();

    $this->defaultJs();
  }

  protected function defaultJs() {
    $subDir = 'pages/';

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

  protected function setupComponents() {
    // Instantiate any commands used by overriding this function
  }

  public function addComponent(CdsComponentInterface $component, $region = 'main') {
    $this->components->addComponent($component, $region);
  }

  public function pageTitle() {
    return $this->pageTitle;
  }

  public function execute() {
    $output = $this->executePage();

    $output .= $this->components->execute();

    return $output;
  }

  public function executePage() {
    $template = "if (typeof %s == 'function') { %s(%s) }";

    $function = 'TopFloor.Cds.Pages.' . $this->name . '.initialize';

    return sprintf($template, $function, $function, $this->getParameters(true));
  }

  public function output() {
    return $this->service->getRenderer()
      ->renderPage($this->view, $this->getViewVars('main'));
  }

  public function sidebarOutput() {
    return $this->service->getRenderer()
      ->renderSidebar($this->sidebarView, $this->getViewVars('sidebar'));
  }

  public function getViewVars($region = 'main') {
    $viewVars = $this->pageVars;

    $components = $this->components->getComponentRegion($region);

    /** @var CdsComponent $component */
    foreach ($components as $component) {
      $viewVars[$component->getName()] = $component;
    }

    return $viewVars;
  }

  public function getComponents() {
    return $this->components;
  }
}
