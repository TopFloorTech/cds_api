<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/7/2016
 * Time: 5:11 PM
 */

namespace TopFloor\Cds;

use CDS;

require_once(dirname(dirname(__FILE__)) . '/cds/cds.php');

class CdsService
{
    protected static $service;

    protected $cds;

    protected $cdsPath;

    protected function __construct($cdsPath = null) {
        if (is_null($cdsPath)) {
            $cdsPath = dirname(dirname(__FILE__)) . '/cds/';
        } elseif (substr($cdsPath, -1) !== '/') {
            $cdsPath .= '/';
        }

        $this->cdsPath = $cdsPath;

        require_once($cdsPath . 'cds.php');

        $this->cds = new CDS();
    }

    public static function getService() {
        if (!isset(self::$service)) {
            self::$service = new CdsService();
        }

        return self::$service;
    }
}