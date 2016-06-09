<?php
use TopFloor\Cds\CdsCaches\CdsCache;

/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/7/2016
 * Time: 4:52 PM
 */
class StaticCdsCache extends CdsCache {
    protected static $cache = array();

    public function get($key, $permanent = false)
    {
        if (!isset(self::$cache[$key])) {
            return null;
        }

        return self::$cache[$key];
    }

    public function set($key, &$value, $permanent = false)
    {
        self::$cache[$key] = &$value;
    }

    function clear($key = null, $wildcard = true)
    {
        if ($wildcard) {
            $copy = self::$cache;

            foreach ($copy as $cacheKey => $value) {
                if (strpos($cacheKey, $key) === 0) {
                    unset(self::$cache[$cacheKey]);
                }
            }
        } elseif (!is_null($key)) {
            unset(self::$cache[$key]);
        }
    }
}