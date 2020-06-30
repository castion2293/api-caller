<?php


namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class HabaneroCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'POST' => [
            'LoginOrCreatePlayer', //創建帳號
            'UpdatePlayerPassword', // 更新密碼
            'DepositPlayerMoney', // 玩家存款
            'WithdrawPlayerMoney', // 玩家提款
            'QueryTransfer', // 查詢轉帳狀態
            'QueryPlayer', // 查詢玩家
            'LogOutPlayer', // 登出玩家
            'SetMaintenanceMode', // 設置維護
            'CreateAndApplyBonusMulti', // 将创建的奖金分配给指定的玩家列表
            'GetBonusAvailablePlayer', // 获取有效并可用于此玩家的 优惠券/促销活动
            'ApplyBonusToPlayerMulti', // 玩家可以优惠券代码使用奖金优惠券
            'GetBonusBalancesForPlayer', // 返回玩家拥有的所有 Bonus Balance 的状态
            'SetPlayerBonusBalanceActive', // 找到一个特定的奖励并启动或停用
            'DeletePlayerBonusBalance', // 删除特定奖金
            'GetBrandCompletedGameResults', // 各品牌完整游戏结果
            'GetGroupCompletedGameResults', // 返回所有集团数据
            'GetBrandTransferTransactions', // 获取各品牌转账交易记录
            'GetGroupTransferTransactions', // 获取各集团转账交易记录
            'GetPlayerTransferTransactions', // 获取玩家转账交易
            'GetPlayerGameTransactions', // 获取玩家游戏交易记录
            'GetPlayerGameResults', // 获取玩家游戏结果
            'GetPlayerStakePayoutSummary', // 获取玩家投注和支付总结
            'ReportGameOverviewPlayer', // 玩家游戏报告概述
            'ReportPlayerStakePayout', // 玩家投注支付报告
            'ReportJackpotWinner', // 赢得奖池玩家报告
            'ReportGameOverviewBrand', // 各品牌游戏概览报告
            'GetGames', // 取得遊戲
            'GetJackpots', // 取得獎池
            'GetBrandPromoEvent', // 取得赢得推广活动的玩家列表
            'GetBrandCompletedGameResultsV2', // 取得代理底下所有會員的注單
        ],
    ];


    /**
     * HabaneroCaller constructor.
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
            'api_url' => config('api_caller.habanero.config.api_url'),
            'api_lobby' => config('api_caller.habanero.config.api_lobby'),
            'brand_ID' => config('api_caller.habanero.config.brand_ID'),
            'api_key' => config('api_caller.habanero.config.api_key'),
        ];

        // 準備固定預設 API 參數
        $this->formParams = [
            'BrandId' => $this->config['brand_ID'],
            'APIKey' => $this->config['api_key'],
        ];
    }

    public function __destruct()
    {
        unset($this->formParams);
        unset($this->config);
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
        return $this->config['api_url'] . $this->action;
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
            $submitUrl = $this->getSubmitUrl();

            // 取得 API 呼叫結果
            $response = $this->guzzleClient->request($this->method, $submitUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $this->formParams,
                'timeout' => '30',
            ]);

            $arrayData = json_decode($response->getBody()->getContents(), true);

            // 部分Api 不會有Success Code 所以直接回傳成功
            if ($this->action === 'LoginOrCreatePlayer' || $this->action === 'GetGroupCompletedGameResults'
            || $this->action === 'GetPlayerGameResults' || $this->action === 'GetBrandCompletedGameResultsV2') {
                return $this->responseFormatter($response, $arrayData);
            }

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if (array_get($arrayData, 'Success') === true || array_get($arrayData, 'Found') === true) {
                return $this->responseFormatter($response, $arrayData);
            } else {
                throw new ApiCallerException($arrayData, 'habanero');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}