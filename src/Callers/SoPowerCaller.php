<?php

namespace SuperPlatform\ApiCaller\Callers;

use Chaoyenpo\SignCode\SignCode;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class SoPowerCaller extends Caller
{
    /**
     * API 設定
     *
     * @var array
     */
    protected $config;

    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'GET' => [
            // -- 玩家進入遊戲大廳 --
            // 正式： https://www.sopow8.com?token=XXXXX
            // 測試： https://www.guoluw.cn?token=XXXXX
        ],
        'POST' => [
            // -- 註冊玩家身分 --
            // @param username 新玩家帳號 4 到 20 個英數字母
            'CREATE_USER',

            // -- 請求使用者 Token --
            // @param username 玩家帳號
            'REQUEST_TOKEN',

            // -- 取得餘額 --
            // @param username 玩家帳號
            'GET_CREDIT',

            // -- 玩家轉帳 --
            // @param username 玩家帳號
            // @param amount 轉帳金額，小數第三位，正數為轉入，負數為轉出
            'TRANSFER_CREDIT',

            // -- 取得代理商帳務報表 --
            // @param start_time 查詢開始時間 unix timestamp 格式
            // @param end_time 查詢結束時間 unix timestamp 格式
            // @param result_ok 是否結算，all=全部, 1=已結算, 2=未結算
            'GET_REPORT',
        ],
    ];

    public function __construct()
    {
        parent::__construct();

        // 定義遊戲 API 設定檔
        $this->config = [
            'api_url' => config("api_caller.so_power.config.api_url"),
            'client_id' => config("api_caller.so_power.config.api_client_id"),
            'secret' => config("api_caller.so_power.config.api_client_secret"),
        ];

        // 準備每次都會有的固定 API 參數
        $this->formParams = [
            'method' => '',
            'timestamp' => '',
            'client_id' => $this->config['client_id'],
            'sign_code' => '',
        ];
    }

    public function __destruct()
    {
        unset($this->formParams);
        unset($this->config);
    }

    /**
     * SignCode 模式加密傳送的資料
     *
     * @return string
     * @throws \Exception
     */
    private function encrypt()
    {
        // 建立加密器
        $signCodeTool = new SignCode([
            'secret' => $this->config['secret'],
            'sign_code_property_name' => 'sign_code'
        ]);

        // 將欲傳送資料進行加密
        return $signCodeTool->generate($this->formParams);
    }

    /**
     * 設定 API 參數
     *
     * @param array $data
     * @return SoPowerCaller
     */
    public function params(array $data = [])
    {
        // 抓到method的方法
        $this->formParams['method'] = $this->action;

        // 將傳送的參數做合併
        $this->formParams = array_merge(
            $this->formParams,
            $data
        );

        return $this;
    }

    /**
     * 發送 API 請求
     *
     * @usage
     *   $caller->methodAction()
     *          ->submit()
     *
     * @return array
     * @throws ApiCallerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function submit()
    {
        try {
            // 只要動作不是建立新會員，username 參數都應該自動補上代理商代碼為前綴
            if ($this->action !== 'CREATE_USER' && array_has($this->formParams, 'username')) {
                $this->formParams['username'] = "{$this->config['client_id']}{$this->formParams['username']}";
            }

            // 在 submit 之前取得參數的加密字串
            $this->formParams['timestamp'] = time();
            $this->formParams['sign_code'] = $this->encrypt();

            // 取得 API 呼叫結果
            $sResponseRawData = $this->guzzleClient->request($this->method, $this->config['api_url'], [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode($this->formParams),
                'timeout' => '30',
            ]);

            $aResponseContentsData = json_decode($sResponseRawData->getBody()->getContents(), true);

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if ($aResponseContentsData['message'] === 'OK') {
                return $this->responseFormatter($sResponseRawData, $aResponseContentsData);
            } else {
                throw new ApiCallerException($aResponseContentsData, 'so_power');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}