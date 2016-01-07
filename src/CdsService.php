<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 10:14 AM
 */

namespace TopFloor\Cds;

use TopFloor\Cds\CdsComponents\CdsComponent;
use TopFloor\Cds\CdsPages\CdsPage;
use TopFloor\Cds\CdsPages\CdsPageInterface;
use TopFloor\Cds\CdsRenderers\CdsRendererInterface;
use TopFloor\Cds\CdsRenderers\TwigCdsRenderer;
use TopFloor\Cds\Collections\CdsComponentCollection;
use TopFloor\Cds\Collections\CdsDependencyCollection;
use TopFloor\Cds\Exceptions\CdsServiceException;
use TopFloor\Cds\Helpers\CdsBreadcrumbsHelper;
use TopFloor\Cds\UrlHandlers\DefaultUrlHandler;
use TopFloor\Cds\RequestHandlers\CurlRequestHandler;
use TopFloor\Cds\RequestHandlers\FsockopenRequestHandler;
use TopFloor\Cds\RequestHandlers\RequestHandler;
use TopFloor\Cds\ResponseParsers\JsonResponseParser;
use TopFloor\Cds\ResponseParsers\ResponseParser;
use TopFloor\Cds\UrlHandlers\EnvironmentBasedUrlHandler;

class CdsService {
	private $host;
	private $domain;
	private $unitSystem = 'english';

	private $availablePages = array(
		'cart' => 'TopFloor\Cds\CdsPages\CartCdsPage',
		'compare' => 'TopFloor\Cds\CdsPages\CompareCdsPage',
		'keys' => 'TopFloor\Cds\CdsPages\KeysCdsPage',
		'product' => 'TopFloor\Cds\CdsPages\ProductCdsPage',
		'search' => 'TopFloor\Cds\CdsPages\SearchCdsPage',
	);

	/** @var CdsPage $page */
	private $page;
	private $responseParser;
	private $requestHandler;
	private $urlHandler;
	private $breadcrumbs;
    private $useRfqCart = true;
	private $dependencies;
	private $categoryInfo;
    private $productInfo;
	private $renderer;
	private $viewsDir;
	private $components;

	public function __construct($host, $domain) {
		$this->host = $host;
		$this->domain = $domain;
		$this->viewsDir = dirname(dirname(__FILE__)) . '/views';
		$this->responseParser = new JsonResponseParser();
		$this->urlHandler = new DefaultUrlHandler($this);
		$this->breadcrumbs = new CdsBreadcrumbsHelper($this);
		$this->categoryInfo = new CdsCategoryInfo($this);
        $this->productInfo = new CdsProductInfo($this);
		$this->renderer = new TwigCdsRenderer($this);
		$this->components = new CdsComponentCollection();

		// try to get unit system from request then cookie
		if (isset($_REQUEST['unit'])) {
			$this->unitSystem = $_REQUEST['unit'];
		} else if (isset($_COOKIE['cds_catalog_unit'])) {
			$this->unitSystem = $_COOKIE['cds_catalog_unit'];
		}
		setcookie('cds.catalog.unit', $this->unitSystem, time() + (86400 * 365));

		$this->dependencies = new CdsDependencyCollection(array(
			'js' => array(
				'cds-catalog' => $this->baseUrl() . '/js/cds-catalog-min.js',
				'custom-cds-js' => $this->baseUrl() . '/d/' . $this->domain . '/cds.js',
				'cds-api' => 'cds-api.js',
			),
			'css' => array(
				'cds-catalog' => $this->baseUrl() . '/css/catalog-3.1.css',
			),
			'settings' => array(
				'host' => $this->getHost(),
				'domain' => $this->getDomain(),
				'unitSystem' => $this->getUnitSystem(),
				'baseUrl' => $this->baseUrl(),
			),
		));

		// Use cURL if it is available, or fall back to Fsockopen
		if (function_exists('curl_version')) {
			$this->requestHandler = new CurlRequestHandler($this);
		} else {
			$this->requestHandler = new FsockopenRequestHandler($this);
		}
	}

	public function getRenderer() {
		return $this->renderer;
	}

