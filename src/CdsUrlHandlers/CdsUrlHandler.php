<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:51 PM
 */

namespace TopFloor\Cds\CdsUrlHandlers;

use TopFloor\Cds\CdsCollections\CdsCollection;
use TopFloor\Cds\CdsService;

abstract class CdsUrlHandler implements CdsUrlHandlerInterface {
	protected $service;

	protected $pageAliases = array(
		'search' => 'category',
		'product' => 'product',
		'keys' => 'search',
		'cart' => 'cart',
		'compare' => 'compare',
	);

	public function __construct(CdsService $service) {
		$this->service = $service;

		$this->initialize();
	}

	protected function initialize() {
		// Any required initialization logic goes here.
	}

	protected function parseQueryString($queryString) {
		if (substr($queryString, 0, 1) == '?') {
			$queryString = substr($queryString, 1);
		}

		$parts = array();

		$queryString = str_replace('%', '__placeholder__', $queryString);

		parse_str($queryString, $parts);

		array_walk($parts, function (&$value, $key) {
			$value = str_replace('__placeholder__', '%', $value);
		});

		return $parts;
	}

	public abstract function construct($parameters = array(), $append = null, $basePath = null);

	public abstract function deconstruct($url, $basePath = null);

	public abstract function getPageFromUri($uri = null, $basePath = null);

	public function getAliasForPage($page) {
		$aliases = $this->service->getConfig()->get('aliases');

		$type = $this->pageAliases[$page];

		$alias = $aliases[$type];

		return $alias;
	}

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
		$unitSystem = $this->service->getConfig()->unitSystem();

		if (isset($_REQUEST['unit'])) {
			$unitSystem = $_REQUEST['unit'];
		} elseif (isset($_COOKIE['cds_catalog_unit'])) {
			$unitSystem = $_COOKIE['cds_catalog_unit'];
		}

		return $unitSystem;
	}

	protected function buildParameters($parameters = array()) {
		if (is_string($parameters)) {
			$parameters = $this->parseQueryString($parameters);
		}

		$defaultParameters = array(
			'page' => 'search',
			//'id' => '',
			//'cid' => '',
			//'filter' => '',
			'unit' => $this->getUnitSystem(),
		);

		if (empty(trim($parameters['page']))) {
			unset($parameters['page']);
		}

		$parameters += $defaultParameters;

		return $parameters;
	}

	public function matchUtilityAlias($uri, $baseUri) {
		$config = $this->service->getConfig();

		$aliases = $config->get('aliases');

		$utilityEntities = array('cart', 'compare', 'keys');

		foreach ($utilityEntities as $utilityEntity) {
			$alias = $this->standardizeAlias($aliases[$this->pageAliases[$utilityEntity]], $baseUri);

			if ($uri == $alias || strpos($uri, $alias) === 0) {
				return $utilityEntity;
			}
		}

		return false;
	}

	public function standardizeAlias($alias, $baseUri = null, $parameters = array(), $baseCategory = null) {
		if (is_null($baseUri)) {
			$baseUri = '';
		}

		$alias = preg_replace('/{section}/', $baseUri, $alias);

		$aliasParts = explode('/', $alias);

		foreach ($aliasParts as $key => $aliasPart) {
			switch ($aliasPart) {
				case '{section}':
					$aliasParts[$key] = $baseUri;
					break;
				case '{category}':
					$aliasParts[$key] = (isset($parameters['cid'])) ? $parameters['cid'] : '';
					break;
				case '{category-hierarchy}':
					$aliasParts[$key] = (isset($parameters['cid'])) ? $this->categoryHierarchy($parameters['cid'], $baseCategory) : '';
					break;
				case '{product}':
					$aliasParts[$key] = (isset($parameters['id'])) ? $parameters['id'] : '';
					break;
			}
		}

		return implode('/', array_filter($aliasParts));
	}

	public function categoryHierarchy($categoryId, $baseCategory = null) {
		if ($categoryId == '%CATEGORY%') {
			return $categoryId;
		}

		$result = $categoryId;

		$categories = CdsCollection::create('categories', $this->service)->getItems();

		$category = $categories[$categoryId];

		while (!empty($category['parent'])) {
			$category = $categories[$category['parent']];

			if ($category['id'] == $baseCategory) {
				break;
			}

			$result = $category['id'] . '/' . $result;
		}

		return $result;
	}

	public function getPageAlias($page) {
		if (isset($this->pageAliases[$page])) {
			return $this->pageAliases[$page];
		}

		return $page;
	}

	public function inProductSection() {
		return true;
	}

	protected function getBasePath($uri) {
		return '';
	}
}
