<?php

namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class RenNiYingCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'GET' => [
            'getPlayerTicket',
            'getPlayer',
            'ledgerQuery'
        ],
        'POST' => [
            'exportPlayer',
            'logoutPlayer',
            'updatePlayerState',
            'updatePlayerBalance'
        ]
    ];

    /**
     * API憑證
     * @var string
     */
    protected $token = '';

    /**
     * RenNiYingCaller constructor.
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
            'api_url' => config('api_caller.ren_ni_ying.config.api_url'),
        ];

        // 準備固定預設 API 參數
        $this->formParams = [

        ];

        $this->getToken();
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
     * @return RealTimeGamingCaller
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
        return $this->config['api_url'] . '/api/partner/' . $this->action;
    }

    public function submit()
    {
        // 決定傳送的位置
        try {
            $submitUrl = $this->getSubmitUrl();

            // 取得 API 呼叫結果
            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $this->token
                ],
                'query' => $this->formParams,
                'timeout' => '30',
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if ($response->getStatusCode() === 200) {
                return $this->responseFormatter($response, $arrayData);
            } else {
                throw new ApiCallerException($arrayData, 'ren_ni_ying');
            }
        } catch (\Exception $exception) {
            $arrayData = [
                'errorCode' => $exception->getCode(),
                'errorMsg' => $exception->getMessage()
            ];

            throw new ApiCallerException($arrayData, 'ren_ni_ying');
        }
    }

    protected function getToken()
    {
        try {
            $response = $this->guzzleClient->request('get', $this->config['api_url'] . '/api/partner/getAuthToken', [

            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            $this->token = 'Bearer ' . array_get($arrayData, 'data.access_token');

        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}