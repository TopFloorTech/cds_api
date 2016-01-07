<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 12/23/2015
 * Time: 8:02 PM
 */

namespace TopFloor\Cds\CdsRenderers;

use TopFloor\Cds\CdsService;

interface CdsRendererInterface {
  public function __construct(CdsService $service);

  public function render($template, $vars = array());

  public function renderPage($template, $vars = array());

  public function renderSidebar($template, $vars = array());

  public function renderComponent($template, $vars = array());

  public function templateExists($template);
}
