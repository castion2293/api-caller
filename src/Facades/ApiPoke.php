<?php

namespace SuperPlatform\ApiCaller\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static poke(string $station, string $action, array $formParams)
 */
class ApiPoke extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        // 回傳 alias 的名稱
        return 'api_poke';
    }
}