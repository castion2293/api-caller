<?php


namespace SuperPlatform\ApiCaller\Tests\Callers;

use BaseTestCase;
use Carbon\Carbon;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

class IncorrectScoreCallerTest extends BaseTestCase
{
    /**
     * 測試上層代理帳號
     *
     * @var string
     */
    protected $testAgent = '';

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

        $this->testAccount = 'a1b2c3d4';

        // 提示測試中的 caller 是哪一個
        $this->console->write('反波膽');
    }

    /**
     * 測試hello
     *
     * @throws ApiCallerException
     */
    public function testHello()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試hello');

            // Act
            $response = ApiCaller::make('incorrect_score')->methodAction('post', 'hello', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'cmd' => 'hello',
                ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('100', $response['errorCode']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試創建會員
     *
     * @throws ApiCallerException
     */
    public function testCreateMember()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試創建會員');

            // Act
            $response = ApiCaller::make('incorrect_score')->methodAction('post', 'MemberRegister', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'agentid' => '',
                'user' => $this->testAccount,
                'password' => 'A1B2C3D4',
                'username' => $this->testAccount,
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('100', $response['errorCode']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試會員登入
     *
     * @throws ApiCallerException
     */
    public function testLoginGame()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試會員登入');

            // Act
            $response = ApiCaller::make('incorrect_score')->methodAction('post', 'LoginGame', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'user' => $this->testAccount,
                'password' => 'A1B2C3D4',
                'lang' => 'cn',
                'ver' => 6,
                'trailmode' => 0
            ])->submit();

            $response1 = ApiCaller::make('incorrect_score')->methodAction('post', 'LoginGame', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'user' => $this->testAccount,
                'password' => 'A1B2C3D4',
                'lang' => 'cn',
                'ver' => 7,
                'trailmode' => 0
            ])->submit();
            $response = $response['response'];
            $response1 = $response1['response'];
            dump($response, $response1);
            $this->assertEquals('100', $response['errorCode']);
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
            $response = ApiCaller::make('incorrect_score')->methodAction('post', 'GetBalance', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'user' => $this->testAccount,
            ])->submit();
            $response = $response['response'];
            dump($response);
            $this->assertEquals('100', $response['errorCode']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試额度转入（单一钱包不适用）
     *
     * @throws ApiCallerException
     */
    public function testDeposit()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試额度转入（单一钱包不适用）');

            $amount = 100;
            $bussId = str_random();
            // Act
            $response = ApiCaller::make('incorrect_score')->methodAction('post', 'ChangeBalance', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'user' => $this->testAccount,
                'money' => $amount,
                'code' => 121,
                'bussId' => $bussId
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('100', $response['errorCode']);

            $responseStatus = ApiCaller::make('incorrect_score')->methodAction('post', 'GetbussStatus', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'user' => $this->testAccount,
                'bussId' => $bussId
            ])->submit();

            $responseStatus = $responseStatus['response'];
            dump($responseStatus);
            $this->assertEquals('100', $response['errorCode']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試额度轉出（单一钱包不适用）
     *
     * @throws ApiCallerException
     */
    public function testWithdraw()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試额度轉出（单一钱包不适用）');

            $amount = 100;
            $bussId = str_random();
            // Act
            $response = ApiCaller::make('incorrect_score')->methodAction('post', 'ChangeBalance', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'user' => $this->testAccount,
                'money' => $amount,
                'code' => 122,
                'bussId' => $bussId
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('100', $response['errorCode']);

            $responseStatus = ApiCaller::make('incorrect_score')->methodAction('post', 'GetbussStatus', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'user' => $this->testAccount,
                'bussId' => $bussId
            ])->submit();

            $responseStatus = $responseStatus['response'];
            dump($responseStatus);
            $this->assertEquals('100', $response['errorCode']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試额度转换查询（单一钱包不适用）
     *
     * @throws ApiCallerException
     */
    public function testGetTransferStatus()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試额度转换查询（单一钱包不适用）');

            $bussId = str_random();
            // Act
            $response = ApiCaller::make('incorrect_score')->methodAction('post', 'GetbussStatus', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'user' => $this->testAccount,
                'bussId' => $bussId
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('100', $response['errorCode']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試查询下注记录
     *
     * @throws ApiCallerException
     */
    public function testGetMemberReport()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查询下注记录');
            $params = [
                'startTime' => Carbon::parse('2020-02-13 00:00:00')->toDateTimeString(),
                'endTime' => Carbon::now()->addDays(2)->toDateTimeString(),
                'user' => ''
            ];

            // Act
            $response = ApiCaller::make('incorrect_score')->methodAction('post', 'GetMemberReportV2', [
                // 路由參數這邊設定
            ])->params(
                // 一般參數這邊設定
                $params
            )->submit();

            $response = $response['response'];
            dump($params,$response);
            $this->assertEquals('100', $response['errorCode']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試賽事結果
     *
     * @throws ApiCallerException
     */
    public function testGetGameResults()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試賽事結果');

            // Act
            $response = ApiCaller::make('incorrect_score')->methodAction('post', 'GetGameResults', [
                // 路由參數這邊設定
            ])->params(
            // 一般參數這邊設定
            )->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('100', $response['errorCode']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試所有賽事列表
     *
     * @throws ApiCallerException
     */
    public function testGetGameMoreList()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試所有賽事列表');

            $params = [
                'stype' => 'H',
                'sdate' => '2020-3-12'
            ];
            // Act
            $response = ApiCaller::make('incorrect_score')->methodAction('post', 'GetGameMoreList', [
                // 路由參數這邊設定
            ])->params(
                // 一般參數這邊設定
                $params
            )->submit();

            dump($response);
            $this->assertArrayHasKey('response', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
}