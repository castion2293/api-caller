<?php

namespace SuperPlatform\ApiCaller\Tests\Callers;

use BaseTestCase;
use Carbon\Carbon;
use Exception;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

class SoPowerCallerTest extends BaseTestCase
{
    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * 測試註冊，且註冊成功
     *
     * @throws Exception
     */
    public function testRegisterSuccess()
    {
        $this->markTestSkipped('測試階段，避免每次都去建立新帳號，在此只測試建立重複帳號即可，此測試先跳過');
        // 顯示測試案例描述
        $this->console->writeln('測試註冊，且註冊成功');

        // === ARRANGE ===
        $method = 'POST';
        $action = 'CREATE_USER';
        $username = "TEST-" . str_random(8);

        // === ACTION ===
        $response = ApiCaller::make('so_power')
            ->methodAction($method, $action, [
                // 路由參數這邊設定
                // 如果沒有就保持空陣列
            ])->params([
                // 參數這邊設定
                'username' => $username,
            ])->submit();

        // === ASSERT ===
        $this->assertEquals('OK', array_get($response, 'response.message'));
        // 玩家帳號將被附加「代理商前綴」，並且全部轉為大寫
        $this->assertEquals(strtoupper("UPG{$username}"), array_get($response, 'response.data.username'));
    }

    /**
     * 測試註冊，且註冊失敗，取得帳號重複 (DUPLICATE_USERNAME) 的錯誤訊息
     *
     * @throws Exception
     */
    public function testRegisterDuplicate()
    {
        // 顯示測試案例描述
        $this->console->writeln('測試註冊，帳號重複');

        // === ARRANGE ===
        $method = 'POST';
        $action = 'CREATE_USER';
        $username = "TEST-USERNAME";

        // === ACTION ===
        try {
            $response = ApiCaller::make('so_power')
                ->methodAction($method, $action, [
                    // 路由參數這邊設定
                    // 如果沒有就保持空陣列
                ])->params([
                    // 參數這邊設定
                    'username' => $username,
                ])->submit();
        } catch (ApiCallerException $exception) {
            $this->assertEquals(strtoupper("DUPLICATE_USERNAME"), array_get($exception->response(), 'message'));
        }
    }

    /**
     * 取得進入遊戲的 Token
     *
     * @throws Exception
     */
    public function testTokenSuccess()
    {
        // === ARRANGE ===
        $method = 'POST';
        $action = 'REQUEST_TOKEN';
        $username = "TEST-USERNAME";

        // === ACTION ===
        $response = ApiCaller::make('so_power')
            ->methodAction($method, $action, [
                // 路由參數這邊設定
                // 如果沒有就保持空陣列
            ])->params([
                // 參數這邊設定
                'username' => $username,
            ])->submit();

        // === ASSERT ===
        $this->assertEquals('OK', array_get($response, 'response.message'));
        $this->assertEquals(strtoupper("UPG{$username}"), array_get($response, 'response.data.username'));
        $this->assertArrayHasKey('token', array_get($response, 'response.data'));
    }

    /**
     * 取得玩家目前的餘額
     *
     * @throws Exception
     */
    public function testGetBalanceSuccess()
    {
        // === ARRANGE ===
        $method = 'POST';
        $action = 'GET_CREDIT';
        $username = "TEST-USERNAME";

        // === ACTION ===
        $response = ApiCaller::make('so_power')
            ->methodAction($method, $action, [
                // 路由參數這邊設定
                // 如果沒有就保持空陣列
            ])->params([
                // 參數這邊設定
                'username' => $username,
            ])->submit();

        // === ASSERT ===
        $this->assertEquals('OK', array_get($response, 'response.message'));
        $this->assertEquals(strtoupper("UPG{$username}"), array_get($response, 'response.data.username'));
        $this->assertArrayHasKey('credit', array_get($response, 'response.data'));
    }

