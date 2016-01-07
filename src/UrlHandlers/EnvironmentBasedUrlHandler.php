<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/19/2015
 * Time: 10:36 PM
 */

namespace TopFloor\Cds\UrlHandlers;

abstract class EnvironmentBasedUrlHandler extends PrettyUrlHandler
{
    public function construct($parameters = array(), $append = '', $baseUri = null)
    {
        $parameters = $this->buildParameters($parameters);

        $url = $this->getUriForPage($parameters['page'], $baseUri);

        if (!empty($parameters['cid'])) {
            $url .= '/' . $parameters['cid'];
        }

        if (!empty($parameters['id'])) {
            $url .= '/' . $parameters['id'];
        }

        return $url . $append;
    }

    public function deconstruct($url, $basePath = null)
    {
        if (substr($url, 0, 1) == '/') {
            $url = substr($url, 1);
        }

        if (is_null($basePath)) {
            $basePath = false;

            foreach ($this->getEnvironments() as $envBasePath => $envCategoryId) {
                if ($url == $envBasePath
                  || $url == $envBasePath . '/'
                  || preg_match('|^' . $envBasePath . '/|', $url) !== false) {
                    $basePath = $envBasePath;

                    break;
                }
            }
        }

        $parameters = array();

        if ($basePath) {
            $parameters['page'] = $this->getPageFromUri($url, $basePath);
            $parameters['cid'] = $this->getBaseCategoryId($basePath);

            // Remove base path from URL
            $url = substr($url, strlen($basePath));

            // Remove query string from URL
            $queryPos = strpos($url, '?');
            if ($queryPos !== false) {
                $url = substr($url, 0, $queryPos);
            }

            // Remove leading and trailing slashes from URL
            if (substr($url, 0, 1) == '/') {
                $url = substr($url, 1);
            }

            if (substr($url, strlen($url)) == '/') {
                $url = substr($url, 0, strlen($url) - 1);
            }

            $pathParts = explode('/', $url);

            if (count($pathParts) > 0) {
                if (array_search($pathParts[0], $this->pagePrefixes)) {
                    array_shift($pathParts);
                }
            }

            if (!empty($pathParts[0])) {
                $parameters['cid'] = $pathParts[0];
            }

            if (!empty($pathParts[1])) {
                $parameters['id'] = $pathParts[1];
            }
        }

        return $this->buildParameters($parameters);
    }

    protected abstract function getEnvironments();

    public function getPageFromUri($uri = null, $baseUri = null) {
        if (empty($baseUri)) {
            $baseUri = $this->getCurrentEnvironment();

            if (empty($baseUri)) {
                return '';
            }
        }

        return parent::getPageFromUri($uri, $baseUri);
    }

    public function getUriForPage($page, $basePath = null) {
        if (is_null($basePath)) {
            $basePath = $this->getCurrentEnvironment();
        }

        return parent::getUriForPage($page, $basePath);
    }

    public function buildParameters($parameters = array()) {
        $basePath = $this->getCurrentEnvironment();

        if ($basePath !== false) {
            $baseCategoryId = $this->getBaseCategoryId($basePath);

            if (!isset($parameters['cid'])) {
                $parameters['cid'] = $baseCategoryId;
            }
        }

        return parent::buildParameters($parameters);
    }

    protected function environmentIsActive($basePath)
    {
        $requestUri = $this->getCurrentUri();

        if (substr($requestUri, 0, 1) == '/') {
            $requestUri = substr($requestUri, 1);
        }

        if ($requestUri == $basePath) {
            return true;
        }

        if ($requestUri == $basePath . '/') {
            return true;
        }

        return (preg_match('|^' . $basePath . '\/|', $requestUri));
    }

    public function getCurrentEnvironment()
    {
        foreach ($this->getEnvironments() as $basePath => $categoryId) {
            if ($this->environmentIsActive($basePath)) {
                return $basePath;
            }
        }

        return false;
    }

    public function getEnvironmentUri() {
        $uri = parent::getCurrentUri();

        $environment = $this->getCurrentEnvironment();

        if (!$environment || $uri == $environment || strlen($environment) >= strlen($uri)) {
            return '';
        }

        $uri = substr($uri, strlen($environment) + 1);

        if (substr($uri, 0, 1) == '/') {
            $uri = substr($uri, 1);
        }

        return $uri;
    }

    protected function getBaseCategoryId($uri = null)
    {
        if (is_null($uri)) {
            $uri = $this->getCurrentEnvironment();
        }

        $environments = $this->getEnvironments();

        if (!empty($environments[$uri] )) {
            return $environments[$uri];
        }

        return '';
    }
}
