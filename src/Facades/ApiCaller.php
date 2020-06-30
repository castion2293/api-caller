<?php

namespace SuperPlatform\ApiCaller\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static make(string $string)
 */
class ApiCaller extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        // 回傳 alias 的名稱
        return 'api_caller';
    }
}