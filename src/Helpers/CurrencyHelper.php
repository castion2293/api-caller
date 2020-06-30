<?php

if (!function_exists('currency_multiply_transfer')) {

    /**
     * "乘" 上幣別對應比例, 若無設定env對應之幣別比例. 預設為台幣 1倍
     * 『轉出點數』『取得餘額』『轉換原生注單』時使用
     * @param String $station
     * @param $number
     * @return float|int
     */
    function currency_multiply_transfer(String $station, $number)
    {
        $currency = config("api_caller.{$station}.config.currency", 'TWD');
        $rate = config("api_caller.{$station}.rate.{$currency}", 1);

        return $number * $rate;
    }
}

if (!function_exists('currency_divide_transfer')) {

    /**
     * "除" 上幣別對應比例, 若無設定env對應之幣別比例. 預設為台幣 1倍
     * 『轉入點數』時使用
     * @param String $station
     * @param $number
     * @return float|int
     */
    function currency_divide_transfer(String $station, $number)
    {
        $currency = config("api_caller.{$station}.config.currency");
        $rate = config("api_caller.{$station}.rate.{$currency}", 1);

        return $number / $rate;
    }
}