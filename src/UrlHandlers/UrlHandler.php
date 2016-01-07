<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:51 PM
 */

namespace TopFloor\Cds\UrlHandlers;

use TopFloor\Cds\CdsService;

abstract class UrlHandler implements UrlHandlerInterface {
	private $service;

	public function __construct(CdsService $service) {
		$this->service = $service;

		$this->initialize();
	}

	protected function initialize() {
		// Any required initialization logic goes here.
	}

	public abstract function construct($parameters = array());

	public abstract function deconstruct($url);

	public abstract function getPageFromUri($uri = null, $basePath = null);

	public abstract function getUriForPage($page, $basePath = null);

	public function getCurrentUri() {
		return strtok($_SERVER["REQUEST_URI"],'?');
	}

	public function get($parameter) {
		$parts = $this->deconstruct($this->getCurrentUri());

		if (!isset($parts[$parameter])) {
			return '';
		}

		return $parts[$parameter];
	}

	public function parameterIsSet($parameter) {
		$parts = $this->deconstruct($this->getCurrentUri());

		return !empty($parts[$parameter]);
	}

	public function getUnitSystem() {
		$unitSystem = $this->service->getUnitSystem();
		if (isset($_REQUEST['unit'])) {
			$unitSystem = $_REQUEST['unit'];
		} elseif (isset($_COOKIE["cds.catalog.unit"])) {
			$unitSystem = $_COOKIE["cds.catalog.unit"];
		}

		return $unitSystem;
	}

	protected function buildParameters($parameters = array()) {
		$defaultParameters = array(
			'page' => 'search',
			'id' => '',
			'cid' => '',
			'filter' => '',
			'units' => $this->getUnitSystem(),
		);

		if (empty(trim($parameters['page']))) {
			unset($parameters['page']);
		}

		$parameters += $defaultParameters;

		return $parameters;
	}
}
