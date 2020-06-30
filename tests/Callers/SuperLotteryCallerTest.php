<?php

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

/**
 * Class SuperLotteryCallerTest
 */
class SuperLotteryCallerTest extends BaseTestCase
{
    // 上層代理帳號
    protected $upAcc;

    // 上層代理密碼
    protected $upPwd;

    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        // 此會員資訊可以透過 api 取回，或登入代理後台查看
        // 此會員帳號是固定提供給測試用
        $this->setPlayerAccount(config('api_caller.super_lottery.config.test_account'));
        $this->setPlayerPassword(config('api_caller.super_lottery.config.test_password'));
        $this->upAcc = config('api_caller.super_lottery.config.up_account');
        $this->upPwd = config('api_caller.super_lottery.config.up_password');

        // 提示測試中的 caller 是哪一個
        $this->console->write('Lottery 101');
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
        ApiCaller::make('super_lottery')->methodAction('post', 'call_a_not_exist_action');
    }

    /**
     * 測試建立會員帳號
     *
     * @throws ApiCallerException
     */
    public function testCreateAccount()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試建立會員帳號');

            // Act
            $response = ApiCaller::make('super_lottery')->methodAction('post', 'account', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'act' => 'create',
                'up_acc' => $this->upAcc,
                'up_pwd' => $this->upPwd,
                'account' => $this->getPlayerAccount(),
                'passwd' => $this->getPlayerPassword(),
                'nickname' => $this->getPlayerAccount(),
            ])->submit();

        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 登入 取得登入網址及參數
     *
     * @throws ApiCallerException
     */
    public function testLogin()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試建立會員帳號');

            // Act
            $response = ApiCaller::make('super_lottery')->methodAction('post', 'login', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => $this->getPlayerAccount(),
                'passwd' => $this->getPlayerPassword(),
            ])->submit();

            $response = $response['response'];
            $this->assertArrayHasKey('code', $response);
            $this->assertEquals('999', $response['code']);
            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 取得帳號狀態
     *
     * @throws ApiCallerException
     */
    public function testReadAccount()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得帳號狀態');

            // Act
            $response = ApiCaller::make('super_lottery')->methodAction('post', 'account', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'act' => 'read',
                'up_acc' => $this->upAcc,
                'up_pwd' => $this->upPwd,
                'account' => $this->getPlayerAccount(),
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertArrayHasKey('code', $response);
            $this->assertEquals('999', $response['code']);
            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 查詢查詢帳號目前點數
     *
     * @throws ApiCallerException
     */
    public function testReadPoints()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢查詢帳號目前點數');

            // Act
            $response = ApiCaller::make('super_lottery')->methodAction('post', 'points', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'act' => 'read',
                'up_acc' => $this->upAcc,
                'up_pwd' => $this->upPwd,
                'account' => $this->getPlayerAccount(),
            ])->submit();

            $response = $response['response'];
            $this->assertArrayHasKey('code', $response);
            $this->assertEquals('999', $response['code']);
            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 新增帳號點數
     *
     * @throws ApiCallerException
     */
    public function testAddPoints()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試新增帳號點數');

            // Act
            $response = ApiCaller::make('super_lottery')->methodAction('post', 'points', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'act' => 'add',
                'up_acc' => $this->upAcc,
                'up_pwd' => $this->upPwd,
                'account' => $this->getPlayerAccount(),
                'Point' => 100.00,
//                'track_id' => $this->getPlayerAccount(),
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('code', $response);
            $this->assertEquals('999', $response['code']);
            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     *
     * 減少帳號點數
     *
     * @throws ApiCallerException
     */
    public function testSubPoints()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試減少帳號點數');

            // Act
            $response = ApiCaller::make('super_lottery')->methodAction('post', 'points', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'act' => 'sub',
                'up_acc' => $this->upAcc,
                'up_pwd' => $this->upPwd,
                'account' => $this->getPlayerAccount(),
                'Point' => 50.00,
//                'track_id' => $this->getPlayerAccount(),
            ])->submit();

            $response = $response['response'];
            $this->assertArrayHasKey('code', $response);
            $this->assertEquals('999', $response['code']);
            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     *
     * 取得報表總帳資訊(代理)
     *
     * @throws ApiCallerException
     */
    public function testReportForAgent()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得報表總帳資訊');

            // Act
            $response = ApiCaller::make('super_lottery')->methodAction('post', 'report', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => $this->upAcc,
                'passwd' => $this->upPwd,
                'start_date' => '2019-06-27',
                'end_date' => '2019-06-27',
            ])->submit();

            $response = $response['response'];
            $this->assertArrayHasKey('code', $response);
            $this->assertEquals('999', $response['code']);
            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     *
     * 取得報表總帳資訊(會員)
     *
     * @throws ApiCallerException
     */
    public function testReportForMember()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得報表總帳資訊');

            // Act
            $response = ApiCaller::make('super_lottery')->methodAction('post', 'report', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => $this->getPlayerAccount(),
                'passwd' => $this->getPlayerPassword(),
                'start_date' => '2019-06-27',
                'end_date' => '2019-06-27',
            ])->submit();

            $response = $response['response'];
            $this->assertArrayHasKey('code', $response);
            $this->assertEquals('999', $response['code']);
            $this->assertArrayHasKey('data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     *
     * 取得報表總帳細單
     *
     * @throws ApiCallerException
     */
    public function testReportItem()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得報表總帳細單');

            // Act
            $response = ApiCaller::make('super_lottery')->methodAction('post', 'reportItem', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => $this->getPlayerAccount(),
                'passwd' => $this->getPlayerPassword(),
                'date' => '2019-05-10',
                'gameID' => 11,
                'flags' => 1
            ])->submit();

            $response = $response['response'];
            $this->assertArrayHasKey('code', $response);
            $this->assertEquals('999', $response['code']);
            $this->assertArrayHasKey('Data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     *
     * 查詢遊戲設定(水倍差)
     *
     * @throws ApiCallerException
     */
    public function testGetGameSetting()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得報表總帳細單');

            // Act
            $response = ApiCaller::make('super_lottery')->methodAction('post', 'getGameSetting', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => $this->getPlayerAccount(),
                'passwd' => $this->getPlayerPassword(),
                'gameID' => 11,
            ])->submit();

            $response = $response['response'];
            $this->assertArrayHasKey('code', $response);
            $this->assertEquals('999', $response['code']);
            $this->assertArrayHasKey('Data', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     *
     * 查詢額度操作結果
     *
     * @throws ApiCallerException
     */
    public function testCheckPointOrder()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('查詢額度操作結果');

            $trackId = str_random(32);
            $points = 2.22;

            // 先進行轉帳動做
            ApiCaller::make('super_lottery')->methodAction('post', 'points', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'act' => 'add',
                'up_acc' => $this->upAcc,
                'up_pwd' => $this->upPwd,
                'account' => $this->getPlayerAccount(),
                'Point' => $points,
                'track_id' => $trackId,
            ])->submit();

            sleep(1);

            // 再進行查帳動做
            $response = ApiCaller::make('super_lottery')->methodAction('post', 'points', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'act' => 'log',
                'up_acc' => $this->upAcc,
                'up_pwd' => $this->upPwd,
                'account' => $this->getPlayerAccount(),
                'track_id' => $trackId,
            ])->submit();

            $arrayData = array_get($response, 'response.data');
            $this->assertArrayHasKey('CPoint', $arrayData);
            $this->assertEquals($points, abs(array_get($arrayData, 'CPoint')));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
}