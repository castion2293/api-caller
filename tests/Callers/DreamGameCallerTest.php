<?php

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

/**
 * Class DreamGameCallerTest
 */
class DreamGameCallerTest extends BaseTestCase
{
    /**
     * @var string 測試用語系
     */
    private $lang;

    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        // 此會員資訊可以透過 api 取回，或登入代理後台查看
        // 此會員帳號是固定提供給測試用
        $this->setPlayerAccount(config('api_caller.dream_game.config.test_account'));
        $this->setPlayerPassword(config('api_caller.dream_game.config.test_password'));
        $this->agent = config('api_caller.dream_game.config.api_agent');
        $this->station = 'dream_game';
        $this->lang = 'en';

        // 提示測試中的 caller 是哪一個
        $this->console->write('Dream Game ');
    }

    /**
     * 測試建立一個已存在會員，並回應帳號已占用
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
            $this->console->writeln('測試建立一個已存在會員，並回應帳號已占用');

            // Act
            $this->expectException('SuperPlatform\ApiCaller\Exceptions\ApiCallerException');
            ApiCaller::make($this->station)->methodAction('post', 'user/signup/{agent}', [
                // 路由參數這邊設定
                'agent' => $this->agent
            ])->params([
                // 一般參數這邊設定
                /**
                 * data is 限紅 TWD
                 *   A 100-250000
                 *   B 50-5000
                 *   C 50-10000
                 *   D 100-10000
                 *   E 100-20000
                 *   F 100-50000
                 *   G 100-100000
                 */
                'data' => 'A',
                /**
                 * json
                 * {
                 *    属性名        属性类型     属性说明
                 *    ----------- require -----------
                 *    username     String      会员登入账号
                 *    password     String      会员密码（MD5）
                 *    currency     String      会员货币简称
                 *    winLimit     Double      会员当天最大可赢取金额[仅统计当天下注], < 1表示无限制
                 *    ----------- optional -----------
                 *    status       Integer     会员状态：0:停用, 1:正常, 2:锁定(不能下注) (optional: default 1)
                 *    balance      Double      会员余额 (optional: default 0)
                 * }
                 */
                'member' => [
                    'username' => $this->getPlayerAccount(),
                    'password' => $this->getPlayerPassword(),
                    'currencyName' => 'TWD',
                    'winLimit' => 1000
                ],
            ])->submit();
        } catch (ApiCallerException $exception) {
            $this->assertEquals(116, $exception->response()['errorCode']);
            throw $exception;
        }
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
            $response = ApiCaller::make($this->station)->methodAction('post', 'user/login/{agent}', [
                // 路由參數這邊設定
                'agent' => $this->agent
            ])->params([
                // 一般參數這邊設定
                'lang' => $this->lang,
                'member' => [
                    'username' => $this->getPlayerAccount(),
                    'password' => $this->getPlayerPassword(),
                ],
            ])->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $response = $response['response'];
            $this->assertArrayHasKey('codeId', $response);
            // 返回的數據僅當 codeId = 0 時 token 為有效 token
            $this->assertEquals(0, $response['codeId']);
            $this->assertArrayHasKey('token', $response);
            $this->assertArrayHasKey('lang', $response);
            $this->assertArrayHasKey('random', $response);
            /**
             * 登入地址類型：
             *
             * "list":["flash 登入地址","wap 登入地址","直接打开APP地址"]
             *   array(3) {
             *     // wap (Flash) 登入地址
             *     0 => "https://a.2023168.com/?token="
             *     // wap (H5) 登入地址
             *     1 => "https://dg-asia.lyhfzb.com/wap/index.html?token="
             *     // APP 地址
             *     2 => "http://f.wechat668.com/download/cn.html?t="
             *   }
             * ---------
             * 1.返回的數據僅當 codeId = 0 時 token 為有效 token,
             * 2.進入游戲地址為游戲地址加上 token, 例如:
             *    PC 瀏覽器進入游戲: list[0] + token
             *    手機瀏覽器進入游戲: list[1] + token + &language=lang
             */
            $this->console->write('PC Flash: ');
            $this->console->writeln($response['list'][0] . $response['token']);
            $this->console->write('PC H5: ');
            $this->console->writeln($response['list'][1] . $response['token'] . '&language=tw');
            $this->console->write('APP: ');
            $this->console->writeln($response['list'][2] . $response['token']);
            $this->assertArrayHasKey('list', $response);
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
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得餘額，取得成功');

            // Act
            $response = ApiCaller::make($this->station)->methodAction('post', 'user/getBalance/{agent}', [
                // 路由參數這邊設定
                'agent' => $this->agent
            ])->params([
                // 一般參數這邊設定
                'member' => [
                    'username' => $this->getPlayerAccount(),
                ],
            ])->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $response = $response['response'];
            $this->assertArrayHasKey('codeId', $response);
            $this->assertEquals(0, $response['codeId']);
            $this->assertArrayHasKey('member', $response);
            $this->assertArrayHasKey('balance', $response['member']);
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
    public function testTransferSuccess()
    {
        // try 嘗試捕捉 ApiCallerException 輸出錯誤訊息幫助測試階段排除錯誤
        try {
            // Arrange
            $amount = 1;

            // Act
            // 增加餘額
            $response = ApiCaller::make($this->station)->methodAction('post', 'account/transfer/{agent}', [
                // 路由參數這邊設定
                'agent' => $this->agent
            ])->params([
                // 一般參數這邊設定
                // 轉帳流水號
                'data' => mt_rand(),
                'member' => [
                    'username' => $this->getPlayerAccount(),
                    'amount' => $amount,
                ],
            ])->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $response = $response['response'];
            $this->assertArrayHasKey('codeId', $response);
            $this->assertEquals(0, $response['codeId']);
            $this->assertArrayHasKey('member', $response);
            $this->assertArrayHasKey('balance', $response['member']);
        } catch (ApiCallerException $exception) {
            $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取回注單且成功
     *
     * 備註：
     *   兩次請求間隔最小為10秒鐘
     *   單次查詢最大數據量1000條
     *   抓取的單有可能有上次已經抓取過的抓單
     *
     * @throws ApiCallerException
     */
    public function testGetReport()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取回注單且成功');

            // Act
            $response = ApiCaller::make($this->station)->methodAction('post', 'game/getReport/{agent}', [
                // 路由參數這邊設定
                'agent' => $this->agent
            ])->params([
                // 一般參數這邊設定
            ])->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $response = $response['response'];
            $this->assertArrayHasKey('codeId', $response);
            $this->assertEquals(0, $response['codeId']);
            $this->assertArrayHasKey('list', $response);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試依照時間撈取注單
     *
     * @throws ApiCallerException
     */
    public function testGetReportByTime()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試依照時間撈取注單');

            // Act
            $response = ApiCaller::make($this->station)->methodAction('post', 'game/getReport/', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'beginTime' => '2019-11-14 00:00:00',
                'endTime' => '2019-11-14 23:59:59',
            ])->submit();

            $response = $response['response'];
            $this->assertArrayHasKey('data', $response);
            // Assert
        } catch (ApiCallerException $exception) {
            throw $exception->response();
        }
    }

    /**
     * 測試修改會員資料成功
     *
     * 備註：
     *   兩次請求間隔最小為10秒鐘
     *   單次查詢最大數據量1000條
     *   抓取的單有可能有上次已經抓取過的抓單
     *
     * @throws ApiCallerException
     */
    public function testUpdateProfileSuccess()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試修改會員資料成功');

            // Act
            $response = ApiCaller::make($this->station)->methodAction('post', 'user/update/{agent}', [
                // 路由參數這邊設定
                'agent' => $this->agent
            ])->params([
                // 一般參數這邊設定
                'member' => [
                    'username' => $this->getPlayerAccount(),
                    'password' => $this->getPlayerPassword(),
                    'winLimit' => 0,
                    'status' => 1,
                ],
            ])->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $response = $response['response'];
            $this->assertArrayHasKey('codeId', $response);
            $this->assertEquals(0, $response['codeId']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試修改會員限紅成功
     *
     * 備註：
     *   兩次請求間隔最小為10秒鐘
     *   單次查詢最大數據量1000條
     *   抓取的單有可能有上次已經抓取過的抓單
     *
     * @throws ApiCallerException
     */
    public function testUpdateLimit()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試修改會員限紅成功');

            // Act
            $response = ApiCaller::make($this->station)->methodAction('post', 'game/updateLimit/{agent}', [
                // 路由參數這邊設定
                'agent' => $this->agent
            ])->params([
                // 一般參數這邊設定
                /**
                 * data is 限紅 TWD
                 *   A 100-250000
                 *   B 50-5000
                 *   C 50-10000
                 *   D 100-10000
                 *   E 100-20000
                 *   F 100-50000
                 *   G 100-100000
                 */
                'data' => 'E',
                'member' => [
                    'username' => $this->getPlayerAccount(),
                ],
            ])->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $response = $response['response'];
            $this->assertArrayHasKey('codeId', $response);
            $this->assertEquals(0, $response['codeId']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試检查存取款操作是否成功
     *
     * @throws ApiCallerException
     */
    public function testAccountCheckTransfer()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試检查存取款操作是否成功');

            $transferNum = str_random(32);

            // 先進行轉帳動做
            ApiCaller::make($this->station)->methodAction('post', 'account/transfer/{agent}', [
                // 路由參數這邊設定
                'agent' => $this->agent
            ])->params([
                // 一般參數這邊設定
                // 轉帳流水號
                'data' => $transferNum,
                'member' => [
                    'username' => $this->getPlayerAccount(),
                    'amount' => 1.11,
                ],
            ])->submit();

            sleep(1);

            // 再進行查帳動做
            $response = ApiCaller::make($this->station)->methodAction('post', 'account/checkTransfer/{agent}', [
                // 路由參數這邊設定
                'agent' => $this->agent
            ])->params([
                // 一般參數這邊設定
                // 轉帳流水號
                'data' => $transferNum,
            ])->submit();

            $response = array_get($response, 'response');
            $this->assertArrayHasKey('codeId', $response);
            $this->assertEquals(0, array_get($response, 'codeId'));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試修改會員信息成功
     *
     * 備註：
     *   兩次請求間隔最小為10秒鐘
     *   單次查詢最大數據量1000條
     *   抓取的單有可能有上次已經抓取過的抓單
     *
     * @throws ApiCallerException
     */
    public function testUpdateWinningLimit()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試修改會員信息成功');

            // Act
            $response = ApiCaller::make($this->station)->methodAction('post', 'user/update/{agent}', [
                // 路由參數這邊設定
                'agent' => $this->agent
            ])->params([
                'member' => [
                    'username' => $this->getPlayerAccount(),
                    'password' => $this->getPlayerPassword(),
                    'winLimit' => config('api_caller.dream_game.config.api_member_winning_limit'),
                    'status' => 1
                ],
            ])->submit();

            // Assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $response = $response['response'];
            $this->assertArrayHasKey('codeId', $response);
            $this->assertEquals(0, $response['codeId']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }
}