	public function setRenderer(CdsRendererInterface $renderer) {
		$this->renderer = $renderer;
	}

	public function getViewsDir() {
		return $this->viewsDir;
	}

	public function getAvailablePages() {
		return $this->availablePages;
	}

	public function addAvailablePage($slug, $class) {
		$this->availablePages[$slug] = $class;
	}

    public function baseUrl() {
        $host = htmlspecialchars($this->getHost());

        return 'http://' . $host . '/catalog3';
    }

	public function getHost() {
		return $this->host;
	}

	public function getDomain() {
		return $this->domain;
	}

    public function useRfqCart($useRfqCart = null) {
        if (!is_null($useRfqCart)) {
            $this->useRfqCart = ($useRfqCart);
        }

        return $this->useRfqCart;
    }

    public function getPage() {
        return $this->page;
    }

	public function getCategoryInfo() {
		return $this->categoryInfo;
	}

	public function getProductInfo() {
        return $this->productInfo;
    }

	/**
	 * @return EnvironmentBasedUrlHandler
	 */
	public function getUrlHandler() {
		return $this->urlHandler;
	}

	public function getBreadcrumbsHelper() {
		return $this->breadcrumbs;
	}

	public function setBreadcrumbsHelper(CdsBreadcrumbsHelper $breadcrumbsHelper) {
		$this->breadcrumbs = $breadcrumbsHelper;
	}

	public function setUrlHandler($urlHandler) {
		$this->urlHandler = $urlHandler;
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

	public function loadingGraphic() {
		$url = 'http://' . htmlspecialchars($this->getHost())
		       . '/catalog3/images/progress_animation_large.gif';

		return $url;
	}

	public function setUnitSystem($unitSystem) {
		$this->unitSystem = $unitSystem;
	}

	public function request($resource) {
		$request = new CdsRequest($this, $this->requestHandler, $this->responseParser);
		$request->setResource($resource);

		return $request;
	}

	public function jsSettings() {
		$dependencies = $this->getDependencies();
        $settings = $dependencies->settings();

		$output = "window.TopFloor = window.TopFloor || {};\n";
		$output .= "TopFloor.Cds = TopFloor.Cds || {};\n";
		$output .= "TopFloor.Cds.Settings = " . json_encode($settings) . ";";

		return $output;
	}

	public function getDependencies() {
		$dependencies = new CdsDependencyCollection();

		$dependencies->addDependencies($this->dependencies->getDependencies());

		$dependencies->addDependencies($this->components->getDependencies()->getDependencies());

		if (!empty($this->page)) {
			$dependencies->addDependencies($this->page->getDependencies()->getDependencies());
		}

		return $dependencies;
	}

	public function createPage($slug) {
		$availablePages = $this->getAvailablePages();

		if (empty($availablePages[$slug]) || !class_exists('\\' . $availablePages[$slug])) {
			throw new CdsServiceException('Requested page slug "' . $slug . '" is not available.');
		}

        $pageName = $availablePages[$slug];

		/** @var CdsPageInterface $page */
		$page = new $pageName($this);

		return $page;
	}

	public function setPage(CdsPageInterface $page) {
		$this->page = $page;
	}

	public function addComponent(CdsComponent $component) {
		$this->components->addComponent($component);
	}

	/**
	 * Return all executable JS defined by this service.
	 *
	 * @return string
	 */
	public function execute() {
		$template = 'jQuery(document).ready(function () { %s });';

		$output = 'TopFloor.Cds.initialize();' . "\n";

		if (!empty($this->page)) {
			$output .= $this->page->execute();
		}

		$output .= $this->components->execute();

		return sprintf($template, $output);
	}

    public function output() {
		if (!empty($this->page)) {
			return $this->page->output();
		}

		return '';
    }

    public function sidebarOutput() {
		if (!empty($this->page)) {
			return $this->page->sidebarOutput();
		}

		return '';
    }

	public function pageTitle() {
		$title = '';

		if (!empty($this->page)) {
			$title = $this->page->pageTitle();
		}

		return $title;
	}

	public function slugify($input) {
		preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
		$ret = $matches[0];

		foreach ($ret as &$match) {
			$match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
		}

		return implode('-', $ret);
	}
}
