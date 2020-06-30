<?php

namespace SuperPlatform\ApiCaller\Exceptions;

use Exception;

class BridgeActionException extends Exception
{
    /**
     * @var array
     */
    protected $response;

    /**
     * @param array $response
     */
    public function __construct(array $response = [])
    {
        parent::__construct('Invalid bridge action. Please use artisan apipoke:list to check available bridge action.');

        $this->response = $response;
    }

    public function response()
    {
        return $this->response;
    }
}