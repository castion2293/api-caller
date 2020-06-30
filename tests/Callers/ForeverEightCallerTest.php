<?php


namespace SuperPlatform\ApiCaller\Tests\Callers;

use Illuminate\Support\Carbon;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;
use BaseTestCase;

class ForeverEightCallerTest extends BaseTestCase
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
        $this->console->write('AV 電子');
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
            $response = ApiCaller::make('forever_eight')->methodAction('POST', 'lg', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'Loginname' => $this->testAccount,
                'Oddtype' => 'A',
                'Cur' => 'TWD',
                'NickName' => $this->testAccount,
                'SecureToken' => str_random(16)
            ])->submit();
            $response = $response['response'];

            $this->assertEquals('1', $response['Status']);
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
            $response = ApiCaller::make('forever_eight')->methodAction('post', 'gb', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'Loginname' => $this->testAccount,
                'Cur' => 'TWD'
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('1', $response['Status']);
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

            $amount = 100.00;
            $rand = rand(1000000000000,9999999999999);

            // Act
            $response = ApiCaller::make('forever_eight')->methodAction('post', 'tc', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'Loginname' => $this->testAccount,
                'Billno' => config('api_caller.forever_eight.config.client_ID').$rand,
                'Type' => 100,
                'Cur' => 'TWD',
                'Credit' => $amount,
            ])->submit();

            $response = $response['response'];

            $transferCreditConfirm = ApiCaller::make('forever_eight')->methodAction('post', 'tcc', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'Loginname' => $this->testAccount,
                'Billno' => config('api_caller.forever_eight.config.client_ID').$rand,
                'TGSno' => $response['Data'],
                'Type' => 100,
                'Cur' => 'TWD',
                'Credit' => $amount,
            ])->submit();

            $transferCreditConfirm = $transferCreditConfirm['response'];

            dump($transferCreditConfirm);
            $this->assertEquals('1', $transferCreditConfirm['Status']);
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
            $response = ApiCaller::make('forever_eight')->methodAction('get', 'fwgame_opt', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'Loginname' => $this->testAccount,
                'Lang' => 'zh-tw',
                'Cur' => 'TWD',
                'GameId' => '1020',
                'Oddtype' => 'A',
                'SecureToken' => str_random(16),
                'HomeURL' => 0,
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('1', $response['Status']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得投注纪录总页数
     *
     * @throws ApiCallerException
     */
    public function testQueryOrderStatus()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得投注纪录总页数');

            // Act
            $response = ApiCaller::make('forever_eight')->methodAction('post', 'GET_PAGES_DETAIL_WITH_DATE', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'Start' => Carbon::now()->subDays(7)->toDateTimeString(),
                'End' => Carbon::now()->toDateTimeString(),
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('1', $response['Status']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試取得指定页数的投注纪录
     *
     * @throws ApiCallerException
     */
    public function testGetRecordsWithDateOnPage()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得指定页数的投注纪录');

            // Act
            $response = ApiCaller::make('forever_eight')->methodAction('post', 'GET_RECORDS_WITH_DATE_ON_PAGE', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'Start' => Carbon::now()->subDays(1)->toDateTimeString(),
                'End' => Carbon::now()->toDateTimeString(),
                'PageNum' => 1,
            ])->submit();

            $response = $response['response'];
            dump($response['Data']);
            $this->assertEquals('1', $response['Status']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
}