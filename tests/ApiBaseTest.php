<?php

use SuperPlatform\ApiCaller\Facades\ApiCaller;

/**
 * Class ApiBaseTest
 */
class ApiBaseTest extends BaseTestCase
{
    /**
     * 測試創建不存在的 Caller
     */
    public function testCallNotExistCaller()
    {
        $this->console->writeln('測試創建不存在的 Caller');

        // Act
        $this->expectException('Error');
        ApiCaller::make('make_api_caller_with_a_wrong_name');
    }
}