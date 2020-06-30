<?php

namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class BingoCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'GET' => [
            # === 帳戶 API ===
            // 營運商帳戶基本資料
            'profile',

            # === 開獎球號 API ===
            // 球號歷史記錄
            'histories',
            // 球號統計記錄
            'balls-count',

            # === 注單限額 API ===
            // 取得營逸商預設限額表
            'ticket-limits',
            // 取得玩家限額表
            'ticket-limits/{playerId}',

            # === 注單 API ===
            // 取得注單列表
            'tickets',
            // 取得注單下注狀態
            'tickets/bet-status',

            # === 玩家 API ===
            // 取得玩家列表
            'players',
            // 查詢玩家資料
            'players/{playerId}',

            # === 賠率 API ===
            // 取得開毀結果賠率表
            'paytables',

            # === 點數 API ===
            // 點數異動歷史記錄
            'points',
            // 點數統計記錄查詢
            'points/statistics',
            // 點數營收查詢
            'points/revenue',
        ],
        'PATCH' => [
            # === 注單限額 API ===
            // 設定營運商預設限額表
            'ticket-limits',
            // 設定玩家限額表
            'ticket-limits/{playerId}',

            # === 玩家 API ===
            // 更新玩家資料
            'players/{playerId}',
            // 更新多個玩家資料
            'players',

            # === 賠率 API ===
            // 設定開獎結果賠率
            'paytables',
        ],
        'POST' => [
            # === 玩家 API ===
            // 建立新玩家
            'players',
            // 取得玩家遊戲連結
            'players/{playerId}/play-url',

            # === 賠率 API ===
            // 取得開毀結果賠率表
            'paytables',

            # === 點數 API ===
            // 充值玩家點數
            'points/{playerId}/deposit',
            // 回收玩家點數
            'points/{playerId}/withdraw'
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
            'api_url' => config("api_caller.bingo.config.api_url"),
            'api_key' => config("api_caller.bingo.config.api_key"),
        ];
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
            $this->formParams,
            // 排除不能被覆寫部分系統自動填入的參數
            array_except($data, [])
        );
        return $this;
    }

    /**
     * 發送 API 請求
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws ApiCallerException
     */
    public function submit()
    {
        try {
            // 組合請求位置
            $submitUrl = $this->config['api_url'] . '/' . $this->action;

            // 取得 API 回應
            $response = $this->guzzleClient->request(
                $this->method,
                $submitUrl,
                [
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:10.0) Gecko/20100101 Firefox/10.0',
                        'Authorization' => "Bearer {$this->config['api_key']}",
                        'X-Requested-With' => 'XMLHttpRequest',
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $this->formParams,
                ]
            );
            $content = json_decode($response->getBody()->getContents(), true);
            $content = $content ?: [];
            return $this->responseFormatter($response, $content);
        } catch (\Exception $exception) {
            // 因為 ApiCallerException 那邊有做 switch case 那段統一回應 response
            // 所以這邊先以同樣格式設定例外 response 結果
            throw new ApiCallerException([
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ], 'bingo');
        }
    }

    /**
     * 輔助函式: 取得兩個時間的毫秒差
     *
     * @param $start
     * @param null $end
     * @return float
     */
    protected function microTimeDiff($start, $end = null)
    {
        if (!$end) {
            $end = microtime();
        }
        list($start_usec, $start_sec) = explode(" ", $start);
        list($end_usec, $end_sec) = explode(" ", $end);

        $diff_sec = intval($end_sec) - intval($start_sec);
        $diff_usec = floatval($end_usec) - floatval($start_usec);
        return floatval($diff_sec) + $diff_usec;
    }
}