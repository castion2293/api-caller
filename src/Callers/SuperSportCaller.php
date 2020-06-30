<?php

namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class SuperSportCaller extends Caller
{
    /**
     * 定義SuperSport可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'POST' => [
            'login',
            'logout',
            'account',
            'points',
            'report',
            'betrow',
            'GetDetailForPayoutTime'
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
            'api_url' => config("api_caller.super_sport.config.api_url"),
            'api_route' => config("api_caller.super_sport.config.api_route"),
            'api_key' => config("api_caller.super_sport.config.api_key"),
            'api_iv' => config("api_caller.super_sport.config.api_iv"),
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
     * AES-128 模式加密傳送的資料
     *
     * @param string $data
     * @return string
     */
    private function encrypt($data)
    {
        return openssl_encrypt($data, "AES-128-CBC", $this->config['api_key'], 0, $this->config['api_iv']);
    }

    /**
     * 加密FormParams內容
     *
     * @return array
     */
    private function encryptFormParams()
    {
        /* 修改密碼為唯一 passwd 不用加密的例外 */
        if ($this->action == 'account' && $this->formParams['act'] == 'mdy') {
            foreach ($this->formParams as $key => $value) {
                if (in_array($key, ['account', 'passwd', 'old_passwd', 'up_account', 'up_passwd'])) {
                    $this->formParams[$key] = $this->encrypt($value);
                }
            }
        } else {
            foreach ($this->formParams as $key => $value) {
                if (in_array($key, ['account', 'passwd', 'old_passwd', 'up_account', 'up_passwd'])) {
                    $this->formParams[$key] = $this->encrypt($value);
                }
            }
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
        return $this->config['api_url'] . "/" . $this->config['api_route'] . "/" . $this->action;
    }

    /**
     * 發送 API 請求
     *
     * @return array
     * @throws ApiCallerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function submit()
    {
        try {
            // 決定傳送的位置
            $submitUrl = $this->getSubmitUrl();

            // 加密該加密的參數
            $this->formParams = $this->encryptFormParams();

            // 取得 API 呼叫結果
            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'headers' => [
                    'content-type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => $this->formParams,
                'timeout' => '130',
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            if ($this->action === 'login') {
                $sLoginUrl = array_get($arrayData, 'data.login_url');
                $aLoginUrl = explode(':', $sLoginUrl);
                $aLoginUrl[0] = 'https';
                $sLoginUrl = implode(':', $aLoginUrl);

                array_set($arrayData, 'data.login_url', $sLoginUrl);
            }

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if ((string)array_get($arrayData, 'code') === '999') {
                return $this->responseFormatter($response, $arrayData);
            } else {
                throw new ApiCallerException($arrayData, 'super_sport');
            }
        } catch (\Exception $exception){
            // 外面多一層catch exception是為了抓到cUrl錯誤類訊息
            throw $exception;
        }
    }
}