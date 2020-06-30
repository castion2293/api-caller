<?php

namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class DreamGameCaller extends Caller
{
    /**
     * @var int
     */
    private $random;

    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'GET' => [

        ],
        'POST' => [
            'user/signup/{agent}', // 註冊新會員
            'user/update/{agent}', // 修改會員資料
            'user/login/{agent}', // 登入遊戲
            'user/getBalance/{agent}', // 取得餘額
            'account/transfer/{agent}', // 轉點，異動 amount 正負代表增加或回收
            'game/getReport/{agent}', // 抓取注單
            'game/getReport/', // 依照時間撈取注單
            'game/markReport/{agent}', // 註銷注單
            'game/updateLimit/{agent}', // 修改会员限红组
            'account/checkTransfer/{agent}' // 检查存取款操作是否成功
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
            'api_url' => config("api_caller.dream_game.config.api_url"),
            'report_url' => config('api_caller.dream_game.config.report_url'),
            'api_key' => config("api_caller.dream_game.config.api_key"),
            'api_agent' => config("api_caller.dream_game.config.api_agent"),
            'api_mobile_suffix' => config("api_caller.dream_game.config.api_mobile_suffix"),
        ];
    }

    public function __destruct()
    {
        unset($this->formParams);
        unset($this->config);
    }

    /**
     * 產生必要參數 token
     *
     * token = md5(agent + api_key + random)
     * @return string
     */
    private function generateToken($random)
    {
        return md5($this->config['api_agent'] . $this->config['api_key'] . $random);
    }

    /**
     * 密碼送出前要 md5
     *
     * @param array $data
     * @return void
     */
    private function transPasswordToMD5(&$data = [])
    {
        if (array_has($data, 'member') && array_has($data['member'], 'password')) {
            $data['member']['password'] = md5($data['member']['password']);
        }
    }

    /**
     * 設定 API 參數
     *
     * @param array $data
     * @return $this
     */
    public function params(array $data = [])
    {
        /**
         * 準備固定預設 API 參數
         *
         * random 用來產生 token 的隨機字串
         * token = md5(agent + api_key + random)
         */
        $this->random = mt_rand();
        $this->formParams['random'] = $this->random;
        $this->formParams['token'] = $this->generateToken($this->random);

        // 密碼送出前要 md5
        $this->transPasswordToMD5($data);

        // 合併轉換過的參數與固定參數
        $this->formParams = array_merge(
            $this->formParams,
            // 排除不能被覆寫部分系統自動填入的參數
            array_except($data, ['token', 'random'])
        );

        return $this;
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
            // 組合請求位置
            $submitUrl = $this->config['api_url'] . '/' . $this->action;

            // 依照時間撈取注單 url跟form params 要做修改
            if ($this->action === 'game/getReport/') {
                $submitUrl = $this->config['report_url'] . '/' . $this->action;

                $this->formParams = [
                    'token' => md5($this->config['api_agent'] . $this->config['api_key']),
                    'beginTime' => array_get($this->formParams, 'beginTime'),
                    'endTime' => array_get($this->formParams, 'endTime'),
                    'agentName' => $this->config['api_agent'],
                ];
            }

            // 取得 API 回應
            $response = $this->guzzleClient->request(
                $this->method,
                $submitUrl,
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $this->formParams,
                    'timeout' => '30',
                ]
            );

            $arrayData = json_decode($response->getBody()->getContents(), true);

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if ((string)array_get($arrayData, 'codeId') === '0') {
                return $this->responseFormatter($response, $arrayData);
            } else {
                throw new ApiCallerException($arrayData, 'dream_game');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}