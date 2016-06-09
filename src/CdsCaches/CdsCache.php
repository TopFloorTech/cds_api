<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/7/2016
 * Time: 3:25 PM
 */

namespace TopFloor\Cds\CdsCaches;


abstract class CdsCache implements CdsCacheInterface
{
    abstract function get($key, $permanent = false);

    abstract function set($key, &$value, $permanent = false);

    public function exists($key, $permanent = false) {
        $value = $this->get($key, $permanent);

        return (!empty($value));
    }

    abstract function clear($key = null, $wildcard = true);
}
