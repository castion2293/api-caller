<?php

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

/**
 * Class CockFightCallerTest
 */
class CockFightCallerTest extends BaseTestCase
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

        $this->testAccount = config('api_caller.cock_fight.config.test_member_account');

        // 提示測試中的 caller 是哪一個
        $this->console->write('cock fight');
    }

    /**
     * 測試強制登出
     *
     * @throws ApiCallerException
     */
    public function testKickoutPlayer()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試強制登出');

            // Act
            $response = ApiCaller::make('cock_fight')->methodAction('post', 'kickout_player', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'login_id' => $this->testAccount,
            ])->submit();

            $response = $response['response'];

            $this->assertEquals(0, array_get($response, 'status_code'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試餘額
     *
     * @throws ApiCallerException
     */
    public function testGetBalance()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試餘額');

            // Act
            $response = ApiCaller::make('cock_fight')->methodAction('post', 'get_balance', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'login_id' => $this->testAccount,
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('balance', $response);
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

            $refNo = str_random(50);
            $amount = 1000.01;

            // Act
            $response = ApiCaller::make('cock_fight')->methodAction('post', 'deposit', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'login_id' => $this->testAccount,
                'name' => $this->testAccount,
                'amount' => $amount,
                'ref_no' => $refNo,
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('amount', $response);
            $this->assertEquals($amount, array_get($response, 'amount'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試提款
     *
     * @throws ApiCallerException
     */
    public function testWithdraw()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試提款');

            $refNo = str_random(50);
            $amount = 1.01;

            // Act
            $response = ApiCaller::make('cock_fight')->methodAction('post', 'withdraw', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'login_id' => $this->testAccount,
                'amount' => $amount,
                'ref_no' => $refNo,
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('amount', $response);
            $this->assertEquals($amount, array_get($response, 'amount'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查詢轉帳
     *
     * @throws ApiCallerException
     */
    public function testCheckTransfer()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢轉帳');

            $refNo = str_random(50);
            $amount = 1.01;

            // 先進行轉帳動做
            $response = ApiCaller::make('cock_fight')->methodAction('post', 'deposit', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'login_id' => $this->testAccount,
                'name' => $this->testAccount,
                'amount' => $amount,
                'ref_no' => $refNo,
            ])->submit();

            sleep(1);

            // 再進行查帳動做
            $response = ApiCaller::make('cock_fight')->methodAction('post', 'check_transfer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'ref_no' => $refNo
            ])->submit();

            $response = $response['response'];

            $this->assertEquals(0, array_get($response, 'status_code'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試獲取轉帳
     *
     * @throws ApiCallerException
     */
    public function testGetTransfers()
    {
        // 捕捉 api 訪問例外
        try {
            // Act
            $response = ApiCaller::make('cock_fight')->methodAction('post', 'get_transfer_2', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'start_datetime' => '2019-11-11 00:00:00',
                'end_datetime' => '2019-11-11 23:59:59',
            ])->submit();

            $response = $response['response'];
            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試獲取交易 還未結算
     *
     * @throws ApiCallerException
     */
    public function testGetCockFightOpenTicket()
    {
        // 捕捉 api 訪問例外
        try {
            // Act
            $response = ApiCaller::make('cock_fight')->methodAction('post', 'get_cockfight_open_ticket_2', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
            ])->submit();

            $response = $response['response'];
            print_r($response);
            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試獲取交易 結算注單以結算時間
     *
     * @throws ApiCallerException
     */
    public function test_cockfight_processed_ticket_2()
    {
        // 捕捉 api 訪問例外
        try {
            // Act
            $response = ApiCaller::make('cock_fight')->methodAction('post', 'get_cockfight_processed_ticket_2', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'start_datetime' => '2019-11-13 09:30:00',
                'end_datetime' => '2019-11-13 10:00:00',
            ])->submit();

            $response = $response['response'];
            print_r($response);
            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試獲取交易 結算注單以下注時間
     *
     * @throws ApiCallerException
     */
    public function testGetCockfightPrecessedTicketByBetTime()
    {
        // 捕捉 api 訪問例外
        try {
            // Act
            $response = ApiCaller::make('cock_fight')->methodAction('post', 'get_cockfight_processed_ticket_by_bet_time', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'start_datetime' => '2019-11-13 09:00:00',
                'end_datetime' => '2019-11-13 09:30:00',
            ])->submit();

            $response = $response['response'];
            print_r($response);
            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試獲取總兑
     *
     * @throws ApiCallerException
     */
    public function testGetCockfightPlayerSummary()
    {
        // 捕捉 api 訪問例外
        try {
            // Act
            $response = ApiCaller::make('cock_fight')->methodAction('post', 'get_cockfight_player_summary', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'match_date' => '2019-11-13'
            ])->submit();

            $response = $response['response'];
            print_r($response);
            $this->assertArrayHasKey('data', $response);
            // TODO:: 數值
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試進入遊戲
     *
     * @throws ApiCallerException
     */
    public function testGetSessionId()
    {
        // 捕捉 api 訪問例外
        try {
            // Act
            $response = ApiCaller::make('cock_fight')->methodAction('post', 'get_session_id', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'login_id' => $this->testAccount,
                'name' => $this->testAccount,
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('session_id', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試設定限紅
     *
     * @throws ApiCallerException
     */
    public function testSetBetLimit()
    {
        // 捕捉 api 訪問例外
        try {
            // Act
            $response = ApiCaller::make('cock_fight')->methodAction('post', 'set_bet_limit', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'login_id' => $this->testAccount,
                'meron_wala_min_bet' => config('api_caller.cock_fight.config.meron_wala_min_bet'),
                'meron_wala_max_bet' => config('api_caller.cock_fight.config.meron_wala_max_bet'),
                'meron_wala_max_match_bet' => config('api_caller.cock_fight.config.meron_wala_max_match_bet'),
                'bdd_min_bet' => config('api_caller.cock_fight.config.bdd_min_bet'),
                'bdd_max_bet' => config('api_caller.cock_fight.config.bdd_max_bet'),
                'bdd_max_match_bet' => config('api_caller.cock_fight.config.bdd_max_match_bet'),
                'ftd_min_bet' => config('api_caller.cock_fight.config.ftd_min_bet'),
                'ftd_max_bet' => config('api_caller.cock_fight.config.ftd_max_bet'),
                'ftd_max_match_bet' => config('api_caller.cock_fight.config.ftd_max_match_bet'),
            ])->submit();

            $response = $response['response'];
            $this->assertEquals(0, array_get($response, 'status_code'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
}