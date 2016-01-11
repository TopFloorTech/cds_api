<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/7/2016
 * Time: 5:11 PM
 */

namespace TopFloor\Cds;

use \CDS;
use TopFloor\Cds\CdsCaches\CdsCache;
use TopFloor\Cds\CdsConfigs\CdsConfig;
use TopFloor\Cds\CdsRequests\CategoryCdsRequest;
use TopFloor\Cds\CdsRequests\DomainCdsRequest;
use TopFloor\Cds\CdsRequests\ProductCdsRequest;
use TopFloor\Cds\CdsRequests\ProductsCdsRequest;
use TopFloor\Cds\CdsUrlHandlers\CdsUrlHandler;
use TopFloor\Cds\CdsUrlHandlers\DefaultCdsUrlHandler;
use TopFloor\Cds\RequestHandlers\RequestHandler;
use TopFloor\Cds\ResponseParsers\JsonResponseParser;

class CdsService
{
    protected static $service;

    protected $cds;

    protected $cdsConfig;
    protected $cdsCache;
    protected $requestHandler;
    protected $responseParser;
    protected $urlHandler;

    protected function __construct(CdsConfig $cdsConfig, CdsUrlHandler $urlHandler = null, CdsCache $cdsCache = null) {
        $this->cdsConfig = $cdsConfig;

        if (is_null($cdsCache)) {
            $cdsCache = new \StaticCdsCache();
        }
        $this->cdsCache = $cdsCache;

        if (is_null($urlHandler)) {
            $urlHandler = new DefaultCdsUrlHandler($this);
        }
        $this->urlHandler = $urlHandler;

        $this->requestHandler = RequestHandler::create($this);
        $this->responseParser = new JsonResponseParser();

        $this->loadCds();
    }

    protected function loadCds() {
        $path = $this->cdsConfig->cdsPath() . 'cds.php';
        if (file_exists($path)) {
            require_once($path);
            $this->cds = new CDS();
        }
    }

    public static function getService($cdsConfig, $cdsUrlHandler = null, $cdsCache = null) {
        if (!isset(self::$service)) {
            self::$service = new CdsService($cdsConfig, $cdsUrlHandler, $cdsCache);
        }

        return self::$service;
    }

    public function getConfig() {
        return $this->cdsConfig;
    }

    public function getCache() {
        return $this->cdsCache;
    }

    public function getUrlHandler() {
        return $this->urlHandler;
    }

    public function setUrlHandler($urlHandler) {
        $this->urlHandler = $urlHandler;
    }

    public function domainRequest() {
        $request = new DomainCdsRequest($this, $this->requestHandler, $this->responseParser);

        return $request;
    }

    public function categoryRequest($category) {
        $request = new CategoryCdsRequest($this, $this->requestHandler, $this->responseParser);

        $request->setCategory($category);

        return $request;
    }

    public function productRequest($productId, $categoryId = null) {
        $request = new ProductCdsRequest($this, $this->requestHandler, $this->responseParser);

        $request->setProduct($productId);
        $request->setCategory($categoryId);

        return $request;
    }

    public function productsRequest($categoryId, $page = 0, $productsPerPage = 15) {
        $request = new ProductsCdsRequest($this, $this->requestHandler, $this->responseParser);

        $request->setCategory($categoryId);
        $request->setPage($page);
        $request->setProductsPerPage($productsPerPage);

        return $request;
    }
}
