<?php


namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class UfaSportCaller extends Caller
{
    /**
     * 定義SuperSport可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'get' => [
            'create',
            'update',
            'balance',
            'deposit',
            'withdraw',
            'login',
            'logout',
            'check_payment',
            'check_online',
            'ticket',
            'parlay',
            'team',
            'league',
            'sportstype',
            'fetch',
            'mark_fetched',
        ],
    ];

    /**
     * SaGamingCaller constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // 載入 API 設定
        $this->config = [
            'api_url' => config("api_caller.ufa_sport.config.api_url"),
            'currency' => config("api_caller.ufa_sport.config.currency"),
            'secret_code' => config("api_caller.ufa_sport.config.secret_code"),
            'host_url' => config("api_caller.ufa_sport.config.host_url"),
            'agent' => config("api_caller.ufa_sport.config.agent"),
        ];

        // 準備固定預設 API 參數
        $this->formParams = [
            'secret' => $this->config['secret_code'],
            'agent' => $this->config['agent'],
            'username' => '',
        ];
    }

    public function __destruct()
    {
        unset($this->formParams);
        unset($this->config);
    }

    /**
     * 組合參數
     *
     * @param array $data
     * @return $this
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
     * @return mixed
     */
    public function getSubmitUrl()
    {
        return $this->config['api_url'];
    }

    /**
     * 發送 API 請求
     *
     * @return array
     * @throws ApiCallerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function submit()
    {
        $this->formParams['action'] = $this->action;

        // 傳送的位置
        $submitUrl = $this->getSubmitUrl();

        // 取得 API 呼叫結果
        $response = $this->guzzleClient->request('get', $submitUrl, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'query' => $this->formParams,
            'timeout' => '30',
        ]);

        // 轉成 XML 資料物件，再轉成陣列
        $xmlData = simplexml_load_string($response->getBody()->getContents());
        $arrayData = json_decode(json_encode((array)$xmlData, JSON_NUMERIC_CHECK), TRUE);

        // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
        if ((string)array_get($arrayData, 'errcode') === '0' || (string)array_get($arrayData, 'errcode') === '1') {
            return $this->responseFormatter($response, $arrayData);
        } else {
            throw new ApiCallerException($arrayData, 'ufa_sport');
        }
    }
}