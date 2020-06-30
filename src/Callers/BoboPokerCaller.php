<?php

namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class BoboPokerCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'POST' => [
            'wallet/createPlayer', // 創建玩家錢包
            'wallet/getPlayerInfo', // 取得玩家錢包資訊
            'wallet/tran', // 轉點接口
            'datasouce/checkOrder', // 確認轉帳訂單
            'launch/{device}',
            'datasouce/getTransactionRecord', // 取得轉點紀錄
            'datasouce/getBalanceChangeRecord', // 取得餘額變動紀錄
            'datasouce/getBetRecordByHour', // 取得近期一小時內遊戲紀錄
            'datasouce/getBetRecordByHourSetDay', // 取得一小時內遊戲紀錄
        ]
    ];

    /**
     * BoboPokerCaller constructor.
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
            'api_url' => config('api_caller.bobo_poker.config.api_url'),
            'md5_key' => config('api_caller.bobo_poker.config.md5_key'),
        ];

        // 準備固定預設 API 參數
        $this->formParams = [];
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

        $this->formParams['requestTime'] = date('YmdHis');

        // 做MD5加密
        $this->formParams['sign'] = $this->md5Encrypt($this->formParams);

        return $this;
    }

    /**
     * 決定送出的 API 位置
     *
     * @return string
     */
    public function getSubmitUrl()
    {
        return $this->config['api_url'] . '/' . $this->action;
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
                    'Content-Type' => 'application/json'
                ],
                'json' => $this->formParams,
                'timeout' => '30',
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if (array_get($arrayData, 'retCode') === '0') {
                return $this->responseFormatter($response, $arrayData);
            } else {
                $errorData = [
                    'errorCode' => array_get($arrayData, 'retCode'),
                    'errorMsg' => array_get($arrayData, 'data')
                ];

                throw new ApiCallerException($errorData, 'bobo_poker');
            }

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * API md5加密 製作sign 字串
     *
     * @param array $params
     * @return string
     */
    private function md5Encrypt(array $params): string
    {
        $arrayKeys = array_keys($params);

        usort($arrayKeys, function ($a, $b) {
            $a = strtolower($a);
            $b = strtolower($b);
            if ($a == $b) {
                return 0;
            }
            return ($a > $b) ? 1 : -1;
        });

        $signString = '';
        foreach ($arrayKeys as $key) {
            $value = array_get($params, $key);
            $signString .= $key . '=' . $value . '&';
        }

        $signString .= 'key=' . $this->config['md5_key'];

        $sign = strtolower(md5($signString));

        return $sign;
    }
}