    /**
     * 取得轉入或轉出玩家餘額(加點數)
     *
     * @throws Exception
     */
    public function testTransferInSuccess()
    {
        // === ARRANGE ===
        $method = 'POST';
        $action = 'TRANSFER_CREDIT';
        $username = "TEST-USERNAME";
        $amount = 1;

        // === ACTION ===
        // 取得當前餘額
        $response = ApiCaller::make('so_power')
            ->methodAction('POST', 'GET_CREDIT', [
                // 路由參數這邊設定
                // 如果沒有就保持空陣列
            ])->params([
                // 參數這邊設定
                'username' => $username,
            ])->submit();
        $currentBalance = array_get($response, "response.data.credit");

        // 轉入
        $response = ApiCaller::make('so_power')
            ->methodAction($method, $action, [
                // 路由參數這邊設定
                // 如果沒有就保持空陣列
            ])->params([
                // 參數這邊設定
                'username' => $username,
                'amount' => $amount,
            ])->submit();

        // === ASSERT ===
        $this->assertEquals('OK', array_get($response, 'response.message'));
        $this->assertEquals(strtoupper("UPG{$username}"), array_get($response, 'response.data.username'));
        $this->assertEquals($currentBalance + $amount, array_get($response, 'response.data.credit'));
    }

    /**
     * 取得轉入或轉出玩家餘額(扣點數)
     *
     * @throws Exception
     */
    public function testTransferOutSuccess()
    {
        // === ARRANGE ===
        $method = 'POST';
        $action = 'TRANSFER_CREDIT';
        $username = "TEST-USERNAME";
        $amount = -1;

        // === ACTION ===
        // 取得當前餘額
        $response = ApiCaller::make('so_power')
            ->methodAction('POST', 'GET_CREDIT', [
                // 路由參數這邊設定
                // 如果沒有就保持空陣列
            ])->params([
                // 參數這邊設定
                'username' => $username,
            ])->submit();
        $currentBalance = array_get($response, "response.data.credit");

        // 轉入
        $response = ApiCaller::make('so_power')
            ->methodAction($method, $action, [
                // 路由參數這邊設定
                // 如果沒有就保持空陣列
            ])->params([
                // 參數這邊設定
                'username' => $username,
                'amount' => $amount,
            ])->submit();

        // === ASSERT ===
        $this->assertEquals('OK', array_get($response, 'response.message'));
        $this->assertEquals(strtoupper("UPG{$username}"), array_get($response, 'response.data.username'));
        $this->assertEquals($currentBalance + $amount, array_get($response, 'response.data.credit'));
    }

    /**
     * 取得代理商的帳務報表 (無資料)
     */
    public function testReportNoData()
    {
        // === ARRANGE ===
        $method = 'POST';
        $action = 'GET_REPORT';
        $username = "TEST-USERNAME";
        $endTime = Carbon::now()->addDay()->timestamp;
        $startTime = Carbon::now()->addDay()->subHours(1)->timestamp;
        $resultOk = 'all';

        // === ACTION ===
        try {
            $response = ApiCaller::make('so_power')
                ->methodAction($method, $action, [
                    // 路由參數這邊設定
                    // 如果沒有就保持空陣列
                ])->params([
                    // 參數這邊設定
                    'username' => $username,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'result_ok' => $resultOk,
                ])->submit();
        } catch (ApiCallerException $exception) {
            $this->assertEquals('NO_DATA', array_get($exception->response(), 'message'));
        }
    }

    /**
     * 取得代理商的帳務報表 (有資料)
     */
    public function testReportWithData()
    {
        $this->markTestSkipped('無法保證此測試每次該時間段（寫死）都會有注單，測試先跳過');
        // === ARRANGE ===
        $method = 'POST';
        $action = 'GET_REPORT';
        $username = "UPGTT8D1791F9";
        $startTime = Carbon::parse("2019-03-22 10:00:00");
        $endTime = Carbon::parse("2019-03-22 11:00:00");
        $resultOk = 'all';

        // === ACTION ===
        sleep(10); // 避免呼叫太過頻繁而回應 REQUEST_FREQUENCY，在此停留 10 秒
        try {
            $response = ApiCaller::make('so_power')
                ->methodAction($method, $action, [
                    // 路由參數這邊設定
                    // 如果沒有就保持空陣列
                ])->params([
                    // 參數這邊設定
                    'username' => $username,
                    'start_time' => $startTime->timestamp,
                    'end_time' => $endTime->timestamp,
                    'result_ok' => $resultOk,
                ])->submit();
        } catch (ApiCallerException $exception) {
        }
        $this->assertEquals('NO_DATA', array_get($exception->response(), 'message'));
    }
}