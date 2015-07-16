<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:56 PM
 */

namespace Cds\CdsCommands;

use Cds\CdsDependencyCollection;
use TopFloor\Cds\CdsService;

abstract class CdsCommand implements CdsCommandInterface {
	protected $service;

	protected $dependencies;

	public function __construct(CdsService $service) {
		$this->service = $service;
		$this->dependencies = new CdsDependencyCollection();

		$this->setDependencies();
	}

	protected function setDependencies() {
		// Override this if there are any dependencies to declare.
	}

	public function getDependencies() {
		return $this->dependencies;
	}

	public abstract function execute($parameters = array());
}