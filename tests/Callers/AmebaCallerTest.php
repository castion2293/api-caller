<?php

namespace SuperPlatform\ApiCaller\Tests\Callers;

use BaseTestCase;
use Carbon\Carbon;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

class AmebaCallerTest extends BaseTestCase
{
    private $sStation = 'ameba';
    private $sCurrency;
    private $sLang;

    /**
     * 初始共用參數
     */
    public function setUp(): void
    {
        parent::setUp();

        // 此會員資訊可以透過 api 取回，或登入代理後台查看
        // 此會員帳號是固定提供給測試用
        $this->setPlayerAccount(config("api_caller.$this->sStation.config.test_account"));
        $this->setPlayerPassword(config("api_caller.$this->sStation.config.test_password"));

        $this->sCurrency = 'TWD';
        $this->sLang = 'zhTW';

        // 提示測試中的 caller 是哪一個
        $this->console->write($this->sStation);
    }

    /**
     * @throws ApiCallerException
     */
    public function testCreateAccount(): void
    {
        $this->catchException(
            '測試 建立玩家帳戶',
            [
                'post',
                'create_account',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
                'account_name' => $this->getPlayerAccount(),
                'currency' => $this->sCurrency,
            ],
            [
                'error_code' => 'OK',
            ]
        );
    }

    /**
     * @throws ApiCallerException
     */
    public function testRegisterToken(): void
    {
        $this->catchException(
            '測試 建立登入 token 並取得 url',
            [
                'post',
                'register_token',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
                'account_name' => $this->getPlayerAccount(),
                'game_id' => 1,
                'lang' => $this->sLang,
            ],
            [
                'error_code' => 'OK',
                'game_url' => null,
            ]
        );
    }

    /**
     * @throws ApiCallerException
     */
    public function testDeposit(): void
    {
        $this->catchException(
            '測試 存款到玩家的帳戶',
            [
                'post',
                'deposit',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
                'account_name' => $this->getPlayerAccount(),
                'amount' => 100,
                'tx_id' => $this->getPlayerAccount() . time(),
            ],
            [
                'error_code' => 'OK',
                'balance' => null,
                'tx_time' => null,
            ]
        );
    }

    /**
     * @throws ApiCallerException
     */
    public function testWithdraw(): void
    {
        $this->catchException(
            '測試 從玩家的帳戶提款',
            [
                'post',
                'withdraw',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
                'account_name' => $this->getPlayerAccount(),
                'amount' => 1,
                'tx_id' => $this->getPlayerAccount() . time(),
            ],
            [
                'error_code' => 'OK',
                'balance' => null,
                'tx_time' => null,
            ]
        );
    }

    /**
     * @throws ApiCallerException
     */
//    public function testGetTransaction(): void
//    {
//        $this->catchException(
//            '測試 根據 transaction id 返回存款或提款的交易資料',
//            [
//                'post',
//                'get_transaction',
//                [
//                    // 路由參數這邊設定
//                ]
//            ],
//            [
//                // 一般參數這邊設定
//                'account_name' => $this->getPlayerAccount(),
//                'type' => "deposit",
////                'type' => "withdraw",
//                'tx_id' => '',
//            ],
//            [
//                'error_code' => 'OK',
//                'type' => null,
//                'tx_id' => null,
//                'account_name' => null,
//                'amount' => null,
//                'state' => null,
//                'tx_time' => null,
//            ]
//        );
//    }

    /**
     * @throws ApiCallerException
     */
    public function testGetBalance(): void
    {
        $this->catchException(
            '測試 返回玩家帳戶餘額',
            [
                'post',
                'get_balance',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
                'account_name' => $this->getPlayerAccount(),
            ],
            [
                'error_code' => 'OK',
                'balance' => null,
            ]
        );
    }

    /**
     * @throws ApiCallerException
     */
//    public function testGetBalances(): void
//    {
//        $this->catchException(
//            '測試 返回多名玩家帳戶餘額',
//            [
//                'post',
//                'get_balances',
//                [
//                    // 路由參數這邊設定
//                ]
//            ],
//            [
//                // 一般參數這邊設定
//                'account_names' => '',
//            ],
//            [
//                'error_code' => 'OK',
//                'players' => null,
//            ]
//        );
//    }

    /**
     * @throws ApiCallerException
     */
    public function testGetBetHistories(): void
    {
        $this->catchException(
            '測試 返回玩家下注紀錄',
            [
                'post',
                'get_bet_histories',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
                'from_time' => Carbon::parse('2019-03-19 12:30:00')->toIso8601String(),
                'to_time' => Carbon::parse('2019-03-19 12:45:00')->toIso8601String(),
            ],
            [
                'error_code' => 'OK',
                'bet_histories' => null,
            ]
        );
    }

