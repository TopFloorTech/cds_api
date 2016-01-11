<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/9/2016
 * Time: 3:48 PM
 */

namespace TopFloor\Cds\CdsReferences;


use TopFloor\Cds\Exceptions\CdsServiceException;

class CategoryCdsReference extends CacheableCdsReference {
  protected function _render() {
    // TODO: Implement _render() method.
    throw new CdsServiceException('Method not implemented.');
  }

  protected function _getUrl() {
    $urlHandler = $this->service->getUrlHandler();

    return $urlHandler->construct(array(
      'page' => 'search',
      'cid' => $this->cdsId,
    ));
  }

  protected function _getLabel() {
    $request = $this->service->categoryRequest($this->cdsId);

    $category = $request->process();

    return $category['label'];
  }
}
