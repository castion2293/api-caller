<?php
//
//namespace SuperPlatform\ApiCaller\Callers;
//
//use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
//
//class HongChowCaller extends Caller
//{
//    /**
//     * 定義可用的方法與動作
//     *
//     * @var array
//     */2
//    protected $enabledMethodActions = [
//        'POST' => [
//            'auth', // 代理授权接口
//            'login', // 会员登录授权接口
//            'logintrial', // 试玩登录授权接口
//            'transfer', // 资金转账(转入或转出)接口
//            'transferinfo', // 资金转账状态查询接口
//            'transferinfo2', // 资金转账状态查询接口 2
//            'transferlist', // 批量获取会员转账记录接口
//            'balance', // 资金查询(余额)接口
//            'bets', // 注单拉取
//        ],
//    ];
//
//    private $authToken = null;
//    private $authTokenExpire = null;
//
//    /**
//     * HongChowCaller constructor.
//     *
//     * @throws ApiCallerException
//     * @throws \GuzzleHttp\Exception\GuzzleException
//     */
//    public function __construct()
//    {
//        parent::__construct();
//
//        $apiConfig = config('api_caller.hong_chow.config');
//
//        foreach ($apiConfig as $k => $v) {
//            switch ($k) {
//                case 'api_url':
//                case 'agent_id':
//                case 'secret_key':
//                    $this->config[$k] = $v;
//            }
//        }
//
//        // 取得 auth token
//        $this->connectAuth();
//        $this->formParams['token'] = $this->encrypt();
//    }
//
//    /**
//     * 設定 API 參數
//     *
//     * @param array $data
//     * @return HongChowCaller
//     */
//    public function params(array $data = [])
//    {
//        $this->formParams = array_merge(
//            $this->formParams, $data
//        );
//
//        return $this;
//    }
//
//    /**
//     * 送出 API 請求
//     *
//     * @return array
//     * @throws \SuperPlatform\ApiCaller\Exceptions\ApiCallerException
//     * @throws \GuzzleHttp\Exception\GuzzleException
//     */
//    public function submit()
//    {
//        // 取得 API 呼叫結果
//        $response = $this->guzzleClient->request($this->method, $this->config['api_url'] . $this->action, [
//            'headers' => [
//                'content-type' => 'application/json',
//            ],
//            'body' => json_encode($this->formParams),
//        ]);
//
//        $arrayData = json_decode($response->getBody()->getContents(), true);
//
//        // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
//        if ($arrayData['code'] === 0) {
//            return $this->responseFormatter($response, $arrayData);
//        } else {
//            throw new ApiCallerException($arrayData, 'hong_chow');
//        }
//    }
//
//    /**
//     * 加密 token
//     *
//     * @return string
//     */
//    private function encrypt()
//    {
//        return base64_encode(md5($this->config['agent_id']) . '|' . $this->authToken . '|' . md5($this->config['secret_key']));
//    }
//
//    /**
//     * @param callable|null $callback
//     * @throws ApiCallerException
//     * @throws \GuzzleHttp\Exception\GuzzleException
//     */
//    private function connectAuth(callable $callback = null)
//    {
//        $response = $this->guzzleClient->request('post', $this->config['api_url'] . 'auth', [
//            'headers' => [
//                'content-type' => 'application/json',
//            ],
//            'body' => json_encode([
//                'agentID' => $this->config['agent_id'],
//                'secret_key' => $this->config['secret_key'],
//            ]),
//        ]);
//
//        $arrayData = json_decode($response->getBody()->getContents(), true);
//
//        if ($arrayData['data']['token'] === '' || $arrayData['data']['expire'] === '') {
//            throw new ApiCallerException($response, 'hong_chow');
//        }
//
//        $this->authToken = $arrayData['data']['token'];
//        $this->authTokenExpire = $arrayData['data']['expire'];
//
//        if ($callback !== null) $callback();
//    }
//}