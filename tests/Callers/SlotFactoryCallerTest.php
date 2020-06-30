<?php

namespace SuperPlatform\ApiCaller\Tests\Callers;

use BaseTestCase;
use Carbon\Carbon;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Facades\ApiCaller;

class SlotFactoryCallerTest extends BaseTestCase
{

    /**
     * 測試上層代理帳號
     *
     * @var string
     */
    protected $testAgent = '';

    /**
     * 測試帳號
     *
     * @var string
     */
    protected $testAccount = '';

    /**
     * 初始共用參數
     */
    public function setUp()
    {
        parent::setUp();

        $this->testAgent = config('api_caller.slot_factory.config.customer_name');
        $this->testAccount = config('api_caller.slot_factory.config.test_member_account');

        // 提示測試中的 caller 是哪一個
        $this->console->write('Slot Factory');
    }

    /**
     * 測試取得玩家注單資訊 Player Report
     *
     * @throws ApiCallerException
     */
    public function testPlayReportRequestForPlayerReport()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得玩家注單資訊 Player Report');

            // Act
            $response = ApiCaller::make('slot_factory')->methodAction('post', 'playreport', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'ReportType' => 'PlayerReport',
                'AccountID' => $this->testAccount,
                'LicenseeName' => $this->testAgent,
                'From' => Carbon::parse('2019-12-30 00:00:00')->timezone('Europe/London')->toDateTimeString(),
                'To' => Carbon::parse('2019-12-31 00:00:00')->timezone('Europe/London')->toDateTimeString(),
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertArrayHasKey('SpinReport', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }

    /**
     * 測試取得玩家注單資訊 Licensee Report
     *
     * @throws ApiCallerException
     */
    public function testPlayReportRequestForLicenseeReport()
    {
        // 捕捉 api 訪問例外
        try {
            // 顯示測試案例描述
            $this->console->writeln('測試取得玩家注單資訊 Player Report');

            // Act
            $response = ApiCaller::make('slot_factory')->methodAction('post', 'playreport', [
                // 路由參數這邊設定
            ])->params([
                // 一般參數這邊設定
                'ReportType' => 'LicenseeReport',
                'LicenseeName' => $this->testAgent,
                'From' => Carbon::parse('2019-12-30 00:00:00')->timezone('Europe/London')->toDateTimeString(),
                'To' => Carbon::parse('2019-12-31 00:00:00')->timezone('Europe/London')->toDateTimeString(),
            ])->submit();

            $response = $response['response'];
            dump($response);
            $this->assertArrayHasKey('SpinReport', $response);
        } catch (ApiCallerException $exception) {
            $this->console->writeln($exception->response());
            throw $exception;
        }
    }
}