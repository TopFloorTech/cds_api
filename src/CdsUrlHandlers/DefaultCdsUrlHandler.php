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

	public function construct($parameters = array(), $append = '') {
		$url = '';

		foreach ($parameters as $key => $value) {
			$url .= '&' . urlencode($key) . '=' . urlencode($value);
		}

		return $url . $append;
	}

	public function deconstruct($url) {
		$parameters = array();

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

	public function getUriForPage($page, $basePath = null)
	{
		$uri = $this->construct(array('page' => $page));

		$uri = $basePath . $uri;

		return $uri;
	}
}
