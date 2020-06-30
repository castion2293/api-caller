<?php

namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class AmebaCaller extends Caller
{
    protected $enabledMethodActions = [
        'POST' => [
            'create_account', // 在游戏平台中建立一个玩家账户
            'register_token', // 开始游戏前创建一个登入的token 若请求成功 API 会返回包含了 token 的游戏 URL 以及其他参数
            'deposit', // 存款到玩家的游戏账户中
            'withdraw', // 由玩家的游戏账户中提现
            'get_transaction', // 根据 transaction id 返回存款或提现的交易资料
            'get_balance', // 返回游戏系统中玩家的帐户余额
            'get_balances', // 返回游戏系统中多名玩家的帐户余额
            'get_bet_histories', // 返回玩家的下注记录
            'get_jackpot_meter', // 根据货币返回当前彩池的累积奖金
            'get_jackpot_wins', // 根据货币返回请求时段的彩池奖金派奖记录
            'request_demo_play', // 创建一个试玩游戏的 demo token，这个 api 会返回一个指定游戏的 demo url 在 demo 模式下不会提供投注记录
            'get_game_history_url', // 生成带访问权限的游戏记录连结
        ],
    ];

    /**
     * AmebaCaller constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $apiConfig = config('api_caller.ameba.config');

        foreach ($apiConfig as $k => $v) {
            switch ($k) {
                case 'site_id':
                case 'secret_key':
                case 'api_url':
                    $this->config[$k] = $v;
            }
        }
    }

    public function __destruct()
    {
        unset($this->formParams);
        unset($this->config);
    }

    /**
     * 設定 API 參數
     * @param array $data
     * @return AmebaCaller
     */
    public function params(array $data = []): self
    {
        $this->formParams = array_merge([
            // 每次 request 都需要的參數
            'action' => $this->action,
            'site_id' => $this->config['site_id'],
        ], $data);

        return $this;
    }

    /**
     * 送出 API 請求
     * @return array
     * @throws ApiCallerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function submit(): array
    {
        $sApiUrl = '';

        switch ($this->action) {
            case 'create_account':
            case 'register_token':
            case 'deposit':
            case 'withdraw':
            case 'get_transaction':
            case 'get_balance':
            case 'get_balances':
            case 'request_demo_play':
                $sApiUrl = $this->config['api_url'] . '/ams/api';
                break;
            case 'get_bet_histories':
            case 'get_game_history_url':
                $sApiUrl = $this->config['api_url'] . '/dms/api';
                break;
            case 'get_jackpot_meter':
            case 'get_jackpot_wins':
                $sApiUrl = $this->config['api_url'] . '/jms/api';
                break;
        }
        try {
            // 取得 API 呼叫結果
            $sResponseRawData = $this->guzzleClient->request(
                $this->method,
                $sApiUrl,
                [
                    'headers' => [
                        'Authorization' => "Bearer {$this->encrypt()}",
        //                    'Accept-Encoding' => 'gzip',
                    ],
                    'timeout' => '40',
                ]
            );

            $aResponseContentsData = json_decode($sResponseRawData->getBody()->getContents(), true);

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if ($aResponseContentsData['error_code'] === 'OK') {
                return $this->responseFormatter($sResponseRawData, $aResponseContentsData);
            } else {
                throw new ApiCallerException($aResponseContentsData, 'ameba');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * JWT (JSON Web Token) 方式加密
     * @return string
     */
    private function encrypt(): string
    {
        // JWT Header
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT',
        ];

        // JWT Payload data
        $payload = $this->formParams;

        $headerEncoded = $this->base64UrlEncode(json_encode($header));
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload));

        // Delimit with period (.)
        $dataEncoded = "$headerEncoded.$payloadEncoded";

        $rawSignature = hash_hmac('sha256', $dataEncoded, $this->config['secret_key'], true);

        $signatureEncoded = $this->base64UrlEncode($rawSignature);

        // Delimit with second period (.)
        return "$dataEncoded.$signatureEncoded";
    }

    /**
     * @param string $data
     * @return string
     */
    private function base64UrlEncode(string $data): string
    {
        $urlSafeData = strtr(base64_encode($data), '+/', '-_');

        return rtrim($urlSafeData, '=');
    }
}