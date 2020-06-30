<?php

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Exceptions\BridgeActionParamsException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

/**
 * Class ApiCallerBridgeTest
 *
 * 這是用來測試橋接方法是否可用
 * TODO 需重構把每個遊戲館分開，比較清楚
 */
class ApiCallerBridgeTest extends BaseTestCase
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
     * @var string 歐博站台名稱
     */
    protected $allBetStation;

    /**
     * @var array 歐博測試玩家帳號
     */
    protected $allBetPlayer;

    /**
     * @var string 賓果站台名稱
     */
    protected $bingoStation;

    /**
     * @var array 賓果測試玩家帳號
     */
    protected $bingoPlayer;

    /**
     * @var string 德州站台名稱
     */
    protected $holdemStation;

    /**
     * @var array 德州測試玩家帳號
     */
    protected $holdemPlayer;

    /**
     * @var string 沙龍站台名稱
     */
    protected $saGamingStation;

    /**
     * @var array 沙龍測試玩家帳號
     */
    protected $saGamingPlayer;

    /**
     * @var string 體彩站台名稱
     */
    protected $superSportStation;

    /**
     * @var array 體彩測試玩家帳號
     */
    protected $superSportPlayer;

    /**
     * @var string 瑪雅站台名稱
     */
    protected $mayaStation;

    /**
     * @var array 瑪雅測試玩家帳號
     */
    protected $mayaPlayer;

    /**
     * 初始化
     */
    public function setUp()
    {
        parent::setUp();

        // 歐博測試資料
        $this->allBetStation = 'all_bet';
        $this->allBetPlayer = [
            'account' => $this->setTranslateAccount(
                env('TEST_APP_ID', env('APP_ID')),
                config('api_caller.all_bet.config.test_account')
            ),
            'password' => $this->setTranslatePasswd(
                config('api_caller.all_bet.config.test_password')
            )
        ];

        // 賓果測試資料
        $this->bingoStation = 'bingo';
        $this->bingoPlayer = [
            'account' => config('api_caller.bingo.config.test_account'),
            'password' => config('api_caller.bingo.config.test_password'),
        ];

        // 沙龍測試資料
        $this->saGamingStation = 'sa_gaming';
        $this->saGamingPlayer = [
            'account' => config('api_caller.sa_gaming.config.test_account'),
            'password' => config('api_caller.sa_gaming.config.test_password'),
        ];

        // 體彩測試資料
        $this->superSportStation = 'super_sport';
        $this->superSportPlayer = [
            'account' => config('api_caller.super_sport.config.test_account'),
            'password' => config('api_caller.super_sport.config.test_password'),
            'up_account' => config('api_caller.super_sport.config.up_account'),
            'up_password' => config('api_caller.super_sport.config.up_password'),
        ];

        // 瑪雅測試資料
        $this->setSiteNo(config('api_caller.maya.config.site_no'));
        $this->setVendorNo(config('api_caller.maya.config.property_id'));
        $this->mayaStation = 'maya';
        $this->mayaPlayer = [
            'account' => config('api_caller.maya.config.test_account'),
            'password' => config('api_caller.maya.config.test_password'),
        ];

        // 提示測試中的 caller 是哪一個
        $this->console->write('Api Caller Bridge Test ');
    }

    /**
     * 測試取得「歐博」遊戲館登入通行證
     *
     * @throws ApiCallerException
     */
    public function testGetAllBetPassportThroughBridgeAction()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得「歐博」遊戲館登入通行證');

            // Arrange
            // 橋接方法
            $bridgeAction = 'passport';
            // 歐博橋接參數
            $allBetParams = [
                'form_params' => [
                    'account' => array_get($this->allBetPlayer, 'account'),
                    'password' => array_get($this->allBetPlayer, 'password'),
                ],
                'route_params' => [],
            ];

            // act
            $allBetResponse = $this->$bridgeAction($this->allBetStation, $allBetParams);

            // assert
            // 歐博回應 message 應為 ok 表示成功
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($allBetResponse));
            $this->assertEquals('ok', $allBetResponse['response']['message']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得「賓果」遊戲館登入通行證
     *
     * @throws ApiCallerException
     */
    public function testGetBingoPassportThroughBridgeAction()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得「賓果」遊戲館登入通行證');

            // Arrange
            // 橋接方法
            $bridgeAction = 'passport';
            // 賓果橋接參數
            $bingoParams = [
                'form_params' => [],
                'route_params' => [
                    'account' => array_get($this->bingoPlayer, 'account'),
                ],
            ];

            // act
            $bingoResponse = $this->$bridgeAction($this->bingoStation, $bingoParams);

            // assert
            // 賓果回應內容應有元素為 play_url 與 mobile_url
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($bingoResponse));
            $this->assertArrayHasKey('play_url', $bingoResponse['response']);
            $this->assertArrayHasKey('mobile_url', $bingoResponse['response']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得「瑪雅」遊戲館登入通行證
     *
     * @throws ApiCallerException
     */
    public function testGetMayaPassportThroughBridgeAction()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得「瑪雅」遊戲館登入通行證');

            // Arrange
            // 橋接方法
            $bridgeAction = 'passport';
            // 瑪雅橋接參數
            $gameMemberID = $this->getGameMemberID($this->mayaPlayer['account']);
            $token = $this->getVendorNo() . '_' . $gameMemberID . '_' . 'login_at' . '_' . date('Y_m_d_H_i_s');
            $mayaParams = [
                'form_params' => [
                    'vender_no' => $this->getVendorNo(),
                    'account' => $this->mayaPlayer['account'],
                    'game_identify' => $gameMemberID,
                    'normal_handicaps' => config('api_caller.maya.config.test_game_config_id'),
                    'language' => 'zh_tw',
                    'pass_token' => $token,
                ],
                'route_params' => [],
            ];

            // act
            $mayaResponse = $this->$bridgeAction($this->mayaStation, $mayaParams);

            // assert
            // 瑪雅回應內容應 response 中應有 InGameUrl 訊息且 ErrorCode 為 0
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($mayaResponse));
            $this->assertEquals(0, $mayaResponse['response']['ErrorCode']);
            $this->assertArrayHasKey('InGameUrl', $mayaResponse['response']);
        } catch (ApiCallerException $exception) {
            $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得「沙龍」遊戲館登入通行證
     *
     * @throws ApiCallerException
     */
    public function testGetSaGamingPassportThroughBridgeAction()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得「沙龍」遊戲館登入通行證');

            // Arrange
            // 橋接方法
            $bridgeAction = 'passport';
            // 沙龍橋接參數
            $saGamingParams = [
                'form_params' => [
                    'account' => array_get($this->saGamingPlayer, 'account'),
                    'currency_type' => 'TWD'
                ],
                'route_params' => [],
            ];

            // act
            $saGamingResponse = $this->$bridgeAction($this->saGamingStation, $saGamingParams);

            // assert
            // 沙龍回應 ErrorMsg 應為 Success 表示成功
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($saGamingResponse));
            $this->assertEquals('Success', $saGamingResponse['response']['ErrorMsg']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得「體育」遊戲館登入通行證
     *
     * @throws ApiCallerException
     */
    public function testGetSuperSportPassportThroughBridgeAction()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得「體育」遊戲館登入通行證');

            // Arrange
            // 橋接方法
            $bridgeAction = 'passport';
            // 體彩橋接參數
            $superSportParams = [
                'form_params' => [
                    'account' => array_get($this->superSportPlayer, 'account'),
                    'password' => array_get($this->superSportPlayer, 'password'),
                    'responseFormat' => 'json'
                ],
                'route_params' => [],
            ];

            // act
            $superSportResponse = $this->$bridgeAction($this->superSportStation, $superSportParams);

            // assert
            // 體彩回應 code = 999 表示成功
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($superSportResponse));
            $this->assertEquals('999', $superSportResponse['response']['code']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得「歐博」會員餘額
     *
     * @throws ApiCallerException
     */
    public function testGetAllBetBalanceThroughBridgeAction()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得「歐博」會員餘額');

            // Arrange
            // 橋接方法
            $bridgeAction = 'getBalance';
            // 歐博橋接參數
            $allBetParams = [
                'form_params' => [
                    'account' => array_get($this->allBetPlayer, 'account'),
                    'password' => array_get($this->allBetPlayer, 'password'),
                ],
                'route_params' => [],
            ];

            // act
            $allBetResponse = $this->$bridgeAction($this->allBetStation, $allBetParams);

            // assert
            // 歐博回應 message 應為 ok 表示成功
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($allBetResponse));
            $this->assertEquals('ok', $allBetResponse['response']['message']);
            $this->assertArrayHasKey('balance', $allBetResponse['response']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得「賓果」會員餘額
     *
     * @throws ApiCallerException
     */
    public function testGetBingoBalanceThroughBridgeAction()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得「賓果」會員餘額');

            // Arrange
            // 橋接方法
            $bridgeAction = 'getBalance';
            // 賓果橋接參數
            $bingoParams = [
                'form_params' => [],
                'route_params' => [
                    'account' => array_get($this->bingoPlayer, 'account'),
                ],
            ];

            // act
            $bingoResponse = $this->$bridgeAction($this->bingoStation, $bingoParams);

            // assert
            // 賓果回應內容應有一個元素為 balance
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($bingoResponse));
            $this->assertArrayHasKey('balance', $bingoResponse['response']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得「瑪雅」會員餘額
     *
     * @throws ApiCallerException
     */
    public function testGetMayaBalanceThroughBridgeAction()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得「瑪雅」會員餘額');

            // Arrange
            // 橋接方法
            $bridgeAction = 'getBalance';
            // 瑪雅橋接參數
            $gameMemberID = $this->getGameMemberID($this->mayaPlayer['account']);
            $mayaParams = [
                'form_params' => [
                    'vender_no' => $this->getVendorNo(),
                    'game_identifies' => $gameMemberID,
                ],
                'route_params' => [],
            ];

            // act
            $mayaResponse = $this->$bridgeAction($this->mayaStation, $mayaParams);

            // assert
            // 瑪雅回應內容應 response 中應有 MemberBalanceList 訊息且 ErrorCode 為 0
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($mayaResponse));
            $this->assertEquals(0, $mayaResponse['response']['ErrorCode']);
            $this->assertArrayHasKey('MemberBalanceList', $mayaResponse['response']);
        } catch (ApiCallerException $exception) {
            $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得「沙龍」會員餘額
     *
     * @throws ApiCallerException
     * @throws BridgeActionParamsException
     */
    public function testGetSaGamingBalanceThroughBridgeAction()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得「沙龍」會員餘額');

            // Arrange
            // 橋接方法
            $bridgeAction = 'getBalance';
            // 沙龍橋接參數
            $saGamingParams = [
                'form_params' => [
                    'account' => array_get($this->saGamingPlayer, 'account'),
                ],
                'route_params' => [],
            ];

            // act
            $saGamingResponse = $this->$bridgeAction($this->saGamingStation, $saGamingParams);

            // assert
            // 沙龍回應 ErrorMsg 應為 Success 表示成功
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($saGamingResponse));
            $this->assertEquals('Success', $saGamingResponse['response']['ErrorMsg']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        } catch (BridgeActionParamsException $exception) {
            // $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得「體育」會員餘額
     *
     * @throws ApiCallerException
     */
    public function testGetSuperSportBalanceThroughBridgeAction()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得「體育」會員餘額');

            // Arrange
            // 橋接方法
            $bridgeAction = 'getBalance';
            // 體彩橋接參數
            $superSportParams = [
                'form_params' => [
                    'account' => array_get($this->superSportPlayer, 'account'),
                    'up_account' => array_get($this->superSportPlayer, 'up_account'),
                    'up_password' => array_get($this->superSportPlayer, 'up_password'),
                    'act' => 'search'
                ],
                'route_params' => [],
            ];

            // act
            $superSportResponse = $this->$bridgeAction($this->superSportStation, $superSportParams);

            // assert
            // 體彩回應 code 為 999 表示成功
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($superSportResponse));
            $this->assertEquals('999', $superSportResponse['response']['code']);
            $this->assertArrayHasKey('point', $superSportResponse['response']['data']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 重載方法
     * @param string $bridgeAction
     * @param array $arguments
     * @return
     */
    public function __call(string $bridgeAction, array $arguments)
    {
        /**
         * 根據 $method 找出對應的橋接方法，例如 'balance' -> 對應各個 $station ($arguments[0]) 、方法是叫什麼名稱
         *
         * 1. 假設 $station 是 bingo，橋接方法 balance 對應的方法應為 GET players/{playerId} 查詢玩家資料
         * 2. 假設 $station 是 all_bet，橋接方法 balance 對應的方法應為 POST get_balance 查詢會員餘額
         *
         * 以此類推
         *
         * $arguments[0] is station
         * $arguments[1] is included route_params for route parameters, and form_params for form parameters
         */
        return ApiCaller::make($arguments[0])->bridge($arguments[0], $bridgeAction, $arguments[1]);
    }

    /**
     * 歐博帳號需加上站台前綴
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
     * 歐博密碼需轉換為 6-10 位數的 hashcode
     *
     * @param string $_Password
     * @return string
     */
    private function setTranslatePasswd($_Password)
    {
        return hash("crc32", $_Password, false);
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
    protected function getGameMemberID($playerAccount)
    {
        return ApiCaller::make('maya')->methodAction('get', 'GetGameMemberID', [
            // 路由參數這邊設定
        ])->params([
            // 一般參數這邊設定
            'VenderNo' => $this->getVendorNo(),
            'VenderMemberID' => $playerAccount,
        ])->submit()['response']['GameMemberID'];
    }
}