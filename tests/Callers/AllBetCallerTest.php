<?php

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

/**
 * Class AllBetCallerTest
 */
class AllBetCallerTest extends BaseTestCase
{
    public $propertyId = '';

    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        // 此會員資訊可以透過 api 取回，或登入代理後台查看
        // 此會員帳號是固定提供給測試用
        $this->setPlayerAccount(
            $this->setTranslateAccount(
                env('TEST_APP_ID', env('APP_ID')),
                config('api_caller.all_bet.config.test_account')
            )
        );
        $this->setPlayerPassword(
            $this->setTranslatePasswd(config('api_caller.all_bet.config.test_password'))
        );
        $this->agent = config('api_caller.all_bet.config.agent');

        // 提示測試中的 caller 是哪一個
        $this->console->write('All Bet ');

        $this->propertyId = config("api_caller.all_bet.config.property_id");
    }

    /**
     * 測試登入，且登入成功
     *
     * @throws ApiCallerException
     */
    public function testLoginSuccess()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試登入，且登入成功');

            // Act
            $response = ApiCaller::make('all_bet')->methodAction(
                'post',
                'forward_game',
                [
                    // 路由參數這邊設定
                ]
            )->params(
                [
                    // 一般參數這邊設定
                    'client' => $this->getPlayerAccount(),
                    'password' => $this->getPlayerPassword(),
                ]
            )->submit();

            // Assert
            $this->assertArrayHasKey('error_code', $response['response']);
            $this->assertEquals('OK', $response['response']['error_code']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試建立一個已存在會員，並新增失敗
     *
     * 備註：因為測試新增會員成功的話會不斷新增會員，所以僅測試可呼叫，新增對象是已存在的即可
     *
     * @throws ApiCallerException
     * @throws Exception
     */
    public function testCreateAExistMember()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試建立一個已存在會員，並新增失敗');

            // Arrange
            // 取得可用盤口，用來創建會員
            $queryHandicap = ApiCaller::make('all_bet')->methodAction(
                'post',
                'query_handicap',
                [
                    // 路由參數這邊設定
                ]
            )->params(
                [
                    // 一般參數這邊設定
                    'agent' => $this->agent,
                ]
            )->submit();
            $handicaps = $queryHandicap['response']['handicaps'];
            $availableHandicaps = [
                'or' => [],
                'vip' => [],
            ];
            foreach ($handicaps as $handicap) {
                $bucketName = ($handicap['handicapType'] == 1) ? 'vip' : 'or';
                array_push($availableHandicaps[$bucketName], $handicap['id']);
            }

            // Act
            $this->expectException('GuzzleHttp\Exception\ServerException');
            $response = ApiCaller::make('all_bet')->methodAction(
                'post',
                'check_or_create',
                [
                    // 路由參數這邊設定
                ]
            )->params(
                [
                    // 一般參數這邊設定
                    'agent' => $this->agent,
                    'client' => $this->getPlayerAccount(),
                    'password' => $this->getPlayerPassword(),
                    'vipHandicaps' => array_first($availableHandicaps['vip']),
                    'orHandicaps' => array_first($availableHandicaps['or']),
                    'orHallRebate' => 0,
                ]
            )->submit();
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        } catch (\Exception $exception) {
            $this->assertEquals(500, $exception->getCode());
            $this->assertTrue(strpos($exception->getMessage(), 'CLIENT_EXIST') !== false);
            throw $exception;
        }
    }

    /**
     * 測試取得盤口訊息
     *
     * @throws ApiCallerException
     */
    public function testGetHandicap()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得盤口訊息');

            // Act
            $response = ApiCaller::make('all_bet')->methodAction(
                'post',
                'query_handicap',
                [
                    // 路由參數這邊設定
                ]
            )->params(
                [
                    // 一般參數這邊設定
                    'agent' => $this->agent,
                ]
            )->submit();

            // Assert
            $this->assertEquals(200, $response['http_code']);
            $this->assertArrayHasKey('error_code', $response['response']);
            $this->assertEquals('OK', $response['response']['error_code']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得餘額，取得成功
     *
     * @throws ApiCallerException
     * @throws Exception
     */
    public function testGetBalanceSuccess()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得餘額，取得成功');

            // Act
            $response = ApiCaller::make('all_bet')->methodAction(
                'post',
                'get_balance',
                [
                    // 路由參數這邊設定
                ]
            )->params(
                [
                    'client' => $this->getPlayerAccount(),
                    'password' => $this->getPlayerPassword(),
                ]
            )->submit();

            // Assert
            $this->assertEquals(200, $response['http_code']);
            $this->assertArrayHasKey('error_code', $response['response']);
            $this->assertEquals('OK', $response['response']['error_code']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        } catch (\Exception $exception) {
            $this->console->writeln($exception->getMessage());
            throw $exception;
        }
    }

    /**
     * 測試踢出會員
     *
     * @throws ApiCallerException
     */
    public function testLogoutGame()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試踢出會員');
            // Act
            $response = ApiCaller::make('all_bet')->methodAction(
                'post',
                'logout_game',
                [
                    // 路由參數這邊設定
                ]
            )->params(
                [
                    // 一般參數這邊設定
                    'random' => mt_rand(),
                    'client' => $this->getPlayerAccount(),
                ]
            )->submit();

            // Assert
            $this->assertEquals(200, $response['http_code']);
            $this->assertArrayHasKey('error_code', $response['response']);
            $this->assertEquals('OK', $response['response']['error_code']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 转账
     *
     * @throws ApiCallerException
     */
    public function testAgentClientTransfer()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試转账');

            $sn = $this->propertyId . substr(md5(time()), 0, 13);

            // Act
            $response = ApiCaller::make('all_bet')->methodAction(
                'post',
                'agent_client_transfer',
                [
                    // 路由參數這邊設定
                ]
            )->params(
                [
                    // 一般參數這邊設定
                    'random' => mt_rand(),
                    'agent' => $this->agent,
                    'sn' => $sn,
                    'client' => $this->getPlayerAccount(),
                    'operFlag' => 1,
                    'credit' => 1.11
                ]
            )->submit();

            $response = array_get($response, 'response');
            $this->assertEquals('OK', array_get($response, 'error_code'));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 􏰮􏰯􏱒􏱓􏰑􏰒􏱁􏱂􏰃􏰄􏰬􏰭􏰮􏰯􏱒􏱓􏰑􏰒􏱁􏱂􏰃􏰄􏰬􏰭查詢電子遊戲投注記錄歷史
     *
     * @throws ApiCallerException
     */
    public function testEGameBetlogHistories()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('查詢電子遊戲投注記錄歷史');

            $response = ApiCaller::make('all_bet')->methodAction(
                'post',
                'egame_betlog_histories',
                [
                    // 路由參數這邊設定
                ]
            )->params(
                [
                    // 一般參數這邊設定
                    'random' => mt_rand(),
                    'agent' => $this->agent,
                    'egameType' => 'af',
                    'startTime' => '2019-12-05 00:00:00',
                    'endTime' => '2019-12-05 23:59:59',
                    'pageIndex' => 1,
                    'pageSize' => 1000,
                ]
            )->submit();

            $response = array_get($response, 'response');
            print_r($response);
            $this->assertEquals("OK", array_get($response, 'error_code'));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 查询转帐状态
     *
     * @throws ApiCallerException
     */
    public function testQueryTransferState()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('查询转帐状态');

            // 先進行轉帳動做
            $sn = $this->propertyId . substr(md5(time()), 0, 13);

            ApiCaller::make('all_bet')->methodAction(
                'post',
                'agent_client_transfer',
                [
                    // 路由參數這邊設定
                ]
            )->params(
                [
                    // 一般參數這邊設定
                    'random' => mt_rand(),
                    'agent' => $this->agent,
                    'sn' => $sn,
                    'client' => $this->getPlayerAccount(),
                    'operFlag' => 1,
                    'credit' => 1.11
                ]
            )->submit();

            sleep(1);

            // 再進行查帳動做
            $response = ApiCaller::make('all_bet')->methodAction(
                'post',
                'query_transfer_state',
                [
                    // 路由參數這邊設定
                ]
            )->params(
                [
                    // 一般參數這邊設定
                    'random' => mt_rand(),
                    'sn' => $sn,
                ]
            )->submit();

            $response = array_get($response, 'response');
            $this->assertArrayHasKey('transferState', $response);
            $this->assertEquals(1, array_get($response, 'transferState'));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 單錢包測試
     *
     *
     */

    /**
     * 查询代理商盘口信息
     */
    public function testQueryAgentHandicaps()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('查询代理商盘口信息');

            $response = ApiCaller::make('all_bet')->methodAction(
                'post',
                'query_agent_handicaps',
                [
                    // 路由參數這邊設定
                ]
            )->params(
                [
                    // 一般參數這邊設定
                    'agent' => $this->agent
                ]
            )->submit();

            $response = array_get($response, 'response');
            print_r($response);
            $this->assertEquals("OK", array_get($response, 'error_code'));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 创建玩家游戏帐号
     */
    public function testCreateClient()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('创建玩家游戏帐号');

            $response = ApiCaller::make('all_bet')->methodAction(
                'post',
                'create_client',
                [
                    // 路由參數這邊設定
                ]
            )->params(
                [
                    // 一般參數這邊設定
                    'agent' => $this->agent,
                    'client' => $this->getPlayerAccount(),
                    'password' => $this->getPlayerPassword(),
                    'vipHandicapNames' => env('ALL_BET_TEST_VIP_HANDICAPS'),
                    'orHandicapNames' => env('ALL_BET_TEST_NORMAL_HANDICAPS'),
                    'orHallRebate' => 0,
                ]
            )->submit();

            $response = array_get($response, 'response');
            print_r($response);
            $this->assertEquals("OK", array_get($response, 'error_code'));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 玩家投注查询
     */
    public function testQueryClientBetlog()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('玩家投注查询');

            $response = ApiCaller::make('all_bet')->methodAction(
                'post',
                'query_client_betlog',
                [
                    // 路由參數這邊設定
                ]
            )->params(
                [
                    // 一般參數這邊設定
                    'client' => $this->getPlayerAccount(),
                    'startTime' => '2020-04-28 00:00:00',
                    'endTime' => '2020-04-28 18:00:00',
                    'pageIndex' => 1,
                    'pageSize' => 100
                ]
            )->submit();

            $response = array_get($response, 'response');
            print_r($response);
            $this->assertEquals("OK", array_get($response, 'error_code'));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 歐博帳號需加上站台前綴，區別每個站台
     *
     * @param int $_Site 站台編號
     * @param string $_Account 帳號
     * @return string
     */
    private function setTranslateAccount($_Site, $_Account)
    {
        return $_Site . "_" . $_Account;
    }

    /**
     * 歐博密碼因為長度限制問題，需轉換為 6-10 位數的 hashcode
     *
     * @param string $_Password
     * @return string
     */
    private function setTranslatePasswd($_Password)
    {
        return hash("crc32", $_Password, false);
    }
}