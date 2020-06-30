<?php

namespace SuperPlatform\ApiCaller\Callers;

use GuzzleHttp\Exception\GuzzleException;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class KkLotteryCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'GET' => [
            'login',
        ],
        'POST' => [
            'createuser',
            'kickuser',
            'fund/getbalance',
            'fund/deposit',
            'fund/withdraw',
            '/data/betlist',
            '/data/official/betlist',
            '/data/prebuylist',
            '/data/official/prebuylist',
            '/data/fundlog',
        ],
    ];

    /**
     * 定義API有返回status code 的 actions
     *
     * @var array
     */
    private $hasStatusActions = [
        'createuser',
        'kickuser',
        'fund/deposit',
        'fund/withdraw',
    ];

    /**
     * 加密後的參數
     *
     * @var array
     */
    protected $encryptParams = [];

    /**
     * KkLotteryCaller constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->config = [
            'api_url' => config('api_caller.kk_lottery.config.api_url'),
            'api_key' => config('api_caller.kk_lottery.config.api_key'),
        ];

        $this->encryptParams = [
            'version' => config('api_caller.kk_lottery.config.api_version'),
            'id' => config('api_caller.kk_lottery.config.agent_id'),
        ];

        // 準備固定預設 API 參數
        $this->formParams = [
            'platformid' => config('api_caller.kk_lottery.config.platform_id'),
            'platformname' => config('api_caller.kk_lottery.config.platform_name'),
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
     * DES 加密
     *
     * @param $data
     * @return string
     */
    private function encrypt($data)
    {
        return urlencode(
            base64_encode(
                openssl_encrypt(
                    json_encode($data),
                    "DES-ECB",
                    $this->config['api_key'],
                    OPENSSL_RAW_DATA
                )
            )
        );
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
     * @throws GuzzleException
     */
    public function submit()
    {
        // 決定傳送的位置
        try {
            $submitUrl = $this->getSubmitUrl();

            // 加密
            $this->encryptParams['data'] = $this->encrypt($this->formParams);

            // 進入遊戲只要回傳跳轉URL 不需要戳API
            if ($this->action === 'login') {
                return [
                    'response' => [
                        'data' => [
                            'url' => $submitUrl . '?' . http_build_query($this->encryptParams),
                        ]
                    ]
                ];
            }

            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'json' => $this->encryptParams,
                'timeout' => '40',
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            // API回傳資料有status code
            if (in_array($this->action, $this->hasStatusActions)) {
                // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
                if (array_get($arrayData, 'data.status') === '1') {
                    return $this->responseFormatter($response, $arrayData);
                } else {
                    $errorCode = [
                        'errorCode' => array_get($arrayData, 'data.status'),
                        'errorMsg' => array_get($arrayData, 'data.message'),
                    ];

                    throw new ApiCallerException($errorCode, 'kk_lottery');
                }
            }

            // 其他沒有status code ＡＰＩ回傳內容
            if (array_get($arrayData, 'code') === '200') {
                return $this->responseFormatter($response, $arrayData);
            } else {
                $errorCode = [
                    'errorCode' => array_get($arrayData, 'code'),
                    'errorMsg' => array_get($arrayData, 'msg'),
                ];

                throw new ApiCallerException($errorCode, 'kk_lottery');
            }

        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}