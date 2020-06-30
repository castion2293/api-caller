<?php

namespace SuperPlatform\ApiCaller\Tests\Callers;

use BaseTestCase;
use Carbon\Carbon;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

class AwcSexyCallerTest extends BaseTestCase
{
    /**
     * 測試帳號
     *
     * @var string
     */
    protected $testAccount = '';

    /**
     * 幣別
     *
     * @var string
     */
    protected $currency = '';

    /**
     * 語言
     *
     * @var string
     */
    protected $language = '';

    /**
     * 限紅組
     *
     * @var array
     */
    protected $betLimitSet = [];

    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        $this->testAccount = config('api_caller.awc_sexy.config.test_member_account');
        $this->currency = config('api_caller.awc_sexy.config.currency');
        $this->language = config('api_caller.awc_sexy.config.language');
        $this->betLimitSet = explode(',', config('api_caller.awc_sexy.config.bet_limit'));

        // 提示測試中的 caller 是哪一個
        $this->console->write('性感百家樂');
    }

    /**
     * 測試 性感百家樂 新增用戶
     *
     */
    public function testCreateAccount()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試性感百家樂 新增用戶');

            $betLimit = [
                'SEXYBCRT' => [
                    'LIVE' => [
                        'limitId' => $this->betLimitSet
                    ]
                ]
            ];

            // Act
            $response = ApiCaller::make('awc_sexy')->methodAction('post', 'createMember', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userId' => $this->testAccount,
                'currency' => $this->currency,
                'betLimit' => json_encode($betLimit),
                'language' => $this->language,
            ])->submit();

            $response = $response['response'];

            $this->assertEquals('0000', array_get($response, 'status'));
        } catch (ApiCallerException $exception) {
            $this->assertEquals('1001', array_get($exception->response(), 'errorCode'));
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 性感百家樂 進入遊戲
     *
     */
    public function testDoLoginAndLaunchGame()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試性感百家樂 進入遊戲');

            // Act
            $response = ApiCaller::make('awc_sexy')->methodAction('post', 'doLoginAndLaunchGame', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userId' => $this->testAccount,
                'gameCode' => 'MX-LIVE-001',
                'gameType' => 'LIVE',
                'platform' => 'SEXYBCRT',
                'isMobileLogin' => false,
            ])->submit();

            $response = $response['response'];

            $this->assertEquals('0000', array_get($response, 'status'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 性感百家樂 進入遊戲大廳
     *
     */
    public function testLogin()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試性感百家樂 進入遊戲大廳');

            // Act
            $response = ApiCaller::make('awc_sexy')->methodAction('post', 'login', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userId' => $this->testAccount,
                'isMobileLogin' => 'false',
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('0000', array_get($response, 'status'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 性感百家樂 登出
     *
     */
    public function testLogout()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試性感百家樂 登出');

            // Act
            $response = ApiCaller::make('awc_sexy')->methodAction('post', 'logout', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userIds' => $this->testAccount,
            ])->submit();

            $response = $response['response'];
            $this->assertEquals('0000', array_get($response, 'status'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 性感百家樂 登出
     *
     */
    public function testGetBalance()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試性感百家樂 登出');

            // Act
            $response = ApiCaller::make('awc_sexy')->methodAction('post', 'getBalance', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userIds' => $this->testAccount,
            ])->submit();

            $response = $response['response'];
            $result = array_get($response, 'results');

            $this->assertArrayHasKey(strtolower($this->testAccount), $result);
            $this->assertEquals('0000', array_get($response, 'status'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 性感百家樂 儲值
     *
     */
    public function testDeposit()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試性感百家樂 儲值');

            // Act
            $response = ApiCaller::make('awc_sexy')->methodAction('post', 'deposit', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userId' => $this->testAccount,
                'amount' => 1,
                'txCode' => str_random(50)
            ])->submit();

            $response = $response['response'];

            $this->assertEquals('0000', array_get($response, 'status'));
            $this->assertArrayHasKey('currentBalance', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 性感百家樂 出金
     *
     */
    public function testWithdraw()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試性感百家樂 出金');

            // Act
            $response = ApiCaller::make('awc_sexy')->methodAction('post', 'withdraw', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userId' => $this->testAccount,
                'txCode' => str_random(50),
                'withdrawType' => 0,
                'transferAmt' => '1',
            ])->submit();

            $response = $response['response'];

            $this->assertEquals('0000', array_get($response, 'status'));
            $this->assertArrayHasKey('currentBalance', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 性感百家樂 轉帳狀態
     *
     */
    public function testCheckTransfer()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試 性感百家樂 轉帳狀態');

            $txCode = str_random(50);
            // Act
            $balanceTransferResponse = ApiCaller::make('awc_sexy')->methodAction('post', 'deposit', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userId' => $this->testAccount,
                'amount' => 1,
                'txCode' => $txCode,
            ])->submit();

            $balanceTransferResponse = $balanceTransferResponse['response'];

            $checkTransferResponse = ApiCaller::make('awc_sexy')->methodAction('post', 'checkTransferOperation', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'txCode' => $txCode,
            ])->submit();

            $checkTransferResponse = $checkTransferResponse['response'];

            $this->assertEquals('0000', array_get($checkTransferResponse, 'status'));
            $this->assertEquals(1, array_get($checkTransferResponse, 'txStatus'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 性感百家樂 撈注單
     *
     */
    public function testGetTransactionByUpdateDate()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試 性感百家樂 撈注單');

            // Act
            $response = ApiCaller::make('awc_sexy')->methodAction('post', 'getTransactionByUpdateDate', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'timeFrom' => now()->subMinutes(30)->toIso8601String(),
            ])->submit();

            $response = $response['response'];

            $this->assertEquals('0000', array_get($response, 'status'));
            $this->assertArrayHasKey('transactions', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 性感百家樂 撈注單
     *
     */
    public function testGetTransactionByTxTime()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試 性感百家樂 撈注單');

            // Act
            $response = ApiCaller::make('awc_sexy')->methodAction('post', 'getTransactionByTxTime', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'startTime' => Carbon::parse('2020-05-21 18:00:00')->toIso8601String(),
                'endTime' => Carbon::parse('2020-05-21 19:00:00')->toIso8601String(),
                'platform' => 'SEXYBCRT',
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('0000', array_get($response, 'status'));
            $this->assertArrayHasKey('transactions', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 性感百家樂 修改限紅
     *
     */
    public function testUpdateBetLimit()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試 性感百家樂 修改限紅');

            $betLimit = [
                'SEXYBCRT' => [
                    'LIVE' => [
                        'limitId' => $this->betLimitSet
                    ]
                ]
            ];

            // Act
            $response = ApiCaller::make('awc_sexy')->methodAction('post', 'updateBetLimit', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userId' => $this->testAccount,
                'betLimit' => json_encode($betLimit),
            ])->submit();

            $response = $response['response'];

            $this->assertEquals('0000', array_get($response, 'status'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 性感百家樂 總帳
     *
     */
    public function testTransactionHistoryResult()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試 性感百家樂 總帳');

            // Act
            $response = ApiCaller::make('awc_sexy')->methodAction('post', 'getTransactionHistoryResult', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userId' => strtolower($this->testAccount),
                'txId' => 3940571
            ])->submit();

            $response = $response['response'];

            $this->assertEquals('0000', array_get($response, 'status'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 性感百家樂 線上會員
     */
    public function testGetOnlinePlayer()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試 性感百家樂 線上會員');

            // Act
            $response = ApiCaller::make('awc_sexy')->methodAction('post', 'getOnlinePlayer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'pageNumber' => 1,
                'pageSize' => 100
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }
}