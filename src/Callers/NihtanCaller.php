<?php

namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class NihtanCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'POST' => [
            'api/session',             // session token
            'api/game/list',           // game list
            'api/transfer/cash-in',    // transfer cash in
            'api/transfer/cash-out',   // transfer cash out
            'user/holding',           // user balance check
        ],
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // 載入 API 設定
        $this->config = [
            'vendor_name' => config("api_caller.nihtan.config.vendor_name"),
            'secret_key' => config("api_caller.nihtan.config.secret_key"),
            'api_url' => config("api_caller.nihtan.config.api_url"),
        ];

        // 準備固定預設 API 參數
        $this->formParams = [
            'vendor_name' => $this->config['vendor_name']
        ];
    }

    /**
     * 3DES 模式加密傳送的資料
     *
     * @param string $data
     * @return string
     */
    private function encrypt($data)
    {
        return hash_hmac('sha256', $data, $this->config['secret_key']);
    }

    /**
     * 設定 API 參數
     *
     * @param array $data
     * @return $this
     */
    public function params(array $data = [])
    {
        $this->formParams = array_merge(
            $this->formParams,
            // 排除不能被覆寫部分系統自動填入的參數
            array_except($data, ['vendor_name' => 'ugdev'])
        );

        return $this;
    }

    /**
     * 發送 API 請求
     *
     * @return array|mixed
     * @throws ApiCallerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function submit()
    {
        $encode_data = $this->encrypt(json_encode($this->formParams));

        // 取得傳送位置
        $submitUrl = 'api.' . $this->config['api_url'] . '/' . $this->action . '?hash=' . $encode_data;

        // 取得 API 呼叫結果
        $response = $this->guzzleClient->request($this->method, $submitUrl, [
            'headers' => [
                'content-type' => 'application/json',
            ],
            'body' => json_encode($this->formParams),
        ]);

        $arrayData = json_decode($response->getBody()->getContents(), TRUE);

        $arrayData = (gettype($arrayData) === 'array') ? $arrayData : [$arrayData];

        return $this->responseFormatter($response, $arrayData);
    }
}