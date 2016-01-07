<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:55 PM
 */

namespace TopFloor\Cds\CdsPages;

use TopFloor\Cds\Collections\CdsDependencyCollection;

interface CdsPageInterface {
	public function pageTitle();

	public function output();

	public function setParameters($parameters = array());

	public function getParameters($encode = false);

	public function getParameter($name);

	public function setParameter($name, $value);

	public function execute();

	public function sidebarOutput();

	public function getComponents();

	/**
	 * @return CdsDependencyCollection
	 */
	public function getDependencies();
}
