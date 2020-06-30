<?php

namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class SaGamingCaller extends Caller
{
    /**
     * 定義沙龍可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'POST' => [
            'RegUserInfo',
            'VerifyUsername',
            'GetUserStatusDV',
            'GetUserBetItemDV',
            'DebitBalanceDV',
            'DebitAllBalanceDV',
            'CreditBalanceDV',
            'CheckOrderId',
            'LoginRequest',
            'LoginRequestTryToPlayDV',
            'KickUser',
            'GetAllBetDetailsDV',
            'GetAllBetDetailsForTimeIntervalDV',
            'GetAllBetDetailsFor30secondsDV',
            'GetUserBetAmountDV',
            'EGameLoginRequest',
            'SlotJackpotQuery',
            'Lotto48GetInfo',
            'Lotto48PlaceBet',
            'GetUserWinLost',
            'GetTransactionDetails',
            'QueryBetLimit',
            'SetBetLimit',
            'GetUserMaxWin',
            'SetUserMaxWin',
            'SetUserMaxWinning',
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
            'api_url' => config("api_caller.sa_gaming.config.api_url"),
            'md5_key' => config("api_caller.sa_gaming.config.md5_key"),
            'secret_key' => config("api_caller.sa_gaming.config.secret_key"),
            'encrypt_key' => config("api_caller.sa_gaming.config.encrypt_key"),
            'check_key' => config("api_caller.sa_gaming.config.check_key"),
            'lobby_code' => config("api_caller.sa_gaming.config.lobby_code"),
            'play_url' => config("api_caller.sa_gaming.config.play_url"),
        ];

        // 準備固定預設 API 參數
        $this->formParams = [
            'Key' => $this->config['secret_key'],
            'Checkkey' => $this->config['check_key'],
        ];
    }

    public function __destruct()
    {
        unset($this->formParams);
        unset($this->config);
    }

    /**
     * DES 模式加密傳送的資料
     *
     * @param string $data
     * @return string
     */
    private function encrypt($data)
    {
        return openssl_encrypt($data, 'des', $this->config['encrypt_key'], 0, $this->config['encrypt_key']);
    }

    /**
     * 組合參數
     *
     * @param array $data
     * @return $this
     */
    public function params(array $data = [])
    {
        // 參數 Method、Key、Checkkey、Time 不能被覆寫，應該由系統填入
        $this->formParams = array_merge(
            $this->formParams,
            array_except($data, ['Method', 'Key', 'Checkkey', 'Time'])
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
     * @return array|mixed
     * @throws ApiCallerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function submit()
    {
        try {
            $this->formParams['Method'] = $this->action;
            $this->formParams['Time'] = date('YmdHis');

            // 傳送的位置
            $submitUrl = $this->getSubmitUrl();

            // 發送 API 請求
            $query = http_build_query($this->formParams);
            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'headers' => [
                    'content-type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'url' => $this->config['api_url'],
                    'q' => $this->encrypt($query),
                    's' => md5($query . $this->config['md5_key'] . $this->formParams['Time'] . $this->formParams['Key'])
                ],
                'timeout' => '40',
            ]);

            // 轉成 XML 資料物件，再轉成陣列
            $xmlData = simplexml_load_string($response->getBody()->getContents(), 'SimpleXMLElement', LIBXML_NOCDATA);
            $arrayData = json_decode(json_encode((array)$xmlData, JSON_NUMERIC_CHECK), TRUE);

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if (array_get($arrayData, 'ErrorMsgId') === 0) {
                return $this->responseFormatter($response, $arrayData);
            } else {
                throw new ApiCallerException($arrayData, 'sa_gaming');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}