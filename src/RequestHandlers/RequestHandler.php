<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 11:09 AM
 */

namespace TopFloor\Cds\RequestHandlers;

use TopFloor\Cds\Cdsrequests\CdsRequest;
use TopFloor\Cds\CdsService;

class RequestHandler {
	/** @var CdsService $service */
	protected $service;

	protected function __construct($service) {
		$this->service = $service;
	}

	public function send(CdsRequest $request) {
		// This class is never instantiated itself, only via a child class.
		return false;
	}

	public static function create($service) {
		// Use cURL if it is available, or fall back to Fsockopen
		if (function_exists('curl_version')) {
			$requestHandler = new CurlRequestHandler($service);
		} else {
			$requestHandler = new FsockopenRequestHandler($service);
		}

		return $requestHandler;
	}
}