<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 11:55 PM
 */

namespace TopFloor\Cds;

class CdsProductInfo {
	/** @var CdsService $service */
	private $service;

	public function __construct(CdsService $service) {
		$this->service = $service;
	}

	public function productId() {
		$urlHandler = $this->service->getUrlHandler();

		$productId = $urlHandler->get('id');

		return $productId;
	}

	public function productRequest($id = null, $category = null) {
		$resourceTemplate = '/catalog3/service?o=product&d=%s&id=%s&unit=%s';
		$categoryTemplate = '&cid=%s';

		if (is_null($id)) {
			$id = $this->productId();
		}

		$resource = sprintf($resourceTemplate, $this->service->getDomain(), $id, $this->service->getUnitSystem());

		if (!is_null($category)) {
			$resource .= sprintf($categoryTemplate, $category);
		}

		return $this->service->request($resource);
	}

	public function productInfo($id = null, $category = null) {
		return $this->productRequest($id, $category)->process();
	}
}
