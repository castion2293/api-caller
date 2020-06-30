<?php
namespace SuperPlatform\ApiCaller;

class ApiCaller
{
    public function make($caller)
    {
        $callerName = ucfirst(camel_case($caller)) . 'Caller';
        $callerName = 'SuperPlatform\\ApiCaller\\Callers\\' . $callerName;

        return new $callerName;
    }
}