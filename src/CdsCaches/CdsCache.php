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
    abstract function &get($key);

    abstract function set($key, &$value);

    public function exists($key) {
        $value = $this->get($key);

        return (!empty($value));
    }
}
