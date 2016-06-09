<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 4:57 PM
 */

namespace TopFloor\Cds\CdsUrlHandlers;

class DefaultCdsUrlHandler extends CdsUrlHandler {
	protected $defaultPage = 'search';

	public function construct($parameters = array(), $append = null, $basePath = null) {
		if (is_null($append)) {
			$append = '';
		}

		if (is_null($basePath)) {
			$basePath = '';
		}

		$parameters = $this->buildParameters($parameters);

		$url = $basePath;

		foreach ($parameters as $key => $value) {
			$url .= (strpos($url, '?') === false) ? '?' : '&';

			$url .= urlencode($key) . '=' . urlencode($value);
		}

		return $url . $append;
	}

	public function deconstruct($url, $basePath = null) {
		$parameters = array();

		if (!is_null($basePath)) {
			if (substr($url, 0, strlen($basePath)) == $basePath) {
				$url = substr($url, strlen($basePath));
			}
		}

		parse_str($url, $parameters);

		return $parameters;
	}

	public function getPageFromUri($uri = null, $basePath = null)
	{
		if (!is_null($basePath)) {
			$uri = substr($uri, 0, strlen($basePath) + 1);
		}

		$params = $this->deconstruct($uri);

		if (isset($params['page'])) {
			return $params['page'];
		}

		return '';
	}

	public function getAliasForPage($page, $basePath = null)
	{
		$aliases = $this->service->getConfig()->get('aliases');

		$type = $this->pageAliases[$page];

		$alias = $aliases[$type];

		return $alias;
	}
}
