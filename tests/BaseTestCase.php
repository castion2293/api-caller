<?php

use Orchestra\Testbench\TestCase;
use SuperPlatform\ApiCaller\Facades\ApiCaller;
use Symfony\Component\Console\Output\ConsoleOutput;

class BaseTestCase extends TestCase
{
    /**
     * @var \Faker\Factory 假資料產生器
     */
    protected $faker;

    /**
     * @var ConsoleOutput 終端器輸出器
     */
    protected $console;

    /**
     * @var string
     */
    protected $station;

    /**
     * @var string
     */
    protected $agent;

    /**
     * @var string 為了測試登入所需要的真實存在會員帳號
     */
    protected $playerAccount;

    /**
     * @var string 為了測試登入所需要的真實存在會員密碼
     */
    protected $playerPassword;

    /**
     * @var array 經過轉換的 guzzle http response 應該有的索引值
     */
    protected $responseShouldHaveKeys;

    /**
     * setUp
     */
    public function setUp()
    {
        parent::setUp();

        $this->console = new ConsoleOutput();
        $this->faker = \Faker\Factory::create();

        // 經過轉換的 guzzle http response 應該有的索引值
        $this->responseShouldHaveKeys = ['http_code', 'http_contents', 'http_headers', 'response'];
    }

    /**
     * 測試時的 Package Providers 設定
     *
     *  ( 等同於原 laravel 設定 config/app.php 的 Autoloaded Service Providers )
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            SuperPlatform\ApiCaller\ApiCallerServiceProvider::class
        ];
    }

    /**
     * 測試時的 Class Aliases 設定
     *
     * ( 等同於原 laravel 中設定 config/app.php 的 Class Aliases )
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [

        ];
    }

    /**
     * 測試時的時區設定
     *
     * ( 等同於原 laravel 中設定 config/app.php 的 Application Timezone )
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return string|null
     */
    protected function getApplicationTimezone($app)
    {
        return 'Asia/Taipei';
    }

    /**
     * 測試時使用的 HTTP Kernel
     *
     * ( 等同於原 laravel 中 app/HTTP/kernel.php )
     * ( 若需要用自訂時，把 Orchestra\Testbench\Http\Kernel 改成自己的 )
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton(
            'Illuminate\Contracts\Http\Kernel',
            'Orchestra\Testbench\Http\Kernel'
        );
    }

    /**
     * 測試時使用的 Console Kernel
     *
     * ( 等同於原 laravel 中 app/Console/kernel.php )
     * ( 若需要用自訂時，把 Orchestra\Testbench\Console\Kernel 改成自己的 )
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function resolveApplicationConsoleKernel($app)
    {
        $app->singleton(
            'Illuminate\Contracts\Console\Kernel',
            'Orchestra\Testbench\Console\Kernel'
        );
    }

    /**
     * 測試時的環境設定
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        // 若有環境變數檔案，嘗試著讀取使用
        if (file_exists(dirname(__DIR__) . '/.env')) {
            $dotenv = new Dotenv\Dotenv(dirname(__DIR__));
            $dotenv->load();
        }
    }

    /**
     * 設定玩家帳號
     */
    public function setPlayerAccount($playerAccount)
    {
        $this->playerAccount = strtolower($playerAccount);
    }

    /**
     * 取得玩家帳號
     */
    public function getPlayerAccount()
    {
        return $this->playerAccount;
    }

    /**
     * 設定玩家密碼
     */
    public function setPlayerPassword($playerPassword)
    {
        $this->playerPassword = strtolower($playerPassword);
    }

    /**
     * 取得玩家密碼
     */
    public function getPlayerPassword()
    {
        return $this->playerPassword;
    }

    /**
     * Json Format
     *
     * 可以用這樣的方式輸出陣列參數到 terminal 中，並排版:
     *
     *    $this->consoleOutputArray([
     *        'method' => 'POST',
     *        'uri' => '/api/login',
     *        'data' => [
     *            'user_identify' => $this->getTestUsername(),
     *            'password' => $this->getTestUserPassword(),
     *        ],
     *    ]);
     *
     * @param array $array
     * @param bool $disablePretty
     * @return void
     */
    public function consoleOutputArray(array $array, $disablePretty = false)
    {
        if (!$disablePretty) {
            $this->console->writeln(json_encode(
                $array,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            ));
        } else {
            $this->console->writeln(json_encode($array));
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
}

