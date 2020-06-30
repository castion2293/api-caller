<?php

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

/**
 * Class BingoCallerTest
 */
class BingoCallerTest extends BaseTestCase
{
    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        // 此會員資訊可以透過 api 取回，或登入代理後台查看
        // 此會員帳號是固定提供給測試用
        $this->setPlayerAccount(config('api_caller.bingo.config.test_account'));
        $this->setPlayerPassword(config('api_caller.bingo.config.test_password'));

        // 提示測試中的 caller 是哪一個
        $this->console->write('Bingo ');
    }

    /**
     * 測試取得會員資料
     *
     * @throws ApiCallerException
     */
    public function testGetMemberProfile()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得會員資料');

            // Act
            $response = ApiCaller::make('bingo')->methodAction('get', 'players/{playerId}', [
                // 路由參數這邊設定
                'playerId' => $this->getPlayerAccount()
            ])->params([
                // 一般參數這邊設定
            ])->submit();

            // Assert
            $this->assertEquals($this->getPlayerAccount(), $response['response']['account']);
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

            // Act
            $this->expectException('SuperPlatform\ApiCaller\Exceptions\ApiCallerException');
            ApiCaller::make('bingo')->methodAction('post', 'players', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'account' => $this->getPlayerAccount(),
                'password' => $this->getPlayerPassword(),
                'password_confirmation' => $this->getPlayerPassword(),
                'name' => 'mame',
            ])->submit();
        } catch (ApiCallerException $exception) {
            $this->assertEquals('422', $exception->response()['errorCode']);
            throw $exception;
        }
    }

    /**
     * 測試產生玩家遊戲連結
     *
     * @throws ApiCallerException
     */
    public function testGetPlayUrl()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試產生玩家遊戲連結');

            // Act
            $response = ApiCaller::make('bingo')->methodAction('post', 'players/{playerId}/play-url', [
                // 路由參數這邊設定
                'playerId' => $this->getPlayerAccount()
            ])->params([
                // 一般參數這邊設定
            ])->submit();

            // Assert
            $this->assertArrayHasKey('play_url', $response['response']);
            $this->assertArrayHasKey('mobile_url', $response['response']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得注單紀錄
     *
     * @throws ApiCallerException
     */
    public function testGetTickets()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得注單紀錄');

            $response = ApiCaller::make('bingo')->methodAction('get', 'tickets', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'playerId' => $this->getPlayerAccount()
            ])->submit();

            // Assert
            $this->assertArrayHasKey('data', $response['response']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得設定會員限額
     *
     * @throws ApiCallerException
     */
    public function testGetLimit()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得設定會員限額');

            $response = ApiCaller::make('bingo')->methodAction('PATCH', 'ticket-limits/{playerId}', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'playerId' => $this->getPlayerAccount(),
                'ticket_limits' => [
                    //一般玩法：單、雙、平，可設定: bet_max(上限)，bet_min(下限)
                    'normal_odd_even_draw' => [
                        'bet_min' => 25,
                        'bet_max' => 20000
                    ],
                    //一般玩法：大、小、合，可設定: bet_max(上限)，bet_min(下限)
                    'normal_big_small_tie' => [
                        'bet_min' => 25,
                        'bet_max' => 20000
                    ],
                    //超級玩法(特別號)：大、小，可設定: bet_max(上限)，bet_min(下限)
                    'super_big_small' => [
                        'bet_min' => 25,
                        'bet_max' => 20000
                    ],
                    //超級玩法(特別號)：單、雙，可設定: bet_max(上限)，bet_min(下限)
                    'super_odd_even' => [
                        'bet_min' => 25,
                        'bet_max' => 20000
                    ],
                    //超級玩法(特別號)：獨猜，可設定: bet_max(上限)，bet_min(下限)
                    'super_guess' => [
                        'bet_min' => 25,
                        'bet_max' => 5000
                    ],
                    //星號，可設定: bet_max(上限)，bet_min(下限)
                    'star' => [
                        'bet_min' => 25,
                        'bet_max' => 3000
                    ],
                    //五行，可設定: bet_max(上限)，bet_min(下限)
                    'elements' => [
                        'bet_min' => 25,
                        'bet_max' => 300
                    ],
                    //四季，可設定: bet_max(上限)，bet_min(下限)
                    'seasons' => [
                        'bet_min' => 25,
                        'bet_max' => 300
                    ],
                    //不出球，可設定: bet_max(上限)，bet_min(下限)
                    'other_fanbodan' => [
                        'bet_min' => 25,
                        'bet_max' => 300
                    ],
                ],
            ])->submit();

            // Assert
            $this->assertArrayHasKey('data', $response['response']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        }
    }
}
