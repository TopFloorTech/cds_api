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
    public function &get($key);

    public function set($key, &$value);

    public function exists($key);
}
