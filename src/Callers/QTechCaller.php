<?php


namespace SuperPlatform\ApiCaller\Callers;


use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class QTechCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'GET' => [
            'wallet/ext/{playerId}',
            'fund-transfers/{transferId}',
            'games',
            'games/most-popular',
            'game-rounds',
            'game-rounds/{roundId}',
            'game-transactions',
            'ngr-player'
        ],
        'POST' => [
            'fund-transfers',
            'games/{gameId}/launch-url',
            'games/lobby-url',
            'players/{playerId}/service-url'
        ],
        'PUT' => [
            'fund-transfers/{transferId}/status'
        ],
    ];

    protected $QueryActions = [
        'game-rounds',
        'game-transactions',
        'ngr-player'
    ];

    /**
     * API憑證
     * @var string
     */
    protected $token = '';

    /**
     * QTechCaller constructor.
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
            'api_url' => config('api_caller.q_tech.config.api_url'),
            'agent_username' => config('api_caller.q_tech.config.agent_username'),
            'agent_password' => config('api_caller.q_tech.config.agent_password'),
        ];

        // 準備固定預設 API 參數
        $this->formParams = [];

        $this->getToken();
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
        return $this->config['api_url'] . '/v1/' . $this->action;
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

            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => $this->token,
            ];
            $httpType = 'json';

            // 一些API需改由http query 傳送
            if (in_array($this->action, $this->QueryActions)) {
                $headers = [
                    'Authorization' => $this->token,
//                    'Time-Zone' => config('api_caller.q_tech.config.time_zone'),
                ];
                $httpType = 'query';
            }

            // 取得 API 呼叫結果
            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'headers' => $headers,
                $httpType => $this->formParams,
                'timeout' => '30',
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if ($response->getStatusCode() === 200) {
                return $this->responseFormatter($response, $arrayData);
            }elseif ($response->getStatusCode() === 201) {
                return $this->responseFormatter($response, $arrayData);
            } else {
                throw new ApiCallerException($arrayData, 'q_tech');
            }
        } catch (\Exception $exception) {
            $arrayData = [
                'errorCode' => $exception->getCode(),
                'errorMsg' => $exception->getMessage()
            ];

            throw new ApiCallerException($arrayData, 'q_tech');
        }
    }

    protected function getToken()
    {
        try {
            $params = [
                'grant_type' => 'password',
                'response_type' => 'token',
                'username' => $this->config['agent_username'],
                'password' => $this->config['agent_password'],
            ];

            $response = $this->guzzleClient->request('get', $this->config['api_url'] . '/v1/auth/token', [
                'query' => $params
            ]);
            $arrayData = json_decode($response->getBody()->getContents(), true);
            $this->token = 'Bearer ' . array_get($arrayData, 'access_token');

        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}