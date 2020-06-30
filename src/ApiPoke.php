<?php

namespace SuperPlatform\ApiCaller;

use SuperPlatform\ApiCaller\Facades\ApiCaller;
use Symfony\Component\Console\Output\ConsoleOutput;

class ApiPoke
{
    public function __construct()
    {
    }

    /**
     * 大師訪問器
     *
     * @param string $station
     * @param string $bridgeAction
     * @param array $params
     * @return mixed
     */
    public function poke(string $station, string $bridgeAction, array $params = [])
    {
        return $this->$bridgeAction($station, $params);
    }

    /**
     * 重載方法
     *
     * @param string $bridgeAction
     * @param array $arguments
     * @return
     */
    public function __call(string $bridgeAction, array $arguments)
    {
        /**
         * 根據 $method 找出對應的橋接方法，例如 'balance' -> 對應各個 $station ($arguments[0]) 、方法是叫什麼名稱
         *
         * 1. 假設 $station 是 bingo，橋接方法 balance 對應的方法應為 GET players/{playerId} 查詢玩家資料
         * 2. 假設 $station 是 all_bet，橋接方法 balance 對應的方法應為 POST get_balance 查詢會員餘額
         *
         * 以此類推
         *
         * $arguments[0] is station
         * $arguments[1] is included route_params for route parameters, and form_params for form parameters
         */
        return ApiCaller::make($arguments[0])->bridge($arguments[0], $bridgeAction, $arguments[1]);
    }
}