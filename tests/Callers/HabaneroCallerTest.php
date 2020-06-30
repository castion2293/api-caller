<?php


namespace SuperPlatform\ApiCaller\Tests\Callers;


use BaseTestCase;
use Carbon\Carbon;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

class HabaneroCallerTest extends BaseTestCase
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
     * 幣別
     *
     * @var string
     */
    protected $currency = '';

    /**
     * 語言
     *
     * @var string
     */
    protected $language = '';
    
    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        $this->testAccount = 'ABC123DEF';
        $this->testPassword = 'Abc123';
        $this->currency = config('api_caller.habanero.config.currency');
        $this->language = config('api_caller.habanero.config.language');
        
        // 提示測試中的 caller 是哪一個
        $this->console->write('HB電子');
    }

    /**
     * 測試 Habanero電子 新增用戶
     *
     */
    public function testCreateAccount()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試Habanero電子 新增用戶');

            // Act
            $response = ApiCaller::make('habanero')->methodAction('post', 'LoginOrCreatePlayer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'PlayerHostAddress' => request()->getClientIp(),
                'UserAgent' => config('api_caller.habanero.config.agent_account'),
                'KeepExistingToken' => true,
                'Username' => $this->testAccount,
                'Password' => $this->testPassword,
                'CurrencyCode' => 'TWD',
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals(true, array_get($response, 'Authenticated'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 Habanero電子 進入遊戲大廳
     *
     */
    public function testLogin()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試Habanero電子 進入遊戲大廳');

            $params = [
                'brandid' => config('api_caller.habanero.config.brand_ID'),
                'keyname' => 'SGWeirdScience',
                'token' => 'pf-1784fb16e1cac08d4ade8209fad922a681f9bn2753hft',
                'mode' => 'real',
                'locale' => 'zh-CN',
            ];
            $params = http_build_query($params);

            // Act
            $response = config('api_caller.habanero.config.api_lobby') . '?' . $params;

            dump($response);
            $this->assertEquals('0000', array_get($response, 'status'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 Habanero電子 登出
     *
     */
    public function testLogout()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試Habanero電子 登出');

            // Act
            $response = ApiCaller::make('habanero')->methodAction('post', 'LogOutPlayer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'Username' => $this->testAccount,
                'Password' => $this->testPassword,
            ])->submit();

            $response = $response['response'];
            $this->assertEquals(true, array_get($response, 'Success'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 Habanero電子 同步餘額
     *
     */
    public function testGetBalance()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試Habanero電子 同步餘額');

            // Act
            $response = ApiCaller::make('habanero')->methodAction('post', 'QueryPlayer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'Username' => $this->testAccount,
                'Password' => $this->testPassword,
            ])->submit();
            $response = $response['response'];

            $this->assertEquals(true, array_get($response, 'Found'));
            $this->assertArrayHasKey('RealBalance', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 Habanero電子 儲值
     *
     */
    public function testDeposit()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試Habanero電子 儲值');

            // Act
            $response = ApiCaller::make('habanero')->methodAction('post', 'DepositPlayerMoney', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'Username' => $this->testAccount,
                'Password' => $this->testPassword,
                'CurrencyCode' => 'TWD',
                'Amount' => 1000,
            ])->submit();

            $response = $response['response'];

            $this->assertEquals(true, array_get($response, 'Success'));
            $this->assertArrayHasKey('Amount', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 Habanero電子 出金
     *
     */
    public function testWithdraw()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試Habanero電子 出金');

            // Act
            $response = ApiCaller::make('habanero')->methodAction('post', 'WithdrawPlayerMoney', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'Username' => $this->testAccount,
                'Password' => $this->testPassword,
                'CurrencyCode' => 'TWD',
                'Amount' => -1,
            ])->submit();

            $response = $response['response'];

            $this->assertEquals(true, array_get($response, 'Success'));
            $this->assertArrayHasKey('RealBalance', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 Habanero電子 轉帳狀態
     *
     */
    public function testCheckTransfer()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試 Habanero電子 轉帳狀態');

            // Act
            $balanceTransferResponse = ApiCaller::make('habanero')->methodAction('post', 'DepositPlayerMoney', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'Username' => $this->testAccount,
                'Password' => $this->testPassword,
                'CurrencyCode' => 'TWD',
                'Amount' => 1,
            ])->submit();
            $balanceTransferResponse = array_get($balanceTransferResponse, 'response.TransactionId');

            $checkTransferResponse = ApiCaller::make('habanero')->methodAction('post', 'QueryTransfer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'RequestId' => $balanceTransferResponse,
            ])->submit();

            $this->assertEquals(true, array_get($checkTransferResponse, 'Success'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試 Habanero電子 撈注單
     *
     */
    public function testGetTransactionByTxTime()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試 Habanero電子 撈注單');

            // Act
            $response = ApiCaller::make('habanero')->methodAction('post', 'GetBrandCompletedGameResultsV2', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'BrandId' => config('api_caller.habanero.config.brand_ID'),
                'DtStartUTC' => Carbon::parse('2020-03-15 00:00:00')->format('YmdHis'),
                'DtEndUTC' => Carbon::parse('2020-04-30 00:00:00')->format('YmdHis')
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertArrayHasKey('0', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }

    /**
    /**
     * 測試 Habanero電子 遊戲結果
     *
     */
    public function testTransactionHistoryResult()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試 Habanero電子 遊戲結果');

            // Act
            $response = ApiCaller::make('habanero')->methodAction('post', 'GetPlayerGameResults', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'Username' => $this->testAccount,
                'DtStartUTC' => Carbon::parse('2020-01-05 00:00:00')->format('YmdHis'),
                'DtEndUTC' => Carbon::now()->format('YmdHis')
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertArrayHasKey('FriendlyGameInstanceId', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
        }
    }
}