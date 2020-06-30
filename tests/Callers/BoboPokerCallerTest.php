<?php

namespace SuperPlatform\ApiCaller\Tests\Callers;

use BaseTestCase;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

class BoboPokerCallerTest extends BaseTestCase
{
    /**
     * 測試上層代理帳號
     *
     * @var string
     */
    protected $testAgent = '';

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

        $this->testAccount = config('api_caller.bobo_poker.config.test_member_account');
        $this->testAgent = config('api_caller.bobo_poker.config.agent_id');

        // 提示測試中的 caller 是哪一個
        $this->console->write('bobo poker');
    }

    /**
     * 測試創建玩家錢包
     *
     * @throws ApiCallerException
     */
    public function testCreatePlayer()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試創建玩家錢包');

            // Act
            $response = ApiCaller::make('bobo_poker')->methodAction('post', 'wallet/createPlayer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'spId' => $this->testAgent,
                'account' => $this->testAccount,
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('retCode', $response);
        } catch (ApiCallerException $exception) {
            $this->assertEquals('ACCOUNT_ALREADY_EXIST', array_get($exception->response(), 'errorCode'));
            $this->assertEquals('account已存在', array_get($exception->response(), 'errorMsg'));
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試取得玩家錢包資訊
     *
     * @throws ApiCallerException
     */
    public function testGetPlayerInfo()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得玩家錢包資訊');

            // Act
            $response = ApiCaller::make('bobo_poker')->methodAction('post', 'wallet/getPlayerInfo', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'spId' => $this->testAgent,
                'account' => $this->testAccount,
            ])->submit();

            $response = $response['response'];
            $data = array_get($response, 'data');

            $this->assertArrayHasKey('balance', $data);
            $this->assertEquals(1, array_get($data, 'status'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試轉點接口
     *
     * @throws ApiCallerException
     */
    public function testTransferWallet()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試轉點接口');

            $tranId = str_random(32);
            $amount = 1;

            // Act
            $response = ApiCaller::make('bobo_poker')->methodAction('post', 'wallet/tran', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'spId' => $this->testAgent,
                'tranId' => $tranId,
                'account' => $this->testAccount,
                'type' => 0, // 轉入
                'amount' => $amount * 100, // 單位為分，需 * 100
            ])->submit();

            $response = $response['response'];
            $data = array_get($response, 'data');

            $this->assertArrayHasKey('amount', $data);
            $this->assertEquals($amount, array_get($data, 'amount') / 100);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試確認轉帳訂單
     *
     * @throws ApiCallerException
     */
    public function testCheckOrder()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試確認轉帳訂單');

            $tranId = str_random(32);
            $amount = 1;

            // 先進行轉帳動做
            ApiCaller::make('bobo_poker')->methodAction('post', 'wallet/tran', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'spId' => $this->testAgent,
                'tranId' => $tranId,
                'account' => $this->testAccount,
                'type' => 0, // 轉入
                'amount' => $amount * 100,
            ])->submit();

            sleep(1);

            // 再進行查帳動做
            $response = ApiCaller::make('bobo_poker')->methodAction('post', 'datasouce/checkOrder', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'spId' => $this->testAgent,
                'tranId' => $tranId,
                'createTranTime' => date('YmdHis'),
            ])->submit();

            $response = $response['response'];
            $data = array_get($response, 'data');

            $this->assertArrayHasKey('isExist', $data);
            $this->assertEquals(1, array_get($data, 'isExist'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得遊戲連結
     *
     * @throws ApiCallerException
     */
    public function testLaunchGame()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得遊戲連結');

            // Act
            $response = ApiCaller::make('bobo_poker')->methodAction('post', 'launch/{device}', [
                // 路由參數這邊設定
                'device' => 'pc'
            ])->params([
                // 一般參數這邊設定
                'spId' => $this->testAgent,
                'productId' => '8857',
                'returnUrl' => 'http://www.google.com',
                'account' => $this->testAccount,
                'logoUrl' => 'http://d87b.com/image/PC/logo.jpg',
                'storeUrl' => 'http://d87b.com/image/PC/store.html'
            ])->submit();

            $response = $response['response'];
            $data = array_get($response, 'data');

            $this->assertArrayHasKey('gameUrl', $data);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得近期一小時內遊戲紀錄
     *
     * @throws ApiCallerException
     */
    public function testGetBetRecordByHour()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得近期一小時內遊戲紀錄');

            // Act
            $response = ApiCaller::make('bobo_poker')->methodAction('post', 'datasouce/getBetRecordByHour', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'spId' => $this->testAgent,
            ])->submit();

            $response = $response['response'];
            $data = array_get($response, 'data');
            $this->assertArrayHasKey('record', $data);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得一小時內遊戲紀錄
     *
     * @throws ApiCallerException
     */
    public function testGetBetRecordByHourSetDay()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得一小時內遊戲紀錄');

            // Act
            $response = ApiCaller::make('bobo_poker')->methodAction('post', 'datasouce/getBetRecordByHourSetDay', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'spId' => $this->testAgent,
                'date' => '20190919',
                'hour' => "17"
            ])->submit();

            $response = $response['response'];
            $data = array_get($response, 'data');

            $this->assertArrayHasKey('record', $data);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得轉點紀錄
     *
     * @throws ApiCallerException
     */
    public function testGetTransactionRecord()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得轉點紀錄');

            // Act
            $response = ApiCaller::make('bobo_poker')->methodAction('post', 'datasouce/getTransactionRecord', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'spId' => $this->testAgent,
                'date' => date('Ymd'),
            ])->submit();

            $response = $response['response'];
            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得餘額變動紀錄
     *
     * @throws ApiCallerException
     */
    public function testGetBalanceChangeRecord()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得餘額變動紀錄');

            // Act
            $response = ApiCaller::make('bobo_poker')->methodAction('post', 'datasouce/getBalanceChangeRecord', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'spId' => $this->testAgent,
                'date' => date('Ymd'),
            ])->submit();

            $response = $response['response'];
            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
}