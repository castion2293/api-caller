<?php

namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class NineKLotteryCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'POST' => [
            'RegisterUser',
            'GetUserBalance',
            'BalanceTransfer',
            'UserLogin',
            'BetList',
            'CheckTransfer'
        ]
    ];

    /**
     * NineKLotteryCaller constructor.
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
            'api_url' => config('api_caller.nine_k_lottery.config.api_url'),
            'api_token' => config('api_caller.nine_k_lottery.config.api_token'),
        ];

        // 準備固定預設 API 參數
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
     * @return static
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
        return $this->config['api_url'] . '/api/' . $this->config['api_token'] . '/' . $this->action;
    }

    /**
     * 判斷是不是使用AMG DEMO帳號
     *
     * @return bool
     */
    private function isAmgDemoAccount()
    {
        $memberAccount = array_get($this->formParams, 'MemberAccount');
        $demoMemberAccounts = explode(',', config('api_caller.nine_k_lottery.config.demo_member_accounts'));

        // 沒有 member account 就不要判斷是不是在demo member accounts 裡面
        if (empty($memberAccount)) {
            return false;
        }

        // member account 不是在 demo member accounts 裡面 return false
        if (!in_array($memberAccount, $demoMemberAccounts)) {
            return false;
        }

        return true;
    }

    /**
     * 送出 API 請求
     *
     * @return array
     * @throws \SuperPlatform\ApiCaller\Exceptions\ApiCallerException
     */
    public function submit()
    {
        // 決定傳送的位置
        try {
            $submitUrl = $this->getSubmitUrl();

            // 為了AMG所做的路由判斷
            if ($this->isAmgDemoAccount()) {
                $this->config['api_token'] = config('api_caller.nine_k_lottery.config.api_demo_token');
                $submitUrl = $this->getSubmitUrl();

                if (array_key_exists('BossID', $this->formParams)) {
                    $this->formParams['BossID'] = config('api_caller.nine_k_lottery.config.agent_demo_id');
                }
            }

            // 取得 API 呼叫結果
            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'form_params' => $this->formParams,
                'timeout' => '40',
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if (array_get($arrayData, 'success') == 0) {
                return $this->responseFormatter($response, $arrayData);
            } else {
                $errorData = [
                    'errorCode' => array_get($arrayData, 'success'),
                    'errorMsg' => array_get($arrayData, 'msg')
                ];

                throw new ApiCallerException($errorData, 'nine_k_lottery');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}