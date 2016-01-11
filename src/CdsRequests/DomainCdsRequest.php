<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/9/2016
 * Time: 10:51 PM
 */

namespace TopFloor\Cds\CdsRequests;


class DomainCdsRequest extends CdsRequest {
  public function getResource() {
    $config = $this->service->getConfig();
    $domain = $config->domain();
    $unitSystem = $config->unitSystem();

    $template = '/catalog3/service?o=domain&d=%s&unit=%s';

    return sprintf($template, $domain, $unitSystem);
  }
}
