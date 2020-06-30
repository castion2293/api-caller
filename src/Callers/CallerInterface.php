<?php

namespace SuperPlatform\ApiCaller\Callers;

interface CallerInterface
{
    /**
     * 設定 API 請求模式與動作
     *
     * @param string ("GET", "POST", "PUT", "DELETE") $method 方法
     * @param string $action 動作或路由
     * @param array $routeParams 路由參數
     * @return static
     */
    public function methodAction(string $method, string $action, array $routeParams = []);

    /**
     * 設定 API 參數
     *
     * @param array $data
     * @return static
     */
    public function params(array $data = []);

    /**
     * 送出 API 請求
     *
     * @return array
     * @throws \SuperPlatform\ApiCaller\Exceptions\ApiCallerException
     */
    public function submit();

    /**
     * 取得進入遊戲站的通行證資訊
     *
     * todo 等到 station-wallet 測試過各遊戲站連結器實作的 passport 就可以把這邊的 passport 移除
     *
     * @param array $options
     * @return array
     * [
     *    'method' => '',
     *    'url' => '',
     *    'params' => [],
     * ]
     */
//    public function passport(array $options);

    /**
     * 橋接方法名稱的對應方法動作查詢器
     *
     * @param string $station
     * @param string $bridgeAction
     * @return
     */
    public static function bridgeActionMapper(string $station, string $bridgeAction);
}