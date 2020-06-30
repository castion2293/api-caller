<?php

namespace SuperPlatform\ApiCaller\Tests\Callers;

use BaseTestCase;
use DateTime;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

/**
 * Class RealTimeGamingCallerTest
 */
class RealTimeGamingCallerTest extends BaseTestCase
{
    protected $testAccount = '';
    private $locale;
    private $language;
    private $amount;

    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        $this->testAccount = config('api_caller.real_time_gaming.config.test_account');

        $this->language = 'CN';
        $this->locale = 'zh-CN';
        $this->amount = 10;

        // 提示測試中的 caller 是哪一個
        $this->console->write('Real Time Gaming');
    }

    /**
     * 測試啟動服務
     *
     * @throws ApiCallerException
     */
    public function testStart()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試啟動服務');

            // Act
            $response = ApiCaller::make('real_time_gaming')->methodAction('get', 'start', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定

            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('agentId', $response);
            $this->assertArrayHasKey('casinos', $response);

        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得Token
     *
     * @throws ApiCallerException
     */
    public function testGetToken()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得Token');

            // Act
            $response = ApiCaller::make('real_time_gaming')->methodAction('get', 'start/token', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'username' => config('api_caller.real_time_gaming.config.api_username'),
                'password' => config('api_caller.real_time_gaming.config.api_password'),
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('token', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試遊戲字串
     *
     * @throws ApiCallerException
     */
    public function testGameStrings()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試遊戲字串');

            // Act
            $response = ApiCaller::make('real_time_gaming')->methodAction('get', 'gamestrings', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'locale' => $this->locale
            ])->submit();

            $response = $response['response'];
            $this->assertArrayHasKey('gameId', array_first($response));
            $this->assertArrayHasKey('locale', array_first($response));
            $this->assertArrayHasKey('name', array_first($response));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試创建一个新RTG玩家
     *
     * @throws ApiCallerException
     */
    public function testPlayerCreation()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試创建一个新RTG玩家');

            // Act
            $response = ApiCaller::make('real_time_gaming')->methodAction('get', 'start', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
            ])->submit();

            $agentId = $response['response']['agentId'];

            $response = ApiCaller::make('real_time_gaming')->methodAction('put', 'player', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'agentId' => $agentId,
                'username' => $this->testAccount,
                'firstName' => $this->testAccount,
                'lastName' => $this->testAccount,
                'email' => 'test@test.com',
                'countryId' => 'TW',
                'gender' => 'Male',
                'birthdate' => '1987-12-18',
                'currency' => 'TWD'
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('id', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試遊戲紀錄
     *
     * @throws ApiCallerException
     * @throws \Exception
     */
    public function testPlayerGameReport()
    {
        $fromDate = date(DATE_ISO8601, strtotime('2019-04-23 09:21:46'));
        $toDate = date(DATE_ISO8601, strtotime('2019-04-25 09:21:46'));

        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試遊戲紀錄');

            // Act
            $response = ApiCaller::make('real_time_gaming')->methodAction('post', 'report/playergame', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'params' => [
                    'agentId' => '13343b0b-036e-433d-b923-548fb4722d28',
                    'fromDate' => $fromDate,
                    'toDate' => $toDate,
                ],
            ])->submit();

            $this->assertEquals('200', array_get($response,'http_code'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試游戏信息
     *
     * @throws ApiCallerException
     */
    public function testGameDetail()
    {
        $fromDate = date(DATE_ISO8601, strtotime('2019-04-01 09:21:46'));
        $toDate = date(DATE_ISO8601, strtotime('2019-04-02 09:21:46'));

        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試游戏信息');

            // Act
            $playergame = ApiCaller::make('real_time_gaming')->methodAction('post', 'report/playergame', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'params' => [
                    'agentId' => '13343b0b-036e-433d-b923-548fb4722d28',
                    'fromDate' => $fromDate,
                    'toDate' => $toDate,
                ],
            ])->submit();

            $betId = array_get($playergame,'response.items.3.id');

            // Act
            $response = ApiCaller::make('real_time_gaming')->methodAction('get', 'report/gamedetail', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'betId' => $betId
            ])->submit();

            $this->assertEquals('200', array_get($response,'http_code'));

        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試提供游戏摘要统计信息
     *
     * @throws ApiCallerException
     */
    public function testCasinoPerformance()
    {
        $fromDate = date(DATE_ISO8601, strtotime('2019-04-01 09:21:46'));
        $toDate = date(DATE_ISO8601, strtotime('2019-04-02 09:21:46'));

        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試提供游戏摘要统计信息');

            $response = ApiCaller::make('real_time_gaming')->methodAction('post', 'report/casinoperformance', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'params' => [
                    'agentId' => '13343b0b-036e-433d-b923-548fb4722d28',
                    'fromDate' => $fromDate,
                    'toDate' => $toDate,
                    'aggregation' => 'DAY'
                ],
            ])->submit();

            $this->assertEquals('200', array_get($response,'http_code'));

        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試存款
     *
     * @throws ApiCallerException
     */
    public function testDeposit()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試存款');

            $response = ApiCaller::make('real_time_gaming')->methodAction('post', 'wallet/deposit/{amount}', [
                // 路由參數這邊設定
                'amount' => $this->amount
            ])->params([
                // 一般參數這邊設定
                'playerLogin' => $this->testAccount,
            ])->submit();

            $response = $response['response'];
            //印出交易紀錄id所以每次都會只增加一筆
            dump('transactionId：'.$response['transactionId']);
            $this->assertEquals('OK', array_get($response,'errorMessage'));
            $this->assertEquals('False', array_get($response,'errorCode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試出款
     *
     * @throws ApiCallerException
     */
    public function testWithdraw()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試出款');

            $response = ApiCaller::make('real_time_gaming')->methodAction('post', 'wallet/withdraw/{amount}', [
                // 路由參數這邊設定
                'amount' => $this->amount
            ])->params([
                // 一般參數這邊設定
                'playerLogin' => $this->testAccount,
            ])->submit();

            $response = $response['response'];
            //印出交易紀錄id所以每次都會只增加一筆
            dump('transactionId：'.$response['transactionId']);
            $this->assertEquals('OK', array_get($response,'errorMessage'));
            $this->assertEquals('False', array_get($response,'errorCode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試 存款与出款
     *
     * @throws ApiCallerException
     */
    public function testDepositsWithWithdraws()
    {
        $fromDate = date(DATE_ISO8601, strtotime('2019-04-08 09:21:46'));
        $toDate = date(DATE_ISO8601, strtotime('2019-04-09 09:21:46'));

        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試存款与出款');

            // Act
            $response = ApiCaller::make('real_time_gaming')->methodAction('post', 'report/depositswithdrawls', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'params' => [
                    'agentId' => '13343b0b-036e-433d-b923-548fb4722d28',
                    'fromDate' => $fromDate,
                    'toDate' => $toDate,
                ],
            ])->submit();

            $this->assertEquals('200', array_get($response,'http_code'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查看餘額
     *
     * @throws ApiCallerException
     */
    public function testGetBalance()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查看餘額');

            // Act
            $response = ApiCaller::make('real_time_gaming')->methodAction('post', 'wallet', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'playerLogin' => $this->testAccount
            ])->submit();
            dump('目前餘額：'.$response);

            $this->assertGreaterThan(0,1);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試预付余额
     *
     * @throws ApiCallerException
     */
    public function testMultiCash()
    {
        $fromDate = date(DATE_ISO8601, strtotime('2019-04-01 09:21:46'));
        $toDate = date(DATE_ISO8601, strtotime('2019-04-02 09:21:46'));
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試预付余额');

            $response = ApiCaller::make('real_time_gaming')->methodAction('post', 'report/multicash', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'params' => [
                    'agentId' => '13343b0b-036e-433d-b923-548fb4722d28',
                    'fromDate' => $fromDate,
                    'toDate' => $toDate,
                ],
            ])->submit();

            $this->assertEquals('200', array_get($response,'http_code'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試启动游戏
     *
     * @throws ApiCallerException
     */
    public function testGameLauncher()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試启动游戏');

            $response = ApiCaller::make('real_time_gaming')->methodAction('post', 'GameLauncher', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'player' => [
                    'playerId' => '',
                    'agentId' => '',
                    'playerLogin' => $this->testAccount
                ],
                'gameId' => '2162689',
                'locale' => $this->locale,
                'returnUrl' => 'www.google.com',
                'isDemo' => false
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('instantPlayUrl', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試進入遊戲大廳
     *
     * @throws ApiCallerException
     */
    public function testLauncherLobby()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試進入遊戲大廳');

            $response = ApiCaller::make('real_time_gaming')->methodAction('post', 'launcher/lobby' , [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'player' => [
                    'playerLogin' => $this->testAccount
                ],
                'locale' => $this->locale,
                'language' => $this->language,
                'isDemo' => false
            ])->submit();

            $response = $response['response'];
            dump(array_get($response,'instantPlayUrl'));
            $this->assertArrayHasKey('instantPlayUrl', $response);
            $this->assertArrayHasKey('token', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
}