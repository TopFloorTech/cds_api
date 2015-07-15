<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 10:14 AM
 */

Namespace TopFloor\Cds;

use TopFloor\Cds\RequestHandlers\CurlRequestHandler;
use TopFloor\Cds\RequestHandlers\FsockopenRequestHandler;
use TopFloor\Cds\RequestHandlers\RequestHandler;
use TopFloor\Cds\ResponseParsers\JsonResponseParser;
use TopFloor\Cds\ResponseParsers\ResponseParser;

class CdsService {
	private $host;
	private $domain;
	private $unitSystem = 'english';
	private $responseParser;
	private $requestHandler;

	public function __construct($host, $domain) {
		$this->host = $host;
		$this->domain = $domain;
		$this->responseParser = new JsonResponseParser();

		// Use cURL if it is available, or fall back to Fsockopen
		if (function_exists('curl_version')) {
			$this->requestHandler = new CurlRequestHandler($this);
		} else {
			$this->requestHandler = new FsockopenRequestHandler($this);
		}

	}

	public function getHost() {
		return $this->host;
	}

	public function getDomain() {
		return $this->domain;
	}

	public function getRequestHandler() {
		return $this->requestHandler;
	}

	public function setRequestHandler(RequestHandler $requestHandler) {
		$this->requestHandler = $requestHandler;
	}

	public function getResponseParser() {
		return $this->responseParser;
	}

	public function setResponseParser(ResponseParser $parser) {
		$this->responseParser = $parser;
	}

	public function getUnitSystem() {
		return $this->unitSystem;
	}

	public function setUnitSystem($unitSystem) {
		$this->unitSystem = $unitSystem;
	}

	public function request($resource) {
		$request = new CdsRequest($this, $this->requestHandler, $this->responseParser);
		$request->setResource($resource);

		return $request;
	}

	public function productRequest($id, $category = null) {
		$resourceTemplate = '/catalog3/service?o=product&d=%s&id=%s&unit=%s';
		$categoryTemplate = '&cid=%s';

		$resource = sprintf($resourceTemplate, $this->getDomain(), $id, $this->getUnitSystem());

		if (!is_null($category)) {
			$resource .= sprintf($categoryTemplate, $category);
		}

		return $this->request($resource);
	}

	public function categoryRequest($category) {
		$resourceTemplate = '/catalog3/service?o=category&d=%s&cid=%s&unit=%s';

		$resource = sprintf($resourceTemplate, $this->getDomain(), $category, $this->getUnitSystem());

		return $this->request($resource);
	}
}
