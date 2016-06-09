<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 2/9/2016
 * Time: 1:07 PM
 */

namespace TopFloor\Cds;

use CDS;
use CDSConfiguration;
use CDSWebService;

class CustomCds extends CDS
{
    protected $service;

    public function __construct(CdsService $service, $cdsPath = null, $request = null) {
        $this->service = $service;

        if (is_null($cdsPath)) {
            $cdsPath = dirname(__FILE__) . '/';
        }

        $this->request = is_null($request) ? $_REQUEST : $request;

        require_once($cdsPath . 'conf.php');
        $this->domainID = CDSConfiguration::DOMAIN;
        $this->host = CDSConfiguration::HOST;
        $this->pageTitlePrefix = CDSConfiguration::PAGE_TITLE_PREFIX;
        $this->pageTitleSuffix = CDSConfiguration::PAGE_TITLE_SUFFIX;
        $this->rootCategoryID = CDSConfiguration::ROOT_CATEGORY_ID;
        $this->unitSystem = CDSConfiguration::DEFAULT_UNIT_SYSTEM;
        $this->customCDSStyle = CDSConfiguration::CUSTOM_STYLE;
        $this->customCDSScript = CDSConfiguration::CUSTOM_SCRIPT;
        $this->cdsNeedsOnLoad = CDSConfiguration::SCRIPT_NEEDS_ONLOAD;
        $this->shouldRewriteLinks = CDSConfiguration::SHOULD_REWRITE_LINKS;
        $this->disableCategoryFacets = CDSConfiguration::DISABLE_CATEGORY_FACETS;
        $this->customFacetContainerID = CDSConfiguration::CUSTOM_FACET_CONTAINER_ID;
        $this->filteredSearchPageAnchor = CDSConfiguration::FILTERED_SEARCH_PAGE_ANCHOR;
        $this->disableSearchPageH2 = CDSConfiguration::DISABLE_SEARCH_PAGE_H2;
        $this->rewriteLinkStyle = CDSConfiguration::REWRITE_LINK_STYLE;
        $this->shouldParseURLs = CDSConfiguration::SHOULD_PARSE_URLS;
        $this->catalogURLPrefix = CDSConfiguration::CATALOG_URL_PREFIX;
        $this->productPageURLPrefix = CDSConfiguration::PRODUCT_PAGE_URL_PREFIX;
        $this->enableCrumbs = CDSConfiguration::ENABLE_CRUMBS;

        if ($this->customFacetContainerID == null) {
            $this->customFacetContainerID = "cds-search-left-container";
        }

        // try to get unit system from request then cookie
        if (isset($this->request['unit'])) {
            $this->unitSystem = $this->request['unit'];
        } else if (isset($_COOKIE['cds_catalog_unit'])) {
            $this->unitSystem = $_COOKIE['cds_catalog_unit'];
        }
        setcookie('cds.catalog.unit', $this->unitSystem, time() + (86400 * 365), '/');

        // determine which catalog page, and load page specific resources
        // from web services
        $this->urlManager = new CustomCdsUrlManager($service, $this);

        $this->page = isset($page) ? $page : 'search';
        if (isset($this->request['page'])) {
            $this->page = $this->request['page'];
        }
        if (isset($this->request['cid'])) {
            $this->categoryID = $this->request['cid'];
        }
        if (isset($this->request['id'])) {
            $this->productID = $this->request['id'];
        }
        if ($this->shouldParseURLs) {
            $this->urlManager->parseCatalogURL($this->page, $this->categoryID, $this->productID);
        }
        if ($this->page !== 'product' && $this->categoryID === null) {
            $this->categoryID = $this->rootCategoryID;
        }

        // product page
        require_once($cdsPath . 'CDSWebService.php');
        $this->webService = new CDSWebService($this->host, $this->unitSystem);

        if ($this->page === 'product') {
            $this->product = $this->webService->sendProductRequest($this->domainID, $this->productID, $error,
                $this->categoryID);
            if ($error !== false) {
                //header('Location: catalog-error.html');
                dpm($error);
                return;
            }
            $this->category = $this->product['category'];
            $this->categoryID = isset($this->category['id']) ? $this->category['id'] : $this->rootCategoryID;
            $this->domain = $this->product['requestDomain'];

            // search page
        } elseif ($this->page === 'search') {
            $this->category = $this->webService->sendCategoryRequest($this->domainID, $this->categoryID, $error);
            if ($error !== false) {
                //header('Location: catalog-error.html');
                dpm($error);
                return;
            }
            $this->domain = $this->category['requestDomain'];
            // all other pages
        } else {
            $this->domain = $this->webService->sendDomainRequest($this->domainID, $error);
            if ($error !== false) {
                //header('Location: catalog-error.html');
                dpm($error);
                return;
            }
        }

        // get domain properties
        $this->enableUnitToggle = $this->isProperty('global.displayUnitToggle');
        $this->enableSearchWithinResults = $this->isProperty('search.allowKeywordsNarrowResults');
        $this->enablePowerGrid = CDSConfiguration::ENABLE_POWER_GRID;
        $this->defaultSearchResultsType = $this->getProperty('search.defaultProductListType');
        $this->enableRFQCart = $this->isProperty('products.enableCart');
        $this->enableSpecSheet = CDSConfiguration::ENABLE_SPEC_SHEET;
        $this->enableEmbeddedViewer = $this->isProperty('products.displayObjViewer');
        $this->enableLegacyFacetedSearch = !($this->isProperty('search.useCloudSearch'));
    }
}
