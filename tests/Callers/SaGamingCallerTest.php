<?php

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

/**
 * Class SaGamingCallerTest
 */
class SaGamingCallerTest extends BaseTestCase
{
    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        // 此會員資訊可以透過 api 取回，或登入代理後台查看
        // 此會員帳號是固定提供給測試用
        $this->setPlayerAccount(config('api_caller.sa_gaming.config.test_account'));
        $this->setPlayerPassword(config('api_caller.sa_gaming.config.test_password'));

        // 提示測試中的 caller 是哪一個
        $this->console->write('SaGaming ');
    }

    /**
     * 測試檢查數據庫中用戶名在此大廳是否已經存在，使用不存在的 username，且返回失敗
     *
     * @throws ApiCallerException
     */
    public function testVerifyUsernameUsingNotExistUsername()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試檢查數據庫中用戶名在此大廳是否已經存在，使用不存在的 username，且返回失敗');

            // Act
            $this->expectException('SuperPlatform\ApiCaller\Exceptions\ApiCallerException');
            ApiCaller::make('sa_gaming')->methodAction('post', 'VerifyUsername')
                ->params([
                    'Username' => 'send_a_wrong_username_to_verify'
                ])->submit();
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試檢查數據庫中用戶名在此大廳是否已經存在，使用存在的 username，且返回成功
     *
     * @throws ApiCallerException
     */
    public function testVerifyUsernameUsingExistUsername()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試檢查數據庫中用戶名在此大廳是否已經存在，使用存在的 username，且返回成功');

            // Act
            $response = ApiCaller::make('sa_gaming')->methodAction('post', 'VerifyUsername')
                ->params([
                    'Username' => $this->getPlayerAccount()
                ])->submit();

            //Assert
            $this->assertEquals('Success', $response['response']['ErrorMsg']);
            $this->assertEquals('true', $response['response']['IsExist']);
            $this->assertEquals($this->getPlayerAccount(), $response['response']['Username']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試建立一個會員，並建立成功
     *
     * 備註：
     * 這邊測試建立已存在會員並不會收到如文件所說錯誤碼 113 用戶名已存在，推測是沙龍端問題
     * 因此在此測試就斷言會建立會員成功
     *
     * @throws ApiCallerException
     */
    public function testCreateMember()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試建立一個會員，並建立成功');

            // Act
            //$this->expectException('SuperPlatform\ApiCaller\Exceptions\ApiCallerException');
            $response = ApiCaller::make('sa_gaming')->methodAction('post', 'RegUserInfo', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'Username' => $this->getPlayerAccount(),
                'CurrencyType' => 'TWD',
            ])->submit();

            //Assert
            $this->assertEquals($response['response']['ErrorMsg'], 'Success');
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得會員狀態
     *
     * @throws ApiCallerException
     */
    public function testGetUserStatusDV()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得會員狀態');

            // Act
            $response = ApiCaller::make('sa_gaming')->methodAction('post', 'GetUserStatusDV')
                ->params([
                    'Username' => $this->getPlayerAccount(),
                ])->submit();

            // Assert
            $this->assertEquals($response['response']['ErrorMsg'], 'Success');
            $this->assertEquals($response['response']['Username'], $this->getPlayerAccount());
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得用戶在 7 天以內範圍的下注列表
     *
     * @throws ApiCallerException
     */
    public function testGetUserBetItemDV()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得用戶在 7 天以內範圍的下注列表');

            // Act
            $response = ApiCaller::make('sa_gaming')->methodAction('post', 'GetUserBetItemDV')
                ->params([
                    'Username' => $this->getPlayerAccount(),
                    'FromTime' => now()->subDays(7)->toDateTimeString(),
                    'ToTime' => now()->toDateTimeString(),
                    'Offset' => 0
                ])->submit();

            // Assert
            $this->assertEquals($response['response']['ErrorMsg'], 'Success');
            $this->assertEquals($response['response']['Username'], $this->getPlayerAccount());
            $this->assertArrayHasKey('UserBetItemList', $response['response']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 取得會員在某最長 31 天內的輸贏金額
     *
     * @throws ApiCallerException
     */
    public function testGetUserWinLost()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('取得會員在某最長 31 天內的輸贏金額');

            // Act
            $response = ApiCaller::make('sa_gaming')->methodAction('post', 'GetUserWinLost')
                ->params([
                    'Username' => $this->getPlayerAccount(),
                    'FromTime' => now()->subDays(31)->toDateTimeString(),
                    'ToTime' => now()->toDateTimeString(),
                    'Type' => 0
                ])->submit();

            // Assert
            $this->assertEquals(0, $response['response']['ErrorMsgId']);
            $this->assertArrayHasKey('Winlost', $response['response']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查詢會員的最大贏額
     *
     * @throws ApiCallerException
     */
    public function testGetUserMaxWin()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢會員的最大贏額');

            // Act
            $response = ApiCaller::make('sa_gaming')->methodAction('post', 'GetUserMaxWin')
                ->params([
                    'Username' => $this->getPlayerAccount(),
                ])->submit();

            // Asssert
            $this->assertEquals(0, $response['response']['ErrorMsgId']);
            $this->assertArrayHasKey('MaxWinning', $response['response']);
            $this->assertArrayHasKey('MaxBalance', $response['response']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試向用戶充入點數
     *
     * @throws ApiCallerException
     */
    public function testCreditBalanceDV()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試向用戶充入點數');

            $orderId = 'IN' . date('YmdHis') . $this->getPlayerAccount();

            // Act
            $response = ApiCaller::make('sa_gaming')->methodAction('post', 'CreditBalanceDV')
                ->params([
                    'Username' => $this->getPlayerAccount(),
                    'OrderId' => $orderId,
                    'CreditAmount' => 1.11
                ])->submit();

            $response = array_get($response, 'response');
            $this->assertArrayHasKey('Balance', $response);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試檢查OrderId狀態
     *
     * @throws ApiCallerException
     */
    public function testCheckOrderId()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試檢查OrderId狀態');

            $orderId = 'IN' . date('YmdHis') . $this->getPlayerAccount();

            // 先進行轉帳動做
            ApiCaller::make('sa_gaming')->methodAction('post', 'CreditBalanceDV')
                ->params([
                    'Username' => $this->getPlayerAccount(),
                    'OrderId' => $orderId,
                    'CreditAmount' => 1.11
                ])->submit();

            sleep(1);

            // 再進行查帳動做
            $response = ApiCaller::make('sa_gaming')->methodAction('post', 'CheckOrderId')
                ->params([
                    'OrderId' => $orderId
                ])->submit();

            $response = array_get($response, 'response');
            $this->assertArrayHasKey('isExist', $response);
            $this->assertEquals('true', array_get($response, 'isExist'));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查詢SetBetLimit
     *
     * @throws ApiCallerException
     */
    public function testSetBetLimit()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試更改限紅');

            $response = ApiCaller::make('sa_gaming')->methodAction('post', 'SetBetLimit')
                ->params([
                    'Username' => $this->getPlayerAccount(),
                    'Currency' => 'TWD',
                    'Set1' => 2048,
                    'Set2' => 16384,
                    'Set3' => '',
                    'Set4' => '',
                    'Set5' => '',
                    'Gametype' => 'moneywheel,roulette,squeezebaccarat,others',
                ])->submit();
            dump($response);
            $response = array_get($response, 'response');
            $this->assertEquals('Success', array_get($response, 'ErrorMsg'));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查詢SetBetLimit
     *
     * @throws ApiCallerException
     */
    public function testQueryBetLimit()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試檢查OrderId狀態');

            $response = ApiCaller::make('sa_gaming')->methodAction('post', 'QueryBetLimit')
                ->params([
                    'Currency' => 'TWD',
                ])->submit();
            dump($response);
            $response = array_get($response, 'response');
            $this->assertArrayHasKey('isExist', $response);
            $this->assertEquals('true', array_get($response, 'isExist'));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
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
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試登出');

            // Act
            $response = ApiCaller::make('sa_gaming')->methodAction('post', 'KickUser')
                ->params([
                    'Username' => $this->getPlayerAccount(),
                ])->submit();

            $response = array_get($response, 'response');

            $this->assertEquals('Success', array_get($response, 'ErrorMsg'));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }
    /**
     * 測試设定会员每日最大赢额
     *
     * @throws ApiCallerException
     */
    public function testSetUserMaxWinning()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試设定会员每日最大赢额');

            $response = ApiCaller::make('sa_gaming')->methodAction('post', 'SetUserMaxWinning')
                ->params([
                    'Time' => now()->toDateTimeString(),
                    'Username' => $this->getPlayerAccount(),
                    'MaxWinning' => 5000000
                ])->submit();
            dump($response);
            $response = array_get($response, 'response');
            $this->assertEquals('Success', array_get($response, 'ErrorMsg'));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }
}