<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:50 PM
 */

namespace TopFloor\Cds\CdsUrlHandlers;

interface CdsUrlHandlerInterface {
	public function construct($parameters = array(), $append = null, $basePath = null);

	public function deconstruct($url, $basePath = null);

	public function get($parameter);

	public function getCurrentUri();

	public function getPageFromUri($uri = null, $basePath = null);

	public function getAliasForPage($page);

	public function parameterIsSet($parameter);
}