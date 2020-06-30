<?php

namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class Cq9GameCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions
        = [
            'GET' => [
                'player/balance/{account}', //查詢餘額
                'order/view', //查詢注單
                'player/token/{account}', //查詢會員token及狀態
                'game/list/{gamehall}', //遊戲列表
                'game/halls', //遊戲廠商列表
                'player/check/{account}', // 檢查帳號是否已存在
                'transaction/record/{mtcode}'
            ],
            'POST' => [
                'player',  //建立會員
                'player/login', //會員登入
                'player/logout', //會員登出
                'player/lobbylink', //進入遊戲大廳
                'player/deposit', //存款
                'player/withdraw', //取款
                'player/gamelink', //取得遊戲連結

                // 單錢包
                'player/sw/lobbylink', // 進入遊戲大廳
                'player/sw/gamelink', // 取得遊戲連結

            ],
            'PUT' => [
                'player',
            ],
        ];


    /**
     * Cq9GameCaller constructor.
     *
     * @throws ApiCallerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();

        // 載入 API 設定
        $this->config = [
            'api_url' => config('api_caller.cq9_game.config.api_url'),
            'api_token' => config('api_caller.cq9_game.config.api_token'),
            'Content-Type' => 'application/x-www-form-urlencoded',
//            'api_username' => config('api_caller.real_time_gaming.config.api_username'),
//            'api_password' => config('api_caller.real_time_gaming.config.api_password')
        ];

        // 準備固定預設 API 參數
//        $this->formParams = [
//
//        ];
    }

    public function __destruct()
    {
        unset($this->formParams);
        unset($this->config);
    }

    /**
     * 設定 API 參數
     *
     * @param array $data
     *
     * @return static
     */
    public function params(array $data = [])
    {
        $this->formParams = array_merge(
            $this->formParams, // 排除不能被覆寫部分系統自動填入的參數
            array_except($data, [])
        );

        return $this;
    }

    /**
     * 送出 API 請求
     *
     * @return array
     * @throws \SuperPlatform\ApiCaller\Exceptions\ApiCallerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function submit()
    {
        try {
            // 取得傳送位置
            $submitUrl = $this->config['api_url'] . '/gameboy/' . $this->action;

            // 取得 API 呼叫結果

            if ($this->method == "GET") {
                $dataType = "query";
            } else {
                $dataType = "form_params";
            }

            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'headers' => [
                    'Content-Type' => $this->config['Content-Type'],
                    'Authorization' => $this->config['api_token'],
                ],
                $dataType => $this->formParams,
                'timeout' => '310',
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            // "0": success / "8": Data not found(尚無資料)
            if ((string)array_get($arrayData, 'status.code') === "0"
                || (string)array_get($arrayData, 'status.code') === "8") {
                return $this->responseFormatter($response, $arrayData);
            } else {
                throw new ApiCallerException($arrayData['status'], 'cq9_game');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}