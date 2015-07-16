<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:51 PM
 */

namespace Cds\UrlHandlers;


abstract class UrlHandler implements UrlHandlerInterface {
	public abstract function construct($parameters = array());

	public abstract function deconstruct($url);
}