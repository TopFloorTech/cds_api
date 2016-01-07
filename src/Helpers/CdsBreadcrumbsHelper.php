<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 11:38 PM
 */

namespace TopFloor\Cds\Helpers;

use TopFloor\Cds\CdsService;

class CdsBreadcrumbsHelper {
	/** @var CdsService $service */
	private $service;

	public function __construct(CdsService $service) {
		$this->service = $service;
	}

	public function getBreadcrumbs() {
		$page = $this->service->getPage();

		if (empty($page)) {
			return array();
		}

		$urlHandler = $this->service->getUrlHandler();

		if ($page == 'product') {
			return $this->getProductBreadcrumbs($urlHandler->get('id'), $urlHandler->get('cid'));
		} else {
			return $this->getCategoryBreadcrumbs($urlHandler->get('cid'));
		}
	}

	public function getProductBreadcrumbs($productId, $categoryId, $addProduct = true) {
		$breadcrumbs = $this->getCategoryBreadcrumbs($categoryId, true, true);

		$productInfo = $this->service->getProductInfo()->productInfo($productId, $categoryId);

		if ($addProduct) {
			$breadcrumbs[] = array(
				'url' => '',
				'label' => $productInfo['label'],
			);
		}

		return $breadcrumbs;
	}

	public function getCategoryBreadcrumbs($categoryId, $addCurrentCategory = true, $linkCurrentCategory = false) {
		$request = $this->service->getCategoryInfo()->categoryRequest($categoryId);
		$categoryInfo = $request->process();
		$urlHandler = $this->service->getUrlHandler();

		$breadcrumbs = array();

		foreach ($categoryInfo['crumbs'] as $crumb) {
			if ($crumb['id'] == 'root') {
				$url = $urlHandler->construct(array('page' => 'search'));
			} else {
				$url = $urlHandler->construct(array(
					'page' => 'search',
					'cid' => urlencode($crumb['id'])
				));
			}

			$breadcrumbs[] = array(
				'url' => $url,
				'label' => $crumb['label'],
			);
		}

		if ($addCurrentCategory) {
			$url = '';

			if ($linkCurrentCategory) {
				$url = $urlHandler->construct(array(
					'page' => 'search',
					'cid' => urlencode($categoryId)
				));
			}

            $breadcrumbs[] = array(
                'url' => $url,
                'label' => $categoryInfo['label'],
            );
		}

		return $breadcrumbs;
	}
}
