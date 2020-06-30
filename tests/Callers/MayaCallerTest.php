<?php

use Carbon\Carbon;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

/**
 * Class MayaCallerTest
 */
class MayaCallerTest extends BaseTestCase
{
    /**
     * 為了測試所需要的分站編號
     */
    protected $siteNo;

    /**
     * 為了測試所需要的代理商編號
     */
    protected $vendorNo;

    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        // 此會員資訊可以透過 api 取回，或登入代理後台查看
        // 此會員帳號是固定提供給測試用
        $this->setPlayerAccount(config('api_caller.maya.config.test_account'));
        $this->setPlayerPassword(config('api_caller.maya.config.test_password'));
        $this->setSiteNo(config('api_caller.maya.config.test_site_no', 'test'));
        $this->setVendorNo(config('api_caller.maya.config.property_id'));

        // 提示測試中的 caller 是哪一個
        $this->console->write('Maya ');
    }

    /**
     * 測試建立一個已存在會員，並新增失敗
     *
     * 備註：因為測試新增會員成功的話會不斷新增會員，所以僅測試可呼叫，新增對象是已存在的即可
     *
     * @throws ApiCallerException
     */
    public function testCreateAExistMember()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試建立一個已存在會員，並新增失敗');

            // Act
            $this->expectException('SuperPlatform\ApiCaller\Exceptions\ApiCallerException');
            ApiCaller::make('maya')->methodAction('get', 'CreateMember', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'VenderNo' => $this->getVendorNo(),
                'SiteNo' => $this->getSiteNo(),
                'VenderMemberID' => $this->getPlayerAccount(),
                'MemberName' => 'api_caller_test',
                'NickName' => 'api_caller_test',
                'TestState' => '0',
                'CurrencyNo' => 'TWD',
            ])->submit();
        } catch (ApiCallerException $exception) {
             $this->consoleOutputArray($exception->response());
            $this->assertEquals(11028, $exception->response()['ErrorCode']);
            throw $exception;
        }
    }

    /**
     * 測試取得在瑪雅的會員主鍵 id
     *
     * @throws ApiCallerException
     */
    public function testGetGameMemberIDSuccess()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得在瑪雅的會員主鍵 id');

            // Act
            $response = ApiCaller::make('maya')->methodAction('get', 'GetGameMemberID', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'VenderNo' => $this->getVendorNo(),
                'VenderMemberID' => $this->getPlayerAccount(),
            ])->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $this->assertEquals('0', $response['response']['ErrorCode']);
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, array_get($response, 'http_code'));
            $this->assertArrayHasKey('GameMemberID', $response['response']);
        } catch (ApiCallerException $exception) {
             $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得餘額，取得成功
     *
     * @throws ApiCallerException
     */
    public function testGetBalanceSuccess()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得餘額，取得成功');

            // Arrange
            // 要先取得瑪雅方的會員 id
            $gameMemberID = ApiCaller::make('maya')->methodAction('get', 'GetGameMemberID', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'VenderNo' => $this->getVendorNo(),
                'VenderMemberID' => 'test11',
            ])->submit()['response']['GameMemberID'];

            // Act
            $response = ApiCaller::make('maya')->methodAction('get', 'GetBalance', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'VenderNo' => $this->getVendorNo(),
                'GameMemberIDs' => $gameMemberID,
            ])->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $this->assertEquals('0', $response['response']['ErrorCode']);
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, array_get($response, 'http_code'));
            $this->assertArrayHasKey('MemberBalanceList', $response['response']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試儲值，且儲值成功
     *
     * @throws ApiCallerException
     */
    public function testFundTransferDepositSuccess()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試儲值，且儲值成功');

            // Arrange
            $gameMemberID = $this->getGameMemberID();

            // Act
            $response = ApiCaller::make('maya')->methodAction('get', 'FundTransfer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'VenderNo' => $this->getVendorNo(),
                'GameMemberID' => $gameMemberID,
                'VenderTransactionID' => 'test_deposit_at_' . time(),
                'Amount' => 5,
                'Direction' => 'in',
            ])->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $this->assertEquals('0', $response['response']['ErrorCode']);
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, array_get($response, 'http_code'));
            $this->assertArrayHasKey('GameTransactionID', $response['response']);
            $this->assertArrayHasKey('AfterBalance', $response['response']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試提領，且提領失敗
     *
     * 測試可呼叫提領方法即可，這邊故意提領一個比較大的數字讓他餘額不足失敗
     *
     * @throws ApiCallerException
     */
    public function testFundTransferWithdrawFail()
    {
        // 因為還要等待，這邊先跳過不用測試這個呼叫
        $this->markTestSkipped('由於 FundTransfer 不允許過於頻繁的操作，需等待數秒，這邊先跳過此測試');

        // 為避免跳出 11047 操作過於頻繁稍等片刻在試，這邊 sleep 一段時間
        sleep(5);

        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試提領，且提領失敗');

            // 要先取得瑪雅方的會員 id
            $gameMemberID = $this->getGameMemberID();

            // Act
            $this->expectException('SuperPlatform\ApiCaller\Exceptions\ApiCallerException');
            $response = ApiCaller::make('maya')->methodAction('get', 'FundTransfer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'VenderNo' => $this->getVendorNo(),
                'GameMemberID' => $gameMemberID,
                'VenderTransactionID' => 'test_deposit_at_' . time(),
                'Amount' => 12345678,
                'Direction' => 'out',
            ])->submit();
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            $this->assertEquals(11017, $exception->response()['ErrorCode']);
            throw $exception;
        }
    }

    /**
     * 測試檢查/查詢轉帳單
     *
     * @throws ApiCallerException
     */
    public function testCheckFundTransferSuccess()
    {
        // 因為還要等待，這邊先跳過不用測試這個呼叫
//        $this->markTestSkipped('由於 FundTransfer 不允許過於頻繁的操作，需等待數秒，這邊先跳過此測試');

        // 為避免跳出 11047 操作過於頻繁稍等片刻在試，這邊 sleep 一段時間
        sleep(5);

        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試檢查/查詢轉帳單');

            // Arrange
            // 取得會員識別碼
            $gameMemberID = $this->getGameMemberID();
            // 準備儲值交易號碼
            $venderTransactionID = 'test_deposit_at_' . time();
            ApiCaller::make('maya')->methodAction('get', 'FundTransfer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'VenderNo' => $this->getVendorNo(),
                'GameMemberID' => $gameMemberID,
                'VenderTransactionID' => $venderTransactionID,
                'Amount' => 5,
                'Direction' => 'in',
            ])->submit();

            // Act
            $response = ApiCaller::make('maya')->methodAction('get', 'CheckFundTransfer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'VenderNo' => $this->getVendorNo(),
                'VenderTransactionID' => $venderTransactionID,
            ])->submit();
