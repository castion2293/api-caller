<?php

namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class VsLotteryCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'POST' => [
            'CreatePlayerAccount',
            'KickOutPlayer',
            'ResetPassword',
            'SetAllowBet',
            'SetAllowLogin',
            'CopySettings',
            'GetLoginUrl',
            'GetPlayerBalance',
            'DepositWithdrawRef',
            'CheckDepositWithdrawStatus',
            'GetFundTransaction',
            'GetBetTransaction',
            'GetGameResult',
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
            'api_url' => config("api_caller.vs_lottery.config.api_url"),
            'partner_id' => config("api_caller.vs_lottery.config.partner_id"),
            'partner_password' => config("api_caller.vs_lottery.config.partner_password"),
            'member_account_prefix' => config("api_caller.vs_lottery.config.member_account_prefix"),

        ];

        // 準備固定預設 API 參數
        $this->formParams = [
            'partnerId' => (int)config("api_caller.vs_lottery.config.partner_id"),
            'partnerPassword' => config("api_caller.vs_lottery.config.partner_password"),
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
        return $this->config['api_url'];
    }

    public function getSoapXml()
    {
        $xml = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body><' . $this->action . ' xmlns="http://www.universal.ws/webservices">';

        foreach ($this->formParams as $key => $value) {
            $xml .= "<{$key}>{$value}</{$key}>";
        }

        $xml .= "</{$this->action}>
  </soap:Body>
</soap:Envelope>";

        return $xml;
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
            // 決定傳送的位置
            $submitUrl = $this->getSubmitUrl();
            // 取得 API 呼叫結果
            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'headers' => [
                    'Content-Type' => 'text/xml; charset=utf-8'
                ],
                'body' => $this->getSoapXml(),
                'timeout' => '30',
            ]);

            $xml = $response->getBody()->getContents();
            $clean_xml = str_ireplace(['SOAP-ENV:', 'SOAP:', 'xs:', 'xml:', 'msdata:'], '', $xml);
            $xmlData = simplexml_load_string($clean_xml);
            $arrayData = json_decode(json_encode((array)$xmlData, JSON_NUMERIC_CHECK), TRUE);
            $arrayData = array_get(array_get($arrayData, 'Body'), $this->action . 'Response');
            $code = "{$this->action}Result";

            if ($this->action == 'GetFundTransaction' ||
                $this->action == 'GetBetTransaction' ||
                $this->action == 'GetGameResult') {
                $code = 'errorCode';
            }

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if ((string)array_get($arrayData, $code) === '0') {
                return $this->responseFormatter($response, $arrayData);
            } else {
                throw new ApiCallerException($arrayData, 'vs_lottery');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}