    /**
     * @throws ApiCallerException
     */
    public function testGetJackpotMeter(): void
    {
        $this->catchException(
            '測試 根據貨幣返回當前彩池累積獎金',
            [
                'post',
                'get_jackpot_meter',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
                'jackpot_id' => 1,
                'currency' => $this->sCurrency,
            ],
            [
                'error_code' => 'OK',
                'jackpot_meters' => null,
            ]
        );
    }

    /**
     * @throws ApiCallerException
     */
    public function testGetJackpotWins(): void
    {
        $this->catchException(
            '測試 根據貨幣返回請求時段的彩池獎金派獎記錄',
            [
                'post',
                'get_jackpot_wins',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
                'jackpot_id' => 1,
                'from_time' => Carbon::parse('2019-03-19 12:30:00')->toIso8601String(),
                'to_time' => Carbon::parse('2019-03-19 12:45:00')->toIso8601String(),
//                'currency' => $this->sCurrency,
            ],
            [
                'error_code' => 'OK',
                'jackpot_wins' => null,
                'currency' => null,
                'exchange_rates' => null,
            ]
        );
    }

    /**
     * @throws ApiCallerException
     */
    public function testRequestDemoPlay(): void
    {
        $this->catchException(
            '測試 建立指定遊戲 demo url',
            [
                'post',
                'request_demo_play',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
                'account_name' => $this->getPlayerAccount(),
                'game_id' => 1,
                'lang' => $this->sLang,
            ],
            [
                'error_code' => 'OK',
                'game_url' => null,
            ]
        );
    }

    /**
     * 查询转帐状态
     *
     * @throws ApiCallerException
     */
    public function testGetTransaction()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('查询转帐状态');

            $txId = str_random(32);

            // 先進行轉帳動做
            ApiCaller::make('ameba')->methodAction('post', 'deposit', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'action' => 'deposit',
                'account_name' => $this->getPlayerAccount(),
                'amount' => '1.11',
                'tx_id' => $txId
            ])->submit();

            sleep(1);

            // 再進行查帳動做
            $response = ApiCaller::make('ameba')->methodAction('post', 'get_transaction', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'action' => 'get_transaction',
                'type' => 'deposit',
                'tx_id' => $txId
            ])->submit();

            $response = array_get($response, 'response');
            $this->assertArrayHasKey('state', $response);
            $this->assertEquals('completed', array_get($response, 'state'));
            $this->assertEquals('OK', array_get($response, 'error_code'));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * @throws ApiCallerException
     */
//    public function testGetGameHistoryUrl(): void
//    {
//        $this->catchException(
//            '測試 生成帶訪問權限的遊戲紀錄連結',
//            [
//                'post',
//                'get_game_history_url',
//                [
//                    // 路由參數這邊設定
//                ]
//            ],
//            [
//                // 一般參數這邊設定
//                'account_name' => $this->getPlayerAccount(),
//                'game_id' => 1,
//                'round_id' => '',
//                'lang' => $this->sLang,
//            ],
//            [
//                'error_code' => 'OK',
//                'game_history_url' => null,
//            ]
//        );
//    }

    /**
     * @param string $sDescriptionMessage
     * @param array $aMethodActionParams
     * @param array $aRequestParams
     * @param array $aResponseExpectKeyValuePairs
     * @throws ApiCallerException
     */
    private function catchException(string $sDescriptionMessage, array $aMethodActionParams, array $aRequestParams, array $aResponseExpectKeyValuePairs): void
    {
        try {
            // 顯示測試案例描述
            $this->console->writeln($sDescriptionMessage);

            // Act
            $aResponseFormatData = ApiCaller::make($this->sStation)->methodAction(
                $aMethodActionParams[0],
                $aMethodActionParams[1],
                $aMethodActionParams[2]
            )->params(
                $aRequestParams
            )->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($aResponseFormatData));
            $aResponseContentsData = $aResponseFormatData['response'];

            var_dump($aResponseContentsData);

            foreach ($aResponseExpectKeyValuePairs as $k => $v) {
                $this->assertArrayHasKey($k, $aResponseContentsData);

                if ($v !== null) {
                    $this->assertEquals($v, $aResponseContentsData[$k]);
                }
            }
        } catch (ApiCallerException $exception) {
            $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }
}