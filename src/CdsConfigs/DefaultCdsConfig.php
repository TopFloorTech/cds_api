<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 2/7/2016
 * Time: 2:23 PM
 */

namespace TopFloor\Cds\CdsConfigs;


class DefaultCdsConfig extends CdsConfig
{
    protected $iniProperties = array();

    protected function initialize()
    {
        parent::initialize();

        $iniPath = $this->cdsPath() . '/cds.ini.php';
        $this->iniProperties = file_exists($iniPath) ? parse_ini_file($iniPath) : array();
    }

    public function get($key)
    {
        if (isset($this->iniProperties[$key])) {
            if ($this->iniProperties[$key] === "null") {
                return null;
            }

            return $this->iniProperties[$key];
        }

        return null;
    }
}