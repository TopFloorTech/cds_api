<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/19/2015
 * Time: 10:08 PM
 */

namespace TopFloor\Cds\CdsUrlHandlers;

use TopFloor\Cds\CdsCollections\CdsCollection;
use TopFloor\Cds\CdsEntities\CdsEntityFactory;

abstract class PrettyCdsUrlHandler extends CdsUrlHandler
{
    protected $defaultPage = 'search';

    public function getEntityFromUri($uri = null, $baseUri = null) {
        $page = $this->getPageFromUri($uri, $baseUri);

        $entity = false;

        if ($page) {
            switch ($page) {
                case 'cart':
                case 'compare':
                case 'keys':
                    $entity = CdsEntityFactory::create($this->service, 'utility', $page);
                    break;
                case 'search':
                case 'product':
                    $entity = CdsEntityFactory::create($this->service, $this->pageAliases[$page], $this->getIdFromUri($uri, $baseUri));
                    break;
            }
        }

        return $entity;
    }

    public function getIdFromUri($uri = null, $baseUri = null) {
        $uri = $this->standardizeUri($uri, $baseUri, true);

        $pathParts = explode('/', $uri);
        $id = array_pop($pathParts);

        return $id;
    }

    public function getPageFromUri($uri = null, $baseUri = null) {
        $uri = $this->standardizeUri($uri, $baseUri, false);

        if (!empty($uri)) {
            $utilityPage = $this->matchUtilityAlias($uri, $baseUri);

            if ($utilityPage) {
                return $utilityPage;
            }

            $pathParts = explode('/', $uri);

            $lastPart = array_pop($pathParts);

            if (!empty($baseUri)) {
                if ($uri == $baseUri) {
                    return 'search';
                }

                $categories = CdsCollection::create('categories', $this->service)->getItems();

                return array_key_exists($lastPart, $categories) ? 'search' : 'product';
            }
        }

        if (!empty($baseUri)) {
            return $this->defaultPage;
        }

        return false;
    }

    public function standardizeUri($uri = null, $baseUri = null, $stripBaseUri = true) {
        if (is_null($uri)) {
            $uri = $this->getCurrentUri();
        }

        if ($stripBaseUri && is_null($baseUri)) {
            $baseUri = $this->getBasePath($uri);
        }

        if (strlen($uri) > 0 && substr($uri, 0, 1) == '/') {
            $uri = substr($uri, 1);
        }

        if ($stripBaseUri && !is_null($baseUri) && strlen($uri) >= strlen($baseUri)) {
            if (substr($uri, 0, strlen($baseUri)) === $baseUri) {
                $uri = substr($uri, strlen($baseUri));
            }
        }

        if (strlen($uri) > 0 && substr($uri, 0, 1) == '/') {
            $uri = substr($uri, 1);
        }

        return $uri;
    }
}
