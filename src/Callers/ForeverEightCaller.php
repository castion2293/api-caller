<?php


namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class ForeverEightCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
            'GET' => [
                'fwgame_opt', // 進入遊戲
            ],
            'POST' => [
                'lg', //創建帳號
                'gb', // 取得餘額
                'tc', // 預備轉帳
                'tcc', // 轉帳確認
                'qos', // 查詢轉帳訂單狀態
                'gjp', // 取得即時彩金
                'GET_PAGES_DETAIL_WITH_DATE', // 取得投注纪录总页数
                'GET_RECORDS_WITH_DATE_ON_PAGE', // 取得指定页数的投注纪录
            ],
        ];


    /**
     * ForeverEightCaller constructor.
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
            'api_url' => config('api_caller.forever_eight.config.api_url'),
            'client_ID' => config('api_caller.forever_eight.config.client_ID'),
            'md5_key' => config('api_caller.forever_eight.config.md5_key'),
            'aes_key' => config('api_caller.forever_eight.config.aes_key'),
            'initial_aes_key' => config('api_caller.forever_eight.config.initial_aes_key'),
        ];

        // 準備固定預設 API 參數
        $this->formParams = [
            'CTGent' => $this->config['client_ID'],
            'Method' => $this->action,
        ];
    }

    public function __destruct()
    {
        unset($this->formParams);
        unset($this->config);
    }

    /**
     * AES-256 模式加密傳送的資料
     *
     * @param string $data
     * @return string
     */
    private function encrypt($data)
    {
        return openssl_encrypt($data, 'aes-256-cbc', $this->config['aes_key'], 0, $this->config['initial_aes_key']);
    }

    /**
     * 組合參數
     *
     * @param array $data
     * @return $this
     */
    public function params(array $data = [])
    {
        $this->formParams['Method'] = $this->action;

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
        $arrayKeys = array_keys($this->formParams);

        usort($arrayKeys, function ($a, $b) {
            $a = strtolower($a);
            $b = strtolower($b);
            if ($a == $b) {
                return 0;
            }
            return ($a > $b) ? 1 : -1;
        });

        $signString = '';
        foreach ($arrayKeys as $key) {
            $value = array_get($this->formParams, $key);
            $signString .= $key . '=' . $value . ',';
        }
        $signString = substr($signString, 0, strlen($signString)-1);

        if ($this->method === 'POST') {
            return $this->config['api_url'] . '/kg_api/v2/doValidate_ssl.php?params=' . $this->encrypt($signString) .
                '&key=' . md5($signString . $this->config['md5_key']);
        } else {
            // 使用於進入遊戲
            return $this->config['api_url'] . '/kg_api/v2/fwValidate_ssl.php?params=' . $this->encrypt($signString) .
                '&key=' . md5($signString . $this->config['md5_key']);
        }
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
        try {
            // 取得 API 呼叫結果
            $response = $this->guzzleClient->request($this->method, $this->getSubmitUrl(), [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'timeout' => '30',
            ]);

            $contents = $response->getBody()->getContents();

            $arrayData = json_decode($contents, true);

            if (empty($arrayData)) {
                $arrayData = [
                    'errorCode' => '',
                    'errorMsg' => $contents
                ];
            }

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if ((string)array_get($arrayData, 'Status') === "1") {
                return $this->responseFormatter($response, $arrayData);
            } else {
                throw new ApiCallerException($arrayData, 'forever_eight');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}