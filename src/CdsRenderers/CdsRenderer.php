<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 12/23/2015
 * Time: 8:06 PM
 */

namespace TopFloor\Cds\CdsRenderers;

use TopFloor\Cds\CdsService;

abstract class CdsRenderer implements CdsRendererInterface {
  /** @var CdsService $service */
  protected $service;

  public function __construct(CdsService $service) {
    $this->service = $service;
  }

  abstract function render($template, $vars = array());

  abstract function templateExists($template);

  public function renderPage($template, $vars = array()) {
    $template = 'pages/' . $template;

    return $this->render($template, $vars);
  }

  public function renderSidebar($template, $vars = array()) {
    $template = 'sidebars/' . $template;

    return $this->render($template, $vars);
  }

  public function renderComponent($template, $vars = array()) {
    $template = 'components/' . $template;

    return $this->render($template, $vars);
  }
}
