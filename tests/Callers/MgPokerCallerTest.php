<?php


namespace SuperPlatform\ApiCaller\Tests\Callers;

use BaseTestCase;
use Carbon\Carbon;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

class MgPokerCallerTest extends BaseTestCase
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

        $this->testAccount = 'a1b2c3d4';

        // 提示測試中的 caller 是哪一個
        $this->console->write('MG棋牌');
    }

    /**
     * 測試gameList
     *
     * @throws ApiCallerException
     */
    public function testGameList()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試GameList');

                // Act
            $response = ApiCaller::make('mg_poker')->methodAction('post', 'getGameList', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                ])->submit();
                
            $response = $response['response'];
            dump($response);
            $this->assertEquals('0', $response['code']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試login
     *
     * @throws ApiCallerException
     */
    public function testLogin()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試Login');

                // Act
            $response = ApiCaller::make('mg_poker')->methodAction('post', 'login', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => $this->testAccount,
                'gameId' => '103',
                'exutUrl' => 'http://localhost:8888/',
                'platform' => 'PC'

            ])->submit();
                
            $response = $response['response'];
            dump($response);
            $this->assertEquals('0', $response['code']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試QueryUserScore
     *
     * @throws ApiCallerException
     */
    public function testQueryUserScore()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得餘額');

                // Act
            $response = ApiCaller::make('mg_poker')->methodAction('post', 'queryUserScore', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => $this->testAccount,

            ])->submit();
                
            $response = $response['response'];
            dd($response);
            $this->assertEquals('0', $response['code']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試doTransfer DepositTask
     *
     * @throws ApiCallerException
     */
    public function testDoTransferDepositTask()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試轉入');

                // Act
            $response = ApiCaller::make('mg_poker')->methodAction('post', 'doTransferDepositTask', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => $this->testAccount,
                'money' => 100,
                'orderId' => rand(),
            ])->submit();
                
            $response = $response['response'];
            dump($response);
            $this->assertEquals('0', $response['code']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試doTransfer WithdrawTask
     *
     * @throws ApiCallerException
     */
    public function testDoTransferWithdrawTask()
    {
        // 捕捉   api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試轉出');

                // Act
            $response = ApiCaller::make('mg_poker')->methodAction('post', 'doTransferWithdrawTask', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => $this->testAccount,
                'money' => 6300,
                'orderId' => rand(),
            ])->submit();
                
            $response = $response['response'];
            dump($response);
            $this->assertEquals('0', $response['code']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試???
     *
     * @throws ApiCallerException
     */
    // public function test???()
    // {
    //     // 捕捉   api 訪問例外
    //     try {
    //         // 顯示測試案例描述
    //         $this->console->writeln('測試???');

    //             // Act
    //         $response = ApiCaller::make('mg_poker')->methodAction('post', '???', [
    //             // 路由參數這邊設定
    //         ])->params([
    //             // 一般參數這邊設定
    //             'account' => $this->testAccount,
    //             'money' => 43,
    //             'orderId' => '2',
    //         ])->submit();
                
    //         $response = $response['response'];
    //         dump($response);
    //         $this->assertEquals('0', $response['code']);
    //     } catch (ApiCallerException $exception) {
    //         $this->console->writeln($exception->response());
    //         throw $exception;
    //     }
    // }
    /**
     * 測試takeBetLogs
     *
     * @throws ApiCallerException
     */
    public function testTakeBetLogs()
    {
        // 捕捉   api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試takeBetLogs');

                // Act
            $response = ApiCaller::make('mg_poker')->methodAction('post', 'takeBetLogs', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'startTime' => Carbon::now()->subhours(24)->toDateTimeString(),
                'endTime' => Carbon::now()->toDateTimeString(),
                'size' => 10,
                'page' => 0,
            ])->submit();
                
            $response = $response['response'];
            dump($response);
            $this->assertEquals('0', $response['code']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試kickUser
     *
     * @throws ApiCallerException
     */
    public function testKickUser()
    {
        // 捕捉   api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試kickUser');

                // Act
            $response = ApiCaller::make('mg_poker')->methodAction('post', 'kickUser', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => $this->testAccount,
            ])->submit();
                
            $response = $response['response'];
            dump($response);
            $this->assertEquals('0', $response['code']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試takeTransferLogs
     *
     * @throws ApiCallerException
     */
    public function testTakeTransferLogs()
    {
        // 捕捉   api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試takeTransferLogs');

                // Act
            $response = ApiCaller::make('mg_poker')->methodAction('post', 'takeTransferLogs', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'type' => 0,
                'account' => $this->testAccount,
                'startTime' => Carbon::now()->subhours(500)->toDateTimeString(),
                'endTime' => Carbon::now()->toDateTimeString(),
                'size' => 50,
                'page' => 0,
            ])->submit();
                
            $response = $response['response'];
            dump($response);
            $this->assertEquals('0', $response['code']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
}