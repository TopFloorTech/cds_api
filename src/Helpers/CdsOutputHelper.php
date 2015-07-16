<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/16/2015
 * Time: 8:40 AM
 */

namespace Cds;


use TopFloor\Cds\CdsService;

class CdsOutputHelper {
	/** @var CdsService $service */
	private $service;

	public $templates = array(
		'loader'                  => '<div id="%s"><img src="%s"><div>%s</div></div>',
		'productContainer'        => '<div id="%s">%s</div>',
		'breadcrumbsList'         => '<ul class="%s">%s</ul>',
		'breadcrumbsItem'         => '<li>%s</li>',
		'breadcrumbsLink'         => '<a href="%s">%s</a>',
		'breadcrumbsSeparator'    => '<span>%s</span>',
		'searchHeaderContainer'   => '<div class="%s">%s</div>',
		'searchHeaderImage'       => '<img src="%s">',
		'searchHeaderHtml'        => '<p>%s</p>',
		'searchHeaderDescription' => '<p>%s</p>',
		'browseList'              => '<ul class="%s">%s</ul>',
		'browseListItem'          => '<li>%s</li>',
		'browseListItemLink'      => '<a href="%s">%s</a>',
		'browseListItemImage'     => '<img src="%s" title="%s" alt="%s">',
		'browseListItemTitle'     => '<h3>%s</h3>',
		'browseListItemDesc'      => '<p>%s</p>',
		'searchSidebarContainer'  => '<div id="%s">%s</div>',
		'searchMainContainer'     => '<div id="%s" class="%s">%s</div>',
		'searchSidebarJs'         => '<script>TopFloor.Cds.Search.sidebarBlock();</script>',
	);

	public function searchSidebarJs() {
		return sprintf($this->templates['searchSidebarJs']);
	}

	public function searchMainContainer($categoryId, $content = null, $id = 'cds-search-right-container', $class = 'cds-browse-container') {
		if (is_null($content)) {
			$content = $this->breadcrumbs($categoryId);
			$content .= $this->searchHeader($categoryId);
			$categoryInfo = $this->service->getCategoryInfo()->categoryInfo($categoryId);

			if ($this->service->getCategoryInfo()->loadProducts($categoryInfo)) {
				$content .= $this->browseList($categoryId);
			} else {
				$content .= $this->productContainer();
			}
		}

		return sprintf($this->templates['searchMainContainer'], $id, $class, $content);
	}

	public function searchSidebarContainer($id = 'cds-search-left-container') {
		return sprintf($this->templates['searchSidebarContainer'], $id);
	}

	public function browseList($categoryId, $class = 'cds-browse-list') {
		$categoryInfo = $this->service->getCategoryInfo()->categoryInfo($categoryId);
		$urlHandler = $this->service->getUrlHandler();

		$items = array();

		foreach ($categoryInfo['children'] as $child) {
			$childOutput = '';

			if (!empty($child['browseImageURL'])) {
				$url = $child['browseImageURL'];
				$title = (isset($child['imageTitle'])) ? $child['imageTitle'] : '';
				$alt = (isset($child['imageAlt'])) ? $child['imageAlt'] : '';

				$childOutput .= sprintf($this->templates['browseListItemImage'], $url, $title, $alt);
			}

			$childOutput .= sprintf($this->templates['browseListItemTitle'], $child['label']);

			if (!empty($child['description'])) {
				$childOutput .= sprintf($this->templates['browseListItemDesc'], $child['description']);
			}

			$url = $urlHandler->construct(array('cid' => urlencode($child['id'])));

			$link = sprintf($this->templates['browseListItemLink'], $url, $childOutput);

			$items[] = sprintf($this->templates['browseListItem'], $link);
		}

		return sprintf($this->templates['browseList'], $class, implode("\n", $items));
	}

	public function searchHeader($categoryId, $class = 'head') {
		$categoryInfo = $this->service->getCategoryInfo()->categoryInfo($categoryId);

		$output = '';

		if (!empty($categoryInfo['searchImageURL'])) {
			$output .= sprintf($this->templates['categoryHeaderImage'], $categoryInfo['searchImageURL']);
		}

		if (!empty($categoryInfo['searchHeaderHTML'])) {
			$output .= sprintf($this->template['categoryHeaderHtml'], $categoryInfo['searchHeaderHTML']);
		}

		if (!empty($categoryInfo['description'])) {
			$output .= sprintf($this->templates['searchHeaderDescription'], $categoryInfo['description']);
		}

		if (empty($output)) {
			return '';
		}

		return sprintf($this->templates['searchHeaderContainer'], $output);
	}

	public function __construct(CdsService $service) {
		$this->service = $service;
	}

	public function loader($containerId = 'cds-product-loading-container', $text = 'Loading products...') {
		$host = htmlspecialchars($this->service->getHost());

		$imgUrl = "http://$host/catalog3/images/progress_animation_large.gif";

		return sprintf($this->templates['loader'], $containerId, $imgUrl, $text);
	}

	public function productContainer($id = 'cds-product-container', $loader = null) {
		if (is_null($loader)) {
			$loader = $this->loader('cds-product-loading-container', 'Loading products...');
		}

		return sprintf($this->templates['productContainer'], $id, $loader);
	}

	public function breadcrumbs($categoryId, $class = 'cds-crumbs', $separator = '&gt;', $addCurrentCategory = true) {
		$breadcrumbs = $this->service->getBreadcrumbsHelper()->getCategoryBreadcrumbs($categoryId, $addCurrentCategory);

		$items = array();

		foreach ($breadcrumbs as $breadcrumb) {
			$link = sprintf($this->templates['breadcrumbsLink'], $breadcrumb['url'], $breadcrumb['label']);

			$items[] = sprintf($this->templates['breadcrumbsItem'], $link);
		}

		$separator = sprintf($this->templates['breadcrumbsSeparator'], $separator);
		$separator = sprintf($this->templates['breadcrumbsItem'], $separator);

		$items = implode($separator, $items);

		return sprintf($this->templates['breadcrumbsList'], $class, $items);
	}
}
