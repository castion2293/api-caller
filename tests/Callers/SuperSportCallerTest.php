<?php

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

/**
 * Class SuperSportCallerTest
 */
class SuperSportCallerTest extends BaseTestCase
{
    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        // 此會員資訊可以透過 api 取回，或登入代理後台查看
        // 此會員帳號是固定提供給測試用
        $this->setPlayerAccount(config('api_caller.super_sport.config.test_account'));
        $this->setPlayerPassword(config('api_caller.super_sport.config.test_password'));

        // 提示測試中的 caller 是哪一個
        $this->console->write('Super Sport ');
    }

    /**
     * 測試呼叫不存在的 action
     */
    public function testAPIMethodNotExistAction()
    {
        // 顯示測試案例描述
        $this->console->writeln('測試呼叫不存在的 action');

        // Act
        $this->expectException('Exception');
        ApiCaller::make('super_sport')->methodAction('post', 'call_a_not_exist_action');

    }

    /**
     * 測試呼叫不存在的 action
     */
    public function testUpdateMember()
    {
        // 顯示測試案例描述
        $this->console->writeln('測試呼叫不存在的 action');

        // Act
        $this->expectException('Exception');
        ApiCaller::make('super_sport')->methodAction('post', 'account', [
            // 路由參數這邊設定
        ])->params([
            // 一般參數這邊設定
            /* required */
            'act' => 'cpSettings',
            'up_account' => 'ALJJJJT61',
            'up_passwd' => 'al1234',
            'account' => 'ALJJJJT61',
            'level' => 1,
            /* optional*/
            'copy_target' => 'ALJJJJT62',
        ])->submit();

    }

    /**
     * 測試登入，且登入成功
     *
     * @throws ApiCallerException
     */
    public function testLoginSuccess()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試登入，且登入成功');

            // Act
            $response = ApiCaller::make('super_sport')->methodAction('post', 'login', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => $this->getPlayerAccount(),
                'passwd' => $this->getPlayerPassword(),
                'responseFormat' => 'json'
            ])->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $response = $response['response'];
            $this->assertArrayHasKey('code', $response);
            $this->assertArrayHasKey('data', $response);
            $this->assertArrayHasKey('login_url', $response['data']);
            $this->assertArrayHasKey('uuid', $response);
            $this->assertArrayHasKey('msg', $response);
            $this->assertEquals('999', $response['code']);
            $this->assertEquals('登入成功', $response['msg']);
        } catch (ApiCallerException $exception) {
            $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試登入，使用錯誤的帳號密碼，取得回應為帳密錯誤
     *
     * @throws ApiCallerException
     */
    public function testLoginFail()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試登入，使用錯誤的帳號密碼，取得回應為帳密錯誤');

            // Act
            $this->expectException('SuperPlatform\ApiCaller\Exceptions\ApiCallerException');
            ApiCaller::make('super_sport')->methodAction('post', 'login', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => 'send_a_wrong_account_to_login',
                'passwd' => 'send_a_wrong_password_to_login',
                'responseFormat' => 'json'
            ])->submit();
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            $this->assertEquals(902, $exception->response()['code']);
            throw $exception;
        }
    }

    /**
     * 測試登出，且登出成功
     *
     * @throws ApiCallerException
     */
    public function testLogoutSuccess()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試登出，且登出成功');

            // Act
            $response = ApiCaller::make('super_sport')->methodAction('post', 'logout', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => $this->getPlayerAccount(),
            ])->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $response = $response['response'];
            $this->assertArrayHasKey('code', $response);
            $this->assertArrayHasKey('data', $response);
            $this->assertArrayHasKey('uuid', $response);
            $this->assertArrayHasKey('msg', $response);
            $this->assertEquals('999', $response['code']);
            $this->assertEquals('登出成功', $response['msg']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試登出，使用錯誤的帳號，取得回應為登出失敗
     *
     * @throws ApiCallerException
     */
    public function testLogoutFail()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試登出，使用錯誤的帳號，取得回應為登出失敗');

            // Act
            $this->expectException('SuperPlatform\ApiCaller\Exceptions\ApiCallerException');
            ApiCaller::make('super_sport')->methodAction('post', 'logout', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => 'send_a_wrong_account_to_logout',
            ])->submit();
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            $this->assertEquals(901, $exception->response()['code']);
            throw $exception;
        }
    }

    /**
     * 測試建立一個已存在會員，並新增失敗
     *
     * 備註：因為測試新增會員成功的話會不斷新增會員，所以僅測試可呼叫，新增對象是已存在的即可
     *
     * @throws ApiCallerException
     */
    public function testCreateAExistMember()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試建立一個已存在會員，並新增失敗');

            // Act
            $this->expectException('SuperPlatform\ApiCaller\Exceptions\ApiCallerException');
            ApiCaller::make('super_sport')->methodAction('post', 'account', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'act' => 'add',
                'up_account' => config('api_caller.super_sport.config.up_account'),
                'up_passwd' => config('api_caller.super_sport.config.up_password'),
                'account' => $this->getPlayerAccount(),
                'passwd' => $this->getPlayerPassword(),
                'nickname' => $this->getPlayerAccount(),
                'level' => '1',
            ])->submit();
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            $this->assertEquals(912, $exception->response()['code']);
            $this->assertEquals('帳號已存在', $exception->response()['msg']);
            throw $exception;
        }
    }

    /**
     * 測試建立會員，使用錯誤的上層帳號，結果新增失敗
     *
     * @throws ApiCallerException
     */
    public function testCreateAMemberUsingWrongAgent()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試建立會員，使用錯誤的上層帳號，結果新增失敗');

            // Act
            $this->expectException('SuperPlatform\ApiCaller\Exceptions\ApiCallerException');
            ApiCaller::make('super_sport')->methodAction('post', 'account', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'act' => 'add',
                'up_account' => 'send_a_wrong_agent_account_to_login',
                'up_passwd' => config('api_caller.super_sport.config.up_password'),
                'account' => $this->getPlayerAccount(),
                'passwd' => $this->getPlayerPassword(),
                'nickname' => $this->getPlayerAccount(),
                'level' => '1',
            ])->submit();
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            $this->assertEquals(915, $exception->response()['code']);
            $this->assertEquals('上層不存在', $exception->response()['msg']);
            throw $exception;
        }
    }

    /**
     * 測試取得餘額，取得成功
     *
     * @throws ApiCallerException
     */
    public function testGetBalanceSuccess()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得餘額，取得成功');

            // Act
            $response = ApiCaller::make('super_sport')->methodAction('post', 'points', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'act' => 'search',
                'up_account' => config('api_caller.super_sport.config.up_account'),
                'up_passwd' => config('api_caller.super_sport.config.up_password'),
                'account' => $this->getPlayerAccount(),
            ])->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $response = $response['response'];
            $this->assertArrayHasKey('code', $response);
            $this->assertArrayHasKey('point', $response);
            $this->assertArrayHasKey('data', $response);
            $this->assertEquals('999', $response['code']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取回注單且成功
     *
     * 備註：本測試對應 api doc 報表 API - 6.1 報表明細(時間區間)
     *
     * @throws ApiCallerException
     */
    public function testGetReport()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取回注單且成功');

            // Act
            $response = ApiCaller::make('super_sport')->methodAction('post', 'report', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'act' => 'detail',
                'account' => $this->getPlayerAccount(),
                'level' => '1',
                's_date' => now()->subDays(14)->toDateString(),
                'e_date' => now()->toDateString()
            ])->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $response = $response['response'];
            $this->assertArrayHasKey('code', $response);
            $this->assertArrayHasKey('data', $response);
            $this->assertEquals('999', $response['code']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取回注單且成功
     *
     * 備註：本測試對應 api doc 報表 API - 6.5 報表明細(依結帳時間查詢)
     *
     * @throws ApiCallerException
     */
    public function testGetDetailForPayoutTime()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取回注單且成功');

            $from = now()->addDays(1);
            $to = now()->subDays(1);

            // Act
            $response = ApiCaller::make('super_sport')->methodAction('post', 'GetDetailForPayoutTime', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => $this->getPlayerAccount(),
                'level' => '1',
                's_date' => $from->toDateString(),
                'e_date' => $to->toDateString(),
                'start_time' => $from->toTimeString(),
                'end_time' => $to->toTimeString(),
            ])->submit();

            $response = $response['response'];
            $this->assertEquals(999, array_get($response, 'code'));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試新增額度
     *
     * @throws ApiCallerException
     */
    public function testAddPoints()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試新增額度');

            $points = 1.11;

            // Act
            $response = ApiCaller::make('super_sport')->methodAction('post', 'points', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'act' => 'add',
                'up_account' => config('api_caller.super_sport.config.up_account'),
                'up_passwd' => config('api_caller.super_sport.config.up_password'),
                'account' => $this->getPlayerAccount(),
                'point' => $points,
                'track_id' => str_random(32)
            ])->submit();

            $data = array_get($response, 'response.data');
            $this->assertArrayHasKey('point', $data);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查詢額度操作結果
     *
     * @throws ApiCallerException
     */
    public function testCheckPoints()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢額度操作結果');

            $points = 1.11;
            $trackId = str_random(32);

            // 先進行轉帳動做
            ApiCaller::make('super_sport')->methodAction('post', 'points', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'act' => 'add',
                'up_account' => config('api_caller.super_sport.config.up_account'),
                'up_passwd' => config('api_caller.super_sport.config.up_password'),
                'account' => $this->getPlayerAccount(),
                'point' => $points,
                'track_id' => $trackId
            ])->submit();

            sleep(1);

            // 再進行查帳動做
            $response = ApiCaller::make('super_sport')->methodAction('post', 'points', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'act' => 'checking',
                'up_account' => config('api_caller.super_sport.config.up_account'),
                'up_passwd' => config('api_caller.super_sport.config.up_password'),
                'account' => $this->getPlayerAccount(),
                'track_id' => $trackId
            ])->submit();

            $data = array_get($response, 'response.data');
            $this->assertArrayHasKey('result', $data);
            $this->assertEquals(1, array_get($data, 'result'));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }
}