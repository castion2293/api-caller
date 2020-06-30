<?php


namespace SuperPlatform\ApiCaller\Tests\Callers;

use BaseTestCase;
use Illuminate\Support\Carbon;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

class WmCasinoCallerTest extends BaseTestCase
{
    /**
     * 測試帳號
     *
     * @var string
     */
    protected $testAccount = '';

    /**
     * 測試密碼
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

        $this->testAccount = 'tester01';
        $this->testPassword = 'aa1234';

        // 提示測試中的 caller 是哪一個
        $this->console->write('WM 真人');
    }

    /**
     * 測試創建客戶登入遊戲帳號
     *
     * @throws ApiCallerException
     */
    public function testHello()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試Hello');

            // Act
            $response = ApiCaller::make('wm_casino')->methodAction('post', 'Hello', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
            ])->submit();
            $response = $response['response'];

            $this->assertEquals('0', $response['errorCode']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試創建客戶登入遊戲帳號
     *
     * @throws ApiCallerException
     */
    public function testRegisterUser()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試創建客戶登入遊戲帳號');

            // Act
            $response = ApiCaller::make('wm_casino')->methodAction('post', 'MemberRegister', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'user' => $this->testAccount,
                'password' => $this->testPassword,
                'username' => $this->testAccount,
                'syslang' => 0
            ])->submit();
            $response = $response['response'];

            $this->assertEquals('104', $response['errorCode']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試查詢客戶遊戲帳號內餘額
     *
     * @throws ApiCallerException
     */
    public function testGetUserBalance()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢客戶遊戲帳號內餘額');

            // Act
            $response = ApiCaller::make('wm_casino')->methodAction('post', 'GetBalance', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'user' => $this->testAccount,
            ])->submit();

            $response = $response['response'];

            $this->assertEquals('0', $response['errorCode']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試轉點
     *
     * @throws ApiCallerException
     */
    public function testBalanceTransfer()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試轉點');

            $amount = 1000.00;

            // Act
            $response = ApiCaller::make('wm_casino')->methodAction('post', 'ChangeBalance', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'user' => $this->testAccount,
                'money' => $amount,
                'syslang' => 0
            ])->submit();

            $response = $response['response'];

            $this->assertEquals('0', $response['errorCode']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試系統自動跳轉並登入遊戲網站
     *
     * @throws ApiCallerException
     */
    public function testUserLogin()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試系統自動跳轉並登入遊戲網站');

            // Act
            $response = ApiCaller::make('wm_casino')->methodAction('post', 'SigninGame', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'user' => $this->testAccount,
                'password' => $this->testPassword,
                'lang' => '9',
                'syslang' => 0,
            ])->submit();

            $response = $response['response'];
            dump('遊戲大廳：'.$response['result']);
            $this->assertEquals('0', $response['errorCode']);
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
    public function testUserLogoutGame()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試登出');

            // Act
            $response = ApiCaller::make('wm_casino')->methodAction('post', 'LogoutGame', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                // 不填則登出所有會員
                'user' => $this->testAccount,
                'syslang' => 0,
            ])->submit();

            $response = $response['response'];

            $this->assertEquals('0', $response['errorCode']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試查詢代理商餘額
     *
     * @throws ApiCallerException
     */
    public function testAgentBalance()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢代理商餘額');

            // Act
            $response = ApiCaller::make('wm_casino')->methodAction('post', 'GetAgentBalance', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'syslang' => 0,
            ])->submit();

            $response = $response['response'];
            dump($response['result']);
            $this->assertEquals('0', $response['errorCode']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試查詢交易紀錄
     *
     * @throws ApiCallerException
     */
    public function testGetMemberTradeReport()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢交易紀錄');

            // Act
            $response = ApiCaller::make('wm_casino')->methodAction('post', 'GetMemberTradeReport', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'user' => $this->testAccount,
                'syslang' => 0,
            ])->submit();

            $response = $response['response'];
            dump($response['result']);
            // 操作成功，但未搜寻到数据
            $this->assertEquals('107', $response['errorCode']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試查詢遊戲紀錄報表
     *
     * @throws ApiCallerException
     */
    public function testGetDateTimeReport()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢遊戲紀錄報表');

            // Act
            $response = ApiCaller::make('wm_casino')->methodAction('post', 'GetDateTimeReport', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'user' => $this->testAccount,
                'startTime' => Carbon::now()->subMinutes(50)->format('YmdHis'),
                'endTime' => Carbon::now()->addMinutes(10)->format('YmdHis'),
                'timetype' => 0,
                'datatype' => 2,
                'syslang' => 0
            ])->submit();

            $response = $response['response'];

            // 指令操作成功但查无此笔资料
            $this->assertEquals('107', $response['errorCode']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試修改限紅
     *
     * @throws ApiCallerException
     */
    public function testEditLimit()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試修改限紅');

            // Act
            $response = ApiCaller::make('wm_casino')->methodAction('post', 'EditLimit', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'user' => $this->testAccount,
                'limitType' => '124',
                'syslang' => 0
            ])->submit();

            $response = $response['response'];

            // 指令操作成功但查无此笔资料
            $this->assertEquals('0', $response['errorCode']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試查詢未結算單
     *
     * @throws ApiCallerException
     */
    public function testGetUnsettleReport()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢未結算單');

            // Act
            $response = ApiCaller::make('wm_casino')->methodAction('post', 'GetUnsettleReport', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'user' => $this->testAccount,
                'Time' => '20190909130500',
                'syslang' => 0
            ])->submit();

            $response = $response['response'];

            // 指令操作成功但查无此笔资料
            $this->assertEquals('107', $response['errorCode']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
}