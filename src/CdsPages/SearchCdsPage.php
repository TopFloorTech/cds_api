<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 12/24/2015
 * Time: 4:56 PM
 */

namespace TopFloor\Cds\CdsPages;


class SearchCdsPage extends CdsPage {
  public function initialize() {
    $host = htmlspecialchars($this->service->getHost());

    $dependencies = $this->dependencies;
    $urlHandler = $this->service->getUrlHandler();
    $categoryId = $this->service->getCategoryInfo()->categoryId();
    $categoryInfo = $this->prepareCategoryInfo($this->service->getCategoryInfo()->categoryInfo($categoryId));

    $productUrlTemplate = $urlHandler->construct(array(
      'page' => 'product',
      'cid' => '%CATEGORY%',
      'id' => '%PRODUCT%',
    ));

    $searchUrlTemplate = $urlHandler->construct(array(
      'page' => 'search',
      'cid' => '%CATEGORY%',
    ), '?facets=1');

    $this->pageVars = array(
      'searchContainerId' => 'cds-search-right-container',
      'searchContainerClass' => 'cds-browse-container',
      'searchHeaderClass' => 'head',
      'category' => $categoryInfo,
      'loadProducts' => $this->service->getCategoryInfo()->loadProducts($categoryInfo),
      'productContainerId' => 'cds-product-container',
      'productLoadingContainerId' => 'cds-product-loading-container',
      'productLoadingImageSrc' => '//' . $this->service->getHost() . '/catalog3/images/progress_animation_large.gif',
      'browseListClass' => 'cds-browse-list',
      'sidebarContainerId' => 'cds-search-left-container',
      'keywords' => (isset($_REQUEST['cdskeys'])) ? $_REQUEST['cdskeys'] : null,
    );

    $dependencies->js('cds-faceted-search', 'http://' . $host . '/catalog3/js/cds-faceted-search2.js');
    $dependencies->setting('Search', array(
      'productUrlTemplate' => $productUrlTemplate,
      'searchUrlTemplate' => $searchUrlTemplate,
      'comparePageUrl' => $urlHandler->construct(array('page' => 'compare')),
      'attributeLabel' => 'Attribute',
      'valueLabel' => 'Value',
      'compareMaxProducts' => 6,
    ));

    $this->defaultParameters = array(
      'categoryId' => $categoryId,
      'displayPowerGrid' => true,
      'renderProductsListType' => 'list',
      'showUnitToggle' => false,
      'appendUnitToProductUrl' => true,
      'enableKeywordSearch' => true,
      'loadProducts' => $this->pageVars['loadProducts'],
      'keywords' => $this->pageVars['keywords'],
      'defaultTitle' => 'Select a Category',
    );

    parent::initialize();
  }

  protected function prepareCategoryInfo($categoryInfo) {
    $urlHandler = $this->service->getUrlHandler();

    foreach ($categoryInfo['children'] as $id => $child) {
      $categoryInfo['children'][$id]['categoryUrl'] = $urlHandler->construct(array('cid' => urlencode($child['id'])));

      $imageAttributes = '';
      if (isset($child['imageTitle'])) {
        $imageAttributes .= ' title="' . $child['imageTitle'] . '"';
      }

      if (isset($child['imageAlt'])) {
        $imageAttributes .= ' alt="' . $child['imageAlt'] . '"';
      }

      $categoryInfo['children'][$id]['imageAttributes'] = $imageAttributes;
    }

    return $categoryInfo;
  }

  public function pageTitle() {
    $categoryId = $this->getParameter('categoryId');

    if (empty($categoryId) || $categoryId == 'root') {
      $title = $this->getParameter('defaultTitle');
    } else {
      $categoryInfo = $this->service->getCategoryInfo()->categoryInfo($categoryId);

      $title = $categoryInfo['label'];
    }

    return $title;
  }
}
