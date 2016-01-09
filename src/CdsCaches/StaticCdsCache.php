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

    public function &get($key)
    {
        if (!isset(self::$cache[$key])) {
            return null;
        }

        return self::$cache[$key];
    }

    public function set($key, &$value)
    {
        self::$cache[$key] = &$value;
    }
}