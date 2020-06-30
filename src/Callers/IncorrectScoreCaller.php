<?php


namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class IncorrectScoreCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'POST' => [
            'hello', // 測試Hello
            'MemberRegister', // 創建會員
            'LoginGame', // 會員登入
            'UserInfo', // 查詢會員信息
            'GetBalance', // 取得會員餘額
            'GetMemberReportV2', //查詢下注紀錄
            'ChangePassword', // 修改會員密碼
            'ChangeBalance', // 额度转换（单一钱包不适用）
            'GetbussStatus', // 额度转换查询（单一钱包不适用）
            'GetGameResults', // 取得賽事結果
            'GetGameMoreList', // 取得所有賽事列表
            'LogoutGame', // 登出
            'GetUserOnline', // 取得會員在線狀態介面
        ]
    ];

    /**
     * IncorrectScoreCaller constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // 載入 API 設定
        $this->config = [
            'api_url' => config('api_caller.incorrect_score.config.api_url'),
            'vendorId' => config('api_caller.incorrect_score.config.vendor_id'),
            'signature' => config('api_caller.incorrect_score.config.signature_key'),
            'lang' => config('api_caller.incorrect_score.config.lang'),
            'agentid' => config('api_caller.incorrect_score.config.agentid'),
        ];

        // 準備固定預設 API 參數
        $this->formParams = [
            'cmd' => $this->action,
            'vendorId' => config('api_caller.incorrect_score.config.vendor_id'),
            'signature' => config('api_caller.incorrect_score.config.signature_key'),
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

        $isTestLineMember = array_get($data, 'isTestLineMember', false);

        if ($isTestLineMember) {
            $this->formParams['vendorId'] = config('api_caller.incorrect_score.config.test_line_vendor_id');
//            $this->formParams['signature'] = config('api_caller.incorrect_score.config.test_line_signature_key');
        }

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
        return $this->config['api_url'] . '/' . $this->action;
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

            $header = [
                'Content-Type' => 'application/json',
            ];

            // 取得 API 呼叫結果
            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'headers' => $header,
                'query' => $this->formParams,
                'timeout' => '30',
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            //因取得所有賽事列表API回傳的errorCode是null，所以直接return
            $getGameMoreList = ($this->action == 'GetGameMoreList');
            if ($getGameMoreList && is_null(array_get($arrayData, 'errorCode'))) {
                return $this->responseFormatter($response, $arrayData);
            }

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if (array_get($arrayData, 'errorCode') === 100) {
                return $this->responseFormatter($response, $arrayData);
            } else {
                $errorData = [
                    'errorCode' => array_get($arrayData, 'errorCode'),
                    'errorMsg' => array_get($arrayData, 'errorMessage')
                ];

                throw new ApiCallerException($errorData, 'incorrect_score');
            }
        } catch (\Exception $exception) {
            $submitUrl = $this->getSubmitUrl();
            if ($submitUrl !== 'LogoutGame') {
                throw $exception;
            }
            throw new $exception;
        }
    }
}