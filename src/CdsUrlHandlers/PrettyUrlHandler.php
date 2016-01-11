<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/19/2015
 * Time: 10:08 PM
 */

namespace TopFloor\Cds\CdsUrlHandlers;

abstract class PrettyCdsUrlHandler extends CdsUrlHandler
{
    protected $defaultPage = 'search';

    protected $aliasPrefixes = array(
        'product' => 'product',
    );

    protected $pagePrefixes = array(
        'cart' => 'cart',
        'compare' => 'compare',
        'keys' => 'keys',
        'product' => '',
        'search' => '',
    );

    public $uriMatchers = array(
        '^([^/]+)/([^/]+)$' => 'product',
        '^([^/]+)$' => 'search',
    );

    public function getPageFromUri($uri = null, $baseUri = null) {
        $uri = $this->standardizeUri($uri, $baseUri);

        if (!empty($uri)) {
            $pathParts = explode('/', $uri);

            if (isset($pathParts[0])) {
                if (array_key_exists($pathParts[0], $this->aliasPrefixes)) {
                    return $this->aliasPrefixes[$pathParts[0]];
                }

                $pagePrefix = array_search($pathParts[0], $this->pagePrefixes);

                if ($pagePrefix !== false) {
                    return $this->pagePrefixes[$pathParts[0]];
                }
            }

            foreach ($this->uriMatchers as $uriMatcher => $page) {
                if (preg_match('|' . $uriMatcher . '|', $uri)) {
                    return $page;
                }
            }
        }

        return $this->defaultPage;
    }

    public function standardizeUri($uri = null, $baseUri = null) {
        if (is_null($uri)) {
            $uri = $this->getCurrentUri();
        }

        if (strlen($uri) > 0 && substr($uri, 0, 1) == '/') {
            $uri = substr($uri, 1);
        }

        if (!is_null($baseUri) && strlen($uri) >= strlen($baseUri)) {
            if (substr($uri, 0, strlen($baseUri)) === $baseUri) {
                $uri = substr($uri, strlen($baseUri));
            }
        }

        if (strlen($uri) > 0 && substr($uri, 0, 1) == '/') {
            $uri = substr($uri, 1);
        }

        return $uri;
    }

    public function getUriForPage($page, $basePath = null) {
        $uri = '/';

        if (!is_null($basePath)) {
            if (substr($basePath, 0, 1) == '/') {
                $basePath = substr($basePath, 1);
            }

            $uri .= $basePath;
        }

        if ($page != $this->defaultPage) {
            if (isset($this->pagePrefixes[$page])) {
                if (!empty($this->pagePrefixes[$page])) {
                    $uri .= '/' . $this->pagePrefixes[$page];
                }
            } else {
                $uri .= '/' . $page;
            }
        }

        return $uri;
    }
}
