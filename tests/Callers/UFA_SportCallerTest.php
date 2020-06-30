<?php

namespace SuperPlatform\ApiCaller\Tests\Callers;

use BaseTestCase;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

/**
 * Class UFA_SportCallerTest
 */
class UFA_SportCallerTest extends BaseTestCase
{
    private $amount;
    protected $testAccount = '';

    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        $this->amount = 100;
        $this->testAccount = 'aaa123';
        // 提示測試中的 caller 是哪一個
        $this->console->write('UFA 體育');
    }

    /**
     * 測試创建一个新UFA體育玩家
     *
     * @throws ApiCallerException
     */
    public function testPlayerCreation()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試创建一个新UFA體育玩家');

            // Act
            $response = ApiCaller::make('ufa_sport')->methodAction('get', 'create', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'username' => $this->testAccount
            ])->submit();
            $response = $response['response'];

            $this->assertEquals('1', array_get($response,'errcode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查詢餘額
     *
     * @throws ApiCallerException
     */
    public function testBalance()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢餘額');

            $response = ApiCaller::make('ufa_sport')->methodAction('get', 'balance', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'username' => $this->testAccount
            ])->submit();

            $response = $response['response'];
            //印出目前餘額
            dump($response['result']);
            $this->assertEquals('0', array_get($response,'errcode'));
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

            $response = ApiCaller::make('ufa_sport')->methodAction('get', 'deposit', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'username' => $this->testAccount,
                'serial' => str_random(32),
                'amount' => $this->amount
            ])->submit();

            $response = $response['response'];
            //印出存款完金額
            dump($response['result']);
            $this->assertEquals('0', array_get($response,'errcode'));
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

            // Act
            $response = ApiCaller::make('ufa_sport')->methodAction('get', 'withdraw', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'username' => $this->testAccount,
                'serial' => str_random(32),
                'amount' => $this->amount
            ])->submit();

            $response = $response['response'];
            //印出出款完金額
            dump($response['result']);
            $this->assertEquals('0', array_get($response,'errcode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試修改會員限紅
     *
     * @throws ApiCallerException
     */
    public function testUpdateLimit()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試修改會員限紅');

            // Act
            $response = ApiCaller::make('ufa_sport')->methodAction('get', 'update', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                /* required */
                'secret' => config("api_caller.ufa_sport.config.secret_code"),
                'agent' => config("api_caller.ufa_sport.config.agent"),
                'username' => $this->testAccount,
                /* optional */
                'max1' => '20000',  // <HDP / OU / OE的最大賭注>
                'max2' => '20000',  // <1X2 / 雙重機會的最大賭注>
                'max3' => '20000',  // <混合過關的最大賭注>
                'max4' => '20000',  // <正確分數/總進球/半場全場/第一個進球最後一個進球的最大投注>
                'max5' => '20000',  // <其他體育HDP / OU / OE的最大賭注>
                'lim1' => '50000',  // <HDP / OU / OE的每匹配匹配>
                'lim2' => '50000',  // <1X2 / 雙重機會的每場比賽匹配>
                'lim3' => '50000',  // <每組合混合過關限制>
                'lim4' => '50000',  // <每場比賽的正確比分/總進球/半場全場/第一球進球最後一球>
                'lim5' => '50000',  // <其他運動HDP / OU / OE的每場比賽匹配>
                'comtype' => 'A',  // <HDP / OU / OE的A，B，C，D，E，F，G，H，I，J的選擇>
                'com1' => '0.25',  // <HDP / OU / OE 佣金>
                'com2' => '0.25',  // <1X2 / 雙重機會的佣金>
                'com3' => '0.25',  // <混合過關佣金>
                'com4' => '0.25',  // <其他佣金>
                'suspend' => 0  // <0：沒有暫停，1：暫停>
            ])->submit();
            $response = $response['response'];

            $this->assertEquals('0', array_get($response,'errcode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試登入遊戲
     *
     * @throws ApiCallerException
     */
    public function testLogin()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試登入遊戲');

            // Act
            $response = ApiCaller::make('ufa_sport')->methodAction('get', 'login', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'username' => $this->testAccount,
                'host' => config("api_caller.ufa_sport.config.host_url"),
                'lang' => 'ZH-CN',
                'accType' => 'MY'
            ])->submit();
            $response = $response['response'];

            $host = array_get($response,'result.login.host');
            $params = array_get($response,'result.login.param');

            $web_url = $host. '?us='.$params['us']. '&k='. $params['k']. '&lang='. $params['lang']. '&accType='. $params['accType']. '&r='. $params['r'];
            dump('網頁版：'.$web_url);
            $mobile_url = 'http://sportmobi.time399.com/public/Validate.aspx?us='. $params['us']. '&k='.$params['k']. '&lang='. $params['lang']. '&accType='. $params['accType']. '&r='. $params['r'];
            dump('手機板：'.$mobile_url);

            $this->assertEquals('0', array_get($response,'errcode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試抓取注單
     *
     * @throws ApiCallerException
     */
    public function testTicket()
    {

        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試抓取注單');

            $response = ApiCaller::make('ufa_sport')->methodAction('get', 'ticket', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'start' => '2019-06-20 13:00:00',
                'duration' => 86400,
                'match_over' => 0
            ])->submit();

            $response = $response['response'];
            dump($response);

            $this->assertEquals('0', array_get($response,'errcode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查詢運動狀態
     *
     * @throws ApiCallerException
     */
    public function testSportsType()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢運動狀態');

            $response = ApiCaller::make('ufa_sport')->methodAction('get', 'sportstype', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'username' => $this->testAccount,
                'sportstype_id' => 99
            ])->submit();

            $response = $response['response'];
            dump(array_get($response,'result.name'));
            $this->assertEquals('0', array_get($response,'errcode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查詢運動隊伍
     *
     * @throws ApiCallerException
     */
    public function testSportsTeam()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢運動隊伍');

            $response = ApiCaller::make('ufa_sport')->methodAction('get', 'team', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'username' => $this->testAccount,
                'team_id' => 20482
            ])->submit();

            $response = $response['response'];
            dump(array_get($response,'result.name'));
            $this->assertEquals('0', array_get($response,'errcode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查詢運動聯盟
     *
     * @throws ApiCallerException
     */
    public function testSportsLeague()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢運動聯盟');

            $response = ApiCaller::make('ufa_sport')->methodAction('get', 'league', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'username' => $this->testAccount,
                'league_id' => 2803
            ])->submit();

            $response = $response['response'];
            dump(array_get($response,'result.name'));
            $this->assertEquals('0', array_get($response,'errcode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得注單
     *
     * @throws ApiCallerException
     */
    public function testFetch()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得注單');

            $response = ApiCaller::make('ufa_sport')->methodAction('get', 'fetch' , [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定

            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('0', array_get($response,'errcode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試取得注單
     *
     * @throws ApiCallerException
     */
    public function testMarkFetch()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得注單');

            $response = ApiCaller::make('ufa_sport')->methodAction('get', 'mark_fetched' , [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'fetch_ids' => '16348714'
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('0', array_get($response,'errcode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試登出
     *
     * @throws ApiCallerException
     */
    public function testLogout()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試登出');

            $response = ApiCaller::make('ufa_sport')->methodAction('get', 'logout' , [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'username' => $this->testAccount,
            ])->submit();

            $response = $response['response'];

            $this->assertEquals('0', array_get($response,'errcode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
}