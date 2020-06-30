<?php

use Illuminate\Support\Carbon;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

/**
 * Class VsLotteryCallerTest
 */
class VsLotteryCallerTest extends BaseTestCase
{
    protected $partnerId;

    protected $partnerPassword;

    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        // 此會員資訊可以透過 api 取回，或登入代理後台查看
        // 此會員帳號是固定提供給測試用
        $this->setPlayerAccount(config('api_caller.vs_lottery.config.member_account_prefix') . 'TEST001');
        $this->setPlayerPassword('TEST1TEST');
        $this->partnerId = config('api_caller.vs_lottery.config.partner_id');
        $this->partnerPassword = config('api_caller.vs_lottery.config.partner_password');

        // 提示測試中的 caller 是哪一個
        $this->console->write('越南彩');
    }

    /**
     * 測試呼叫不存在的 action
     */
//    public function testAPIMethodNotExistAction()
//    {
//        // 顯示測試案例描述
//        $this->console->writeln('測試呼叫不存在的 action');
//
//        // Act
//        $this->expectException('Exception');
//        ApiCaller::make('vs_lottery')->methodAction('post', 'call_a_not_exist_action');
//    }

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
            $response = ApiCaller::make('vs_lottery')->methodAction('post', 'CreatePlayerAccount', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userName' => 'Tester07',
                'password' => 'aa1111',
                'currencyCode' => config("api_caller.vs_lottery.config.currency"),
                'firstName' => 'tester07',
                'lastName' => 'tester07',
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertArrayHasKey('CreatePlayerAccountResult', $response);
            $this->assertEquals('0', $response['CreatePlayerAccountResult']);
            $this->assertArrayHasKey('lotteryUserName', $response);

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
            $this->console->writeln('測試取得登入網址');

            // Act
            $response = ApiCaller::make('vs_lottery')->methodAction('post', 'GetLoginUrl', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userName' => config("api_caller.vs_lottery.config.member_account_prefix") . 'Tester03',
                'password' => 'aa2222',
                'lang' => config("api_caller.vs_lottery.config.lang"),
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertArrayHasKey('GetLoginUrlResult', $response);
            $this->assertEquals('0', $response['GetLoginUrlResult']);
            $this->assertArrayHasKey('url', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試踢除會員登入
     *
     * @throws ApiCallerException
     */
    public function testKickOutPlayer()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試踢除會員登入');

            // Act
            $response = ApiCaller::make('vs_lottery')->methodAction('post', 'KickOutPlayer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userName' => config("api_caller.vs_lottery.config.member_account_prefix") . 'Tester03',
            ])->submit();

            $response = $response['response'];

            dump($response);
            $this->assertArrayHasKey('KickOutPlayerResult', $response);
            $this->assertEquals('0', $response['KickOutPlayerResult']);

        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試設定會員下注狀態
     *
     * @throws ApiCallerException
     */
    public function testSetAllowBet()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試設定會員下注狀態');

            // Act
            $response = ApiCaller::make('vs_lottery')->methodAction('post', 'SetAllowBet', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userName' => config("api_caller.vs_lottery.config.member_account_prefix") . 'Tester03',
                'isAllowBet' => 'true',
            ])->submit();

            $response = $response['response'];

            dump($response);
            $this->assertArrayHasKey('SetAllowBetResult', $response);
            $this->assertEquals('0', $response['SetAllowBetResult']);

        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試設定會員登入狀態
     *
     * @throws ApiCallerException
     */
    public function testSetAllowLogin()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試設定會員登入狀態');

            // Act
            $response = ApiCaller::make('vs_lottery')->methodAction('post', 'SetAllowLogin', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userName' => config("api_caller.vs_lottery.config.member_account_prefix") . 'Tester03',
                'isAllowLogin' => 'true',
            ])->submit();

            $response = $response['response'];

            dump($response);
            $this->assertArrayHasKey('SetAllowLoginResult', $response);
            $this->assertEquals('0', $response['SetAllowLoginResult']);

        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試設定會員下注狀態
     *
     * @throws ApiCallerException
     */
    public function testResetPassword()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試設定會員下注狀態');

            // Act
            $response = ApiCaller::make('vs_lottery')->methodAction('post', 'ResetPassword', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userName' => config("api_caller.vs_lottery.config.member_account_prefix") . 'Tester03',
                'newPassword' => 'aa2222',
            ])->submit();

            $response = $response['response'];

            dump($response);
            $this->assertArrayHasKey('ResetPasswordResult', $response);
            $this->assertEquals('0', $response['ResetPasswordResult']);

        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試複製會員設定
     *
     * @throws ApiCallerException
     */
    public function testCopySettings()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試複製會員設定');

            // Act
            $response = ApiCaller::make('vs_lottery')->methodAction('post', 'CopySettings', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userName' => config("api_caller.vs_lottery.config.member_account_prefix") . 'Tester02',
                'copyFromUserName' => config("api_caller.vs_lottery.config.member_account_prefix") . 'Tester03',
                'isCopyBetLimit' => 'true',
                'isCopyPositionTaking' => 'true',
                'isCopyCommOdds' => 'true',
            ])->submit();

            $response = $response['response'];

            dump($response);
            $this->assertArrayHasKey('CopySettingsResult', $response);
            $this->assertEquals('0', $response['CopySettingsResult']);

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
            $this->console->writeln('測試，查詢帳號目前點數');

            $response = ApiCaller::make('vs_lottery')->methodAction('post', 'GetPlayerBalance', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userName' => config("api_caller.vs_lottery.config.member_account_prefix") . 'Tester03',
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertArrayHasKey('GetPlayerBalanceResult', $response);
            $this->assertEquals('0', $response['GetPlayerBalanceResult']);
            $this->assertArrayHasKey('balance', $response);
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
            $response = ApiCaller::make('vs_lottery')->methodAction('post', 'DepositWithdrawRef', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'userName' => config("api_caller.vs_lottery.config.member_account_prefix") . 'Tester03',
                'amount' => -10,
                'clientRefTransId' => "test005",
            ])->submit();

            $response = $response['response'];

            dump($response);
            $this->assertArrayHasKey('DepositWithdrawRefResult', $response);
            $this->assertEquals('0', $response['DepositWithdrawRefResult']);
            $this->assertArrayHasKey('balanceAfter', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 查詢交易狀態
     *
     * @throws ApiCallerException
     */
    public function testGetDepositWithdrawStatus()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢交易狀態');

            // Act
            $response = ApiCaller::make('vs_lottery')->methodAction('post', 'CheckDepositWithdrawStatus', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'clientRefTransId' => "test005",
            ])->submit();

            $response = $response['response'];

            dump($response);
            $this->assertArrayHasKey('CheckDepositWithdrawStatusResult', $response);
            $this->assertEquals('0', $response['CheckDepositWithdrawStatusResult']);
            $this->assertArrayHasKey('isSuccess', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 查詢交易紀錄
     *
     * @throws ApiCallerException
     */
    public function testGetFundTransaction()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢交易紀錄');

            // Act
            $response = ApiCaller::make('vs_lottery')->methodAction('post', 'GetFundTransaction', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'fromDate' => Carbon::now()->startOfYear()->toIso8601String(),
                'toDate' => Carbon::now()->endOfYear()->toIso8601String(),
                'fromRowNo' => 0,
            ])->submit();

            $response = $response['response'];
            dump($response);

            $this->assertArrayHasKey('errorCode', $response);
            $this->assertEquals('0', $response['errorCode']);
            $this->assertArrayHasKey('totalRows', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
 * 查詢注單
 *
 * @throws ApiCallerException
 */
    public function testGetBetTransaction()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢注單紀錄');

            // Act
            $response = ApiCaller::make('vs_lottery')->methodAction('post', 'GetBetTransaction', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'fromDate' => Carbon::now()->startOfYear()->toIso8601String(),
                'toDate' => Carbon::now()->endOfYear()->toIso8601String(),
                'fromRowNo' => 0,
            ])->submit();

            $response = $response['response'];
            dump($response);

            $this->assertArrayHasKey('errorCode', $response);
            $this->assertEquals('0', $response['errorCode']);
            $this->assertArrayHasKey('totalRows', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 查詢開牌結果
     *
     * @throws ApiCallerException
     */
    public function testGetGameResult()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢開牌結果');

            // Act
            $response = ApiCaller::make('vs_lottery')->methodAction('post', 'GetGameResult', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
//                'fromDate' => Carbon::now()->startOfWeek()->toIso8601String(),
                'fromDate' => '2019-11-21T18:06:35.547',
//                'toDate' => Carbon::now()->endOfWeek()->toIso8601String(),
                'toDate' => '2019-11-21T18:06:35.547',
                'culture' => config("api_caller.vs_lottery.config.lang"),
                'gameTypeId' => "",
            ])->submit();

            $response = $response['response'];
            dump($response);

            $this->assertArrayHasKey('errorCode', $response);
            $this->assertEquals('0', $response['errorCode']);
//            $this->assertArrayHasKey('errorCode', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
}