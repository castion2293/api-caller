<?php

namespace SuperPlatform\ApiCaller\Callers;

use GuzzleHttp\Exception\GuzzleException as GuzzleExceptionAlias;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class AwcSexyCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'POST' => [
            'createMember',
            'doLoginAndLaunchGame',
            'login',
            'logout',
            'getBalance',
            'deposit',
            'withdraw',
            'checkTransferOperation',
            'getTransactionByUpdateDate',
            'getTransactionByTxTime',
            'updateBetLimit',
            'getTransactionHistoryResult',
            'getOnlinePlayer',
        ]
    ];

    /**
     * AwcSexyCaller constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->config = [
            'api_url' => config('api_caller.awc_sexy.config.api_url'),
            'fetch_url' => config('api_caller.awc_sexy.config.fetch_url'),
        ];

        // 準備固定預設 API 參數
        $this->formParams = [
            'cert' => config('api_caller.awc_sexy.config.api_key'),
            'agentId' => config('api_caller.awc_sexy.config.agent_id'),
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
        // 若是抓單API則使用抓單URL
        if ($this->action == 'getTransactionByUpdateDate' || 
            $this->action == 'getTransactionByTxTime' ) {
            return $this->config['fetch_url'] . '/fetch/' . $this->action;
        }
        return $this->config['api_url'] . '/wallet/' . $this->action;
    }

    /**
     * 送出 API 請求
     *
     * @return array
     * @throws GuzzleExceptionAlias
     */
    public function submit()
    {
        // 決定傳送的位置
        try {
            $submitUrl = $this->getSubmitUrl();

            // 取得 API 呼叫結果
            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'charset' => 'UTF-8'
                ],
                'form_params' => $this->formParams,
                'timeout' => '30',
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            // Api OnlinePlayer 不會有status code 所以直接回傳成功
            if ($this->action === 'getOnlinePlayer') {
                return $this->responseFormatter($response, $arrayData);
            }

            $status = array_get($arrayData, 'status');

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            // 1028 訪問太頻繁不需要噴出錯誤
            if ($status === '0000') {
                return $this->responseFormatter($response, $arrayData);
            } else {
                $errorData = [
                    'errorCode' => array_get($arrayData, 'status'),
                    'errorMsg' => array_get($arrayData, 'desc'),
                ];

                throw new ApiCallerException($errorData, 'awc_sexy');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}