<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 4:40 PM
 */

namespace Cds;


use Cds\CdsCommands\CdsCommand;

class CdsCommandCollection {
	private $commands = array();

	public function getCommands() {
		return $this->commands;
	}

	public function addCommand(CdsCommand $command) {
		$this->commands[] = $command;
	}

	/*
	 * Execute all commands in this list and return the concatenated results
	 */
	public function execute() {
		$output = '';

		/** @var CdsCommand $command */
		foreach ($this->commands as $command) {
			$output .= $command->execute() . "\n";
		}

		return $output;
	}

	public function getDependencies($type = null) {
		$dependencies = array(
			'options' => array(),
			'js' => array(),
			'css' => array(),
		);

		/** @var CdsCommand $command */
		foreach ($this->commands as $command) {
			foreach ($command->getDependencies() as $type => $typeDependencies) {
				foreach ($typeDependencies as $key => $typeDependency) {
					if ($type == 'settings') {
						$dependencies['settings'][$key] = $typeDependency;
					} else {
						if (!in_array($typeDependency, $dependencies[$type])) {
							$dependencies[$type][] = $typeDependency;
						}
					}
				}
			}
		}

		if (is_null($type)) {
			return $dependencies;
		}

		return $dependencies[$type];
	}
}