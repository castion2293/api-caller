<?php


namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class BingoBullCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'POST' => [
            '/api/getToken',        // 功能(取得 Token)
            '/api/adduser',         // 功能(新增用戶)
            '/api/login',           // 功能(用戶登入)
            '/api/getReport',       // 功能(取得報表)
            '/api/getPoint',        // 功能(取得點數)
            '/api/addPoint',        // 功能(增加點數)
            '/api/deductionPoint',  // 功能(扣除點數)

        ]
    ];

    /**
     * API憑證
     * @var string
     */
    protected $token = '';

    /**
     * BingoBullCaller constructor.
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
            'api_url' => config('api_caller.bingo_bull.config.api_url'),
            'apikey' => config('api_caller.bingo_bull.config.api_key'),
            'prefix_code' => config('api_caller.bingo_bull.config.prefix_code'),
        ];

        // 準備固定預設 API 參數
        $this->formParams = [
            'apikey' => config('api_caller.bingo_bull.config.api_key'),
            'token' => $this->getToken(),
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
        return $this->config['api_url'] . $this->action;
    }

    /**
     * 送出 API 請求
     *
     * @return array
     * @throws \SuperPlatform\ApiCaller\Exceptions\ApiCallerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function submit()
    {
        // 決定傳送的位置
        try {
            $submitUrl = $this->getSubmitUrl();

            // 取得 API 呼叫結果
            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $this->formParams,
                'timeout' => '40',
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if (array_get($arrayData, 'typeCode') === '1') {
                return $this->responseFormatter($response, $arrayData);
            } else {
                $errorDate = [
                    'errorCode' => array_get($arrayData, 'typeCode'),
                    'errorMsg' => array_get($arrayData, 'status')
                ];

                throw new ApiCallerException($errorDate, 'bingo_bull');
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
            $response = $this->guzzleClient->request('post', $this->config['api_url'] . '/api/getToken', [
                'json' => [
                    'apikey' => $this->config['apikey'],
                ]
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            return array_get($arrayData, 'token');
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}