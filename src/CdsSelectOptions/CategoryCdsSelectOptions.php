<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/10/2016
 * Time: 12:18 AM
 */

namespace TopFloor\Cds\SelectOptions;

class CategoryCdsSelectOptions extends CacheableCdsSelectOptions {

  public function getCacheKey() {
    return 'select-options-category';
  }

  public function loadData() {
    $options = array();

    $request = $this->service->categoryRequest('root');
    $category = $request->process();

    $options['root'] = $category['label'];

    if (count($category['children']) > 0) {
      $options += $this->getChildren($category['children']);
    }

    return $options;
  }

  public function getChildren($children, $prefix = '') {
    $options = array();

    foreach ($children as $child) {
      $options[$child['id']] = $prefix . ' ' . $child['label'];

      $request = $this->service->categoryRequest($child['id']);
      $category = $request->process();

      if (count($category['children']) > 0) {
        $options += $this->getChildren($category['children'], $prefix . '-');
      }
    }

    return $options;
  }
}
