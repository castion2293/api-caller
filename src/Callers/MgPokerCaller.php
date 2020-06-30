<?php


namespace SuperPlatform\ApiCaller\Callers;


use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class MgPokerCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'POST' => [
            'getGameList',              // 取得遊戲列表
            'login',                    // 獲取遊戲起動地址
            'queryUserScore',           // 查詢用戶分數
            'doTransferDepositTask',    // 上分到遊戲裡
            'doTransferWithdrawTask',   // 從遊戲下分
            '',                         // 通知平台推出遊戲 
            'takeBetLogs',              // 拉取訂單詳情
            'kickUser',                 // 踢出玩家
            'takeTransferLogs',         // 拉取訂單訊息
        ]
    ];
    
    /**
     * MgPokerCaller constructor.
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
            'api_url' => config('api_caller.mg_poker.config.api_url'),
            'vendor_id' => config('api_caller.mg_poker.config.vendor_id'),
            'signature_key' => config('api_caller.mg_poker.config.signature_key'),
        ];

        // 準備固定預設 API 參數
        $this->formParams = [
            'agent' => config('api_caller.mg_poker.config.vendor_id'),
            'timestamp' => time(),
        ];

    }

    public function __destruct()
    {
        unset($this->formParams);
        unset($this->config);
    }

    /**
     * 3DES 模式加密傳送的資料
     *
     * @param string $data
     * @return string
     */
    private function encrypt($data)
    {
        return md5($data . $this->config['signature_key']);
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
            $this->formParams,
            array_except($data, ['agent', 'timestamp'])
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
        // 決定傳送的位置
        try {
            $encode_data = $this->encrypt(json_encode($this->formParams));

            $submitUrl = $this->config['api_url'] . '/' . $this->action;

            // 取得 API 呼叫結果
            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $encode_data,
                ],
                'json' => $this->formParams,
                'timeout' => '310',
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            // 若發生「操作成功，但未搜寻到数据」就一樣正常回傳，此訊息是為了戳抓注單API「沒有資料」才發生的錯誤
            if (array_get($arrayData, 'code') == 0) {
                return $this->responseFormatter($response, $arrayData);
            } else {
                throw new ApiCallerException($arrayData, 'mg_poker');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}