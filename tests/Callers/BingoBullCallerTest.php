<?php


namespace SuperPlatform\ApiCaller\Tests\Callers;

use Carbon\Carbon;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;
use BaseTestCase;

class BingoBullCallerTest extends BaseTestCase
{
    /**
     * 測試帳號
     *
     * @var string
     */
    protected $testAccount = '';

    /**
     * 測試帳號
     *
     * @var string
     */
    protected $testPassword = '';

    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        $this->testAccount =  config('api_caller.bingo_bull.config.prefix_code') . 'TBS91';
        $this->testPassword = 'ABC123CBA';

        // 提示測試中的 caller 是哪一個
        $this->console->write('賓果牛牛');
    }

    /**
     * 測試取得Token
     *
     * @throws ApiCallerException
     */
    public function testToken()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得Token');

            // Act
            $response = ApiCaller::make('bingo_bull')->methodAction('post', '/api/postToken', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'apikey' => config('api_caller.bingo_bull.config.api_key'),
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('token', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試 賓果牛牛 新增用戶
     *
     * @throws ApiCallerException
     */
    public function testCreateAccount()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試賓果牛牛 新增用戶');

            // Act
            $response = ApiCaller::make('bingo_bull')->methodAction('post', '/api/adduser', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => $this->testAccount,
                'password' => '54terds12',
            ])->submit();

            $response = $response['response'];
dump($response);
            $this->assertEquals('1', array_get($response,'typeCode'));
        } catch (ApiCallerException $exception) {
            $this->assertEquals('10006', array_get($exception->response(), 'typeCode'));
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
            $response = ApiCaller::make('bingo_bull')->methodAction('post', '/api/login', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => $this->testAccount,
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('1', array_get($response,'typeCode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試增加點數
     *
     * @throws ApiCallerException
     */
    public function testAddPoint()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試增加點數');

            // Act
            $response = ApiCaller::make('bingo_bull')->methodAction('post', '/api/addPoint', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => $this->testAccount,
                'point' => 5000,
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('1', array_get($response,'typeCode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試扣除點數
     *
     * @throws ApiCallerException
     */
    public function testDeductionPoint()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試扣除點數');

            // Act
            $response = ApiCaller::make('bingo_bull')->methodAction('post', '/api/deductionPoint', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => $this->testAccount,
                'point' => 50,
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('1', array_get($response,'typeCode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得餘額
     *
     * @throws ApiCallerException
     */
    public function testGetBalance()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得餘額');


            // Act
            $response = ApiCaller::make('bingo_bull')->methodAction('post', '/api/getPoint', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => $this->testAccount,
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('1', array_get($response,'typeCode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查詢注單
     *
     * @throws ApiCallerException
     */
    public function testGetReport()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢注單');

            // Act
            $response = ApiCaller::make('bingo_bull')->methodAction('post', '/api/getReport', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'startTime' => Carbon::now()->subDays(1)->timestamp,
                'endTime' => Carbon::now()->timestamp,
                'page' => 1,
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('1', array_get($response,'typeCode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
}