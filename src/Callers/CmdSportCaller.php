<?php


namespace SuperPlatform\ApiCaller\Callers;


use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class CmdSportCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'GET' => [
            'createmember',
            'getbalance',
            'balancetransfer',
            'kickuser',
            'exist',
            'checkfundtransferstatus',
            'betrecord',
            'languageinfo',
            'parlaybetrecord',
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
            'api_url' => config('api_caller.cmd_sport.config.api_url'),
            'partner_key' => config('api_caller.cmd_sport.config.partner_key'),
        ];

        // 準備固定預設 API 參數
        $this->formParams = [
            'PartnerKey' => config('api_caller.cmd_sport.config.partner_key'),
        ];

//        $this->getToken();
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

        $this->formParams['Method'] = $this->action;

        return $this;
    }

    /**
     * 決定送出的 API 位置
     *
     * @return string
     */
    public function getSubmitUrl()
    {
        return $this->config['api_url'] . 'SportsApi.aspx';
    }

    /**
     * 決定登入的裝置位置(電腦版登入時使用)
     *
     * @return string
     */
//    public function getWebLoginUrl()
//    {
//        return $this->config['web_url'] . 'auth.aspx';
//    }

    /**
     * 決定登入的裝置位置(手機版登入時使用)
     *
     * @return string
     */
//    public function getMobileLoginUrl()
//    {
//        return $this->config['mobile_url'] . 'auth.aspx';
//    }

    /**
     * 送出 API 請求
     *
     * @return array
     * @throws \SuperPlatform\ApiCaller\Exceptions\ApiCallerException
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function submit()
    {
        // 決定傳送的位置
        try {
            $submitUrl = $this->getSubmitUrl();

            $headers = [
                'Content-Type' => 'application/json',
//                'Authorization' => $this->token,
                'Authorization' => "",
            ];
            $httpType = 'query';


            // 取得 API 呼叫結果
            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'headers' => $headers,
                $httpType => $this->formParams,
                'timeout' => '40',
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if (array_get($arrayData, "Code") === 0) {
                return $this->responseFormatter($response, $arrayData);
            } else {
                $errorData = [
                    'errorCode' => array_get($arrayData, 'Code'),
                    'errorMsg' => array_get($arrayData, 'Message')
                ];
                throw new ApiCallerException($errorData, 'cmd_sport');
            }
        } catch (\Exception $exception) {
            throw $exception;
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