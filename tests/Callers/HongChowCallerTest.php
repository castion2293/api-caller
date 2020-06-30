<?php
//
//namespace Callers;
//
//use BaseTestCase;
//use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
//use SuperPlatform\ApiCaller\Facades\ApiCaller;
//
//class HongChowCallerTest extends BaseTestCase
//{
//    const TRANSFER_TYPE_IN = 1;
//    const TRANSFER_TYPE_OUT = 2;
//
//    /**
//     * 初始共用參數
//     */
//    public function setUp()
//    {
//        parent::setUp();
//
//        // 此會員資訊可以透過 api 取回，或登入代理後台查看
//        // 此會員帳號是固定提供給測試用
//        $this->setPlayerAccount(config('api_caller.hong_chow.config.backend_account'));
//        $this->setPlayerPassword(config('api_caller.hong_chow.config.backend_password'));
//
//        // 提示測試中的 caller 是哪一個
//        $this->console->write('hong_chow ');
//    }
//
//    /**
//     * 測試 登入 且登入成功
//     *
//     * @throws ApiCallerException
//     */
//    public function testLoginSuccess()
//    {
//        // 捕捉 api 訪問例外
//        try {
//            // 顯示測試案例描述
//            $this->console->writeln('測試 登入 且登入成功');
//
//            // Act
//            $response = ApiCaller::make('hong_chow')->methodAction('post', 'login', [
//                // 路由參數這邊設定
//            ])->params([
//                // 一般參數這邊設定
//                'username' => $this->getPlayerAccount(),
//            ])->submit();
//
//            // Assert
//            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
//            $response = $response['response'];
//            var_dump($response);
//            $this->assertArrayHasKey('code', $response);
//            $this->assertArrayHasKey('msg', $response);
//            $this->assertArrayHasKey('data', $response);
//            $this->assertArrayHasKey('pc_url', $response['data']);
//            $this->assertArrayHasKey('h5_url', $response['data']);
//            $this->assertArrayHasKey('token', $response['data']);
//            $this->assertEquals('0', $response['code']);
//        } catch (ApiCallerException $exception) {
//            $this->consoleOutputArray($exception->response());
//            throw $exception;
//        }
//    }
//
//    /**
//     * 測試 "試玩"登入 且登入成功
//     *
//     * @throws ApiCallerException
//     */
//    public function testLogintrialSuccess()
//    {
//        // 捕捉 api 訪問例外
//        try {
//            // 顯示測試案例描述
//            $this->console->writeln('測試 "試玩"登入 且登入成功');
//
//            // Act
//            $response = ApiCaller::make('hong_chow')->methodAction('post', 'logintrial', [
//                // 路由參數這邊設定
//            ])->params([
//                // 一般參數這邊設定
//                'username' => $this->getPlayerAccount(),
//            ])->submit();
//
//            // Assert
//            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
//            $response = $response['response'];
//            var_dump($response);
//            $this->assertArrayHasKey('code', $response);
//            $this->assertArrayHasKey('msg', $response);
//            $this->assertArrayHasKey('data', $response);
//            $this->assertArrayHasKey('pc_url', $response['data']);
//            $this->assertArrayHasKey('h5_url', $response['data']);
//            $this->assertArrayHasKey('token', $response['data']);
//            $this->assertEquals('0', $response['code']);
//        } catch (ApiCallerException $exception) {
//            $this->consoleOutputArray($exception->response());
//            throw $exception;
//        }
//    }
//
//    /**
//     * 測試 資金轉入
//     *
//     * @throws ApiCallerException
//     */
//    public function testTransferIn()
//    {
//        // 捕捉 api 訪問例外
//        try {
//            // 顯示測試案例描述
//            $this->console->writeln('測試 資金轉入');
//
//            // Act
//            $response = ApiCaller::make('hong_chow')->methodAction('post', 'transfer', [
//                // 路由參數這邊設定
//            ])->params([
//                // 一般參數這邊設定
//                'username' => $this->getPlayerAccount(),
//                'money' => 100,
//                'type' => self::TRANSFER_TYPE_IN,
//            ])->submit();
//
//            var_dump($response['response']['data']);
//
//            // Assert
//            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
//            $response = $response['response'];
//            $this->assertArrayHasKey('code', $response);
//            $this->assertArrayHasKey('msg', $response);
//            $this->assertArrayHasKey('data', $response);
//            $this->assertArrayHasKey('currentMoney', $response['data']);
//            $this->assertArrayHasKey('id', $response['data']);
//            $this->assertEquals('0', $response['code']);
//        } catch (ApiCallerException $exception) {
//            $this->consoleOutputArray($exception->response());
//            throw $exception;
//        }
//    }
//
//    /**
//     * 測試 資金轉出
//     *
//     * @throws ApiCallerException
//     */
//    public function testTransferOut()
//    {
//        // 捕捉 api 訪問例外
//        try {
//            // 顯示測試案例描述
//            $this->console->writeln('測試 資金轉出');
//
//            // Act
//            $response = ApiCaller::make('hong_chow')->methodAction('post', 'transfer', [
//                // 路由參數這邊設定
//            ])->params([
//                // 一般參數這邊設定
//                'username' => $this->getPlayerAccount(),
//                'money' => 100,
//                'type' => self::TRANSFER_TYPE_OUT,
//            ])->submit();
//
//            print_r($response['response']['data']['id']);
//
//            // Assert
//            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
//            $response = $response['response'];
//            $this->assertArrayHasKey('code', $response);
//            $this->assertArrayHasKey('msg', $response);
//            $this->assertArrayHasKey('data', $response);
//            $this->assertArrayHasKey('currentMoney', $response['data']);
//            $this->assertArrayHasKey('id', $response['data']);
//            $this->assertEquals('0', $response['code']);
//        } catch (ApiCallerException $exception) {
//            $this->consoleOutputArray($exception->response());
//            throw $exception;
//        }
//    }
//
//    /**
//     * 測試 資金轉帳狀態查詢
//     *
//     * @throws ApiCallerException
//     */
//    public function testTransferinfo()
//    {
//        // 捕捉 api 訪問例外
//        try {
//            // 顯示測試案例描述
//            $this->console->writeln('測試 資金轉帳狀態查詢');
//
//            // Act
//            $response = ApiCaller::make('hong_chow')->methodAction('post', 'transferinfo', [
//                // 路由參數這邊設定
//            ])->params([
//                // 一般參數這邊設定
//                'username' => $this->getPlayerAccount(),
//                'id' => 10521,
//            ])->submit();
//
//            print_r($response['response']);
//
//            // Assert
//            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
//            $response = $response['response'];
//            $this->assertArrayHasKey('code', $response);
//            $this->assertArrayHasKey('msg', $response);
//            $this->assertArrayHasKey('data', $response);
//            $this->assertArrayHasKey('money_log_id', $response['data']);
//            $this->assertArrayHasKey('user_id', $response['data']);
//            $this->assertArrayHasKey('agent_id', $response['data']);
//            $this->assertArrayHasKey('moneytype', $response['data']);
//            $this->assertArrayHasKey('beforebalance', $response['data']);
//            $this->assertArrayHasKey('balance', $response['data']);
//            $this->assertArrayHasKey('bet_id', $response['data']);
//            $this->assertArrayHasKey('amount', $response['data']);
//            $this->assertArrayHasKey('createtime', $response['data']);
//            $this->assertArrayHasKey('remark', $response['data']);
//            $this->assertArrayHasKey('create_user', $response['data']);
//            $this->assertEquals('0', $response['code']);
//        } catch (ApiCallerException $exception) {
//            $this->consoleOutputArray($exception->response());
//            throw $exception;
//        }
//    }
//
//    /**
//     * 測試 批量獲取會員轉帳紀錄
//     *
//     * @throws ApiCallerException
//     */
//    public function testTransferlist()
//    {
//        // 捕捉 api 訪問例外
//        try {
//            // 顯示測試案例描述
//            $this->console->writeln('測試 批量獲取會員轉帳紀錄');
//
//            // Act
//            $response = ApiCaller::make('hong_chow')->methodAction('post', 'transferlist', [
//                // 路由參數這邊設定
//            ])->params([
//                // 一般參數這邊設定
//                'username' => $this->getPlayerAccount(),
//                'startTime' => '2019-01-25 00:00:00',
//                'endTime' => '2019-01-26 00:00:00',
//            ])->submit();
//
//            print_r($response['response']);
//
//            // Assert
//            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
//            $response = $response['response'];
//            $this->assertArrayHasKey('code', $response);
//            $this->assertArrayHasKey('msg', $response);
//            $this->assertArrayHasKey('data', $response);
//            $this->assertEquals('0', $response['code']);
//        } catch (ApiCallerException $exception) {
//            $this->consoleOutputArray($exception->response());
//            throw $exception;
//        }
//    }
//
//    /**
//     * 測試 資金查詢
//     *
//     * @throws ApiCallerException
//     */
//    public function testBalance()
//    {
//        // 捕捉 api 訪問例外
//        try {
//            // 顯示測試案例描述
//            $this->console->writeln('測試 資金查詢');
//
//            // Act
//            $response = ApiCaller::make('hong_chow')->methodAction('post', 'balance', [
//                // 路由參數這邊設定
//            ])->params([
//                // 一般參數這邊設定
//                'username' => $this->getPlayerAccount(),
//            ])->submit();
//
//            print_r($response['response']);
//
//            // Assert
//            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
//            $response = $response['response'];
//            $this->assertArrayHasKey('code', $response);
//            $this->assertArrayHasKey('msg', $response);
//            $this->assertArrayHasKey('data', $response);
//            $this->assertArrayHasKey('balance', $response['data']);
//            $this->assertEquals('0', $response['code']);
//        } catch (ApiCallerException $exception) {
//            $this->consoleOutputArray($exception->response());
//            throw $exception;
//        }
//    }
//
//    /**
//     * 測試 注單拉取
//     *
//     * @throws ApiCallerException
//     */
//    public function testBets()
//    {
//        // 捕捉 api 訪問例外
//        try {
//            // 顯示測試案例描述
//            $this->console->writeln('測試 注單拉取');
//
//            // Act
//            $response = ApiCaller::make('hong_chow')->methodAction('post', 'bets', [
//                // 路由參數這邊設定
//            ])->params([
//                // 一般參數這邊設定
//                'start_sync_version' => 1,
//            ])->submit();
//
//            print_r($response['response']);
//
//            // Assert
//            $this->assertEquals($this->responseShouldHaveKeys, array_keys($response));
//            $response = $response['response'];
//            $this->assertArrayHasKey('code', $response);
//            $this->assertArrayHasKey('msg', $response);
//            $this->assertArrayHasKey('data', $response);
//            $this->assertArrayHasKey('next_sync_version', $response['data']);
//            $this->assertArrayHasKey('data', $response['data']);
//            $this->assertEquals('0', $response['code']);
//        } catch (ApiCallerException $exception) {
//            $this->consoleOutputArray($exception->response());
//            throw $exception;
//        }
//    }
//}