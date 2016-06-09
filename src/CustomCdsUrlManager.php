<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 2/9/2016
 * Time: 11:19 AM
 */

namespace TopFloor\Cds;

use CDS;
use CDSUrlManager;
use TopFloor\Cds\CdsUrlHandlers\EnvironmentBasedCdsUrlHandler;

class CustomCdsUrlManager extends CDSUrlManager
{
    protected $service;

    protected $basePath;

    protected $myCds;

    protected $fallbackUrls = array(
        '?page=product&id=%PRODUCT%',
        '?page=product&cid=%CATEGORY%&id=%PRODUCT%',
        '?page=search&cid=%CATEGORY%'
    );

    public function __construct(CdsService $service, CDS $cds, $basePath = null)
    {
        $this->service = $service;
        $this->basePath = $basePath;
        $this->myCds = $cds;

        parent::__construct($cds);
    }

    public function getCatalogURL($catalogURL) {
        /** @var EnvironmentBasedCdsUrlHandler $urlHandler */
        $urlHandler = $this->service->getUrlHandler();

        $basePath = $this->getBasePath($catalogURL);

        $url = $urlHandler->construct($catalogURL, null, $basePath);

        $url = $this->addCategoryHierarchy($url, $basePath);

        return $url;
    }

    protected function getBasePath($catalogUrl) {
        /** @var EnvironmentBasedCdsUrlHandler $urlHandler */
        $urlHandler = $this->service->getUrlHandler();

        $basePath = (is_null($this->basePath))
            ? $urlHandler->getCurrentEnvironment($this->shouldUseFallback($catalogUrl))
            : $this->basePath;

        return $basePath;
    }

    protected function shouldUseFallback($catalogUrl) {
        return in_array($catalogUrl, $this->fallbackUrls);
    }

    protected function addCategoryHierarchy($url, $basePath) {
        if (strpos($url, '%CATEGORY%') === FALSE) {
            return $url;
        }

        $pathArray = $this->getParentPathArray($basePath);

        $replacement = implode('/', $pathArray);

        if (!$this->isProductUrl($url)) {
            $replacement .= '/%CATEGORY%';
        }

        $url = str_replace('%CATEGORY%', $replacement, $url);

        return $url;
    }

    protected function isProductUrl($url) {
        return (strpos($url, '%PRODUCT%') !== FALSE);
    }

    protected function getParentPathArray($basePath) {
        $pathParts = [];

        $baseParts = explode('/', $basePath);
        $end = array_pop($baseParts);

        if (!empty($this->myCds->category["id"]) && $end == $this->myCds->category["id"]) {
            return $pathParts;
        }

        $started = false;

        if (!empty($this->myCds->category)) {
            if (!empty($this->myCds->category["crumbs"])) {
                foreach ($this->myCds->category["crumbs"] as $crumb) {
                    if ($started) {
                        $pathParts[] = $crumb["id"];
                    }

                    if ($end == $crumb['id']) {
                        $started = true;
                    }
                }
            }

            if ($started) {
                $pathParts[] = $this->myCds->category["id"];
            }
        }

        return $pathParts;
    }
}
