<?php

namespace SuperPlatform\ApiCaller\Tests\Callers;

use BaseTestCase;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

class NineKLotteryCallerTest extends BaseTestCase
{
    /**
     * 測試帳號
     *
     * @var string
     */
    protected $testAccount = '';

    /**
     * 測試密碼
     *
     * @var string
     */
    protected $testPassword = '';

    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        $this->testAccount = config('api_caller.nine_k_lottery.config.test_member_account');
        $this->testPassword = config('api_caller.nine_k_lottery.config.test_member_password');

        // 提示測試中的 caller 是哪一個
        $this->console->write('9k Lottery');
    }

    /**
     * 測試創建客戶登入遊戲帳號
     *
     * @throws ApiCallerException
     */
    public function testRegisterUser()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試創建客戶登入遊戲帳號');

            $agentId = config('api_caller.nine_k_lottery.config.agent_id');

            // Act
            $response = ApiCaller::make('nine_k_lottery')->methodAction('post', 'RegisterUser', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'BossID' => $agentId,
                'MemberAccount' => $this->testAccount,
                'MemberPassword' => $this->testPassword
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->assertEquals('-1003', array_get($exception->response(), 'errorCode'));
            $this->assertEquals('MemberAccount 已存在', array_get($exception->response(), 'errorMsg'));
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試從代理商帳號存入額度到客戶遊戲帳號，或從客戶遊戲帳戶提取額度到代理商帳號
     *
     * @throws ApiCallerException
     */
    public function testGetUserBalance()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢客戶遊戲帳號內餘額');

            // Act
            $response = ApiCaller::make('nine_k_lottery')->methodAction('post', 'GetUserBalance', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'MemberAccount' => $this->testAccount,
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試從代理商帳號存入額度到客戶遊戲帳號，或從客戶遊戲帳戶提取額度到代理商帳號
     *
     * @throws ApiCallerException
     */
    public function testBalanceTransfer()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試從代理商帳號存入額度到客戶遊戲帳號，或從客戶遊戲帳戶提取額度到代理商帳號');

            $amount = 10.00;

            // Act
            $response = ApiCaller::make('nine_k_lottery')->methodAction('post', 'BalanceTransfer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'MemberAccount' => $this->testAccount,
                'Balance' => $amount,
            ])->submit();

            $response = $response['response'];

            $before = array_get($response, 'data.BalanceTransfer.BeforeBalance');
            $after = array_get($response, 'data.BalanceTransfer.AfterBalance');
            $status = array_get($response, 'data.BalanceTransfer.TransferStatus');

            $this->assertArrayHasKey('data', $response);
            $this->assertEquals('Y', $status);
            $this->assertEquals($amount, $after - $before);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查詢轉帳狀態
     *
     * @throws ApiCallerException
     */
    public function testCheckTransfer()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢轉帳狀態');

            $amount = 1.00;

            // Act
            $balanceTransferResponse = ApiCaller::make('nine_k_lottery')->methodAction('post', 'BalanceTransfer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'MemberAccount' => $this->testAccount,
                'Balance' => $amount,
                'TradeNo' => '111111'
            ])->submit();

            $balanceTransferResponse = $balanceTransferResponse['response'];

            $transactionID = array_get($balanceTransferResponse, 'data.BalanceTransfer.TransactionID');

            $checkTransferResponse = ApiCaller::make('nine_k_lottery')->methodAction('post', 'CheckTransfer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'MemberAccount' => $this->testAccount,
                'TransactionID' => $transactionID,
            ])->submit();

            $checkTransferResponse = $checkTransferResponse['response'];
            $transferStatus = array_get($checkTransferResponse, 'data.CheckTransfer.TransferStatus');

            $this->assertArrayHasKey('data', $checkTransferResponse);
            $this->assertEquals('Y', $transferStatus);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試系統自動跳轉並登入遊戲網站
     *
     * @throws ApiCallerException
     */
    public function testUserLogin()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試系統自動跳轉並登入遊戲網站');

            // Act
            $response = ApiCaller::make('nine_k_lottery')->methodAction('post', 'UserLogin', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'MemberAccount' => $this->testAccount,
                'MemberPassword' => $this->testPassword,
                'Platform' => 'desktop'
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('data', $response);

        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試系統自動跳轉並登入遊戲網站 遊戲碼
     *
     * @throws ApiCallerException
     */
    public function testUserLoginWithGameCode()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試系統自動跳轉並登入遊戲網站 遊戲碼');

            // Act
            $response = ApiCaller::make('nine_k_lottery')->methodAction('post', 'UserLogin', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'MemberAccount' => $this->testAccount,
                'MemberPassword' => $this->testPassword,
                'GameCode' => 'BJPK10',
                'Platform' => 'desktop'
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查詢客戶投注記錄
     *
     * @throws ApiCallerException
     */
    public function testBetList()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢客戶投注記錄');

            $agentId = config('api_caller.nine_k_lottery.config.agent_id');

            // Act
            $response = ApiCaller::make('nine_k_lottery')->methodAction('post', 'BetList', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'StartTime' => '2019-06-19 14:00:00',
                'EndTime' => '2019-06-19 16:00:00',
                'BossID' => $agentId,
                'Page' => 1,
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
}