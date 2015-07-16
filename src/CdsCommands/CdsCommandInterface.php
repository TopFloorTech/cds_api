<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:55 PM
 */

namespace Cds\CdsCommands;


interface CdsCommandInterface {
	public function execute($parameters = array());

	public function getDependencies();
}