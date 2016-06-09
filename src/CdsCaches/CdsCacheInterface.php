<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/7/2016
 * Time: 3:22 PM
 */

namespace TopFloor\Cds\CdsCaches;


interface CdsCacheInterface
{
    public function get($key, $permanent = false);

    public function set($key, &$value, $permanent = false);

    public function exists($key, $permanent = false);

    public function clear($key = null, $wildcard = true);
}
