<?php

namespace SuperPlatform\ApiCaller\Tests\Callers;

use BaseTestCase;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

class KkLotteryCallerTest extends BaseTestCase
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
     * 國別
     *
     * @var string
     */
    protected $country = '';

    /**
     * 玩家賠率
     *
     * @var
     */
    protected $odds;

    /**
     * 用戶類型
     *
     * @var
     */
    protected static $normal = 0;

    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        $this->testAccount = config('api_caller.kk_lottery.config.test_member_account');
        $this->currency = config('api_caller.kk_lottery.config.currency');
        $this->country = config('api_caller.kk_lottery.config.country');
        $this->odds = config('api_caller.kk_lottery.config.odds');

        // 提示測試中的 caller 是哪一個
        $this->console->write('KK彩票');
    }

    /**
     * 測試 KK彩票 新增用戶
     *
     */
    public function testCreateUser()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試KK彩票新增用戶');

            // Act
            $response = ApiCaller::make('kk_lottery')->methodAction('post', 'createuser', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'username' => $this->testAccount,
                'usertype' => static::$normal,
                'countrycode' => $this->country,
                'curencycode' => $this->currency,
            ])->submit();

            $response = $response['response'];

            $this->assertEquals('1', array_get($response, 'data.status'));
        } catch (ApiCallerException $exception) {
            $this->assertEquals('2', array_get($exception->response(), 'errorCode'));
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 KK彩票 玩家登入
     *
     */
    public function testLogin()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試KK彩票玩家登入');

            // Act
            $response = ApiCaller::make('kk_lottery')->methodAction('get', 'login', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'username' => $this->testAccount,
                'logintime' => now()->timestamp * 1000,
                'odds' => floatval($this->odds),
                'backurl' => 'www.google.com',
            ])->submit();

            $response = $response['response'];

            dump($response);
            $this->assertArrayHasKey('url', $response['data']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 KK彩票 玩家登出
     *
     */
    public function testKickUser()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試KK彩票玩家登出');

            // Act
            $response = ApiCaller::make('kk_lottery')->methodAction('post', 'kickuser', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'username' => $this->testAccount,
            ])->submit();

            $response = $response['response'];

            $this->assertEquals('1', array_get($response, 'data.status'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 KK彩票 查询玩家余额
     *
     */
    public function testGetBalance()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試KK彩票查询玩家余额');

            // Act
            $response = ApiCaller::make('kk_lottery')->methodAction('post', 'fund/getbalance', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'username' => $this->testAccount,
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertArrayHasKey('available_balance', $response['data']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 KK彩票 玩家充值
     *
     */
    public function testDeposit()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試KK彩票 玩家充值');

            // Act
            $response = ApiCaller::make('kk_lottery')->methodAction('post', 'fund/deposit', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'username' => $this->testAccount,
                'amount' => 1.11,
                'curencycode' => $this->currency,
                'orderid' => str_random(32),
                'deposittime' => now()->timestamp * 1000,
            ])->submit();

            $response = $response['response'];

            $this->assertEquals('1', array_get($response, 'data.status'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 KK彩票 玩家提款
     *
     */
    public function testWithdraw()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試KK彩票 玩家提款');

            // Act
            $response = ApiCaller::make('kk_lottery')->methodAction('post', 'fund/withdraw', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'username' => $this->testAccount,
                'amount' => 1.11,
                'curencycode' => $this->currency,
                'orderid' => str_random(32),
                'withdrawtime' => now()->timestamp * 1000,
            ])->submit();

            $response = $response['response'];

            $this->assertEquals('1', array_get($response, 'data.status'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 KK彩票 投注紀錄 自營彩
     *
     */
    public function testDataBetList()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試KK彩票 玩家提款 自營彩');

            // Act
            $response = ApiCaller::make('kk_lottery')->methodAction('post', '/data/betlist', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'starttime' => '2020-01-06 00:00:00',
                'endtime' => '2020-01-06 23:59:59',
                'pagenumber' => 0,
                'pagesize' => 1000,
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertArrayHasKey('rows', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 KK彩票 投注紀錄 官方彩
     *
     */
    public function testDataOfficialBetList()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試KK彩票 玩家提款 官方彩');

            // Act
            $response = ApiCaller::make('kk_lottery')->methodAction('post', '/data/official/betlist', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'modifystarttime' => '2020-01-08 00:00:00',
                'modifyendtime' => '2020-01-08 23:59:59',
                'pagenumber' => 1,
                'pagesize' => 1000,
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertArrayHasKey('rows', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 KK彩票 追號紀錄 自營彩
     *
     */
    public function testDataPreBuyList()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試KK彩票 玩家提款 自營彩');

            // Act
            $response = ApiCaller::make('kk_lottery')->methodAction('post', '/data/prebuylist', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'modifystarttime' => '2020-01-09 10:40:00',
                'modifyendtime' => '2020-01-09 10:41:59',
                'pagenumber' => 1,
                'pagesize' => 1000,
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertArrayHasKey('rows', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 KK彩票 追號紀錄 官方彩
     *
     */
    public function testDataOfficialPreBuyList()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試KK彩票 追號紀錄 官方彩');

            // Act
            $response = ApiCaller::make('kk_lottery')->methodAction('post', '/data/official/prebuylist', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'modifystarttime' => '2020-01-09 10:40:00',
                'modifyendtime' => '2020-01-09 10:41:59',
                'pagenumber' => 1,
                'pagesize' => 1000,
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertArrayHasKey('rows', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 KK彩票 投注紀錄 账变记录
     *
     */
    public function testDataFundLog()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試KK彩票 玩家 账变记录');

            // Act 先進行充值
            $orderId = str_random(32);
            $depositResponse = ApiCaller::make('kk_lottery')->methodAction('post', 'fund/deposit', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'username' => $this->testAccount,
                'amount' => 1.11,
                'curencycode' => $this->currency,
                'orderid' => $orderId,
                'deposittime' => now()->timestamp * 1000,
            ])->submit();

            $depositResponse = $depositResponse['response'];

            // Act 再檢查流水號
            $fundLogResponse = ApiCaller::make('kk_lottery')->methodAction('post', '/data/fundlog', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
//                'username' => $this->testAccount,
                'orderid' => $orderId,
//                'starttime' => '2020-01-06 00:00:00',
//                'endtime' => '2020-01-06 23:59:59',
                'pagenumber' => 1,
                'pagesize' => 1000,
            ])->submit();

            $fundLogResponse = array_get($fundLogResponse, 'response.rows');
            $relatedOrder = array_get(array_first($fundLogResponse), 'related_order');

            $this->assertEquals($relatedOrder, $orderId);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }
}