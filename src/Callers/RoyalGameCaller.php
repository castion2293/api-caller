<?php

namespace SuperPlatform\ApiCaller\Callers;

use Http\Message\Authentication\BasicAuth;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class RoyalGameCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'GET' => [

        ],
        'POST' => [
            'VPSWMember',                  //玩家帳號
            'VPSWFundTransfer',            //充值提取
            'VPSWMemberBalance',           //錢包額度
            'VPSGameLimit',                //皇家真人遊戲限注
            'VPSMemberLimit',              //玩家在皇家真人遊戲限注
            'VPSGetMemberSessionKey',      //遊戲進入金鑰
            'VPSGetMemberOnline',          //玩家在線狀態
            'VPSStakeDetail2',             //拉帳
            'VPSOpenRecord',               //皇家真人遊戲開牌紀錄
            'VPSOpenAccounts',             //查詢本場帳 (當前下注資訊、未結算之注單)
            'VPSKickMemberNB',             //玩家踢線 (僅限皇家真人遊戲使用)
            'VPSGetServerListByBucketID',  //查詢遊戲列表
            'VPSPBWMemberLimit',           //玩家限紅資訊(僅限網投真人遊戲使用)
            'VPSMemberLimit_STAR'          //玩家限紅資訊(僅限Star真人遊戲使用)
        ],
    ];

    /**
     * constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // 載入 API 設定
        $this->config = [
            'api_url' => config("api_caller.royal_game.config.api_url"),
            'token_key' => config("api_caller.royal_game.config.token_key"),
            'bucket_id' => config("api_caller.royal_game.config.bucket_id"),
            'game_url' => config("api_caller.royal_game.config.game_url"),
        ];
        // 準備每次都會有的固定 API 參數
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
     * @return $this
     */
    public function params(array $data = [])
    {
        $this->formParams = array_merge(
            $this->formParams,
            $data
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws ApiCallerException
     * @throws \Exception
     */
    public function submit()
    {
        try {
            $this->formParams['timestamp'] = time();

            $timestamp = $this->formParams['timestamp'];
            $token = $this->config['token_key'];
            $call = $this->action;
            $params_data = json_encode($this->formParams);
            $hash = hash('md5', $token . $call . $params_data . $timestamp);

            $authorization = base64_encode(sprintf('%s:%s', $this->config['bucket_id'], $token));

            // 傳送的位置
            $submitUrl = $this->getSubmitUrl();

            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'headers' => [
                    'Authorization' => 'Basic ' . $authorization,
                    'X-RG-Time' => $timestamp,
                    'X-RG-Hash' => $hash,
                ],
                'form_params' => [
                    'call' => $call,
                    'data' => $params_data
                ],
                'timeout' => '16',
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if ((string)array_get($arrayData, 'ErrorCode') == '0') {
                return $this->responseFormatter($response, $arrayData);
            } else {
                $errorData = [
                    'error_code' => array_get($arrayData, 'ErrorCode'),
                    'error_msg' => array_get($arrayData, 'ErrorMessage')
                ];

                throw new ApiCallerException($errorData, 'royal_game');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}