<?php

namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class SlotFactoryCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'POST' => [
            'playreport', // 玩家注單
        ]
    ];

    /**
     * SlotFactoryCaller constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // 載入 API 設定
        $this->config = [
            'api_backend_url' => config('api_caller.slot_factory.config.api_backend_url'),
            'secret_key' => config('api_caller.slot_factory.config.secret_key'),
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

        $this->formParams['Timestamp'] = now()->timestamp;

        return $this;
    }

    /**
     * 決定送出的 API 位置
     *
     * @return string
     */
    public function getSubmitUrl()
    {
        return 'https://' . $this->config['api_backend_url'] . '/' . $this->action;
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

            // 組header
            $data = json_encode($this->formParams);

            $header = [
                'HMAC' => $this->hashHmac($data),
                'Content-Type' => 'application/json',
                'Content-Length' => strlen($data)
            ];

            // 取得 API 呼叫結果
            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'headers' => $header,
                'json' => $this->formParams,
                'timeout' => '30',
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if (array_get($arrayData, 'StatusCode') === 0) {
                return $this->responseFormatter($response, $arrayData);
            } else {
                $errorData = [
                    'errorCode' => array_get($arrayData, 'StatusCode'),
                    'errorMsg' => array_get($arrayData, 'StatusDescription')
                ];

                throw new ApiCallerException($errorData, 'slot_factory');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * hash Hmac 加密
     *
     * @param string $data
     * @return string
     */
    private function hashHmac(string $data): string
    {
        return base64_encode(hash_hmac('sha256', $data, $this->config['secret_key'], true));
    }
}