<?php

namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class AllBetCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'POST' => [
            // 多錢包
            'query_handicap',                       // 2.1 代理商盤口列表
            'check_or_create',                      // 2.2 創建會員帳號
            'get_balance',                          // 2.3 查詢會員餘額
            'agent_client_transfer',                // 2.4 會員點數異動
            'forward_game',                         // 2.5 會員登入遊戲
            'client_betlog_query',                  // 2.6 會員注單查詢
            'betlog_daily_histories',               // 2.7 會員單日注單紀錄 30天內
            'logout_game',                          // 2.8 登出遊戲
            'betlog_daily_modified_histories',      // 2.9 查詢單日注單修改紀錄 30天內
            'create_demo_account',                  // 2.10 創建試玩帳號
            'modify_client',                        // 2.11 修改會員盤口
            'setup_client_password',                // 2.12 設定會員密碼
            'query_transfer_state',                 // 2.13 查詢轉帳狀態
            'betlog_pieceof_histories_in30days',    // 2.14 30天內小時區間會員注單
            'maintain_state_setting',               // 2.15 維護狀態 禁止登入並登出會員
            'client_history_surplus',                // 2.16 會員歷史輸贏查詢與重置
            'egame_betlog_histories',                // 2.16 查詢電子遊戲投注記錄歷史

            // 單錢包
            'query_agent_handicaps',                  // 2.1 查询代理商盘口信息
            'create_client',                          // 2.2 创建玩家游戏帐号
            'query_client_betlog',                    // 2.4 玩家投注查询
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
            'agent' => config("api_caller.all_bet.config.agent"),
            'api_url' => config("api_caller.all_bet.config.api_url"),
            'des_key' => base64_decode(config("api_caller.all_bet.config.des_key")),
            'des_iv' => base64_decode(config("api_caller.all_bet.config.des_iv")),
            'md5_key' => config("api_caller.all_bet.config.md5_key"),
            'property_id' => config("api_caller.all_bet.config.property_id"),
        ];

        // 準備固定預設 API 參數
        $this->formParams = [
            'random' => mt_rand(),
        ];
    }

    public function __destruct()
    {
        unset($this->formParams);
        unset($this->config);
    }

    /**
     * 3DES 模式加密傳送的資料
     *
     * @param string $data
     * @return string
     */
    private function encrypt($data)
    {
        return base64_encode(openssl_encrypt(
            $this->pkCs5Pad($data),
            'des-ede3-cbc',
            $this->config['des_key'],
            OPENSSL_NO_PADDING,
            $this->config['des_iv']
        ));
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
            array_except($data, ['random'])
        );

        return $this;
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
            $encode_data = $this->encrypt(http_build_query($this->formParams));

            // 組合表單參數
            $formParams = [
                'data' => $encode_data,
                'sign' => base64_encode(md5($encode_data . $this->config['md5_key'], TRUE)),
                'propertyId' => $this->config['property_id'],
            ];

            // 取得傳送位置
            $submitUrl = $this->config['api_url'] . '/' . $this->action;

            // 取得 API 呼叫結果
            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'headers' => [
                    'content-type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => $formParams,
                'timeout' => '130',
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), TRUE);

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if ((string)array_get($arrayData, 'error_code') === 'OK') {
                return $this->responseFormatter($response, $arrayData);
            } else {
                throw new ApiCallerException($arrayData, 'all_bet');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}