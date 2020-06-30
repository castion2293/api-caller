<?php

namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class CockFightCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'POST' => [
            'kickout_player', // 強制登出
            'get_balance', // 餘額
            'withdraw', // 提款
            'deposit', // 存款
            'check_transfer', // 查詢轉帳
            'get_transfer_2', // 獲取轉帳
            'get_cockfight_open_ticket_2', // 獲取交易 還未結算
            'get_cockfight_processed_ticket_2', // 獲取交易 結算注單以結算時間
            'get_cockfight_processed_ticket_by_bet_time', // 獲取交易 結算注單以下注時間
            'get_cockfight_player_summary', // 獲取總兑
            'get_session_id', // 進入遊戲
            'set_bet_limit', // 設定限紅
        ]
    ];

    /**
     * CockFightCaller constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // 載入 API 設定
        $this->config = [
            'api_url' => config('api_caller.cock_fight.config.api_url'),
            'api_key' => config('api_caller.cock_fight.config.api_key'),
            'agent_code' => config('api_caller.cock_fight.config.agent_code'),
        ];

        // 準備固定預設 API 參數
        $this->formParams = [
            'api_key' => array_get($this->config, 'api_key'),
            'agent_code' => array_get($this->config, 'agent_code')
        ];
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
     * @return static
     */
    public function params(array $data = [])
    {
        $this->formParams = array_merge(
            $this->formParams, $data
        );

        return $this;
    }

    /**
     * 決定送出的 API 位置
     *
     * @return string
     */
    public function getSubmitUrl()
    {
        return $this->config['api_url'] . '/' . $this->action . '.aspx';
    }

    /**
     * 送出 API 請求
     *
     * @return array
     * @throws \SuperPlatform\ApiCaller\Exceptions\ApiCallerException
     */
    public function submit()
    {
        // 決定傳送的位置
        try {
            $submitUrl = $this->getSubmitUrl();

            // 取得 API 呼叫結果
            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => $this->formParams,
                'timeout' => '20',
            ]);

            // 轉成 XML 資料物件，再轉成陣列
            $xmlData = simplexml_load_string($response->getBody()->getContents());
            $arrayData = json_decode(json_encode((array)$xmlData, JSON_NUMERIC_CHECK), TRUE);

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if (array_get($arrayData, 'status_code') === 0) {
                $data = array_get($arrayData, 'data');

                $columnSet = [];

                if (!empty($data)) {
                    $rowSet = explode('|', $data);
                    foreach ($rowSet as $row) {
                        $columnSet[] = explode(',', $row);
                    }
                }

                $arrayData['data'] = $columnSet;

                return $this->responseFormatter($response, $arrayData);
            } else {
                $errorCode = array_get($arrayData, 'status_code');
                $errorMsg = array_get($arrayData, 'status_text');

                // 如果錯誤訊息是 間格小於60秒 就不噴出
                if (strpos($errorMsg, 'repeat access within allow interval - 60 secs') !== false) {
                    return $this->responseFormatter($response, $arrayData);
                }

                $errorData = [
                    'errorCode' => $errorCode,
                    'errorMsg' => $errorMsg
                ];

                throw new ApiCallerException($errorData, 'cock_fight');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}