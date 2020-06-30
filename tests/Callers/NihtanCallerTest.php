<?php

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

/**
 * Class NihtanCallerTest
 */
class NihtanCallerTest extends BaseTestCase
{
    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        $this->markTestSkipped('Nihtan 尚未通知可呼叫 (沒有 .env 可配置)，暫時不測試');
    }

    /**
     * testApiSessionSuccess
     */
    public function testApiSessionSuccess()
    {
        try {
            $response = ApiCaller::make('nihtan')->methodAction('post', 'api/session')
                ->params([
                    'user_id' => 'gd499',
                    'user_name' => 'test',
                    'user_ip' => '172.105.230.115',
                    'currency' => 'TWD',
                    'lang' => 'en'
                ])->submit();

            $this->assertEquals('200', $response['http_code']);
            $this->assertArrayHasKey('token', $response['response']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln(json_encode($exception->response(), JSON_PRETTY_PRINT));
        }
    }

    /**
     * testApiGameListSuccess
     */
    public function testApiGameListSuccess()
    {
        try {
            $response = ApiCaller::make('nihtan')->methodAction('post', 'api/game/list')
                ->params([])
                ->submit();

            $this->assertEquals('200', $response['http_code']);
            $this->assertEquals(['Sicbo', 'Poker', 'Dragon-Tiger', 'Baccarat'], array_keys($response['response']));
        } catch (ApiCallerException $exception) {
            $this->console->writeln(json_encode($exception->response(), JSON_PRETTY_PRINT));
        }
    }

    /**
     * testApiTransferCashIn
     */
    public function testApiTransferCashIn()
    {
        try {
            $response = ApiCaller::make('nihtan')->methodAction('post', 'api/transfer/cash-in')
                ->params([
                    'user_id' => '00009ffj',
                    'user_name' => 'vada65',
                    'user_ip' => '172.105.230.115',
                    'amount' => 5000,
                ])
                ->submit();
            $this->assertEquals('200', $response['http_code']);
            $this->assertEquals('ok', $response['response']['status']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln(json_encode($exception->response(), JSON_PRETTY_PRINT));
        }
    }

    /**
     * testApiTransferCahOut
     */
    public function testApiTransferCahOut()
    {
        try {
            $response = ApiCaller::make('nihtan')->methodAction('post', 'api/transfer/cash-out')
                ->params([
                    'user_id' => 'gd499',
                    'user_name' => 'test',
                    'user_ip' => '172.105.230.115',
                    'amount' => 1,
                ])
                ->submit();

            $this->assertEquals('200', $response['http_code']);
            $this->assertEquals('ok', $response['response']['status']);
        } catch (ApiCallerException $exception) {
            $this->console->writeln(json_encode($exception->response(), JSON_PRETTY_PRINT));
        }
    }

    /**
     * testUserBalanceCheck
     */
    public function testUserBalanceCheck()
    {
        try {
            $response = ApiCaller::make('nihtan')->methodAction('post', 'user/holding')
                ->params([
                    'user_id' => '00009ffj',
                    'user_name' => 'vada65',
                ])
                ->submit();
            $this->assertEquals('200', $response['http_code']);
            $this->assertNotNull($response['response'][0]);
        } catch (ApiCallerException $exception) {
            $this->console->writeln(json_encode($exception->response(), JSON_PRETTY_PRINT));
        }
    }
}