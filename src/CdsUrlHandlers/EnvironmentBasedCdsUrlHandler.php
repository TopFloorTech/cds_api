<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/19/2015
 * Time: 10:36 PM
 */

namespace TopFloor\Cds\CdsUrlHandlers;

abstract class EnvironmentBasedCdsUrlHandler extends PrettyCdsUrlHandler
{
    public function construct($parameters = array(), $append = null, $basePath = null)
    {
        $parameters = $this->buildParameters($parameters);

        $uri = '/';

        if (is_null($append)) {
            $append = '';
        }

        if (is_null($basePath)) {
            $basePath = $this->getCurrentEnvironment();
        }

        $alias = $this->getAliasForPage($parameters['page']);
        $alias = $this->standardizeAlias($alias, $basePath, $parameters);

        $uri .= $alias;

        return $uri . $append;
    }

    public function standardizeAlias($alias, $baseUri = null, $parameters = array(), $baseCategory = null)
    {
        if (is_null($baseUri)) {
            $baseUri = $this->getCurrentEnvironment();
        }

        if (is_null($baseCategory) && $baseUri) {
            $environments = $this->getEnvironments();

            $baseCategory = isset($environments[$baseUri]) ? $environments[$baseUri] : null;
        }

        return parent::standardizeAlias($alias, $baseUri, $parameters, $baseCategory);
    }

    public function deconstruct($url, $basePath = null)
    {
        if (substr($url, 0, 1) == '/') {
            $url = substr($url, 1);
        }

        if (is_null($basePath)) {
            $basePath = $this->getBasePath($url);
        }

        $parameters = array();

        if ($basePath) {
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

            $entity = $this->getEntityFromUri($url, $basePath);

            if ($entity) {
                $parameters += $entity->getParameters();
            }
        }

        return $this->buildParameters($parameters);
    }

    protected abstract function getEnvironments();

    protected function getBasePath($uri) {
        $basePath = null;

        foreach ($this->getEnvironments() as $envBasePath => $envCategoryId) {
            if ($uri == $envBasePath
                || $uri == $envBasePath . '/'
                || preg_match('|^' . $envBasePath . '/|', $uri) !== false) {
                $basePath = $envBasePath;

                break;
            }
        }

        return $basePath;
    }

    public function getIdFromUri($uri = null, $baseUri = null) {
        $uri = $this->standardizeUri($uri, $baseUri, false);

        if (is_null($baseUri)) {
            $baseUri = $this->getBasePath($uri);

            if (empty($baseUri)) {
                return '';
            }
        }

        $environments = $this->getEnvironments();

        if (empty($uri) && array_key_exists($baseUri, $environments)) {
            return $environments[$baseUri];
        }

        return parent::getIdFromUri($uri, $baseUri);
    }

    public function getPageFromUri($uri = null, $baseUri = null) {
        if (empty($baseUri)) {
            $baseUri = $this->getCurrentEnvironment();
        }

        return parent::getPageFromUri($uri, $baseUri);
    }

    public function inProductSection() {
        return ($this->getCurrentEnvironment());
    }

    public function buildParameters($parameters = array(), $basePath = null) {
        if (is_string($parameters)) {
            $parameters = $this->parseQueryString($parameters);
        }

        if (is_null($basePath)) {
            $basePath = $this->getCurrentEnvironment();
        }

        if ($basePath !== false && !isset($parameters['cid'])) {
            $parameters['cid'] = $this->getBaseCategoryId($basePath);;
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

    public function getCurrentEnvironment($fallbackToDefault = false)
    {
        $environments = $this->getEnvironments();

        foreach ($environments as $basePath => $categoryId) {
            if ($this->environmentIsActive($basePath)) {
                return $basePath;
            }
        }

        if ($fallbackToDefault && !empty($environments)) {
            return array_keys($environments)[0];
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

    public function getEnvironmentCategoryId($basePath) {
        $environments = $this->getEnvironments();

        if (!array_key_exists($basePath, $environments)) {
            return false;
        }

        return $environments[$basePath];
    }
}
