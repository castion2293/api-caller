<?php

namespace SuperPlatform\ApiCaller\Exceptions;

use Exception;

class BridgeActionParamsException extends Exception
{
    /**
     * @var array
     */
    protected $response;

    /**
     * @param array $response
     */
    public function __construct($response = [])
    {
        parent::__construct('Api caller receive failure response, use `$exception->response()` get more details.');

        $this->response = $response ?: [];
    }

    public function response()
    {
        return $this->response;
    }
}