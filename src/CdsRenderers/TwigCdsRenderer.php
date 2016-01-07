<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 12/23/2015
 * Time: 8:37 PM
 */

namespace TopFloor\Cds\CdsRenderers;

use TopFloor\Cds\CdsService;
use Twig_Environment;
use Twig_Loader_Filesystem;

class TwigCdsRenderer extends CdsRenderer {
  protected $twig;

  public function __construct(CdsService $service) {
    $loader = new Twig_Loader_Filesystem($service->getViewsDir());

    $this->twig = new Twig_Environment($loader);

    parent::__construct($service);
  }

  public function render($template, $vars = array()) {
    $output = '';

    if ($this->templateExists($template)) {
      $output = $this->twig->render($this->getTemplatePath($template), $vars);
    }

    return $output;
  }

  public function templateExists($template) {
    return file_exists($this->getTemplatePath($template, true));
  }

  protected function getTemplatePath($template, $absolute = false) {
    if (strlen($template) <= 5 || (substr($template, -5) !== '.html')) {
      $template .= '.html';
    }

    if ($absolute) {
      $template = $this->service->getViewsDir() . '/' . $template;
    }

    return $template;
  }
}
