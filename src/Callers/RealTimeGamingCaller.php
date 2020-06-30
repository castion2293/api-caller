<?php

namespace SuperPlatform\ApiCaller\Callers;

use function GuzzleHttp\Psr7\copy_to_string;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class RealTimeGamingCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'GET' => [
            'start',
            'gamestrings',
            'start/token',
            'report/gamedetail'
        ],
        'POST' => [
            'GameLauncher',
            'wallet',
            'wallet/deposit/{amount}',
            'wallet/withdraw/{amount}',
            'report/playergame',
            'report/casinoperformance',
            'report/depositswithdrawls',
            'report/multicash',
            'GameLauncher',
            'launcher/lobby',
            'casinoperformance'
        ],
        'PUT' => [
            'player'
        ]
    ];

    /**
     * RealTimeGamingCaller constructor.
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
            'api_url' => config('api_caller.real_time_gaming.config.api_url'),
            'api_username' => config('api_caller.real_time_gaming.config.api_username'),
            'api_password' => config('api_caller.real_time_gaming.config.api_password')
        ];

        // 準備固定預設 API 參數
        $this->formParams = [

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
        return $this->config['api_url'] . 'api/' . $this->action;
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
            $token = $this->getToken();

            // 決定傳送的位置
            $submitUrl = $this->getSubmitUrl();

            if ($this->method === 'PUT' or $this->method === 'POST') {
                // 取得 API 呼叫結果
                $response = $this->guzzleClient->request($this->method, $submitUrl, [
                    'headers' => [
                        'Authorization' => $token,
                        'Content-Type' => 'application/json'
                    ],
                    'body' => json_encode($this->formParams),
                    'timeout' => '50',
                ]);

                $arrayData = json_decode($response->getBody()->getContents(), true);

                // 如果方法是抓取餘額則回傳值為浮點數
                if ($this->getSubmitUrl() == $this->config['api_url'] . 'api/wallet') {
                    return doubleval($arrayData);
                }

                // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
                if ($response->getStatusCode() === 200) {
                    unset($token);
                    return $this->responseFormatter($response, $arrayData);
                } elseif ($response->getStatusCode() === 201) {
                    unset($token);
                    return $this->responseFormatter($response, $arrayData);
                } else {
                    unset($token);
                    throw new ApiCallerException($arrayData, 'real_time_gaming');
                }
            }

            // 取得 API 呼叫結果
            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $token
                ],
                'query' => $this->formParams,
                'timeout' => '50',
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if ($response->getStatusCode() === 200) {
                $responseFormatter = $this->responseFormatter($response, $arrayData);
                unset($token);
                return $responseFormatter;
            } else {
                unset($token);
                throw new ApiCallerException($arrayData, 'real_time_gaming');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    protected function getToken()
    {
        try {
            $response = $this->guzzleClient->request('get', $this->config['api_url'] . 'api/start/token', [
                'query' => [
                    'username' => $this->config['api_username'],
                    'password' => $this->config['api_password']
                ]
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            return array_get($arrayData, 'token');

        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
