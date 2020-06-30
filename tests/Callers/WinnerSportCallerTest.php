<?php

namespace SuperPlatform\ApiCaller\Tests\Callers;

use BaseTestCase;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use SuperPlatform\ApiCaller\Callers\WinnerSportCaller;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

class WinnerSportCallerTest extends BaseTestCase
{
    /* @var WinnerSportCaller */
    private $oCaller;
    private $sStation = WinnerSportCaller::STATION_NAME;

    public function setUp(): void
    {
        parent::setUp();

        $this->setPlayerAccount('troy');
        $this->setPlayerPassword('troy');
        $this->console->write($this->sStation);
        $this->oCaller = ApiCaller::make($this->sStation);
    }

    /**
     * @throws GuzzleException
     */
    public function testCreateMember(): void
    {
        $this->generateTest(
            '測試 新增會員帳號',
            [
                'POST',
                'Create_Member',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
                'username' => $this->getPlayerAccount(),
                'alias' => $this->getPlayerAccount(),
                'currency' => 1,
                'istest' => 2,
                'top' => config("api_caller.$this->sStation.config.top_account"),
            ],
            function ($aResponseContentsData) {
                if ($aResponseContentsData['code'] === '002') $this->markTestSkipped('帳號重複，略過測試。');

                $this->assertEquals('001', $aResponseContentsData['code']);
            }
        );
    }

    /**
     * @throws GuzzleException
     */
    public function testMemberLogin(): void
    {
        $this->generateTest(
            '測試 帳號登入',
            [
                'POST',
                'Member_Login',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
                'username' => $this->getPlayerAccount(),
                'slangx' => 'zh-cn',
                'mobile' => 1,
            ],
            function ($aResponseContentsData) {
                $this->assertEquals('001', $aResponseContentsData['code']);
            }
        );
    }

    /**
     * @throws GuzzleException
     */
    public function testMemberEdit(): void
    {
        $this->generateTest(
            '測試 修改會員帳號',
            [
                'POST',
                'Member_Edit',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
                'username' => $this->getPlayerAccount(),
                'alias' => $this->getPlayerAccount(),
//                'istest' => 2,
                'top' => config("api_caller.$this->sStation.config.top_account"),
                'status' => 1,
            ],
            function ($aResponseContentsData) {
                $this->assertEquals('001', $aResponseContentsData['code']);
            }
        );
    }

    /**
     * @throws GuzzleException
     */
    public function testMemberMoney(): void
    {
        $this->generateTest(
            '測試 檢查點數',
            [
                'POST',
                'Member_Money',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
                'username' => $this->getPlayerAccount(),
            ],
            function ($aResponseContentsData) {
                $this->assertEquals('001', $aResponseContentsData['code']);
            }
        );
    }

    /**
     * @throws GuzzleException
     */
    public function testTransferMoneyDeposit(): void
    {
        $this->generateTest(
            '測試 存款',
            [
                'POST',
                'Transfer_Money',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
                'username' => $this->getPlayerAccount(),
                'money' => 500,
            ],
            function ($aResponseContentsData) {
                $this->assertEquals('001', $aResponseContentsData['code']);
            }
        );
    }

    /**
     * @throws GuzzleException
     */
    public function testTransferMoneyWithdraw(): void
    {
        $this->generateTest(
            '測試 提款',
            [
                'POST',
                'Transfer_Money',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
                'username' => $this->getPlayerAccount(),
                'money' => -500,
            ],
            function ($aResponseContentsData) {
                $this->assertEquals('001', $aResponseContentsData['code']);
            }
        );
    }

    /**
     * @throws GuzzleException
     */
    public function testTransferCheck(): void
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('查询转帐状态');

            // 先進行轉帳動做
            $billNo = mt_rand();

