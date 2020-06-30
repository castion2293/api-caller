<?php

namespace SuperPlatform\ApiCaller\Tests\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;
use BaseTestCase;

class RoyalGameCallerTest extends BaseTestCase
{
    protected $testAccount = '';
    private $currency;
    private $amount;

    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        $this->testAccount = 'upgtest123';

        $this->currency = 'NT';
        $this->amount = 100;

        // 提示測試中的 caller 是哪一個
        $this->console->write('Royal Game');
    }

    /**
     * 測試创建一个新RG玩家
     *
     * @throws ApiCallerException
     */
    public function testPlayerCreation()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試创建一个新RG玩家');

            // Act
            $response = ApiCaller::make('royal_game')->methodAction('post', 'VPSWMember', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'RequestID' => str_random(32),
                'Control' => '1',
                'BucketID' => config("api_caller.royal_game.config.bucket_id"),
                'MemberID' => $this->testAccount,
                'MemberName' => $this->testAccount,
                'IP' => '0.0.0.0',
                'Currency' => $this->currency,
                'Operator' => 'tester'
            ])->submit();

            $response = array_get($response, 'response');
            $this->assertEquals('0', array_get($response, 'ErrorCode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試RG充值提取操作及查詢
     *
     * @throws ApiCallerException
     */
    public function testFoundTransfer()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試RG充值提取操作及查詢');

            // Act
            $response = ApiCaller::make('royal_game')->methodAction('post', 'VPSWFundTransfer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'RequestID' => str_random(32),
                'Control' => 1,
                'TransferID' => str_random(32),
                'BucketID' => config("api_caller.royal_game.config.bucket_id"),
                'MemberID' => $this->testAccount,
                // 正負數
                'TransferMoney' => (string)$this->amount,
                'Operator' => 'tester',
                'IP' => '0.0.0.0',
            ])->submit();

            // 充值前會員金額
            dump('充值前會員金額' . array_get($response,'response.Result.BeforMoney'));
            dump('充值後會員金額' . array_get($response,'response.Result.AfterMoney'));

            $this->assertEquals('成功', array_get($response,'response.ErrorMessage'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得玩家帳號在RG錢包之當前額度
     *
     * @throws ApiCallerException
     */
    public function testMemberBalance()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得玩家帳號在RG錢包之當前額度');

            // Act
            $response = ApiCaller::make('royal_game')->methodAction('post', 'VPSWMemberBalance', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'RequestID' => str_random(32),
                'BucketID' => config("api_caller.royal_game.config.bucket_id"),
                'MemberID' => $this->testAccount,
            ])->submit();

            dump(array_get($response,'response.Money'));

            $this->assertEquals('0', array_get($response,'response.ErrorCode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查詢各遊戲現有之限注範本(僅限皇家真人遊戲使用)
     *
     * @throws ApiCallerException
     */
    public function testGameLimit()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢各遊戲現有之限注範本(僅限皇家真人遊戲使用)');

            // Act
            $response = ApiCaller::make('royal_game')->methodAction('post', 'VPSGameLimit', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'RequestID' => str_random(32),
                'GameID' => 'Bacc',
            ])->submit();

            dump(array_get($response,'response.LimitInfo'));

            $this->assertEquals('0', array_get($response,'response.ErrorCode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試玩家限紅資訊(僅限皇家真人遊戲使用)
     *
     * @throws ApiCallerException
     */
    public function testMemberLimit()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試玩家限紅資訊(僅限皇家真人遊戲使用)');

            // Act
            $response = ApiCaller::make('royal_game')->methodAction('post', 'VPSMemberLimit', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'RequestID' => str_random(32),
                'Member' => config("api_caller.royal_game.config.bucket_id").'@' .$this->testAccount,
                'Control' => 4,
//                'LevelList' => [1,20,3],

            ])->submit();
            dump(array_get($response,'response.List'));

            $this->assertEquals('0', array_get($response,'response.ErrorCode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試設置玩家限紅資訊(僅限皇家真人遊戲使用)
     *
     * @throws ApiCallerException
     */
    public function testMemberSettingLimit()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試設置玩家限紅資訊(僅限皇家真人遊戲使用)');

            // Act
            $response = ApiCaller::make('royal_game')->methodAction('post', 'VPSMemberLimit', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'RequestID' => str_random(32),
                'Member' => config("api_caller.royal_game.config.bucket_id").'@' .$this->testAccount,
                'Control' => 5,
                // 查看文件中的限注範本
                'LevelList' => [10,20,25],

            ])->submit();
            dump(array_get($response,'response'));

            $this->assertEquals('0', array_get($response,'response.ErrorCode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試修改玩家限紅資訊(僅限皇家真人遊戲使用)
     *
     * @throws ApiCallerException
     */
    public function testMemberUpdateLimit()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試修改玩家限紅資訊(僅限皇家真人遊戲使用)');

            // Act
            $response = ApiCaller::make('royal_game')->methodAction('post', 'VPSMemberLimit', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'RequestID' => str_random(32),
                'Member' => config("api_caller.royal_game.config.bucket_id").'@' .$this->testAccount,
                'Control' => 6,
                // 查看文件中的限注範本
                'List' => array([
                    'GameID' => 'Bacc',
                    'Level' => [11,21,31],
                    ],[
                    'GameID' => 'LunPan',
                    'Level' => [11,21,31],
                    ]
                ),
            ])->submit();

            dump(array_get($response,'response'));

            $this->assertEquals('0', array_get($response,'response.ErrorCode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得進入遊戲的SessionKey
     *
     * @throws ApiCallerException
     */
    public function testGetMemberSessionKey()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得進入遊戲的SessionKey');

            // Act
            $response = ApiCaller::make('royal_game')->methodAction('post', 'VPSGetMemberSessionKey', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'RequestID' => str_random(32),
                'BucketID' => config("api_caller.royal_game.config.bucket_id"),
                'MemberID' => $this->testAccount,
                'Password' => str_random(32),
                'GameType' => 1,
                'ServerName' => 'lobby',
            ])->submit();

            dump(array_get($response,'response.SessionKey'));
            $SessionKey = array_get($response,'response.SessionKey');
            $passport =  config("api_caller.royal_game.config.game_url").'/Entrance?SessionKey='.$SessionKey;
            dump($passport);
            $response = array_get($response,'response');
            $this->assertEquals('成功', array_get($response,'ErrorMessage'));
            $this->assertEquals('0', array_get($response,'ErrorCode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試拉帳，取玩家下注明細
     *
     * @throws ApiCallerException
     */
    public function testPullGame()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試拉帳，取玩家下注明細');
            $beginId = 1;
            // Act
            $response = ApiCaller::make('royal_game')->methodAction('post', 'VPSStakeDetail2', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'RequestID' => str_random(32),
                'BucketID' => config("api_caller.royal_game.config.bucket_id"),
                // 依照每筆注單的ID 從這個號碼之後全部都獲得注單資料
                'MaxID' => (int)$beginId,
                'GameType' => 1,
                'ProviderID' => 'Royal'
            ])->submit();

            dump(array_get($response,'response.Result'));

            $this->assertEquals('0', array_get($response,'response.ErrorCode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查詢開牌記錄
     * 可查詢特定時間內的開牌紀錄(僅限真人遊戲)
     * @throws ApiCallerException
     */
    public function testSelectRecord()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢開牌記錄');

            // Act
            $response = ApiCaller::make('royal_game')->methodAction('post', 'VPSOpenRecord', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'RequestID' => str_random(32),
                'BucketID' => config("api_caller.royal_game.config.bucket_id"),
                'ServerName' => 'BaccA',
                'Date' => '2019-05-14',
            ])->submit();

            dump(array_get($response,'response.List'));

            $this->assertEquals('0', array_get($response,'response.ErrorCode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查詢本場帳
     * 當前下注資訊、未結算之注單
     * @throws ApiCallerException
     */
    public function testSelectAccountRecord()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢本場帳');

            // Act
            $response = ApiCaller::make('royal_game')->methodAction('post', 'VPSOpenAccounts', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'RequestID' => str_random(32),
                'BucketID' => config("api_caller.royal_game.config.bucket_id"),
                'ProviderID' => 'Royal',
                'StartTime' => '2019-05-04 10:00:00',
                'EndTime' => '2019-05-04 12:00:00'
            ])->submit();

            dump(array_get($response,'response'));

            $this->assertEquals('0', array_get($response,'response.ErrorCode'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

}