<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/14/2016
 * Time: 11:04 AM
 */

namespace TopFloor\Cds\CdsCollections;


class CategoriesCdsCollection extends CacheableCdsCollection
{
  protected $rootCategory = null;

  protected $parent = null;

  protected $permanent = true;

  public function setRootCategory($rootCategory) {
    $this->rootCategory = $rootCategory;

    if ($rootCategory != '') {
      $this->cache = false;
    }
  }

  public function setParent($parent) {
    $this->parent = $parent;
  }

  public function loadData() {
    $categories = array();

    $request = $this->service->categoryRequest($this->rootCategory);
    $categoryInfo = $request->process();

    $categories[$categoryInfo['id']] = array(
      'id' => $categoryInfo['id'],
      'label' => $categoryInfo['label'],
      'parent' => $this->parent,
      'depth' => 0,
    );

    if (count($categoryInfo['children']) > 0) {
      $this->addChildren($categories, $categoryInfo['children'], $this->rootCategory);
    }

    return $categories;
  }

  public function addChildren(&$categories, $children, $parentId, $depth = 1) {
    foreach ($children as $child) {
      $options[$child['id']] = $child['label'];

      $categories[$child['id']] = array(
        'id' => $child['id'],
        'label' => $child['label'],
        'parent' => $parentId,
        'depth' => $depth,
      );

      if (count($child['children']) > 0) {
        $this->addChildren($categories, $child['children'], $child['id'], $depth + 1);
      }
    }
  }

  public function getCacheKey() {
    $key = 'cds-categories';

    if (!is_null($this->rootCategory)) {
      $key .= '-' . $this->rootCategory;
    }

    return $key;
  }
}
