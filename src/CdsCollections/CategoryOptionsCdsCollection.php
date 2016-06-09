<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/10/2016
 * Time: 12:18 AM
 */

namespace TopFloor\Cds\CdsCollections;

class CategoryOptionsCdsCollection extends CacheableCdsCollection {

  protected $permanent = true;

  public function getCacheKey() {
    return 'select-options-category';
  }

  public function loadData() {
    $options = array();

    $categories = (new CategoriesCdsCollection($this->service))->getItems();

    foreach ($categories as $category) {
      if (is_null($category['parent'])) {
        $options[$category['id']] = $category['label'];

        $options += $this->getChildren($categories, $category['id'], '-');
      }
    }

    return $options;
  }

  public function getChildren($categories, $parentId, $prefix = '', $maxDepth = null, $depth = 0) {
    $options = array();

    foreach ($categories as $child) {
      if (is_null($child['parent']) || $child['parent'] != $parentId) {
        continue;
      }

      $options[$child['id']] = $prefix . ' ' . $child['label'] . ' (' . $child['id'] . ')';

      if (is_null($maxDepth) || $depth < $maxDepth) {
        $options += $this->getChildren($categories, $child['id'], $prefix . '-', $maxDepth, $depth + 1);
      }
    }

    return $options;
  }
}
