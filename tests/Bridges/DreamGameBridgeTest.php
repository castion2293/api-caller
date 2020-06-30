<?php

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Exceptions\BridgeActionParamsException;

/**
 * Class DreamGameBridgeTest
 *
 * 這是用來測試橋接方法是否可用
 */
class DreamGameBridgeTest extends BaseTestCase
{
    /**
     * @var string lang 填入簡寫
     *
     *  代號    簡寫     描述
     *  0       en      英文
     *  1       cn      中文简体
     *  2       tw      中文繁体
     *  3       kr      韩语
     *  4       my      缅甸语
     *  5       th      泰语
     */
    protected $language;

    /**
     * 初始化
     */
    public function setUp()
    {
        parent::setUp();

        // 測試資料
        $this->station = 'dream_game';
        $this->agent = config('api_caller.dream_game.config.api_agent');
        $this->playerAccount = config('api_caller.dream_game.config.test_account');
        $this->playerPassword = config('api_caller.dream_game.config.test_password');
        $this->language = 'tw';

        // 提示測試中的 bridge 是哪一個
        $this->console->write('Dream Game Bridge Test ');
    }

    /**
     * 測試取得遊戲館登入通行證
     *
     * @throws ApiCallerException
     * @throws Exception
     */
    public function testGetPassportThroughBridgeAction()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得遊戲館登入通行證');

            // Arrange
            // 橋接方法
            $bridgeAction = 'passport';
            // 橋接參數
            $params = [
                'form_params' => [
                    'language' => $this->language,
                    'member' => [
                        'username' => $this->playerAccount,
                        'password' => $this->playerPassword,
                    ],
                ],
                'route_params' => [
                    'agent' => $this->agent,
                ],
            ];

            // act
            $response = $this->$bridgeAction($this->station, $params);

            // assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        } catch (BridgeActionParamsException $exception) {
            // $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得會員餘額
     *
     * @throws ApiCallerException
     * @throws BridgeActionParamsException
     */
    public function testGetBalanceThroughBridgeAction()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得會員餘額');

            // Arrange
            // 橋接方法
            $bridgeAction = 'getBalance';
            // 沙龍橋接參數
            $params = [
                'form_params' => [
                    'member' => [
                        'username' => $this->playerAccount,
                    ],
                ],
                'route_params' => [
                    'agent' => $this->agent,
                ],
            ];

            // act
            $response = $this->$bridgeAction($this->station, $params);

            // assert
            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
            $response = $response['response'];
            $this->assertArrayHasKey('codeId', $response);
            $this->assertEquals(0, $response['codeId']);
            $this->assertArrayHasKey('member', $response);
            $this->assertArrayHasKey('balance', $response['member']);
        } catch (ApiCallerException $exception) {
            // $this->consoleOutputArray($exception->response());
            throw $exception;
        } catch (BridgeActionParamsException $exception) {
            // $this->console->writeln($exception->response());
            throw $exception;
        }
    }
}