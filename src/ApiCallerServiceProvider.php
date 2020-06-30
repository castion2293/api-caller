<?php

namespace SuperPlatform\ApiCaller;

use Illuminate\Support\ServiceProvider;

class ApiCallerServiceProvider extends ServiceProvider
{
    /**`
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // 合併套件設定檔
        $this->mergeConfigFrom(__DIR__ . '/../config/api_caller.php', 'api_caller');
        $this->mergeConfigFrom(__DIR__ . '/../config/api_caller_bridge.php', 'api_caller_bridge');
        $this->mergeConfigFrom(__DIR__ . '/../config/api_caller_category.php', 'api_caller_category');
        $this->mergeConfigFrom(__DIR__ . '/../config/api_caller_report_game_scope.php', 'api_caller_report_game_scope');

        $this->publishes([
            __DIR__ . '/../config/api_caller.php'
            => config_path('api_caller.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../config/api_caller_category.php'
            => config_path('api_caller_category.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../config/api_caller_report_game_scope.php'
            => config_path('api_caller_report_game_scope.php'),
        ]);

        // include helpers after 合併套件設定檔
        $this->includeHelpers();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('api_caller', 'SuperPlatform\ApiCaller\ApiCaller');
        $loader->alias('api_poke', 'SuperPlatform\ApiCaller\ApiPoke');
    }

    /**
     * include helpers
     */
    protected function includeHelpers()
    {
        $file = __DIR__ . '/Helpers/CurrencyHelper.php';
        if (file_exists($file)) {
            require_once($file);
        }

    }
}