dump($response);
            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $this->assertEquals('0', $response['response']['ErrorCode']);
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, array_get($response, 'http_code'));
            $this->assertArrayHasKey('GameTransactionID', $response['response']);
            $this->assertArrayHasKey('AfterBalance', $response['response']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得會員當前上線狀態
     *
     * @throws ApiCallerException
     */
    public function testCheckIsOnlineSuccess()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得會員當前上線狀態');

            // Arrange
            // 要先取得瑪雅方的會員 id
            $gameMemberID = $this->getGameMemberID();

            // Act
            $response = ApiCaller::make('maya')->methodAction('get', 'CheckIsOnline', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'VenderNo' => $this->getVendorNo(),
                'GameMemberIDs' => $gameMemberID,
                'GameID' => 'Baccarat',
                'StartDateTime' => now()->subDays(7)->toDateTimeString(),
                'EndDateTime' => now()->toDateTimeString(),
                'PageSize' => 10,
                'CurrentPage' => 1,
                'LanguageNo' => 'en_us'
            ])->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $this->assertEquals('0', $response['response']['ErrorCode']);
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, array_get($response, 'http_code'));
            $this->assertArrayHasKey('MemberOnLineStateList', $response['response']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試踢除線上會員
     *
     * @throws ApiCallerException
     */
    public function testKickMembersSuccess()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試踢除線上會員');

            // Arrange
            // 要先取得瑪雅方的會員 id
            $gameMemberID = $this->getGameMemberID();

            // Act
            $response = ApiCaller::make('maya')->methodAction('get', 'KickMembers', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'VenderNo' => $this->getVendorNo(),
                'GameMemberIDs' => $gameMemberID,
            ])->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $this->assertEquals('0', $response['response']['ErrorCode']);
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, array_get($response, 'http_code'));
            $this->assertArrayHasKey('KickMemberNumber', $response['response']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試設定會員狀態
     *
     * @throws ApiCallerException
     */
    public function testSetMemberStateSuccess()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試踢除線上會員');

            // Arrange
            // 要先取得瑪雅方的會員 id
            $gameMemberID = $this->getGameMemberID();

            // Act
            $response = ApiCaller::make('maya')->methodAction('get', 'SetMemberState', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'VenderNo' => $this->getVendorNo(),
                'GameMemberIDs' => $gameMemberID,
                'State' => 1
            ])->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $this->assertEquals('0', $response['response']['ErrorCode']);
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, array_get($response, 'http_code'));
            $this->assertArrayHasKey('StateModifyMemberNumber', $response['response']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試獲取會員的遊戲注單明細
     *
     * @throws ApiCallerException
     */
    public function testGetGameDetailForMember()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試獲取會員的遊戲注單明細');

            // Arrange
            // 要先取得瑪雅方的會員 id
            $gameMemberID = $this->getGameMemberID();

            // Act
            $response = ApiCaller::make('maya')->methodAction('get', 'GetGameDetailForMember', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'VenderNo' => $this->getVendorNo(),
                'GameMemberID' => $gameMemberID,
                'GameID' => '101',
                'StartDateTime' => Carbon::now()->subDays(5)->format('YmdHis'),
                'EndDateTime' => Carbon::now()->format('YmdHis'),
                'PageSize' => 10,
                'CurrentPage' => 1,
                'LanguageNo' => 'zh_tw',
            ])->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $this->assertEquals('0', $response['response']['ErrorCode']);
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, array_get($response, 'http_code'));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試進入遊戲
     *
     * @throws ApiCallerException
     */
    public function testInGameSuccess()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試進入遊戲');

            // Arrange
            // 要先取得瑪雅方的會員 id
            $gameMemberID = $this->getGameMemberID();
            $token = str_random(50);

            // Act
            $response = ApiCaller::make('maya')->methodAction('post', 'InGame', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'VenderNo' => $this->getVendorNo(),
                'GameMemberID' => $gameMemberID,
                'MemberName' => $this->getPlayerAccount(),
                'GameConfigID' => '1727',
                'LanguageNo' => 'zh_tw',
                'Token' => $token,
            ])->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $this->assertEquals('0', $response['response']['ErrorCode']);
            $this->assertEquals(\Illuminate\Http\Response::HTTP_OK, array_get($response, 'http_code'));
        } catch (ApiCallerException $exception) {
             $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 設定測試用分站編號
     */
    public function setSiteNo($siteNo)
    {
        $this->siteNo = $siteNo;
    }

    /**
     * 取得測試用分站編號
     */
    public function getSiteNo()
    {
        return $this->siteNo;
    }

    /**
     * 設定代理商編號
     */
    public function setVendorNo($vendorNo)
    {
        $this->vendorNo = $vendorNo;
    }

    /**
     * 取得代理商編號
     */
    public function getVendorNo()
    {
        return $this->vendorNo;
    }

    /**
     * @return mixed
     */
    protected function getGameMemberID()
    {
        return ApiCaller::make('maya')->methodAction('get', 'GetGameMemberID', [
            // 路由參數這邊設定
        ])->params([
            // 一般參數這邊設定
            'VenderNo' => $this->getVendorNo(),
            'VenderMemberID' => $this->getPlayerAccount(),
        ])->submit()['response']['GameMemberID'];
    }
}