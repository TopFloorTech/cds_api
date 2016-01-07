<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 4:40 PM
 */

namespace TopFloor\Cds\Collections;

use TopFloor\Cds\CdsComponents\CdsComponentInterface;

class CdsComponentCollection {
	private $components = array();

	public function getComponents() {
		return $this->components;
	}

	public function getComponentRegion($region = 'main') {
		if (!isset($this->components[$region])) {
			return array();
		}

		return (array) $this->components[$region];
	}

	public function addComponent(CdsComponentInterface $component, $region = 'main') {
		$this->components[$region][] = $component;
	}

	public function getDependencies() {
		$dependencies = new CdsDependencyCollection();

		/** @var CdsComponentInterface $component */
		foreach ($this->components as $region => $components) {
			foreach ($components as $component) {
				$dependencies->addDependencies($component->getDependencies()->getDependencies());
			}
		}

		return $dependencies;
	}

	/*
	 * Execute all commands in this list and return the concatenated results
	 */
	public function execute($regions = null) {
		if (empty($regions)) {
			$regions = array_keys($this->components);
		} else {
			$regions = (array) $regions;
		}

		$output = array();

		foreach ($regions as $region) {
			if (isset($this->components[$region]) && is_array($this->components[$region])) {
				/** @var CdsComponentInterface $component */
				foreach ($this->components[$region] as $component) {
					$output[$region][] = $component->execute();
				}

				$output[$region] = implode("\n", $output[$region]);
			}
		}

		$output = implode("\n", $output);

		return $output;
	}

	public function output($regions = null) {
		if (empty($regions)) {
			$regions = array_keys($this->components);
		} else {
			$regions = (array) $regions;
		}

		$output = array();

		foreach ($regions as $region) {
			if (isset($this->components[$region]) && is_array($this->components[$region])) {
				/** @var CdsComponentInterface $component */
				foreach ($this->components[$region] as $component) {
					$output[$region][] = $component->output();
				}

				$output[$region] = implode("\n", $output[$region]);
			}
		}

		return $output;
	}
}
