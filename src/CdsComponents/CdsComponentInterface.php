<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:55 PM
 */

namespace TopFloor\Cds\CdsComponents;

use TopFloor\Cds\Collections\CdsDependencyCollection;

interface CdsComponentInterface {
	public function execute();

	public function output();

	public function setParameters($parameters = array());

	public function getParameters($encode = false);

	public function getParameter($name);

	public function setParameter($name, $value);

    /**
     * @return CdsDependencyCollection
     */
	public function getDependencies();

	public function isEnabled();

	public function getName();
}