            ApiCaller::make('winner_sport')->methodAction('post', 'Transfer_Money', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'username' => $this->getPlayerAccount(),
                'money' => 1.11,
                'billno' => $billNo
            ])->submit();

            sleep(1);

            // 再進行查帳動做
            $response = ApiCaller::make('winner_sport')->methodAction('post', 'Transfer_Check', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'username' => $this->getPlayerAccount(),
                'billno' => $billNo
            ])->submit();

            $response = array_get($response, 'response');
            $this->assertArrayHasKey('code', $response);
            $this->assertEquals('001', array_get($response, 'code'));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * @throws GuzzleException
     */
    public function testMinusMoney(): void
    {
        $this->markTestSkipped('略過測試');
        $this->generateTest(
            '測試 抓取所有額度為負數的會員帳號',
            [
                'POST',
                'Minus_Money',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
            ],
            function ($aResponseContentsData) {
                $this->assertEquals('001', $aResponseContentsData['code']);
            }
        );
    }

    /**
     * @throws GuzzleException
     */
    public function testZeroMoney(): void
    {
        $this->markTestSkipped('略過測試');
        $this->generateTest(
            '測試 負額度會員的額度歸零',
            [
                'POST',
                'Zero_Money',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
                'username' => $this->getPlayerAccount(),
            ],
            function ($aResponseContentsData) {
                $this->assertEquals('001', $aResponseContentsData['code']);
            }
        );
    }

    /**
     * @throws GuzzleException
     */
    public function testGetTix(): void
    {
        $this->generateTest(
            '測試 抓取注單',
            [
                'POST',
                'Get_Tix',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
//                'agent' => '',
//                'maxModId' => 0,
                'checked' => 0,
            ],
            function ($aResponseContentsData) {
                $this->assertEquals('001', $aResponseContentsData['code']); // 001 成功 002 無任何紀錄
            }
        );
    }

    /**
     * @throws GuzzleException
     */
    public function testFindTix1(): void
    {
        $this->markTestSkipped('略過測試');
        $this->generateTest(
            '測試 查詢注單1',
            [
                'POST',
                'Find_Tix1',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
                'orderdate' => '',
//                'agent' => '',
//                'page' => 1,
            ],
            function ($aResponseContentsData) {
                $this->assertEquals('001', $aResponseContentsData['code']);
            }
        );
    }

    /**
     * @throws GuzzleException
     */
    public function testFindTix2(): void
    {
        $this->generateTest(
            '測試 查詢注單2',
            [
                'POST',
                'Find_Tix2',
                [
                    // 路由參數這邊設定
                ]
            ],
            [
                // 一般參數這邊設定
                'sdate' => Carbon::parse('2019-06-26 17:00:00')->toDateTimeString(),
                'edate' => Carbon::parse('2019-06-26 18:00:00')->toDateTimeString(),
//                'agent' => config("api_caller.$this->sStation.config.top_account"),
                'page' => 1,
            ],
            function ($aResponseContentsData) {
                $this->assertEquals('001', $aResponseContentsData['code']); // 001 成功 002 無任何紀錄
            }
        );
    }

    /**
     * @param string $sDescriptionMessage
     * @param array $aMethodActionParams
     * @param array $aRequestParams
     * @param callable $cTestCase
     * @throws GuzzleException
     * @throws Exception
     */
    private function generateTest(string $sDescriptionMessage, array $aMethodActionParams, array $aRequestParams, callable $cTestCase): void
    {
        try {
            $this->console->writeln($sDescriptionMessage);

            $aResponseFormatData = $this->oCaller->methodAction(
                $aMethodActionParams[0],
                $aMethodActionParams[1],
                $aMethodActionParams[2]
            )->params(
                $aRequestParams
            )->submit();

            $this->assertEquals($this->responseShouldHaveKeys, array_keys($aResponseFormatData));
            $aResponseContentsData = $aResponseFormatData['response'];

            var_dump($aResponseContentsData);

            $cTestCase($aResponseContentsData);
        } catch (ApiCallerException $e) {
            $this->consoleOutputArray($e->response());
            $this->fail('測試出錯，結束測試。');
        }
    }
}