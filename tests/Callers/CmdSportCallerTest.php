<?php

namespace SuperPlatform\ApiCaller\Tests\Callers;

use BaseTestCase;
use Carbon\Carbon;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

class CmdSportCallerTest extends BaseTestCase
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
     * 國家
     *
     * @var string
     */
    protected $country = '';

    /**
     * 語言
     *
     * @var string
     */
    protected $language = '';

    /**
     * 模式
     *
     * @var string
     */
    protected $mode = '';

    /**
     * 限紅
     *
     * @var string
     */
    protected $betLimitCode = '';

    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        $this->testAccount = config('api_caller.cmd_sport.config.test_member_account');
        $this->currency = config('api_caller.cmd_sport.config.currency');
        $this->language = config('api_caller.cmd_sport.config.lang');

        // 提示測試中的 caller 是哪一個
        $this->console->write('CMD SPORT');
    }

    /**
     * 測試創建玩家錢包
     *
     * @throws ApiCallerException
     */
    public function testCreatePlayer()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試創建玩家帳號');

            // Act
            $response = ApiCaller::make('cmd_sport')->methodAction('GET', 'createmember', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                "UserName" => $this->testAccount,
                "Currency" => $this->currency,
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('Code', $response);
        } catch (ApiCallerException $exception) {
            $this->assertEquals('-98', array_get($exception->response(), 'errorCode'));
            $this->assertEquals('UserAlreadyExists', array_get($exception->response(), 'errorMsg'));
            $this->console->writeln($exception->response());
        }
    }

    /**
     * 測試檢索玩家餘額
     *
     * @throws ApiCallerException
     */
    public function testGetPlayerBalance()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢玩家餘額');

            // Act
            $response = ApiCaller::make('cmd_sport')->methodAction('get', 'getbalance', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                "UserName" => $this->testAccount,
            ])->submit();
            dump($response);
            $response = $response['response'];
            $this->assertEquals('0', array_get($response, 'Code'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試轉帳錢包(存款)
     *
     * @throws ApiCallerException
     */
    public function testDepositTransfers()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試轉帳錢包(存款)');


            // 查詢餘額
            $beforeBalance = ApiCaller::make('cmd_sport')->methodAction('get', 'getbalance', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                "UserName" => $this->testAccount,
            ])->submit();
            $beforeBalance = array_get($beforeBalance, "response.Data.0.BetAmount");

            // 存入金額
            $money = 10;
            // Act
            $response = ApiCaller::make('cmd_sport')->methodAction('GET', 'balancetransfer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'UserName' => $this->testAccount,
                'PaymentType' => 1,
                'Money' => $money,
                'TicketNo' => str_random(50)
            ])->submit();

            $response = $response['response'];

            $this->assertEquals(array_get($response, "Data.BetAmount"), $beforeBalance + $money);
            $this->assertEquals('0', array_get($response, 'Code'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試轉帳錢包(取款)
     *
     * @throws ApiCallerException
     */
    public function testWithdrawTransfers()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試轉帳錢包(取款)');


            // 查詢餘額
            $beforeBalance = ApiCaller::make('cmd_sport')->methodAction('get', 'getbalance', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                "UserName" => $this->testAccount,
            ])->submit();
            $beforeBalance = array_get($beforeBalance, "response.Data.0.BetAmount");

            // 提領金額
            $money = 0.01;
            // Act
            $response = ApiCaller::make('cmd_sport')->methodAction('GET', 'balancetransfer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'UserName' => $this->testAccount,
                'PaymentType' => 0,
                'Money' => $money,
                'TicketNo' => str_random(50)
            ])->submit();
            dump($response);
            $response = $response['response'];

            $this->assertEquals(array_get($response, "Data.BetAmount"), $beforeBalance - $money);
            $this->assertEquals('0', array_get($response, 'Code'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試登出會員
     *
     * @throws ApiCallerException
     */
    public function testCompleteTransfer()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試登出會員');

            // Act
            $responseFirst = ApiCaller::make('cmd_sport')->methodAction('GET', 'kickuser', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'UserName' => $this->testAccount
            ])->submit();

            $response = $responseFirst['response'];


            $this->assertEquals('0', array_get($response, 'Code'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查詢用戶是否存在
     *
     * @throws ApiCallerException
     */
    public function testRetrieveTransferDetails()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查詢用戶是否存在');

            // Act
            $response = ApiCaller::make('cmd_sport')->methodAction('GET', 'exist', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'UserName' => $this->testAccount,
            ])->submit();

            $response = $response['response'];

            $this->assertEquals('0', array_get($response, 'Code'));
            $this->assertEquals(true, array_get($response, 'Data'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試查询存取款交易状态
     *
     * @throws ApiCallerException
     */
    public function testTransferStatus()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試查询存取款交易状态');

            $ticketNo = str_random(50);
            // 存款
            ApiCaller::make('cmd_sport')->methodAction('GET', 'balancetransfer', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'UserName' => $this->testAccount,
                'PaymentType' => 1,
                'Money' => 10,
                'TicketNo' => $ticketNo
            ])->submit();

            // Act
            $response = ApiCaller::make('cmd_sport')->methodAction('GET', 'checkfundtransferstatus', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'TicketNo' => $ticketNo,
                'UserName'=> $this->testAccount
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('0', array_get($response, 'Code'));
            $this->assertNotSame([], array_get($response, 'Data'));

        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }


    /**
     * 測試取得遊戲注單
     *
     * @throws ApiCallerException
     */
    public function testGameRounds()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得遊戲注單');

            // Act
            $response = ApiCaller::make('cmd_sport')->methodAction('GET', 'betrecord', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'Version' => 0,
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertEquals('0', array_get($response, 'Code'));
            $this->assertNotSame([], array_get($response, 'Data'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得遊戲過關細單
     *
     * @throws ApiCallerException
     */
    public function testParTicketDetail()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得遊戲過關細單');

            // 取得注單
            $tickets = ApiCaller::make('cmd_sport')->methodAction('GET', 'betrecord', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'Version' => 69240905,
            ])->submit();
            // 取出過關單

            $parTicket = array_get($tickets, "response.Data.0");

            // Act
            $response = ApiCaller::make('cmd_sport')->methodAction('GET', 'parlaybetrecord', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'SocTransId' => array_get($parTicket, "SocTransId"),
            ])->submit();

            $parTicketInfo = array_get($response, "response");
            dd($parTicket, $parTicketInfo);
            $this->assertEquals('0', array_get($parTicketInfo, 'Code'));
            $this->assertNotSame([], array_get($parTicketInfo, 'Data'));
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得語言信息
     *
     * @throws ApiCallerException
     */
    public function testGetLangInfo()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得語言信息');

            // 取得一筆注單
            $tickets = ApiCaller::make('cmd_sport')->methodAction('GET', 'betrecord', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'Version' => 0,
            ])->submit();
            $ticket = array_get($tickets, "response.Data.0");

            $caller = ApiCaller::make('cmd_sport');
            foreach ($ticket as $key => $value) {
                // 主隊id
                if ($key == "HomeTeamId") {
                    $homeTeamId = $caller->methodAction('GET', 'languageinfo', [
                        // 路由參數這邊設定
                    ])->params([
                        // 一般參數這邊設定
                        'Type' => 0,
                        'ID' => (string)$value,
                    ])->submit();
                    $homeTeamIdLangData = array_get($homeTeamId, "response");
                }
                // 客隊id
                if ($key == "AwayTeamId") {
                    $awayTeamId = $caller->methodAction('GET', 'languageinfo', [
                        // 路由參數這邊設定
                    ])->params([
                        // 一般參數這邊設定
                        'Type' => 0,
                        'ID' => (string)$value,
                    ])->submit();
                    $awayTeamIdLangData = array_get($awayTeamId, "response");
                }
                // 聯賽id
                if ($key == "LeagueId") {
                    $leagueId = $caller->methodAction('GET', 'languageinfo', [
                        // 路由參數這邊設定
                    ])->params([
                        // 一般參數這邊設定
                        'Type' => 1,
                        'ID' => (string)$value,
                    ])->submit();
                    $leagueIdLangData = array_get($leagueId, "response");
                }
                // 特別投注id
                if ($key == "SpecialId" && !empty($value)) {
                    $specialId = $caller->methodAction('GET', 'languageinfo', [
                        // 路由參數這邊設定
                    ])->params([
                        // 一般參數這邊設定
                        'Type' => 2,
                        'ID' => (string)$value,
                    ])->submit();
                    $specialIdLangData = array_get($specialId, "response");
                }
            }
//            dd(array_get($ticket, "SpecialId"));
            // Act
            dump($homeTeamIdLangData, $awayTeamIdLangData, $leagueIdLangData);
            $this->assertArrayHasKey('zh-TW', array_get($homeTeamIdLangData, 'Data'));
            $this->assertArrayHasKey('zh-TW', array_get($awayTeamIdLangData, 'Data'));
            $this->assertArrayHasKey('zh-TW', array_get($leagueIdLangData, 'Data'));
            if (!empty(array_get($ticket, "SpecialId"))) {
                $this->assertArrayHasKey('zh-TW', array_get($specialIdLangData, 'Data'));
            }
            $this->assertNotSame([], array_get($homeTeamIdLangData, 'Data'));
            $this->assertNotSame([], array_get($awayTeamIdLangData, 'Data'));
            $this->assertNotSame([], array_get($leagueIdLangData, 'Data'));
            if (!empty(array_get($ticket, "SpecialId"))) {
                $this->assertNotSame([], array_get($specialIdLangData, 'Data'));
            }
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }



    public function testDatatimeConv()
    {
        $transDate = 637098445466100000;
        $a = 621355968000000000;

        $time = ($transDate - $a)/10000000;
        dd(Carbon::createFromTimestamp($time)->subHours(8)->toDateTimeString());
        dd($time);
        dd(date('Y-m-d H:i:s', $time));
    }

    /**
     * 測試遊戲局注單 找下一頁
     *
     * @throws ApiCallerException
     */
    public function testGameRoundsForNextPage()
    {
        $from  = Carbon::parse('2019-08-28 00:00:00')->format('Y-m-d\TH:i:s');
        $to = Carbon::parse('2019-08-28 23:59:59')->format('Y-m-d\TH:i:s');

        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試遊戲局注單');

            // Act
            $firstResponse = ApiCaller::make('q_tech')->methodAction('get', 'game-rounds', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'from' => $from,
                'to'  => $to,
                'size' => 1,
            ])->submit();

            $links = array_get($firstResponse, 'response.links');
            $href = array_get(array_first($links), 'href');
            $query = array_get(explode('?', $href), 1);
            parse_str($query, $queryArray);

            $secondResponse = ApiCaller::make('q_tech')->methodAction('get', 'game-rounds', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'cursor' => array_get($queryArray, 'cursor'),
                'from' => $from,
                'to'  => $to,
                'size' => 1,
            ])->submit();

            $response = $secondResponse['response'];
            dump($response);
            $this->assertArrayHasKey('totalCount', $response);
            $this->assertArrayHasKey('items', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試遊戲局細節
     *
     * @throws ApiCallerException
     */
    public function testGameRoundDetails()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試遊戲局細節');

            // Act
            $response = ApiCaller::make('q_tech')->methodAction('get', 'game-rounds/{roundId}', [
                // 路由參數這邊設定
                'roundId' => '5d4d0f96feaff60001eb8bf6'
            ])->params([
                // 一般參數這邊設定
            ])->submit();

            $response = $response['response'];
            $this->assertArrayHasKey('gameId', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試遊戲交易
     *
     * @throws ApiCallerException
     */
    public function testGameTransactions()
    {
        $from  = Carbon::parse('2019-08-21 00:00:00')->format('Y-m-d\TH:i:s');
        $to = Carbon::parse('2019-08-21 23:59:59')->format('Y-m-d\TH:i:s');

        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試遊戲交易');

            // Act
            $response = ApiCaller::make('q_tech')->methodAction('get', 'game-transactions', [
                // 路由參數這邊設定
                'roundId' => '5d4d0f96feaff60001eb8bf6'
            ])->params([
                // 一般參數這邊設定
                'playerId' => $this->testAccount,
                'from' => $from,
                'to'  => $to,
                'size' => 1000,
                'page' => 0
            ])->submit();

            $response = $response['response'];

            $this->assertArrayHasKey('totalCount', $response);
            $this->assertArrayHasKey('items', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
}