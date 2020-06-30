<?php

use Carbon\Carbon;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

/**
 * Class AllBetCallerTest
 */
class Cq9GamingCallerTest extends BaseTestCase
{
    protected $station;

    protected $lang;


    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        $this->station = "cq9_game";
        $this->lang = 'zh-cn';
        // 此會員資訊可以透過 api 取回，或登入代理後台查看
        // 此會員帳號是固定提供給測試用
        $this->setPlayerAccount(config("api_caller.$this->station.config.test_member_account"));
        $this->setPlayerPassword(config("api_caller.$this->station.config.test_member_password"));

        // 提示測試中的 caller 是哪一個
        $this->console->write($this->station);
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
            $response = ApiCaller::make('cq9_game')
                ->methodAction('post', 'player/login', [
                    // 路由參數這邊設定
                ])->params([
                    // 一般參數這邊設定
                    'account' => $this->getPlayerAccount(),
                    'password' => $this->getPlayerPassword(),
                ])->submit();

            // Assert
            $this->assertArrayHasKey('code', $response['response']["status"]);
            $this->assertEquals('Success',
                $response['response']["status"]['message']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查詢玩家token
     *
     * @throws ApiCallerException
     */
    public function testCheckToken()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('查詢玩家token');

            // Act
            $response = ApiCaller::make('cq9_game')
                ->methodAction('get', 'player/token/{account}', [
                    // 路由參數這邊設定
                    "account" => $this->getPlayerAccount()
                ])->params([
                    // 一般參數這邊設定
                ])->submit();
            $response = $response['response'];

            // Assert
            $this->assertArrayHasKey('status', $response['data']);
            $this->assertEquals('Success', $response["status"]['message']);
            $this->assertEquals('0', $response["status"]['code']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試會員登出
     *
     * @throws ApiCallerException
     * @throws Exception
     */
    public function testLogoutSuccess()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試會員登出，取得成功');

            // Act
            $response = ApiCaller::make('cq9_game')
                ->methodAction('post', 'player/logout', [
                    // 路由參數這邊設定
                ])->params([
                    'account' => $this->getPlayerAccount(),
                ])->submit();

            $response = $response['response'];
            // Assert
            $this->assertEquals('0', array_get($response, 'status.code'));
            $this->assertEquals('Success',
                array_get($response, 'status.message'));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        } catch (\Exception $exception) {
            $this->console->writeln($exception->getMessage());
            throw $exception;
        }
    }

