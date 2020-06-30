<?php

namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class HoldemCaller extends Caller
{
    /**
     * 定義德州可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'POST' => [
            'GetPoints',            //取得玩家目前點數
            'ChangePoints',         //玩家補扣點數
            'WinLose',              //遊戲記錄
            'GetAccumulateWinLose', //取得玩家目前累計輸贏值
            'PrizeLog',             //彩金記錄
            'KickUser',             //踢玩家
            'GetOnlineUser',        //取在線玩家
            'playgame'              //進入遊戲
        ],
    ];

    /**
     * HoldemCaller constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // 載入 API 設定
        $this->config = [
            'api_url' => config("api_caller.holdem.config.api_url"),
            'company_key' => config("api_caller.holdem.config.company_key"),
            'des_iv' => config("api_caller.holdem.config.api_iv"),
            'des_key' => config("api_caller.holdem.config.api_key"),
            'play_url' => config("api_caller.holdem.config.play_url"),
        ];

        // 準備固定預設 API 參數
        $this->formParams = [
            'CompanyKey' => $this->config['company_key'],
        ];
    }

    /**
     * DES 模式加密傳送的資料
     *
     * @param string $data
     * @return string
     */
    private function encrypt($data)
    {
        return bin2hex(openssl_encrypt($this->pkcs5Pad($data), 'des-ede3-cbc', $this->config['des_key'], OPENSSL_NO_PADDING, $this->config['des_iv']));
    }

    /**
     * 加密FormParams內容
     *
     * @return array
     */
    private function encryptFormParams()
    {
        foreach ($this->formParams as $formParam => $value) {
            $this->formParams[$formParam] = $this->encrypt($value);
        }

        return $this->formParams;
    }

    /**
     * 組合參數
     *
     * @param array $data
     * @return $this
     */
    public function params(array $data = [])
    {
        // 參數 Method、Key、Iv、CompanyKey 不能被覆寫，應該由系統填入
        $this->formParams = array_merge(
            $this->formParams,
            array_except($data, ['Method', 'CompanyKey', 'Key', 'Iv'])
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
        $actionUrl = (isset($this->config['proxy_api_url'])) ?
            $this->config['proxy_api_url'] : $this->config['api_url'] . $this->action . ".php";

        if ($this->action == 'playgame') {
            $actionUrl = $this->config['play_url'] . $this->action . ".php";
        }

        $this->encryptFormParams();

        $response = $this->guzzleClient->request($this->method, $actionUrl, [
            'headers' => [
                'User-Agent' => 'Mozilla/4.0 (compatible;)',
                'content-type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => $this->formParams,
        ]);

        $arrayData = json_decode($response->getBody()->getContents(), TRUE);

        if (array_get($arrayData, 'result') == 1) {
            return $this->responseFormatter($response, $arrayData);
        } else {
            throw new ApiCallerException($arrayData, 'holdem');
        }
    }

}