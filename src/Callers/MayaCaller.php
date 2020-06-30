<?php

namespace SuperPlatform\ApiCaller\Callers;

use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class MayaCaller extends Caller
{
    /**
     * 定義可用的方法與動作
     *
     * @var array
     */
    protected $enabledMethodActions = [
        'GET' => [
            'CreateMember',                          // 帳號 API - 建立會員
            'GetGameMemberID',                       // 帳號 API - 取得會員主键 ID
            'GetBalance',                            // 點數 API - 取得遊戲平台餘額
            'FundTransfer',                          // 點數 API - 遊戲平台進行轉帳(轉入/轉出)
            'CheckFundTransfer',                     // 點數 API - 檢查轉帳單
            'GetOnLineCounts',                       // 帳號 API - 取得在線會員數量
            'CheckIsOnline',                         // 帳號 API - 檢查會員是否在線上
            'KickMembers',                           // 帳號 API - 踢出指定會員
            'SetMemberState',                        // 帳號 API - 設定會員狀態

            'GetAccountMemberList',                  // 報表 API - 取得時間內有帳的會員
            'GetGameDetailForSequence',              // 報表 API - 按流水號獲取取游戲明细
            'GetModifyDetailForSequence',            // 報表 API - 按流水號獲取遊戲改單明细
            'GetGameDetailForMember',                // 報表 API - 獲取單一會員的遊戲注單明细(時間區間)
            'GetMemberSummary',                      // 報表 API - 取得會員指定起始時間到現在的資料匯整
            'GetAllMemberSummary',                   // 報表 API - 分頁獲取指定時間內有下注會員對應的匯總記錄
        ],
        'POST' => [
            'InGame',                                // 登入 API - 進入遊戲

            'GetMainBalance',                        // 接口 API - 取得錢包餘額
            'GetMemberLimitInfo',                    // 接口 API - 登入app，取得會員限紅
            'GameFundTransfer',                      // 接口 API - 電子錢包轉入接口
            'CheckLogin'                             // 接口 API - 遊戲端會員驗證
        ],
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // 載入 API 設定
        $this->config = [
            'api_url' => config("api_caller.maya.config.api_url"),
            'property_id' => config("api_caller.maya.config.property_id"),
            'md5_key' => config("api_caller.maya.config.md5_key"),
            'des_key' => config("api_caller.maya.config.des_key"),
        ];
    }

    public function __destruct()
    {
        unset($this->config);
    }

    /**
     * md5 模式加密傳送的資料
     *
     * @param $data
     * @return string
     */
    private function md5_encrypt($data)
    {
        $arrayKeys = array_keys($data);
        /* 加入規定之 pwd */
        array_push($arrayKeys, 'pwd');
        $data['pwd'] = $this->config['md5_key'];

        usort($arrayKeys, function ($a, $b) {
            $a = strtolower($a);
            $b = strtolower($b);
            if ($a == $b) {
                return 0;
            }
            return ($a > $b) ? 1 : -1;
        });

        $str = '';
        foreach ($arrayKeys as $value) {
            $v = $data[$value];
            if (is_null($v)) {
                $str .= $this->config['md5_key'];
            } else {
                $str .= $v;
            }
        }
        return md5($str);
    }


    /**
     * 將參數陣列串成字串
     *
     * @param $array
     * @return bool|string
     */
    private function getParams($array)
    {
        $string = '';
        foreach ($array as $key => $value) {
            $string .= $key . '=' . $value . '&';
        }
        return substr($string, 0, -1);
    }

    /**
     * 獲取DES加密後的 DESDATA 值
     *
     * @param array params 訪問介面時需要帶入的參數
     * @return mixed 返回 DESDATA 數據簽名值
     */
    private function desEncrypt($str)
    {
        return bin2hex(
            openssl_encrypt(
                $this->pkcs5Pad($str),
                "DES-EDE3-CBC",
                $this->config['des_key'],
                OPENSSL_RAW_DATA | OPENSSL_NO_PADDING,
                $this->config['des_key']
            )
        );
    }

    /**
     * 取得進入遊戲網址
     *
     * @param $params
     * @return string
     */
    private function getInGameUrl($params)
    {
        $params['MD5DATA'] = array_get($params, 'MD5DATA');
        $paramString = $this->getParams($params);
        $desEncrypt = $this->desEncrypt($paramString);
        $url = $this->config['api_url'] . '/Page/InGame' .
            "?VenderNo={$params['VenderNo']}" .
            "&DESDATA={$desEncrypt}";

        return $url;
    }

    /**
     * 設定 API 參數
     *
     * @param array $data
     * @return $this
     */
    public function params(array $data = [])
    {
        $this->formParams = array_merge(
            $this->formParams,
            // 排除不能被覆寫部分(系統自動填入的參數)
            array_except($data, ['NowDateTime'])
        );
        $this->formParams['NowDateTime'] = date('YmdHis');
        $MD5DATA = $this->md5_encrypt($this->formParams);
        $this->formParams['MD5DATA'] = $MD5DATA;

        return $this;
    }

    /**
     * 發送 API 請求
     *
     * @return array|mixed
     * @throws ApiCallerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function submit()
    {
        try {
            // 組合表單參數
            $formParams = $this->formParams;

            // 表頭
            $headers = [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:10.0) Gecko/20100101 Firefox/10.0',
                'X-Requested-With' => 'XMLHttpRequest',
                'content-type' => 'application/x-www-form-urlencoded',
            ];

            // guzzleClient request $options 參數
            $options = null;

            // 取得傳送位置
            $submitUrl = $this->config['api_url'] . '/API/' . $this->action;

            // 表頭與參數
            $options = [
                'headers' => $headers,
                'query' => $formParams,
                'timeout' => '30',
            ];

            /**
             * 若是進入遊戲，必須根據加密結果回傳跳轉網址，不支援訪問 api 取得通行證的方式
             *
             * 注意，進入遊戲的跳轉網址長這樣：$this->config['api_url'] . '/Page/InGame?VenderNo=...'，完整格式請參考以下第一點
             *
             * 1) 格式：http://www.xxxxxx.com/Page/InGame?VenderNo=vgtest11&DESDATA=32efc65748e0f50f51f1206b9ad4671458058e1663adbff5dadd88adc81f781f905f0e2f428c3c586f2b36060c0649efd5c4e6038405735fe538400fb6da6dfd8dacef31170cdba4a1006376e2fdc2f37c40c606c6c984a0cf2bb5d864f0ed5f923e47f5391fe10e4de161d535d48e069fedda68bf9e12254c27f1da15f1a4e38477e6b3cc6288d04b33850f7ca81067f21a3d2cd5037b8c24ef2ed493a56f11e0f95c5a427996a9ff450f3462d14817
             * 2) 在瑪雅視訊標準API URL中，域名後帶Page標識字符串的為Page API。 Page API 是一種頁面跳轉的API。 Page API與普通API不同的是，Page API 採用DES加密方式，如Page API第1點URL範例所示。
             * 3) DES 加密流程：
             *   a) DES加密需要MD5數據簽名作為參數，第一步先生成MD5數據簽名MD5DATA，參照MD5加密。
             *   b) 把API規定必需的參數帶上，加上NowDateTime(與第1步MD5加密的NowDateTime一致)和第一步加密後的MD5DATA，各參數間用&分隔，如: VenderMemberID=1&NowDateTime=20170308160038&MD5DATA=82c7767a73109e02a0e97ffa16347132。
             *   c) 把拼接好的參數字符串和DES key，作為參數傳入DES 加密方法。其中DES key 瑪雅視訊也會提前提供。切記要區分MD5 key 和DES key的使用場景。
             *   d) 加密DES後，把返回後的加密串轉成16進制小寫字符串，就會得到第1點中DESDATA中的內容。
             *   e) 得到DESDATA後，即可通過GET或POST方法拼接成第1步的URL，發送到瑪雅視訊接口服務器
             *   f) 基於傳輸安全，pwd密鑰不需要在URL中傳輸，只需在DES加密中使用即可
             */
            if ($this->action === 'InGame') {
                return [
                    'http_code' => 200,
                    'http_contents' => '',
                    'http_headers' => '',
                    'response' => [
                        'ErrorCode' => 0,
                        'InGameUrl' => $this->getInGameUrl($formParams),
                    ],
                ];
            }
            // 取得 API 呼叫結果
            $response = $this->guzzleClient->request(
                $this->method,
                $submitUrl,
                $options
            );


            $arrayData = json_decode($response->getBody()->getContents(), true);

            // 只有在正確成功完成 API 的動作，才會將結果回傳，不然就是統一丟例外
            if ((string)array_get($arrayData, 'ErrorCode') === '0') {
                return $this->responseFormatter($response, $arrayData);
            } else {
                throw new ApiCallerException($arrayData, 'maya');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}