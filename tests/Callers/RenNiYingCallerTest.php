<?php

namespace SuperPlatform\ApiCaller\Tests\Callers;

use BaseTestCase;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

class RenNiYingCallerTest extends BaseTestCase
{
    /**
     * 測試帳號
     *
     * @var string
     */
    protected $testAccount = '';

    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        $this->testAccount = config('api_caller.ren_ni_ying.config.test_member_account');

        // 提示測試中的 caller 是哪一個
        $this->console->write('Ren Ni Ying');
    }

    /**
     * 測試導入玩家到 RNY 系統
     *
     * @throws ApiCallerException
     */
    public function testExportPlayer()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試導入玩家到 RNY 系統');

            $agentId = config('api_caller.ren_ni_ying.config.agent_id');
            $balk = config('api_caller.ren_ni_ying.config.balk');
            $proportion = config('api_caller.ren_ni_ying.config.proportion');
            $maxProfit = config('api_caller.ren_ni_ying.config.max_profit');
            $keepRebateRate = config('api_caller.ren_ni_ying.config.keep_rebate_rate');

            // Act
            $response = ApiCaller::make('ren_ni_ying')->methodAction('post', 'exportPlayer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'parentAgentUserId' => $agentId,
                'userId' => $this->testAccount,
                'nick' => $this->testAccount,
                'balk' => $balk,
                'initialCredit' => 0,
                'parentProportion' => $proportion,
                'maxProfit' => $maxProfit,
                'keepRebateRate' =>$keepRebateRate
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得玩家進入 RNY 遊戲信息
     *
     * @throws ApiCallerException
     */
    public function testGetPlayerTicket()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得玩家進入 RNY 遊戲信息');

            // Act
            $response = ApiCaller::make('ren_ni_ying')->methodAction('get', 'getPlayerTicket', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userId' => $this->testAccount,
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試登出指定玩家
     *
     * @throws ApiCallerException
     */
    public function testLogoutPlayer()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試登出指定玩家');

            // Act
            $response = ApiCaller::make('ren_ni_ying')->methodAction('post', 'logoutPlayer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userId' => $this->testAccount,
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得玩家信息
     *
     * @throws ApiCallerException
     */
    public function testGetPlayer()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得玩家信息');

            // Act
            $response = ApiCaller::make('ren_ni_ying')->methodAction('get', 'getPlayer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userId' => $this->testAccount,
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試更新玩家的狀態
     *
     * @throws ApiCallerException
     */
    public function testUpdatePlayerState()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試更新玩家的狀態');

            // Act
            $response = ApiCaller::make('ren_ni_ying')->methodAction('post', 'updatePlayerState', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userId' => $this->testAccount,
                'state' => 2
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試更新玩家餘額
     *
     * @throws ApiCallerException
     */
    public function testUpdatePlayerBalance()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試更新玩家餘額');

            $amount = 1;

            // Act
            $response = ApiCaller::make('ren_ni_ying')->methodAction('post', 'updatePlayerBalance', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userId' => $this->testAccount,
                'amount' => $amount
            ])->submit();

            $response = $response['response'];
            $before = array_get($response, 'data.beforeCreditSum');
            $after = array_get($response, 'data.afterCreditSum');

            $this->assertArrayHasKey('data', $response);
            $this->assertEquals($amount, $after - $before);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查詢注單
     *
     * @throws ApiCallerException
     */
    public function testLedgerQuery()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試更新玩家餘額');

            // Act
            $response = ApiCaller::make('ren_ni_ying')->methodAction('get', 'ledgerQuery', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'pageIdx' => 1,
                'pageSize' => 1000,
                'status' => 1,
                'recent' => config('api_caller.ren_ni_ying.config.ticket_time_range')
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
}