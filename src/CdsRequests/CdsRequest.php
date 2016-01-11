<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/7/2016
 * Time: 5:09 PM
 */

namespace TopFloor\Cds\CdsRequests;

use TopFloor\Cds\CdsService;
use TopFloor\Cds\RequestHandlers\RequestHandler;
use TopFloor\Cds\ResponseParsers\ResponseParser;

abstract class CdsRequest implements CdsRequestInterface
{
  protected $service;

  protected $handler;

  protected $parser;

  protected $resource = '/';

  public function __construct(CdsService $service, RequestHandler $handler, ResponseParser $parser) {
    $this->service = $service;
    $this->handler = $handler;
    $this->parser = $parser;
  }

  public function getResponseParser() {
    return $this->parser;
  }

  public function getRequestHandler() {
    return $this->handler;
  }

  public abstract function getResource();

  public function process() {
    $result = $this->handler->send($this);

    return $result;
  }
}