    /**
     * 測試建立一個已存在會員，並新增失敗
     *
     * 備註：因為測試新增會員成功的話會不斷新增會員，所以僅測試可呼叫，新增對象是已存在的即可
     *
     * @throws ApiCallerException
     * @throws Exception
     */
    public function testCreateAExistMember()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試建立一個已存在會員，並新增失敗');
            // Act
            $this->expectException('SuperPlatform\ApiCaller\Exceptions\ApiCallerException');
            $response = ApiCaller::make('cq9_game')
                ->methodAction('post', 'player', [
                    // 路由參數這邊設定
                ])->params([
                    // 一般參數這邊設定
                    'account' => config("api_caller.$this->station.config.test_account"),
                    'password' => config("api_caller.$this->station.config.test_password"),
                ])->submit();

        } catch (ApiCallerException $exception) {
            $this->assertEquals(6, $exception->response()['errorCode']);
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

            $loginResponse = ApiCaller::make('cq9_game')
                ->methodAction('post', 'player/login', [
                    // 路由參數這邊設定
                ])->params([
                    // 一般參數這邊設定
                    'account' => $this->getPlayerAccount(),
                    'password' => $this->getPlayerPassword(),
                ])->submit();

            $userToken = $loginResponse['response']['data']['usertoken'];


            // 顯示測試案例描述
            $this->console->writeln('測試進入遊戲大廳');

            $response = ApiCaller::make('cq9_game')
                ->methodAction('post', 'player/lobbylink', [
                    // 路由參數這邊設定
                ])->params([
                    // 一般參數這邊設定
                    'usertoken' => $userToken,
                    'lang' => $this->lang,
                ])->submit();

            $response = $response['response'];

            dump(array_get($response, 'data.url'));
            $this->assertArrayHasKey('url', $response['data']);
            $this->assertArrayHasKey('token', $response['data']);
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
    public function testGetGameUrl()
    {
        // 捕捉 api 訪問例外
        try {

            $loginResponse = ApiCaller::make('cq9_game')
                ->methodAction('post', 'player/login', [
                    // 路由參數這邊設定
                ])->params([
                    // 一般參數這邊設定
                    'account' => 'TT600B2F54',
                    'password' => 'TT6ca63f9f',
                ])->submit();

            $userToken = $loginResponse['response']['data']['usertoken'];


            // 顯示測試案例描述
            $this->console->writeln('測試取得遊戲連結');

            $response = ApiCaller::make('cq9_game')
                ->methodAction('post', 'player/gamelink', [
                    // 路由參數這邊設定
                ])->params([
                    // 一般參數這邊設定
                    'usertoken' => $userToken,
                    'gamehall' => 'CQ9',
                    'gamecode' => 'AT01',
                    'gameplat' => "web",
                    'lang' => $this->lang,
                ])->submit();

            $response = $response['response'];

            dump(array_get($response, 'data.url'));
            $this->assertArrayHasKey('url', $response['data']);
            $this->assertArrayHasKey('token', $response['data']);
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

            $response = ApiCaller::make('cq9_game')
                ->methodAction('post', 'player/deposit', [
                    // 路由參數這邊設定
                ])->params([
                    // 一般參數這邊設定
                    'account' => $this->getPlayerAccount(),
                    'mtcode' => str_random(32),
                    'amount' => "100",
                ])->submit();

            $response = $response['response'];

            $this->assertEquals('0', array_get($response, 'status.code'));
            $this->assertEquals('Success',
                array_get($response, 'status.message'));
            $this->assertArrayHasKey('balance', $response['data']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取款
     *
     * @throws ApiCallerException
     */
    public function testWithdraw()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取款');

            $response = ApiCaller::make('cq9_game')
                ->methodAction('post', 'player/withdraw', [
                    // 路由參數這邊設定
                ])->params([
                    // 一般參數這邊設定
                    'account' => $this->getPlayerAccount(),
                    'mtcode' => str_random(32),
                    'amount' => "100",
                ])->submit();

            $response = $response['response'];

            $this->assertEquals('0', array_get($response, 'status.code'));
            $this->assertEquals('Success',
                array_get($response, 'status.message'));
            $this->assertArrayHasKey('balance', $response['data']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得餘額，取得成功
     *
     * @throws ApiCallerException
     * @throws Exception
     */
    public function testGetBalanceSuccess()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得餘額，取得成功');

            // Act
            $response = ApiCaller::make('cq9_game')
                ->methodAction('get', 'player/balance/{account}', [
                    // 路由參數這邊設定
                    "account" => $this->getPlayerAccount(),
                ])->params([

                ])->submit();

            $response = $response['response'];
            // Assert
            $this->assertEquals('0', array_get($response, 'status.code'));
            $this->assertEquals('Success',
                array_get($response, 'status.message'));
            $this->assertArrayHasKey('balance', $response['data']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        } catch (\Exception $exception) {
            $this->console->writeln($exception->getMessage());
            throw $exception;
        }
    }

    /**
     * 測試取回注單且成功
     *
     * 備註：
     *   注單查詢以一天基礎
     *
     * @throws ApiCallerException
     */
    public function testGetReport()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取回注單且成功');

            // cq9 時間參數必須轉為他們那的時區(utc-4), 並且格式為 "rfc3339"
            // example: 本地時間:2019-06-06 11:00:00 需轉為 2019-06-05T23:00:00-04:00
            $dateTime = '2019-06-13 00:00:00';
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $dateTime)
                ->timezone('America/Boa_Vista')->toRfc3339String();

            $dateTime2 = '2019-06-13 23:59:59';
            $end = Carbon::createFromFormat('Y-m-d H:i:s', $dateTime2)
                ->timezone('America/Boa_Vista')->toRfc3339String();

            // Act
            $response = ApiCaller::make('cq9_game')
                ->methodAction('get', 'order/view', [
                    // 路由參數這邊設定
                ])->params([
                    // 一般參數這邊設定
                    "starttime" => $start,
                    "endtime" => $end,
                    "page" => "1",
                ])->submit();

            $response = $response['response'];

            // Assert
            $this->assertArrayHasKey('Data', $response['data']);
            $this->assertEquals("0", $response['status']['code']);
            $this->assertEquals("Success", $response['status']['message']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }


    /**
     * 測試取得遊戲列表
     *
     * @throws ApiCallerException
     */
    public function testGetGameList()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得遊戲列表');

            // Act
            $response = ApiCaller::make('cq9_game')
                ->methodAction('get', 'game/list/{gamehall}', [
                    // 路由參數這邊設定
                    'gamehall' => "cq9",
                ])->params([
                    // 一般參數這邊設定
                ])->submit();
            $response = $response['response'];
            dump($response);
            // Assert
            $this->assertEquals("0", $response['status']['code']);
            $this->assertEquals('Success', $response['status']['message']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得遊戲列表
     *
     * @throws ApiCallerException
     */
    public function testGetHallList()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得遊戲廠商列表');

            // Act
            $response = ApiCaller::make('cq9_game')
                ->methodAction('get', 'game/halls', [
                    // 路由參數這邊設定
                ])->params([
                    // 一般參數這邊設定
                ])->submit();
            $response = $response['response'];
            // Assert
            $this->assertEquals("0", $response['status']['code']);
            $this->assertEquals('Success', $response['status']['message']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試單一交易查詢
     *
     * @throws ApiCallerException
     */
    public function testTransactionRecord()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得遊戲廠商列表');

            $mtcode = str_random(32);

            // 先進行轉帳動做
            ApiCaller::make('cq9_game')
                ->methodAction('post', 'player/deposit', [
                    // 路由參數這邊設定
                ])->params([
                    // 一般參數這邊設定
                    'account' => $this->getPlayerAccount(),
                    'mtcode' => $mtcode,
                    'amount' => "1.11",
                ])->submit();

            sleep(1);

            // 再進行查帳動做
            $response = ApiCaller::make('cq9_game')
                ->methodAction('get', 'transaction/record/{mtcode}', [
                    // 路由參數這邊設定
                    'mtcode' => $mtcode
                ])->params([
                    // 一般參數這邊設定
                ])->submit();

            $arrayData = array_get($response, 'response.data');
            $this->assertArrayHasKey('status', $arrayData);
            $this->assertEquals('success', array_get($arrayData, 'status'));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試帳號是否存在
     *
     * @throws ApiCallerException
     */
    public function testCheckAccountExist()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試檢查帳號是否存在');

            // Act
            $response = ApiCaller::make('cq9_game')
                ->methodAction('get', 'player/check/{account}', [
                    // 路由參數這邊設定
                    "account" => $this->getPlayerAccount(),
                ])->params([
                    // 一般參數這邊設定
                ])->submit();
            $response = $response['response'];

            // Assert
            $this->assertEquals("0", $response['status']['code']);
            $this->assertEquals('Success', $response['status']['message']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試登出帳號
     *
     * @throws ApiCallerException
     */
    public function testLogout()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試登出帳號');

            // Act
            $response = ApiCaller::make('cq9_game')
                ->methodAction('post', 'player/logout', [
                    // 路由參數這邊設定
                ])->params([
                    // 一般參數這邊設定
                    'account' => $this->getPlayerAccount(),
                ])->submit();
            $response = $response['response'];

            // Assert
            $this->assertEquals("0", $response['status']['code']);
            $this->assertEquals('Success', $response['status']['message']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }
}