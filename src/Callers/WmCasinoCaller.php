<?php


namespace SuperPlatform\ApiCaller\Callers;


use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class WmCasinoCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'POST' => [
            // === 多重錢包 ===
            'Hello',                // 測試用API
            'MemberRegister',       // 註冊
            'SigninGame',           // 登入遊戲 (與單一錢包不同)
            'LogoutGame',           // 登出遊戲
            'ChangePassword',       // 變更密碼
            'GetAgentBalance',      // 查詢代理商餘額
            'GetBalance',           // 取餘額
            'ChangeBalance',        // 加扣點
            'GetMemberTradeReport', // 交易紀錄報表
            'GetDateTimeReport',    // 遊戲紀錄報表
            'GetTipReport',         // 小費紀錄報表
            'EnableorDisablemem',   // 停啟用登入遊戲與下注
            'EditLimit',            // 修改限額
            'GetUnsettleReport',    // 未結算單查詢

            // === 單一錢包 ===
            'LoginGame',           // 登入遊戲 (與多重錢包不同)
            'CallBalance',         // 取餘額 (我方需實作部分)
            'PointInout',          // 加扣點 (我方需實作部分)
            'TimeoutBetReturn',    // 回滾 (我方需實作部分)
            'SendMemberReport',    // 結算報表
            'GetDealidStatus',     // 单一钱包查询
        ]
    ];
    
    /**
     * WmCasinoCaller constructor.
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
            'api_url' => config('api_caller.wm_casino.config.api_url'),
            'vendor_id' => config('api_caller.wm_casino.config.vendor_id'),
            'signature_key' => config('api_caller.wm_casino.config.signature_key'),
        ];

        // 準備固定預設 API 參數
        $this->formParams = [
            'cmd' => $this->action,
            'vendorId' => config('api_caller.wm_casino.config.vendor_id'),
            'signature' => config('api_caller.wm_casino.config.signature_key'),
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
        // 抓到cmd的方法
        $this->formParams['cmd'] = $this->action;

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
        return $this->config['api_url'] . 'cmd=' . $this->action;
    }

    /**
     * 送出 API 請求
     *
     * @return array
     * @throws \SuperPlatform\ApiCaller\Exceptions\ApiCallerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function submit()
    {
        // 決定傳送的位置
        try {
            $submitUrl = $this->getSubmitUrl();

            // 取得 API 呼叫結果
            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'query' => $this->formParams,
                'timeout' => '310',
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            // 若發生「操作成功，但未搜寻到数据」就一樣正常回傳，此訊息是為了戳抓注單API「沒有資料」才發生的錯誤
            if (array_get($arrayData, 'errorCode') == 0 || array_get($arrayData, 'errorCode') === 107) {
                return $this->responseFormatter($response, $arrayData);
            } else {
                throw new ApiCallerException($arrayData, 'wm_casino');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}