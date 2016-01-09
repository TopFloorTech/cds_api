<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 1/7/2016
 * Time: 5:25 PM
 */

namespace TopFloor\Cds\CdsServices;


use TopFloor\Cds\RequestHandlers\RequestHandler;

interface CdsServiceInterface
{
    public function __construct(RequestHandler $requestHandler, ResponseParser $responseParser);


}