<?php

namespace SuperPlatform\ApiCaller\Tests\Callers;

use BaseTestCase;
use Carbon\Carbon;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

class QTechCallerTest extends BaseTestCase
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
     * 國家
     *
     * @var string
     */
    protected $country = '';

    /**
     * 語言
     *
     * @var string
     */
    protected $language = '';

    /**
     * 模式
     *
     * @var string
     */
    protected $mode = '';

    /**
     * 限紅
     *
     * @var string
     */
    protected $betLimitCode = '';

    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        $this->testAccount = config('api_caller.q_tech.config.test_member_account');
        $this->currency = config('api_caller.q_tech.config.currency');
        $this->country = config('api_caller.q_tech.config.country');
        $this->language = config('api_caller.q_tech.config.language');
        $this->mode = config('api_caller.q_tech.config.mode');
        $this->betLimitCode = config('api_caller.q_tech.config.bet_limit_code');

        // 提示測試中的 caller 是哪一個
        $this->console->write('Q Tech');
    }

    /**
     * 測試轉帳錢包
     *
     * @throws ApiCallerException
     */
    public function testFundTransfers()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試轉帳錢包');

            // Act
            $response = ApiCaller::make('q_tech')->methodAction('post', 'fund-transfers', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'type' => 'CREDIT',
                'referenceId' => str_random(50),
                'playerId' => $this->testAccount,
                'amount' => 0.01,
                'currency' => $this->currency
            ])->submit();

            $response = $response['response'];

            $this->assertEquals($this->testAccount, array_get($response, 'playerId'));
            $this->assertArrayHasKey('id', $response);
            $this->assertEquals('PENDING', array_get($response, 'status'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試完成交易
     *
     * @throws ApiCallerException
     */
    public function testCompleteTransfer()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試完成交易');

            // Act
            $responseFirst = ApiCaller::make('q_tech')->methodAction('post', 'fund-transfers', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'type' => 'CREDIT',
                'referenceId' => str_random(50),
                'playerId' => $this->testAccount,
                'amount' => 100.00,
                'currency' => $this->currency
            ])->submit();

            $response = $responseFirst['response'];

            $transferId = array_get($response, 'id');
            $responseSecond = ApiCaller::make('q_tech')->methodAction('put', 'fund-transfers/{transferId}/status', [
                // 路由參數這邊設定
                'transferId' => $transferId
            ])->params([
                // 一般參數這邊設定
                'status' => 'COMPLETED'
            ])->submit();
            $response = $responseSecond['response'];

            $this->assertEquals('COMPLETED', array_get($response, 'status'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試檢索玩家餘額
     *
     * @throws ApiCallerException
     */
    public function testRetrievePlayerBalance()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試檢索玩家餘額');

            // Act
            $response = ApiCaller::make('q_tech')->methodAction('get', 'wallet/ext/{playerId}', [
                // 路由參數這邊設定
                'playerId' => $this->testAccount
            ])->params([
                // 一般參數這邊設定
            ])->submit();

            $response = $response['response'];
            $this->assertArrayHasKey('amount', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試檢索轉帳細節
     *
     * @throws ApiCallerException
     */
    public function testRetrieveTransferDetails()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試檢索轉帳細節');

            // Act
            $responseFirst = ApiCaller::make('q_tech')->methodAction('post', 'fund-transfers', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'type' => 'CREDIT',
                'referenceId' => str_random(50),
                'playerId' => $this->testAccount,
                'amount' => 0.01,
                'currency' => $this->currency
            ])->submit();

            $response = $responseFirst['response'];
            $transferId = array_get($response, 'id');

            $responseSecond = ApiCaller::make('q_tech')->methodAction('get', 'fund-transfers/{transferId}', [
                // 路由參數這邊設定
                'transferId' => $transferId
            ])->params([
                // 一般參數這邊設定
            ])->submit();
            $response = $responseSecond['response'];

            $this->assertEquals('PENDING', array_get($response, 'status'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試檢索遊戲列表
     *
     * @throws ApiCallerException
     */
    public function testGameList()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試檢索遊戲列表');

            // Act
            $response = ApiCaller::make('q_tech')->methodAction('get', 'games', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
            ])->submit();

            $response = $response['response'];
            $this->assertArrayHasKey('items', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試檢索熱門遊戲
     *
     * @throws ApiCallerException
     */
    public function testMostPopularGames()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試檢索熱門遊戲');

            // Act
            $response = ApiCaller::make('q_tech')->methodAction('get', 'games/most-popular', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'currencies' => $this->currency,
                'size' => 20,
                'page' => 1
            ])->submit();

            $response = $response['response'];
            $this->assertArrayHasKey('items', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試啟動遊戲
     *
     * @throws ApiCallerException
     */
    public function testGameLauncher()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試啟動遊戲');

            // Act
            $response = ApiCaller::make('q_tech')->methodAction('post', 'games/{gameId}/launch-url', [
                // 路由參數這邊設定
                'gameId' => 'BNG-12animals'
            ])->params([
                // 一般參數這邊設定
                'playerId' => $this->testAccount,
                'currency' => $this->currency,
                'country' => $this->country,
                'lang' => $this->language,
                'mode' => $this->mode,
                'device' => 'desktop',
                'betLimitCode' => $this->betLimitCode
            ])->submit();

            $response = $response['response'];
            $this->assertArrayHasKey('url', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試遊戲大廳
     *
     * @throws ApiCallerException
     */
    public function testGameLobby()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試遊戲大廳');

            // Act
            $response = ApiCaller::make('q_tech')->methodAction('post', 'games/lobby-url', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'playerId' => $this->testAccount,
                'currency' => $this->currency,
                'country' => $this->country,
                'lang' => $this->language,
                'mode' => $this->mode,
                'device' => 'desktop',
                'betLimitCode' => $this->betLimitCode
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertArrayHasKey('url', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試玩家遊戲歷史紀錄
     *
     * @throws ApiCallerException
     */
    public function testPlayerGameHistory()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試玩家遊戲歷史紀錄');

            // Act
            $response = ApiCaller::make('q_tech')->methodAction('post', 'players/{playerId}/service-url', [
                // 路由參數這邊設定
                'playerId' => $this->testAccount
            ])->params([
                // 一般參數這邊設定
                'currency' => $this->currency,
                'country' => $this->country,
                'lang' => $this->language,
            ])->submit();

            $response = $response['response'];
            $this->assertArrayHasKey('url', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試遊戲局注單
     *
     * @throws ApiCallerException
     */
    public function testGameRounds()
    {
        $from  = Carbon::parse('2019-08-28 00:00:00')->format('Y-m-d\TH:i:s');
        $to = Carbon::parse('2019-08-28 23:59:59')->format('Y-m-d\TH:i:s');

        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試遊戲局注單');

            // Act
            $response = ApiCaller::make('q_tech')->methodAction('get', 'game-rounds', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'from' => $from,
                'to'  => $to,
                'size' => 1000,
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertArrayHasKey('totalCount', $response);
            $this->assertArrayHasKey('items', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試遊戲局注單 找下一頁
     *
     * @throws ApiCallerException
     */
    public function testGameRoundsForNextPage()
    {
        $from  = Carbon::parse('2019-08-28 00:00:00')->format('Y-m-d\TH:i:s');
        $to = Carbon::parse('2019-08-28 23:59:59')->format('Y-m-d\TH:i:s');

        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試遊戲局注單');

            // Act
            $firstResponse = ApiCaller::make('q_tech')->methodAction('get', 'game-rounds', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'from' => $from,
                'to'  => $to,
                'size' => 1,
            ])->submit();

            $links = array_get($firstResponse, 'response.links');
            $href = array_get(array_first($links), 'href');
            $query = array_get(explode('?', $href), 1);
            parse_str($query, $queryArray);

            $secondResponse = ApiCaller::make('q_tech')->methodAction('get', 'game-rounds', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'cursor' => array_get($queryArray, 'cursor'),
                'from' => $from,
                'to'  => $to,
                'size' => 1,
            ])->submit();

            $response = $secondResponse['response'];
            dump($response);
            $this->assertArrayHasKey('totalCount', $response);
            $this->assertArrayHasKey('items', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試遊戲局細節
     *
     * @throws ApiCallerException
     */
    public function testGameRoundDetails()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試遊戲局細節');

            // Act
            $response = ApiCaller::make('q_tech')->methodAction('get', 'game-rounds/{roundId}', [
                // 路由參數這邊設定
                'roundId' => '5d4d0f96feaff60001eb8bf6'
            ])->params([
                // 一般參數這邊設定
            ])->submit();

            $response = $response['response'];
            $this->assertArrayHasKey('gameId', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試遊戲交易
     *
     * @throws ApiCallerException
     */
    public function testGameTransactions()
    {
        $from  = Carbon::parse('2019-08-21 00:00:00')->format('Y-m-d\TH:i:s');
        $to = Carbon::parse('2019-08-21 23:59:59')->format('Y-m-d\TH:i:s');

        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試遊戲交易');

            // Act
            $response = ApiCaller::make('q_tech')->methodAction('get', 'game-transactions', [
                // 路由參數這邊設定
                'roundId' => '5d4d0f96feaff60001eb8bf6'
            ])->params([
                // 一般參數這邊設定
                'playerId' => $this->testAccount,
                'from' => $from,
                'to'  => $to,
                'size' => 1000,
                'page' => 0
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('totalCount', $response);
            $this->assertArrayHasKey('items', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試每個玩家的NGR
     *
     * @throws ApiCallerException
     */
    public function testNgrPerPlayer()
    {
        $from  = Carbon::parse('2019-08-09 00:00:00')->format('Y-m-d\TH:i:s');
        $to = Carbon::parse('2019-08-09 23:59:59')->format('Y-m-d\TH:i:s');

        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試每個玩家的NGR');

            // Act
            $response = ApiCaller::make('q_tech')->methodAction('get', 'ngr-player', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'from' => $from,
                'to'  => $to,
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('items', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
}