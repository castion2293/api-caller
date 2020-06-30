<?php
/*
|--------------------------------------------------------------------------
| Bridge Action Name 橋接名稱 - API Action 對應表
|
| 1. example_bridge_action: 根據 API 功能，統一 API action 名稱，並使其格式為 camel case
| 2. method: 根據 API 文件，定義應該使用的 http method 對應當前訪問的 API Action
| 3. action: 實際對應的 API Action（需根據各遊戲館文件自行補到 StationCaller::$enabledMethodActions 中）
| 4. params: 根據 API 文件，轉換傳入的橋接參數，到對應文件中實際參數的名稱，例如傳入 account 對應 all_bet 建立帳號需要的帳號參數名稱為 client
|
| 功能參照表，新增 Action 前請檢查若有符合相同功能的 action，請使用參照表的 Action
|
|   // 橋接 bridge action name
|   'example_bridge_action' => [
|       'all_bet'     => ['method' => 'POST', 'action' => 'real_all_bet_action_name',   'route_params' => [...], 'form_params' => [...]],
|       'bingo'       => ['method' => 'POST', 'action' => 'real_bingo_action_name',     'route_params' => [...], 'form_params' => [...]],
|       'holdem'      => ['method' => 'POST', 'action' => 'real_holdem_action_name',    'route_params' => [...], 'form_params' => [...]],
|       'hy'          => ['method' => 'POST', 'action' => 'real_hy_action_name',        'route_params' => [...], 'form_params' => [...]],
|       'royal'       => ['method' => 'POST', 'action' => 'real_royal_action_name',     'route_params' => [...], 'form_params' => [...]],
|       'sa_gaming'   => ['method' => 'POST', 'action' => 'real_sa_gaming_action_name', 'route_params' => [...], 'form_params' => [...]],
|       'super_sport' => ['method' => 'POST', 'action' => 'real_super_action_name',     'route_params' => [...], 'form_params' => [...]],
|       'new_station' => ['method' => 'POST', 'action' => 'real_super_action_name',     'route_params' => [...], 'form_params' => [...]],
|   ],
|
|   // 路由參數：統一名稱一律使用 snake_case
|   'route_params' => [
|       // 必傳參數
|       'require' => [
|           // 統一名稱 => 對應名稱
|           'account' => 'client'
|           ...
|       ],
|       // 選填參數
|       'optional' => [
|           // 統一名稱 => 對應名稱
|           'name' => 'user_name'
|           ...
|       ],
|   ]
|
|   // 表單參數：統一名稱一律使用 snake_case
|   'form_params' => [
|       // 必傳參數
|       'require' => [
|           // 統一名稱 => 對應名稱
|           'account' => 'client'
|           ...
|       ],
|       // 選填參數
|       'optional' => [
|           // 統一名稱 => 對應名稱
|           'name' => 'user_name'
|           ...
|       ],
|   ]
|
| 未來新增任何遊戲館，需到每個橋接 action 中，新增對應遊戲館的橋接名稱，否則會拋出例外
|
| 若有遊戲管不支援該方法，method and action 留空。
|--------------------------------------------------------------------------
*/
return [
    /*
    |--------------------------------------------------------------------------
    | Create 新增資料
    |--------------------------------------------------------------------------
    */
    /**
     * 建立帳號
     *
     * 參數統一名稱：
     * host               站台號碼
     * agent              代理帳號
     * account            帳號
     * password           密碼
     * password_again     確認密碼
     * name               名稱
     * normal_handicaps   普通限紅（盤口）
     * vip_handicaps      vip 限紅（盤口）
     * normal_hall_rebate 普通廳洗碼比
     * dv_hall_rebate     電子遊戲歐博廳洗碼比
     * lax_hall_rebate    電子遊戲 A 廳洗碼比
     * lst_hall_rebate    電子遊戲 B 廳洗碼比
     * max_win            最大贏額限制
     * max_lost           最大輸額限制
     * day_max_win        每日最大贏額限制
     * day_max_lost       每日最大輸額限制
     * muster             族群 / 群組
     * remark             備註
     * currency_type      貨幣種類
     */
    'createAccount' => [
        'all_bet' => [
            'method' => 'POST',
            // 單錢包與多錢包的接口不同
            'action' => (env('APP_IS_SINGLE_BALANCE_SITE') === 'yes') ? 'create_client' : 'check_or_create',
            'form_params' => [
                'require' => [
                    'agent' => 'agent',
                    'account' => 'client',
                    'password' => 'password',
                    // 單錢包與多錢包的接口不同
                    'normal_handicaps' => (env('APP_IS_SINGLE_BALANCE_SITE') === 'yes') ? 'orHandicapNames' : 'orHandicaps',
                    // 單錢包與多錢包的接口不同
                    'vip_handicaps' => (env('APP_IS_SINGLE_BALANCE_SITE') === 'yes') ? 'vipHandicapNames' : 'vipHandicaps',
                    'normal_hall_rebate' => 'orHallRebate',
                ],
                'optional' => [
                    'dv_hall_rebate' => 'dvHallRebate',
                    'lax_hall_rebate' => 'laxHallRebate',
                    'lst_hall_rebate' => 'lstHallRebate',
                    'max_win' => 'maxWin',
                    'max_lost' => 'maxLost',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo' => [
            'method' => 'POST',
            'action' => 'players',
            'form_params' => [
                'require' => [
                    'account' => 'account',
                    'password' => 'password',
                    'password_again' => 'password_confirmation',
                    'name' => 'name',
                ],
                'optional' => [
                    'day_max_win' => 'day_winnings_quota',
                    'day_max_lost' => 'day_losings_quota',
                    'muster' => 'muster',
                    'remark' => 'remark',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'holdem' => [ // 不支援
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'sa_gaming' => [
            'method' => 'POST',
            'action' => 'RegUserInfo',
            'form_params' => [
                'require' => [
                    'account' => 'Username',
                    'currency_type' => 'CurrencyType',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'super_sport' => [
            'method' => 'POST',
            'action' => 'account',
            'form_params' => [
                'require' => [
                    'act' => 'act',
                    'account' => 'account',
                    'password' => 'passwd',
                    'nickname' => 'nickname',
                    'level' => 'level',
                    'up_account' => 'up_account',
                    'up_password' => 'up_passwd'
                ],
                'optional' => [
                    'copy_target' => 'copy_target'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ufa_sport' => [
            'method' => 'get',
            'action' => 'create',
            'form_params' => [
                'require' => [
                    'secret' => 'secret',
                    'agent' => 'agent',
                    'username' => 'username'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'maya' => [
            'method' => 'GET',
            'action' => 'CreateMember',
            'form_params' => [
                'require' => [
                    'VenderNo' => 'VenderNo',
                    'SiteNo' => 'SiteNo',
                    'VenderMemberID' => 'VenderMemberID',
                    'MemberName' => 'MemberName',
                    'TestState' => 'TestState',
                    'CurrencyNo' => 'CurrencyNo'
                ],
                'optional' => [
                    'LayerNo' => 'LayerNo',
                    'NickName' => 'NickName',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'so_power' => [
            'method' => 'POST',
            'action' => 'CREATE_USER',
            'form_params' => [
                'require' => [
                    'method' => 'CREATE_USER',
                    'Timestamp' => 'timestamp',
                    'Username' => 'username',
                    'Client_id' => 'client_id',
                    'Sign_Code' => 'sign_code'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'dream_game' => [
            'method' => 'POST',
            'action' => 'user/signup/{agent}',
            'form_params' => [
                'require' => [
                    /**
                     * data is 限紅 TWD
                     *   A 100-250000
                     *   B 50-5000
                     *   C 50-10000
                     *   D 100-10000
                     *   E 100-20000
                     *   F 100-50000
                     *   G 100-100000
                     */
                    'data' => 'data',
                    /**
                     * member json
                     * {
                     *    属性名        属性类型     属性说明
                     *    ----------- require -----------
                     *    username     String      会员登入账号
                     *    password     String      会员密码（MD5), 在 caller params 有實做 md5 無需自己加
                     *    currency     String      会员货币简称,
                     *      币种ID  币种     名称       特别说明
                     *      1       CNY     人民币
                     *      2       USD     美元
                     *      3       MYR     马来西亚币
                     *      4       HKD     港币
                     *      5       THB     泰珠
                     *      6       SGD     新加坡元
                     *      7       PHP     菲律宾比索
                     *      8       TWD     台币
                     *      9       VND     越南盾
                     *      10      IDR     印尼(盾)
                     *      11      JPY     日元
                     *      12      KHR     柬埔寨币
                     *      13      KRW     韩元
                     *      16      AUD     澳大利亚元
                     *      19      INR     印度卢比
                     *      20      EUR     欧元
                     *      21      GBP     英镑
                     *      22      CAD     加拿大
                     *      23      KRW2    韩元       已去除3个0，游戏中1块，等同于实际1000块
                     *      24      MMK     缅甸币
                     *      25      MMK2    缅甸币     已去除3个0，游戏中1块，等同于实际1000块
                     *      29      VND2    越南盾     已去除3个0，游戏中1块，等同于实际1000块
                     *      30      IDR2    印尼(盾)   已去除3个0，游戏中1块，等同于实际1000块
                     *
                     *    winLimit     Double      会员当天最大可赢取金额[仅统计当天下注], < 1表示无限制
                     *    ----------- optional -----------
                     *    status       Integer     会员状态：0:停用, 1:正常, 2:锁定(不能下注) (optional: default 1)
                     *    balance      Double      会员余额 (optional: default 0)
                     * }
                     */
                    'member' => 'member',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [
                    'agent' => 'agent',
                ],
                'optional' => [],
            ],
        ],
        'super_lottery' => [
            'method' => 'POST',
            'action' => 'account',
            'form_params' => [
                'require' => [
                    'act' => 'act',
                    'up_account' => 'up_acc',
                    'up_password' => 'up_pwd',
                    'account' => 'account',
                    'password' => 'passwd',
                    'nickname' => 'nickname'
                ],
                'optional' => [
                    'copy_target' => 'copy_target'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
// 'hong_chow' => [
//     'method' => 'POST',
//     'action' => 'login',
//     'form_params' => [
//         'require' => [
//             'account' => 'username',
//         ],
//         'optional' => [],
//     ],
//     'route_params' => [
//         'require' => [],
//         'optional' => [],
//     ],
// ],
        'ameba' => [
            'method' => 'POST',
            'action' => 'create_account',
            'form_params' => [
                'require' => [
                    'account' => 'account_name',
                    'currency_type' => 'currency',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'real_time_gaming' => [
            'method' => 'PUT',
            'action' => 'player',
            'form_params' => [
                'require' => [
                    'agentId' => 'agentId',
                    'username' => 'username',
                    'firstName' => 'firstName',
                    'lastName' => 'lastName',
                    'email' => 'email',
                    'countryId' => 'countryId',
                    'gender' => 'gender',
                    'birthdate' => 'birthdate',
                    'currency' => 'currency'
                ],
                'optional' => [
                    'languageId' => 'languageId',
                    'walletId' => 'walletId',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'royal_game' => [
            'method' => 'post',
            'action' => 'VPSWMember',
            'form_params' => [
                'require' => [
                    'RequestID' => 'RequestID',
                    'Control' => 'Control',
                    'BucketID' => 'BucketID',
                    'MemberID' => 'MemberID',
                    'MemberName' => 'MemberName',
                    'Currency' => 'Currency',
                    'Operator' => 'Operator',
                    'IP' => 'IP'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ren_ni_ying' => [
            'method' => 'post',
            'action' => 'exportPlayer',
            'form_params' => [
                'require' => [
                    'parentAgentUserId' => 'parentAgentUserId',
                    'userId' => 'userId',
                    'nick' => 'nick',
                    'balk' => 'balk',
                    'initialCredit' => 'initialCredit',
                    'parentProportion' => 'parentProportion',
                ],
                'optional' => [
                    'maxProfit' => 'maxProfit',
                    'keepRebateRate' => 'keepRebateRate'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'cq9_game' => [
            'method' => "POST",
            'action' => "player",
            'form_params' => [
                'require' => [
                    'account' => 'account',
                    'password' => 'password',
                ],
                'optional' => [
                    'nickname' => 'nickname',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'winner_sport' => [
            'method' => 'post',
            'action' => 'Create_Member',
            'form_params' => [
                'require' => [
                    'username' => 'username',
                    'alias' => 'alias',
                    'istest' => 'istest',
                    'top' => 'top',
                ],
                'optional' => [
                    'currency' => 'currency',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'nine_k_lottery' => [
            'method' => "POST",
            'action' => "RegisterUser",
            'form_params' => [
                'require' => [
                    'BossID' => 'BossID',
                    'MemberAccount' => 'MemberAccount',
                    'MemberPassword' => 'MemberPassword',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'nine_k_lottery_2' => [
            'method' => "POST",
            'action' => "RegisterUser",
            'form_params' => [
                'require' => [
                    'BossID' => 'BossID',
                    'MemberAccount' => 'MemberAccount',
                    'MemberPassword' => 'MemberPassword',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'wm_casino' => [
            'method' => "POST",
            'action' => "MemberRegister",
            'form_params' => [
                'require' => [
                    'user' => 'user',
                    'password' => 'password',
                    'username' => 'username',
                    'syslang' => 'syslang',
                ],
                'optional' => [
                    // 最大可赢
                    'maxwin' => 'maxwin',
                    // 最大可輸
                    'maxlose' => 'maxlose',
                    // 會員退水是否歸零
                    'rakeback' => 'rakeback',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bobo_poker' => [
            'method' => "POST",
            'action' => "wallet/createPlayer",
            'form_params' => [
                'require' => [
                    'spId' => 'spId',
                    'account' => 'account'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'forever_eight' => [
            'method' => "POST",
            'action' => "lg",
            'form_params' => [
                'require' => [
                    'Loginname' => 'Loginname',
                    'Oddtype' => 'Oddtype',
                    'Cur' => 'Cur',
                    'NickName' => 'NickName',
                    'SecureToken' => 'SecureToken',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'slot_factory' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'cmd_sport' => [
            'method' => "GET",
            'action' => "createmember",
            'form_params' => [
                'require' => [
                    'UserName' => 'UserName',
                    'Currency' => 'Currency',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo_bull' => [
            'method' => "POST",
            'action' => "/api/adduser",
            'form_params' => [
                'require' => [
                    'account' => 'account',
                    'password' => 'password',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'vs_lottery' => [
            'method' => "POST",
            'action' => "CreatePlayerAccount",
            'form_params' => [
                'require' => [
                    'userName' => 'userName',
                    'password' => 'password',
                    'currencyCode' => 'currencyCode',
                    'firstName' => 'firstName',
                    'lastName' => 'lastName',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'awc_sexy' => [
            'method' => "POST",
            'action' => "createMember",
            'form_params' => [
                'require' => [
                    'userId' => 'userId',
                    'currency' => 'currency',
                    'betLimit' => 'betLimit',
                    'language' => 'language',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'habanero' => [
            'method' => "POST",
            'action' => "LoginOrCreatePlayer",
            'form_params' => [
                'require' => [
                    'PlayerHostAddress' => 'PlayerHostAddress',
                    'UserAgent' => 'UserAgent',
                    'KeepExistingToken' => 'KeepExistingToken',
                    'Username' => 'Username',
                    'Password' => 'Password',
                    'CurrencyCode' => 'CurrencyCode',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'kk_lottery' => [
            'method' => "POST",
            'action' => "createuser",
            'form_params' => [
                'require' => [
                    'username' => 'username',
                    'usertype' => 'usertype',
                    'countrycode' => 'countrycode',
                    'currencycode' => 'currencycode',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'incorrect_score' => [
            'method' => "post",
            'action' => "MemberRegister",
            'form_params' => [
                'require' => [
                    'agentid' => 'agentid',
                    'user' => 'user',
                    'password' => 'password',
                    'username' => 'username',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'mg_poker' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Read 讀取資料
    |--------------------------------------------------------------------------
    */
    /**
     * 取得登入通行證 / 連結
     *
     * 參數統一名稱：
     * host             站台號碼
     * vender_no        代理商編號
     * account          帳號
     * password         密碼
     * language         語言
     * redirect_to      重導
     * game_hall        遊戲廳
     * expires_in       過期時間
     * currency_type    貨幣種類
     * game_identify    遊戲端會員辨識碼
     * normal_handicaps 普通限紅（盤口）
     * pass_token       遊戲端通行證
     * page_style       頁面風格
     * show_recharge    是否顯示內部儲值按鈕
     * open_url         試玩帳號開戶頁面
     * open_back_url    手機端遊戲關閉跳轉回此頁
     * is_trial         此值帶入 1 表示是試玩帳號， otherwise 為正式帳號
     * entry_type       0 為電腦版 Flash 遊戲介面（不傳預設為此值）傳入 1 為手機 H5
     * token            token
     *
     * 備註：瑪雅有統一橋接參數
     */
    'passport' => [
        'all_bet' => [
            'method' => 'POST',
            'action' => 'forward_game',
            'form_params' => [
                'require' => [
                    'account' => 'client',
                    'password' => 'password',
                ],
                'optional' => [
                    'language' => 'language',
                    'redirect_to' => 'targetSite',
                    'game_hall' => 'gameHall',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo' => [
            'method' => 'POST',
            'action' => 'players/{playerId}/play-url',
            'form_params' => [
                'require' => [],
                'optional' => [
                    'language' => 'lang',
                    'expires_in' => 'expires_in',
                ],
            ],
            'route_params' => [
                'require' => [
                    'account' => 'playerId',
                ],
                'optional' => [],
            ],
        ],
        'holdem' => [
            'method' => 'POST',
            'action' => 'playgame',
            'form_params' => [
                'require' => [
                    'host' => 'PlatformID',
                    'account' => 'loginID',
                    'password' => 'loginPW',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'sa_gaming' => [
            'method' => 'POST',
            'action' => 'LoginRequest',
            'form_params' => [
                'require' => [
                    'account' => 'Username',
                ],
                'optional' => [
                    'currency_type' => 'CurrencyType',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'so_power' => [
            'method' => 'POST',
            'action' => 'REQUEST_TOKEN',
            'form_params' => [
                'require' => [
                    'method' => 'REQUEST_TOKEN',
                    'Timestamp' => 'timestamp',
                    'Username' => 'username',
                    'Client_id' => 'client_id',
                    'Sign_Code' => 'sign_code'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'super_sport' => [
            'method' => 'POST',
            'action' => 'login',
            'form_params' => [
                'require' => [
                    'account' => 'account',
                    'password' => 'passwd',
                    'responseFormat' => 'responseFormat'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ufa_sport' => [
            'method' => 'get',
            'action' => 'login',
            'form_params' => [
                'require' => [
                    'secret' => 'secret',
                    'agent' => 'agent',
                    'username' => 'username',
                    'host' => 'host',
                    'lang' => 'lang',
                    'accType' => 'accType'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'maya' => [
            'method' => 'POST',
            'action' => 'InGame',
            'form_params' => [
                'require' => [
                    'vender_no' => 'VenderNo',
                    'account' => 'MemberName',
                    'game_identify' => 'GameMemberID',
                    'normal_handicaps' => 'GameConfigID',
                    'language' => 'LanguageNo',
                    'pass_token' => 'Token'
                ],
                'optional' => [
                    'host' => 'SiteNo',
                    'page_style' => 'PageStyle',
                    'show_recharge' => 'ShowRecharge',
                    'open_url' => 'OpenURL',
                    'open_back_url' => 'OpenBackURL',
                    'is_trial' => 'IsTrial',
                    'entry_type' => 'EntryType'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
//        'nihtan' => [
//            'method' => 'POST',
//            'action' => 'api/session',
//            'form_params' => [
//                'require' => [
//                    'user_id' => 'user_id',
//                    'user_name' => 'user_name',
//                    'user_ip' => 'user_ip',
//                    'currency' => 'currency'
//                ],
//                'optional' => [
//                    'currency' => 'currency',
//                    'denomination' => 'denomination',
//                    'lang' => 'lang',
//                    'pc_redirect' => 'pc_redirect',
//                    'mo_redirect' => 'mo_redirect',
//                ],
//            ],
//            'route_params' => [
//                'require' => [],
//                'optional' => [],
//            ],
//        ],
        'dream_game' => [
            'method' => 'POST',
            'action' => 'user/login/{agent}',
            'form_params' => [
                'require' => [
                    /**
                     * language 填入簡寫
                     *
                     *  代號    簡寫     描述
                     *  0       en      英文
                     *  1       cn      中文简体
                     *  2       tw      中文繁体
                     *  3       kr      韩语
                     *  4       my      缅甸语
                     *  5       th      泰语
                     */
                    'language' => 'lang',
                    /**
                     * member json
                     * {
                     *    "username":"会员账号",
                     *    "password":"会员密码"//可以不传,如果密码不同,将自动修改DG数据库保存的密码
                     * }
                     */
                    'member' => 'member',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [
                    'agent' => 'agent',
                ],
                'optional' => [],
            ],
        ],
        'super_lottery' => [
            'method' => 'POST',
            'action' => 'login',
            'form_params' => [
                'require' => [
                    'account' => 'account',
                    'password' => 'passwd',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
//        'hong_chow' => [
//            'method' => 'POST',
//            'action' => 'login',
//            'form_params' => [
//                'require' => [
//                    'account' => 'username',
//                ],
//                'optional' => [],
//            ],
//            'route_params' => [
//                'require' => [],
//                'optional' => [],
//            ],
//        ],
        'ameba' => [
            'method' => 'POST',
            'action' => 'register_token',
            'form_params' => [
                'require' => [
                    'account' => 'account_name',
                    'game_id' => 'game_id',
                    'language' => 'lang',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'real_time_gaming' => [
            'method' => 'POST',
            'action' => 'launcher/lobby',
            'form_params' => [
                'require' => [
                    /**
                     * player json
                     * {
                     *    "playerLogin": "String - 玩家登陆 "
                     *       (如果未使用playerId则需要）,
                     *    "playerId": "玩家的系统ID。如果使用playerId作为参数，则不需要playerLogin(玩家登陆) 及 agentId (代理ID)"
                     *      （如果未使用playerLogin，则为必需）
                     * }
                     */
                    'player' => 'player',
                    'locale' => 'locale',
                    'language' => 'language',
                    'isDemo' => 'isDemo'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'royal_game' => [
            'method' => 'post',
            'action' => 'VPSGetMemberSessionKey',
            'form_params' => [
                'require' => [
                    'RequestID' => 'RequestID',
                    'BucketID' => 'BucketID',
                    'MemberID' => 'MemberID',
                    'Password' => 'Password',
                    'GameType' => 'GameType',
                    'ServerName' => 'ServerName'
                ],
                'optional' => [
                    'Lang' => 'Lang'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ren_ni_ying' => [
            'method' => 'get',
            'action' => 'getPlayerTicket',
            'form_params' => [
                'require' => [
                    'userId' => 'userId',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'cq9_game' => [
            'method' => 'POST',
            // 單錢包與多錢包的接口不同
            'action' => (env('APP_IS_SINGLE_BALANCE_SITE') === 'yes') ? 'player/sw/lobbylink' : 'player/lobbylink',
            'form_params' => [
                'require' => [
                    'usertoken' => 'usertoken',
                ],
                'optional' => [
                    'lang' => 'lang'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'winner_sport' => [
            'method' => 'POST',
            'action' => 'Member_Login',
            'form_params' => [
                'require' => [
                    'username' => 'username',
                    'slangx' => 'slangx',
                ],
                'optional' => [
                    'mobile' => 'mobile',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'nine_k_lottery' => [
            'method' => 'POST',
            'action' => 'UserLogin',
            'form_params' => [
                'require' => [
                    'MemberAccount' => 'MemberAccount',
                    'MemberPassword' => 'MemberPassword',
                ],
                'optional' => [
                    'Platform' => 'Platform'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'nine_k_lottery_2' => [
            'method' => 'POST',
            'action' => 'UserLogin',
            'form_params' => [
                'require' => [
                    'MemberAccount' => 'MemberAccount',
                    'MemberPassword' => 'MemberPassword',
                ],
                'optional' => [
                    'Platform' => 'Platform'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'q_tech' => [
            'method' => 'POST',
            'action' => 'games/lobby-url',
            'form_params' => [
                'require' => [
                    'playerId' => 'playerId',
                    'currency' => 'currency',
                    'country' => 'country',
                    'lang' => 'lang',
                    'mode' => 'mode',
                    'device' => 'device',
                ],
                'optional' => [
                    'betLimitCode' => 'betLimitCode'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'wm_casino' => [
            'method' => 'POST',
            'action' => (env('APP_IS_SINGLE_BALANCE_SITE') === 'yes') ? 'LoginGame' : 'SigninGame',
            'form_params' => [
                'require' => [
                    'user' => 'user',
                    'password' => 'password',
                    'lang' => 'lang',
                    'syslang' => 'syslang',
                ],
                'optional' => [
                    // 試玩
                    'isTest' => 'isTest',
                    // 棋牌風格
                    'ui' => 'ui',
                    // 5:竖版; 6:竖版(微信专用); 9:横版(微信专用)
                    'site' => 'site'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bobo_poker' => [
            'method' => 'POST',
            'action' => 'launch/{device}',
            'form_params' => [
                'require' => [
                    'spId' => 'spId',
                    'productId' => 'productId',
                    'returnUrl' => 'returnUrl',
                    'account' => 'account',
                    'logoUrl' => 'logoUrl',
                    'storeUrl' => 'storeUrl'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [
                    'device' => 'device'
                ],
                'optional' => [],
            ],
        ],
        'forever_eight' => [
            'method' => "get",
            'action' => "fwgame_opt",
            'form_params' => [
                'require' => [
                    'Loginname' => 'Loginname',
                    'Lang' => 'Lang',
                    'Cur' => 'Cur',
                    'GameId' => 'GameId',
                    'Oddtype' => 'Oddtype',
                    'SecureToken' => 'SecureToken',
                    'HomeURL' => 'HomeURL',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'slot_factory' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'cmd_sport' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'cock_fight' => [
            'method' => "post",
            'action' => "get_session_id",
            'form_params' => [
                'require' => [
                    'login_id' => 'login_id',
                    'name' => 'name',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'vs_lottery' => [
            'method' => "post",
            'action' => "GetLoginUrl",
            'form_params' => [
                'require' => [
                    'userName' => 'userName',
                    'password' => 'password',
                    'lang' => 'lang',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo_bull' => [
            'method' => "POST",
            'action' => "/api/login",
            'form_params' => [
                'require' => [
                    'account' => 'account',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'awc_sexy' => [
            'method' => "POST",
            'action' => "login",
            'form_params' => [
                'require' => [
                    'userId' => 'userId',
                    'isMobileLogin' => 'isMobileLogin',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'habanero' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'kk_lottery' => [
            'method' => "GET",
            'action' => "login",
            'form_params' => [
                'require' => [
                    'username' => 'username',
                    'logintime' => 'logintime',
                    'backurl' => 'backurl',
                ],
                'optional' => [
                    'odds' => 'odds',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'incorrect_score' => [
            'method' => "post",
            'action' => "LoginGame",
            'form_params' => [
                'require' => [
                    'user' => 'user',
                    'password' => 'password',
                    'lang' => 'lang',
                    'ver' => 'ver',
                    'trailmode' => 'trailmode',
                ],
                'optional' => [
                    'callbackUrl' => 'callbackUrl',
                    'game_token' => 'game_token',
                    'isTestLineMember' => 'isTestLineMember',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'mg_poker' => [
            'method' => "post",
            'action' => "login",
            'form_params' => [
                'require' => [
                    'account' => 'account',
                    'gameId' => 'gameId',
                    'platform' => 'platform',
                    'exitUrl' => 'exitUrl',
                ],
                'optional' => [
                    'ip' => 'ip',
                    'appUrl' => 'appUrl',
                    'theme' => 'theme',
                    'p1' => 'p1',
                    'p2' => 'p2',
                    'token' => 'token',
                    'code' => 'code',
                    
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
    ],

    /**
     * 取得餘額
     *
     * 參數統一名稱：
     * host             站台號碼
     * account          帳號
     * password         密碼
     * vender_no        代理商編號
     * game_identifies  遊戲端會員辨識碼（複數）
     * token            token
     *
     * 備註：瑪雅有統一橋接參數
     */
    'getBalance' => [
        'all_bet' => [
            'method' => 'POST',
            'action' => 'get_balance',
            'form_params' => [
                'require' => [
                    'account' => 'client',
                    'password' => 'password',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo' => [
            'method' => 'GET',
            'action' => 'players/{playerId}',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [
                    'account' => 'playerId',
                ],
                'optional' => [],
            ],
        ],
        'holdem' => [
            'method' => 'POST',
            'action' => 'GetPoints',
            'form_params' => [
                'require' => [
                    'host' => 'PlatformID',
                    'account' => 'MemberAccount',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'so_power' => [
            'method' => 'POST',
            'action' => 'GET_CREDIT',
            'form_params' => [
                'require' => [
                    'method' => 'GET_CREDIT',
                    'Timestamp' => 'timestamp',
                    'Username' => 'username',
                    'Client_id' => 'client_id',
                    'Sign_Code' => 'sign_code'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'sa_gaming' => [
            'method' => 'POST',
            'action' => 'GetUserStatusDV',
            'form_params' => [
                'require' => [
                    'account' => 'Username',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'super_sport' => [
            'method' => 'POST',
            'action' => 'points',
            'form_params' => [
                'require' => [
                    'account' => 'account',
                    'up_account' => 'up_account',
                    'up_password' => 'up_passwd',
                    'act' => 'act'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ufa_sport' => [
            'method' => 'get',
            'action' => 'balance',
            'form_params' => [
                'require' => [
                    'secret' => 'secret',
                    'agent' => 'agent',
                    'username' => 'username'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'maya' => [
            'method' => 'GET',
            'action' => 'GetBalance',
            'form_params' => [
                'require' => [
                    'vender_no' => 'VenderNo',
                    'game_identifies' => 'GameMemberIDs',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
//        'nihtan' => [
//            'method' => 'POST',
//            'action' => 'user/holding',
//            'form_params' => [
//                'require' => [
//                    'user_id' => 'user_id',
//                    'user_name' => 'user_name',
//                ],
//                'optional' => [],
//            ],
//            'route_params' => [
//                'require' => [],
//                'optional' => [],
//            ],
//        ],
        'dream_game' => [
            'method' => 'POST',
            'action' => 'user/getBalance/{agent}',
            'form_params' => [
                'require' => [
                    /**
                     * member json
                     * {
                     *    "username":"会员账号",
                     * }
                     */
                    'member' => 'member',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [
                    'agent' => 'agent',
                ],
                'optional' => [],
            ],
        ],
        'super_lottery' => [
            'method' => 'POST',
            'action' => 'points',
            'form_params' => [
                'require' => [
                    'act' => 'act',
                    'account' => 'account',
                    'up_account' => 'up_acc',
                    'up_password' => 'up_pwd',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
//        'hong_chow' => [
//            'method' => 'POST',
//            'action' => 'balance',
//            'form_params' => [
//                'require' => [
//                    'account' => 'username',
//                ],
//                'optional' => [],
//            ],
//            'route_params' => [
//                'require' => [],
//                'optional' => [],
//            ],
//        ],
        'ameba' => [
            'method' => 'POST',
            'action' => 'get_balance',
            'form_params' => [
                'require' => [
                    'account' => 'account_name',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'real_time_gaming' => [
            'method' => 'POST',
            'action' => 'wallet',
            'form_params' => [
                'require' => [
                    'playerLogin' => 'playerLogin'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'royal_game' => [
            'method' => 'post',
            'action' => 'VPSWMemberBalance',
            'form_params' => [
                'require' => [
                    'RequestID' => 'RequestID',
                    'BucketID' => 'BucketID',
                    'MemberID' => 'MemberID',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ren_ni_ying' => [
            'method' => 'get',
            'action' => 'getPlayer',
            'form_params' => [
                'require' => [
                    'userId' => 'userId',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'cq9_game' => [
            'method' => 'GET',
            'action' => 'player/balance/{account}',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [
                    'account' => 'account',
                ],
                'optional' => [],
            ],
        ],
        'winner_sport' => [
            'method' => 'post',
            'action' => 'Member_Money',
            'form_params' => [
                'require' => [
                    'username' => 'username',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'nine_k_lottery' => [
            'method' => 'POST',
            'action' => 'GetUserBalance',
            'form_params' => [
                'require' => [
                    'MemberAccount' => 'MemberAccount',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'nine_k_lottery_2' => [
            'method' => 'POST',
            'action' => 'GetUserBalance',
            'form_params' => [
                'require' => [
                    'MemberAccount' => 'MemberAccount',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'q_tech' => [
            'method' => 'GET',
            'action' => 'wallet/ext/{playerId}',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [
                    'playerId' => 'playerId'
                ],
                'optional' => [],
            ],
        ],
        'bobo_poker' => [
            'method' => 'POST',
            'action' => 'wallet/getPlayerInfo',
            'form_params' => [
                'require' => [
                    'spId' => 'spId',
                    'account' => 'account'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'wm_casino' => [
            'method' => 'POST',
            'action' => 'GetBalance',
            'form_params' => [
                'require' => [
                    'user' => 'user',
                    'syslang' => 'syslang',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'forever_eight' => [
            'method' => "POST",
            'action' => "gb",
            'form_params' => [
                'require' => [
                    'Loginname' => 'Loginname',
                    'Cur' => 'Cur',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'slot_factory' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'cmd_sport' => [
            'method' => 'GET',
            'action' => 'getbalance',
            'form_params' => [
                'require' => [
                    'UserName' => 'UserName',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'cock_fight' => [
            'method' => 'POST',
            'action' => 'get_balance',
            'form_params' => [
                'require' => [
                    'login_id' => 'login_id'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo_bull' => [
            'method' => "POST",
            'action' => "/api/getPoint",
            'form_params' => [
                'require' => [
                    'account' => 'account',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'vs_lottery' => [
            'method' => 'POST',
            'action' => 'GetPlayerBalance',
            'form_params' => [
                'require' => [
                    'userName' => 'userName',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'awc_sexy' => [
            'method' => 'POST',
            'action' => 'getBalance',
            'form_params' => [
                'require' => [
                    'userIds' => 'userIds',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'kk_lottery' => [
            'method' => 'POST',
            'action' => 'fund/getbalance',
            'form_params' => [
                'require' => [
                    'username' => 'username',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'habanero' => [
            'method' => "POST",
            'action' => "QueryPlayer",
            'form_params' => [
                'require' => [
                    'Username' => 'Username',
                    'Password' => 'Password',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'incorrect_score' => [
            'method' => "POST",
            'action' => "GetBalance",
            'form_params' => [
                'require' => [
                    'user' => 'user',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'mg_poker' => [
            'method' => "POST",
            'action' => "queryUserScore",
            'form_params' => [
                'require' => [
                    'account' => 'account',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
    ],

    /**
     * 取得原生注單
     *
     * 參數統一名稱：
     * account          帳號
     * start_date_time  指定查詢日期時間起點
     * end_date_time    指定查詢日期時間終點
     * start_date       指定查詢日期起點
     * end_date         指定查詢日期終點
     * start_time       指定查詢時間起點
     * end_time         指定查詢時間終點
     * page             分頁器參數：指定查詢資料頁碼
     * page_size        分頁器參數：指定分頁大小
     * game_round_id    遊戲局號 ID
     * game_type_id     遊戲類型 ID
     * token            token
     */
    'getTickets' => [
        'all_bet' => [
            'method' => 'POST',
            'action' => 'client_betlog_query',
            'form_params' => [
                'require' => [
                    'account' => 'client',
                    'start_date_time' => 'startTime',
                    'end_date_time' => 'endTime',
                    'page' => 'pageIndex',
                    'page_size' => 'pageSize',
                ],
                'optional' => [
                    'game_round_id' => 'gameRoundId',
                    'game_type_id' => 'gameType',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo' => [
            'method' => 'GET',
            'action' => 'tickets',
            'form_params' => [
                'require' => [],
                'optional' => [
                    'start_date_time' => 'created_at_begin',
                    'end_date_time' => 'created_at_end',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'holdem' => [
            'method' => 'POST',
            'action' => 'WinLose',
            'form_params' => [
                'require' => [],
                'optional' => [
                    'start_date_time' => 'BeginTime',
                    'end_date_time' => 'EndTime',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'sa_gaming' => [
            'method' => 'POST',
            'action' => 'GetAllBetDetailsForTimeIntervalDV',
            'form_params' => [
                'require' => [
                    'start_date_time' => 'FromTime',
                    'end_date_time' => 'ToTime',
                ],
                'optional' => [
                    'account' => 'Username',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'super_sport' => [
            'method' => 'POST',
            'action' => 'report',
            'form_params' => [
                'require' => [
                    'act' => 'act',
                    'account' => 'account',
                    'level' => 'level',
                    'start_date' => 's_date',
                    'end_date' => 'e_date'
                ],
                'optional' => [
                    'start_time' => 'start_time',
                    'end_time' => 'end_time',
                    'ball' => 'ball',
                    'type' => 'type'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'maya' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'so_power' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'dream_game' => [
            /**
             * 備註：
             *   兩次請求間隔最小為10秒鐘
             *   單次查詢最大數據量1000條
             *   抓取的單有可能有上次已經抓取過的抓單
             */
            'method' => 'POST',
            'action' => 'game/getReport/{agent}',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [
                    'agent' => 'agent',
                ],
                'optional' => [],
            ],
        ],
        'super_lottery' => [
            'method' => 'POST',
            'action' => 'report',
            'form_params' => [
                'require' => [
                    'account' => 'account',
                    'password' => 'passwd',
                    'start_date' => 'start_date',
                    'end_date' => 'end_date'
                ],
                'optional' => [
                    'start_time' => 'start_time',
                    'end_time' => 'end_time',
                    'ball' => 'ball',
                    'type' => 'type'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
//        'hong_chow' => [
//            'method' => 'POST',
//            'action' => 'bets',
//            'form_params' => [
//                'require' => [
//                    'start_sync_version' => 'start_sync_version',
//                ],
//                'optional' => [],
//            ],
//            'route_params' => [
//                'require' => [],
//                'optional' => [],
//            ],
//        ],
        'ameba' => [
            'method' => 'POST',
            'action' => 'get_bet_histories',
            'form_params' => [
                'require' => [
                    'start_date_time' => 'from_time',
                    'end_date_time' => 'to_time',
                ],
                'optional' => [
                    'group' => 'group',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ren_ni_ying' => [
            'method' => 'get',
            'action' => 'ledgerQuery',
            'form_params' => [
                'require' => [
                    'pageIdx' => 'pageIdx',
                    'pageSize' => 'pageSize',
                ],
                'optional' => [
                    'gameId' => 'gameId',
                    'status' => 'status',
                    'startDate' => 'startDate',
                    'endDate' => 'endDate',
                    'queryUserId' => 'queryUserId',
                    'recent' => 'recent'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'winner_sport' => [
            'method' => 'post',
            'action' => 'Find_Tix2',
            'form_params' => [
                'require' => [
                    'sdate' => 'sdate',
                    'edate' => 'edate',
                ],
                'optional' => [
                    'agent' => 'agent',
                    'page' => 'page',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'nine_k_lottery' => [
            'method' => 'POST',
            'action' => 'BetList',
            'form_params' => [
                'require' => [
                    'StartTime' => 'StartTime',
                    'EndTime' => 'EndTime',
                    'BossID' => 'BossID'
                ],
                'optional' => [
                    'Page' => 'Page'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'nine_k_lottery_2' => [
            'method' => 'POST',
            'action' => 'BetList',
            'form_params' => [
                'require' => [
                    'StartTime' => 'StartTime',
                    'EndTime' => 'EndTime',
                    'BossID' => 'BossID'
                ],
                'optional' => [
                    'Page' => 'Page'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'q_tech' => [
            'method' => 'GET',
            'action' => 'game-rounds',
            'form_params' => [
                'require' => [
                    'playerId' => 'playerId',
                    'from' => 'from',
                    'to' => 'to',
                ],
                'optional' => [
                    'size' => 'size',
                    'page' => 'page'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => []
            ],
        ],
        'bobo_poker' => [
            'method' => 'POST',
            'action' => 'datasouce/getBetRecordByHour',
            'form_params' => [
                'require' => [
                    'spId' => 'spId'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => []
            ],
        ],
        'wm_casino' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'forever_eight' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo_bull' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'vs_lottery' => [
            'method' => "POST",
            'action' => "GetBetTransaction",
            'form_params' => [
                'require' => [
                    'fromDate' => 'fromDate',
                    'toDate' => 'toDate',
                    'fromRowNo' => 'fromRowNo',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'habanero' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'incorrect_score' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'mg_poker' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
    ],

    /**
     * 取得限紅設定
     *
     * 參數統一名稱：
     * account          帳號
     */
    'getBetLimits' => [
        'all_bet' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo' => [
            'method' => 'PATCH',
            'action' => 'ticket-limits/{playerId}',
            'form_params' => [
                'require' => [],
                'optional' => [
                    'ticket_limits' => 'ticket_limits',
                    //一般玩法：單、雙、平，可設定: bet_max(上限)，bet_min(下限)
                    'normal_odd_even_draw' => 'normal_odd_even_draw',
                    //一般玩法：大、小、合，可設定: bet_max(上限)，bet_min(下限)
                    'normal_big_small_tie' => 'normal_big_small_tie',
                    //超級玩法(特別號)：大、小，可設定: bet_max(上限)，bet_min(下限)
                    'super_big_small' => 'super_big_small',
                    //超級玩法(特別號)：單、雙，可設定: bet_max(上限)，bet_min(下限)
                    'super_odd_even' => 'super_odd_even',
                    //超級玩法(特別號)：獨猜，可設定: bet_max(上限)，bet_min(下限)
                    'super_guess' => 'super_guess',
                    //星號，可設定: bet_max(上限)，bet_min(下限)
                    'star' => 'star',
                    //五行，可設定: bet_max(上限)，bet_min(下限)
                    'elements' => 'elements',
                    //四季，可設定: bet_max(上限)，bet_min(下限)
                    'seasons' => 'seasons',
                    //不出球，可設定: bet_max(上限)，bet_min(下限)
                    'other_fanbodan' => 'other_fanbodan'
                ],
            ],
            'route_params' => [
                'require' => [
                    'account' => 'playerId',
                ],
                'optional' => [],
            ],
        ],
        'holdem' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'sa_gaming' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'so_power' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'super_sport' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'maya' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'dream_game' => [
            'method' => 'POST',
            'action' => 'game/updateLimit/{agent}',
            'form_params' => [
                'require' => [
                    /**
                     * member json
                     * {
                     *    "username":"DG66777",
                     *    "password":"MD5(password)", 在 caller params 有實做 md5 無需自己加
                     *    "winLimit":0.0,
                     *    "status":1 会员状态：0:停用, 1:正常, 2:锁定(不能下注)
                     * }
                     */
                    'member' => 'member',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'super_lottery' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'royal_game' => [
            'method' => 'post',
            'action' => 'VPSMemberLimit',
            'form_params' => [
                'require' => [
                    'RequestID' => 'RequestID',
                    'Member' => 'Member',
                    'Control' => 'Control',
                    'LevelList' => 'LevelList',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ren_ni_ying' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'wm_casino' => [
            'method' => 'POST',
            'action' => 'EditLimit',
            'form_params' => [
                'require' => [
                    'user' => 'user',
                    'limitType' => 'limitType',
                    'syslang' => 'syslang'
                ],
                'optional' => [
                    // 最大可赢
                    'maxwin' => 'maxwin',
                    // 最大可輸
                    'maxlose' => 'maxlose',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'forever_eight' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo_bull' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'vs_lottery' => [
            'method' => "post",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'habanero' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'incorrect_score' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'mg_poker' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Update 修改資料
    |--------------------------------------------------------------------------
    */
    /**
     * 儲值點數
     *
     * 參數統一名稱：
     * host             站台號碼
     * account          帳號
     * agent            代理帳號
     * token            代幣值
     * trace_id         追蹤碼
     * oper_flag        儲值旗標
     */
    'deposit' => [
        'all_bet' => [
            'method' => 'POST',
            'action' => 'agent_client_transfer',
            'form_params' => [
                'require' => [
                    'trace_id' => 'sn',
                    'account' => 'client',
                    'point' => 'credit',
                    'agent' => 'agent',
                    'oper_flag' => 'operFlag',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo' => [
            'method' => 'POST',
            'action' => 'points/{playerId}/deposit',
            'form_params' => [
                'require' => [
                    'point' => 'volume',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [
                    'account' => 'playerId',
                ],
                'optional' => [],
            ],
        ],
        'holdem' => [
            'method' => 'POST',
            'action' => 'ChangePoints',
            'form_params' => [
                'require' => [
                    'host' => 'PlatformID',
                    'account' => 'MemberAccount',
                    'point' => 'Points',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'sa_gaming' => [
            'method' => 'POST',
            'action' => 'CreditBalanceDV',
            'form_params' => [
                'require' => [
                    'account' => 'Username',
                    'trace_id' => 'OrderId',
                    'point' => 'CreditAmount',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'so_power' => [
            'method' => 'POST',
            'action' => 'TRANSFER_CREDIT',
            'form_params' => [
                'require' => [
                    'method' => 'TRANSFER_CREDIT',
                    'Timestamp' => 'timestamp',
                    'Username' => 'username',
                    'Client_id' => 'client_id',
                    'Sign_Code' => 'sign_code'
                ],
                'optional' => [
                    'amount' => 'amount'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'super_sport' => [
            'method' => 'POST',
            'action' => 'points',
            'form_params' => [
                'require' => [
                    'account' => 'account',
                    'act' => 'act',
                    'point' => 'point',
                    'up_account' => 'up_account',
                    'up_password' => 'up_passwd',
                ],
                'optional' => [
                    'track_id' => 'track_id'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ufa_sport' => [
            'method' => 'get',
            'action' => 'deposit',
            'form_params' => [
                'require' => [
                    'secret' => 'secret',
                    'agent' => 'agent',
                    'username' => 'username',
                    'serial' => 'serial',
                    'amount' => 'amount'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'maya' => [
            'method' => 'GET',
            'action' => 'FundTransfer',
            'form_params' => [
                'require' => [
                    'VenderNo' => 'VenderNo',
                    'GameMemberID' => 'GameMemberID',
                    'VenderTransactionID' => 'VenderTransactionID',
                    'Amount' => 'Amount',
                    'Direction' => 'Direction'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
//        'nihtan' => [
//            'method' => 'POST',
//            'action' => 'api/transfer/cash-in',
//            'form_params' => [
//                'require' => [
//                    'user_id' => 'user_id',
//                    'user_name' => 'user_name',
//                    'user_ip' => 'user_ip',
//                    'amount' => 'amount',
//                ],
//                'optional' => [
//                    'pc_redirect' => 'pc_redirect',
//                    'mo_redirect' => 'mo_redirect',
//                    'callback_url' => 'callback_url',
//                    'check_url' => 'check_url'
//                ],
//            ],
//            'route_params' => [
//                'require' => [],
//                'optional' => [],
//            ],
//        ],
        'dream_game' => [
            'method' => 'POST',
            'action' => 'account/transfer/{agent}',
            'form_params' => [
                'require' => [
                    // 轉帳流水號
                    'data' => 'data',
                    /**
                     * member json
                     * {
                     *    "username":"会员账号",
                     *    "amount":"為存取款金額，正數存款負數取款，請確保保留不超過3位小數，否則將收到錯誤碼11"
                     * }
                     */
                    'member' => 'member',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [
                    'agent' => 'agent',
                ],
                'optional' => [],
            ],
        ],
        'super_lottery' => [
            'method' => 'POST',
            'action' => 'points',
            'form_params' => [
                'require' => [
                    'act' => 'act',
                    'up_account' => 'up_acc',
                    'up_password' => 'up_pwd',
                    'account' => 'account',
                    'point' => 'Point',
                ],
                'optional' => [
                    'track_id' => 'track_id'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
//        'hong_chow' => [
//            'method' => 'POST',
//            'action' => 'transfer',
//            'form_params' => [
//                'require' => [
//                    'account' => 'username',
//                    'point' => 'money',
//                    'type' => 'type',
//                ],
//                'optional' => [
//                    'out_trade_no' => 'out_trade_no',
//                ],
//            ],
//            'route_params' => [
//                'require' => [],
//                'optional' => [],
//            ],
//        ],
        'ameba' => [
            'method' => 'POST',
            'action' => 'deposit',
            'form_params' => [
                'require' => [
                    'account' => 'account_name',
                    'point' => 'amount',
                    'trace_id' => 'tx_id',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'real_time_gaming' => [
            'method' => 'POST',
            'action' => 'wallet/deposit/{amount}',
            'form_params' => [
                'require' => [
                    'playerLogin' => 'playerLogin',

                ],
                'optional' => [
                    'agentId' => 'agentId',
                    'trackingOne' => 'trackingOne',
                    'trackingTwo' => 'trackingTwo',
                    'trackingThree' => 'trackingThree',
                    'trackingFour' => 'trackingFour'
                ],
            ],
            'route_params' => [
                'require' => [
                    'amount' => 'amount'
                ],
                'optional' => [],
            ],
        ],
        'royal_game' => [
            'method' => 'post',
            'action' => 'VPSWFundTransfer',
            'form_params' => [
                'require' => [
                    'RequestID' => 'RequestID',
                    'Control' => 'Control',
                    'TransferID' => 'TransferID',
                    'BucketID' => 'BucketID',
                    'MemberID' => 'MemberID',
                    'TransferMoney' => 'TransferMoney',
                    'Operator' => 'Operator',
                    'IP' => 'IP'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ren_ni_ying' => [
            'method' => 'post',
            'action' => 'updatePlayerBalance',
            'form_params' => [
                'require' => [
                    'userId' => 'userId',
                    'amount' => 'amount'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'cq9_game' => [
            'method' => 'POST',
            'action' => 'player/deposit',
            'form_params' => [
                'require' => [
                    'account' => 'account',
                    'amount' => 'amount',
                    'mtcode' => 'mtcode',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'nine_k_lottery' => [
            'method' => 'POST',
            'action' => 'BalanceTransfer',
            'form_params' => [
                'require' => [
                    'MemberAccount' => 'MemberAccount',
                    'Balance' => 'Balance'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'nine_k_lottery_2' => [
            'method' => 'POST',
            'action' => 'BalanceTransfer',
            'form_params' => [
                'require' => [
                    'MemberAccount' => 'MemberAccount',
                    'Balance' => 'Balance'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'winner_sport' => [
            'method' => 'post',
            'action' => 'Transfer_Money',
            'form_params' => [
                'require' => [
                    'username' => 'username',
                    'money' => 'money',
                ],
                'optional' => [
                    'billno' => 'billno',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'q_tech' => [
            'method' => 'POST',
            'action' => 'fund-transfers',
            'form_params' => [
                'require' => [
                    'type' => 'type',
                    'referenceId' => 'referenceId',
                    'playerId' => 'playerId',
                    'amount' => 'amount',
                    'currency' => 'currency'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'wm_casino' => [
            'method' => 'POST',
            'action' => 'ChangeBalance',
            'form_params' => [
                'require' => [
                    'user' => 'user',
                    'money' => 'money',
                    'syslang' => 'syslang',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bobo_poker' => [
            'method' => 'POST',
            'action' => 'wallet/tran',
            'form_params' => [
                'require' => [
                    'spId' => 'spId',
                    'tranId' => 'tranId',
                    'account' => 'account',
                    'type' => 'type',
                    'amount' => 'amount'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'forever_eight' => [
            'method' => 'POST',
            'action' => 'tc',
            'form_params' => [
                'require' => [
                    'Loginname' => 'Loginname',
                    'Billno' => 'Billno',
                    'Type' => 'Type',
                    'Cur' => 'Cur',
                    'Credit' => 'Credit',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'slot_factory' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'cock_fight' => [
            'method' => 'POST',
            'action' => 'deposit',
            'form_params' => [
                'require' => [
                    'login_id' => 'login_id',
                    'name' => 'name',
                    'amount' => 'amount',
                    'ref_no' => 'ref_no',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'vs_lottery' => [
            'method' => 'POST',
            'action' => 'DepositWithdrawRef',
            'form_params' => [
                'require' => [
                    'userName' => 'userName',
                    'amount' => 'amount',
                    'clientRefTransId' => 'clientRefTransId',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo_bull' => [
            'method' => "POST",
            'action' => "/api/addPoint",
            'form_params' => [
                'require' => [
                    'account' => 'account',
                    'point' => 'point',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'cmd_sport' => [
            'method' => 'GET',
            'action' => 'balancetransfer',
            'form_params' => [
                'require' => [
                    'UserName' => 'UserName',
                    'PaymentType' => 'PaymentType',
                    'Money' => 'Money',
                    'TicketNo' => 'TicketNo',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'awc_sexy' => [
            'method' => 'POST',
            'action' => 'deposit',
            'form_params' => [
                'require' => [
                    'userId' => 'userId',
                    'transferAmount' => 'transferAmount',
                    'txCode' => 'txCode',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'habanero' => [
            'method' => "POST",
            'action' => "DepositPlayerMoney",
            'form_params' => [
                'require' => [
                    'Username' => 'Username',
                    'Password' => 'Password',
                    'CurrencyCode' => 'CurrencyCode',
                    'Amount' => 'Amount',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'kk_lottery' => [
            'method' => 'POST',
            'action' => 'fund/deposit',
            'form_params' => [
                'require' => [
                    'username' => 'username',
                    'amount' => 'amount',
                    'currencycode' => 'currencycode',
                    'orderid' => 'orderid',
                    'deposittime' => 'deposittime',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'incorrect_score' => [
            'method' => "POST",
            'action' => "ChangeBalance",
            'form_params' => [
                'require' => [
                    'user' => 'user',
                    'money' => 'money',
                    'code' => 'code',
                    'bussId' => 'bussId',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'mg_poker' => [
            'method' => "POST",
            'action' => "doTransferDepositTask",
            'form_params' => [
                'require' => [
                    'account' => 'account',
                    'money' => 'money',
                    'orderId' => 'orderId',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
    ],

    /**
     * 回收點數
     *
     * 參數統一名稱：
     * host             站台號碼
     * account          帳號
     * agent            代理帳號
     * token            代幣值
     * trace_id         追蹤碼
     * oper_flag        儲值旗標
     */
    'withdraw' => [
        'all_bet' => [
            'method' => 'POST',
            'action' => 'agent_client_transfer',
            'form_params' => [
                'require' => [
                    'trace_id' => 'sn',
                    'account' => 'client',
                    'point' => 'credit',
                    'agent' => 'agent',
                    'oper_flag' => 'operFlag',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo' => [
            'method' => 'POST',
            'action' => 'points/{playerId}/withdraw',
            'form_params' => [
                'require' => [
                    'point' => 'volume',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [
                    'account' => 'playerId',
                ],
                'optional' => [],
            ],
        ],
        'holdem' => [
            'method' => 'POST',
            'action' => 'ChangePoints',
            'form_params' => [
                'require' => [
                    'host' => 'PlatformID',
                    'account' => 'MemberAccount',
                    'point' => 'Points',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'sa_gaming' => [
            'method' => 'POST',
            'action' => 'DebitBalanceDV',
            'form_params' => [
                'require' => [
                    'account' => 'Username',
                    'trace_id' => 'OrderId',
                    'point' => 'DebitAmount',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'so_power' => [
            'method' => 'POST',
            'action' => 'TRANSFER_CREDIT',
            'form_params' => [
                'require' => [
                    'method' => 'TRANSFER_CREDIT',
                    'Timestamp' => 'timestamp',
                    'Username' => 'username',
                    'Client_id' => 'client_id',
                    'Sign_Code' => 'sign_code'
                ],
                'optional' => [
                    'amount' => 'amount'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'super_sport' => [
            'method' => 'POST',
            'action' => 'points',
            'form_params' => [
                'require' => [
                    'account' => 'account',
                    'act' => 'act',
                    'point' => 'point',
                    'up_account' => 'up_account',
                    'up_password' => 'up_passwd',
                ],
                'optional' => [
                    'track_id' => 'track_id'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ufa_sport' => [
            'method' => 'get',
            'action' => 'withdraw',
            'form_params' => [
                'require' => [
                    'secret' => 'secret',
                    'agent' => 'agent',
                    'username' => 'username',
                    'serial' => 'serial',
                    'amount' => 'amount'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'maya' => [
            'method' => 'GET',
            'action' => 'FundTransfer',
            'form_params' => [
                'require' => [
                    'VenderNo' => 'VenderNo',
                    'GameMemberID' => 'GameMemberID',
                    'VenderTransactionID' => 'VenderTransactionID',
                    'Amount' => 'Amount',
                    'Direction' => 'Direction'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
//        'nihtan' => [
//            'method' => 'POST',
//            'action' => 'api/transfer/cash-out',
//            'form_params' => [
//                'require' => [
//                    'user_id' => 'user_id',
//                    'user_name' => 'user_name',
//                    'user_ip' => 'user_ip',
//                    'amount' => 'amount',
//                ],
//                'optional' => [
//                    'callback_url' => 'callback_url'
//                ],
//            ],
//            'route_params' => [
//                'require' => [],
//                'optional' => [],
//            ],
//        ],
        'dream_game' => [
            'method' => 'POST',
            'action' => 'account/transfer/{agent}',
            'form_params' => [
                'require' => [
                    // 轉帳流水號
                    'data' => 'data',
                    /**
                     * member json
                     * {
                     *    "username":"会员账号",
                     *    "amount":"（負數）為存取款金額，正數存款負數取款，請確保保留不超過3位小數，否則將收到錯誤碼11"
                     * }
                     */
                    'member' => 'member',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [
                    'agent' => 'agent',
                ],
                'optional' => [],
            ],
        ],
        'super_lottery' => [
            'method' => 'POST',
            'action' => 'points',
            'form_params' => [
                'require' => [
                    'act' => 'act',
                    'up_account' => 'up_acc',
                    'up_password' => 'up_pwd',
                    'account' => 'account',
                    'point' => 'Point',
                ],
                'optional' => [
                    'track_id' => 'track_id'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
//        'hong_chow' => [
//            'method' => 'POST',
//            'action' => 'transfer',
//            'form_params' => [
//                'require' => [
//                    'account' => 'username',
//                    'point' => 'money',
//                    'type' => 'type',
//                ],
//                'optional' => [
//                    'out_trade_no' => 'out_trade_no',
//                ],
//            ],
//            'route_params' => [
//                'require' => [],
//                'optional' => [],
//            ],
//        ],
        'ameba' => [
            'method' => 'POST',
            'action' => 'withdraw',
            'form_params' => [
                'require' => [
                    'account' => 'account_name',
                    'point' => 'amount',
                    'trace_id' => 'tx_id',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'real_time_gaming' => [
            'method' => 'POST',
            'action' => 'wallet/withdraw/{amount}',
            'form_params' => [
                'require' => [
                    'playerLogin' => 'playerLogin'
                ],
                'optional' => [
                    'agentId' => 'agentId',
                    'trackingOne' => 'trackingOne',
                    'trackingTwo' => 'trackingTwo',
                    'trackingThree' => 'trackingThree',
                    'trackingFour' => 'trackingFour'
                ],
            ],
            'route_params' => [
                'require' => [
                    'amount' => 'amount'
                ],
                'optional' => [],
            ],
        ],
        'royal_game' => [
            'method' => 'post',
            'action' => 'VPSWFundTransfer',
            'form_params' => [
                'require' => [
                    'RequestID' => 'RequestID',
                    'Control' => 'Control',
                    'TransferID' => 'TransferID',
                    'BucketID' => 'BucketID',
                    'MemberID' => 'MemberID',
                    'TransferMoney' => 'TransferMoney',
                    'Operator' => 'Operator',
                    'IP' => 'IP'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'cq9_game' => [
            'method' => 'POST',
            'action' => 'player/withdraw',
            'form_params' => [
                'require' => [
                    'account' => 'account',
                    'amount' => 'amount',
                    'mtcode' => 'mtcode',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ren_ni_ying' => [
            'method' => 'post',
            'action' => 'updatePlayerBalance',
            'form_params' => [
                'require' => [
                    'userId' => 'userId',
                    'amount' => 'amount',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'nine_k_lottery' => [
            'method' => 'POST',
            'action' => 'BalanceTransfer',
            'form_params' => [
                'require' => [
                    'MemberAccount' => 'MemberAccount',
                    'Balance' => 'Balance',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'nine_k_lottery_2' => [
            'method' => 'POST',
            'action' => 'BalanceTransfer',
            'form_params' => [
                'require' => [
                    'MemberAccount' => 'MemberAccount',
                    'Balance' => 'Balance',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'winner_sport' => [
            'method' => 'post',
            'action' => 'Transfer_Money',
            'form_params' => [
                'require' => [
                    'username' => 'username',
                    'money' => 'money',
                ],
                'optional' => [
                    'billno' => 'billno',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'q_tech' => [
            'method' => 'POST',
            'action' => 'fund-transfers',
            'form_params' => [
                'require' => [
                    'type' => 'type',
                    'referenceId' => 'referenceId',
                    'playerId' => 'playerId',
                    'amount' => 'amount',
                    'currency' => 'currency'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'wm_casino' => [
            'method' => 'POST',
            'action' => 'ChangeBalance',
            'form_params' => [
                'require' => [
                    'user' => 'user',
                    'money' => 'money',
                    'syslang' => 'syslang',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bobo_poker' => [
            'method' => 'POST',
            'action' => 'wallet/tran',
            'form_params' => [
                'require' => [
                    'spId' => 'spId',
                    'tranId' => 'tranId',
                    'account' => 'account',
                    'type' => 'type',
                    'amount' => 'amount'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'forever_eight' => [
            'method' => "POST",
            'action' => "tc",
            'form_params' => [
                'require' => [
                    'Loginname' => 'Loginname',
                    'Billno' => 'Billno',
                    'Type' => 'Type',
                    'Cur' => 'Cur',
                    'Credit' => 'Credit',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'slot_factory' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'cock_fight' => [
            'method' => "POST",
            'action' => "withdraw",
            'form_params' => [
                'require' => [
                    'login_id' => 'login_id',
                    'amount' => 'amount',
                    'ref_no' => 'ref_no',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'vs_lottery' => [
            'method' => 'POST',
            'action' => 'DepositWithdrawRef',
            'form_params' => [
                'require' => [
                    'userName' => 'userName',
                    'amount' => 'amount',
                    'clientRefTransId' => 'clientRefTransId',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo_bull' => [
            'method' => "POST",
            'action' => "/api/deductionPoint",
            'form_params' => [
                'require' => [
                    'account' => 'account',
                    'point' => 'point',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'cmd_sport' => [
            'method' => 'GET',
            'action' => 'balancetransfer',
            'form_params' => [
                'require' => [
                    'UserName' => 'UserName',
                    'PaymentType' => 'PaymentType',
                    'Money' => 'Money',
                    'TicketNo' => 'TicketNo',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'awc_sexy' => [
            'method' => 'POST',
            'action' => 'withdraw',
            'form_params' => [
                'require' => [
                    'userId' => 'userId',
                    'txCode' => 'txCode',
                    'withdrawType' => 'withdrawType',
                    'transferAmount' => 'transferAmount',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'kk_lottery' => [
            'method' => 'POST',
            'action' => 'fund/withdraw',
            'form_params' => [
                'require' => [
                    'username' => 'username',
                    'amount' => 'amount',
                    'currencycode' => 'currencycode',
                    'orderid' => 'orderid',
                    'withdrawtime' => 'withdrawtime',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'habanero' => [
            'method' => "POST",
            'action' => "WithdrawPlayerMoney",
            'form_params' => [
                'require' => [
                    'Username' => 'Username',
                    'Password' => 'Password',
                    'CurrencyCode' => 'CurrencyCode',
                    'Amount' => 'Amount',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'incorrect_score' => [
            'method' => "POST",
            'action' => "ChangeBalance",
            'form_params' => [
                'require' => [
                    'user' => 'user',
                    'money' => 'money',
                    'code' => 'code',
                    'bussId' => 'bussId',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'mg_poker' => [
            'method' => "POST",
            'action' => "doTransferWithdrawTask",
            'form_params' => [
                'require' => [
                    'account' => 'account',
                    'money' => 'money',
                    'orderId' => 'orderId',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
    ],

    /**
     * 修改個人資料
     *
     * 參數統一名稱：
     * host                 站台號碼
     * account              帳號
     * name                 名稱/匿稱
     * vip_ticket_limits    vip 限紅
     * normal_ticket_limits 一般限紅
     * day_max_win          單日最大贏額限制/單日最高可贏額度
     * day_max_win_switch   單日最大贏額限制/單日最高可贏額度 開關
     * day_max_lost         單日最大輸額限制/單日最高可輸額度
     * day_max_lost_switch  單日最大輸額限制/單日最高可輸額度 開關
     * day_credit           單日信用額度
     * day_credit_switch    單日信用額度 開關
     * remark               註解
     */
    'updateProfile' => [
        'all_bet' => [
            'method' => 'POST',
            'action' => 'modify_client',
            'form_params' => [
                'require' => [
                    'account' => 'client',
                ],
                'optional' => [
                    'vip_ticket_limits' => 'vipHandicaps',
                    'normal_ticket_limits' => 'orHandicaps',
                    'day_max_win' => 'maxWin',
                    'day_max_lost' => 'maxLost',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo' => [
            'method' => 'PATCH',
            'action' => 'players/{playerId}',
            'form_params' => [
                'require' => [],
                'optional' => [
                    // 顯示名稱
                    'name' => 'name',
                    // 群組名稱
                    'group' => 'muster',
                    // 單日最高可贏額度
                    'day_max_win' => 'day_winnings_quota',
                    // 單日最高可贏額度限制狀態 0: 停用限制 1: 啟用
                    'day_max_win_switch' => 'day_winnings_quota_status',
                    // 單日最高可輸額度
                    'day_max_lost' => 'day_losings_quota',
                    // 單日最高可輸額度限制狀態 0: 停用限制 1: 啟用
                    'day_max_lost_switch' => 'day_losings_quota_status',
                    // 單日最高下注額度
                    'day_credit' => 'day_bets_quota',
                    // 單日最高下注額度限制狀態 0: 停用限制 1: 啟用
                    'day_credit_switch' => 'day_bets_quota_status',
                    // 註解
                    'remark' => 'remark',
                ],
            ],
            'route_params' => [
                'require' => [
                    'account' => 'playerId',
                ],
                'optional' => [],
            ],
        ],
        'holdem' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'sa_gaming' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'so_power' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'super_sport' => [
            'method' => 'POST',
            'action' => 'account',
            'form_params' => [
                'require' => [
                    'act' => 'act',
                    'account' => 'account',
                    'old_password' => 'old_passwd',
                    'up_account' => 'up_account',
                    'up_password' => 'up_passwd',
                    'level' => 'level'
                ],
                'optional' => [
                    'password' => 'passwd',
                    'nickname' => 'nickname',
                    'allowed_playing' => 'allowed_playing'
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'maya' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'dream_game' => [
            'method' => 'POST',
            'action' => 'user/update/{agent}',
            'form_params' => [
                'require' => [
                    /**
                     * member json
                     * {
                     *    "username":"DG66777",
                     *    "password":"MD5(password)", 在 caller params 有實做 md5 無需自己加
                     *    "winLimit":0.0,
                     *    "status":1 会员状态：0:停用, 1:正常, 2:锁定(不能下注)
                     * }
                     */
                    'member' => 'member',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [
                    'agent' => 'agent',
                ],
                'optional' => [],
            ],
        ],
        'super_lottery' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'real_time_gaming' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ren_ni_ying' => [
            'method' => 'post',
            'action' => 'updatePlayerState',
            'form_params' => [
                'require' => [
                    'userId' => 'userId',
                    'state' => 'state'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'winner_sport' => [
            'method' => 'post',
            'action' => 'Member_Edit',
            'form_params' => [
                'require' => [
                    'username' => 'username',
                    'top' => 'top'
                ],
                'optional' => [
                    'alias' => 'alias',
                    'istest' => 'istest',
                    'status' => 'status',
                    'defname' => 'defname',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'wm_casino' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'forever_eight' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo_bull' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'habanero' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'incorrect_score' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'mg_poker' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [

                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 其他動作
    |--------------------------------------------------------------------------
    */
    // 剔除線上會員 / 登出
    'kickOnlinePlayer' => [
        'all_bet' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'holdem' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'sa_gaming' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'so_power' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'super_sport' => [
            'method' => 'POST',
            'action' => 'logout',
            'form_params' => [
                'require' => [
                    'account' => 'account'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ufa_sport' => [
            'method' => 'get',
            'action' => 'logout',
            'form_params' => [
                'require' => [
                    'secret' => 'secret',
                    'agent' => 'agent',
                    'username' => 'username',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'maya' => [
            'method' => 'GET',
            'action' => 'KickMembers',
            'form_params' => [
                'require' => [
                    'VenderNo' => 'VenderNo',
                    'GameMemberIDs' => 'GameMemberIDs',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'dream_game' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'super_lottery' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'real_time_gaming' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ren_ni_ying' => [
            'method' => 'post',
            'action' => 'logoutPlayer',
            'form_params' => [
                'require' => [
                    'userId' => 'userId'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'cq9_game' => [
            'method' => 'post',
            'action' => 'player/logout',
            'form_params' => [
                'require' => [
                    'account' => 'account'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'wm_casino' => [
            'method' => 'POST',
            'action' => 'LogoutGame',
            'form_params' => [
                'require' => [
                    'user' => 'user',
                    'syslang' => 'syslang'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'forever_eight' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'cmd_sport' => [
            'method' => 'get',
            'action' => 'kickuser',
            'form_params' => [
                'require' => [
                    'UserName' => 'UserName'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo_bull' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'vs_lottery' => [
            'method' => "POST",
            'action' => "KickOutPlayer",
            'form_params' => [
                'require' => [
                    'userName' => 'userName',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'habanero' => [
            'method' => "POST",
            'action' => "LogOutPlayer",
            'form_params' => [
                'require' => [
                    'Username' => 'Username',
                    'Password' => 'Password',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'incorrect_score' => [
            'method' => "POST",
            'action' => "LogoutGame",
            'form_params' => [
                'require' => [
                    'user' => 'user'
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'mg_poker' => [
            'method' => "POST",
            'action' => "kickUser",
            'form_params' => [
                'require' => [
                    'account' => 'account',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
    ],

    /**
     * 修改下注限紅
     *
     * 參數統一名稱：
     * account          帳號
     */
    'updateBetLimit' => [
        'all_bet' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'holdem' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'sa_gaming' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'so_power' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'super_sport' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ufa_sport' => [
            'method' => 'get',
            'action' => 'update',
            'form_params' => [
                'require' => [
                    'secret' => 'secret',
                    'agent' => 'agent',
                    'username' => 'username',
                    'max1' => 'max1',  // <早盤 / 今日 / 滾球的最大賭注>
                    'max2' => 'max2',  // <1X2 / 雙重機會的最大賭注>
                    'max3' => 'max3',  // <混合過關的最大賭注>
                    'max4' => 'max4',  // <正確分數/總進球/半場全場/第一個進球最後一個進球的最大投注>
                    'max5' => 'max5',  // <其他體育早盤 / 今日 / 滾球的最大賭注>
                    'lim1' => 'lim1',  // <早盤 / 今日 / 滾球的每匹配匹配>
                    'lim2' => 'lim2',  // <1X2 / 雙重機會的每場比賽匹配>
                    'lim3' => 'lim3',  // <每組合混合過關限制>
                    'lim4' => 'lim4',  // <每場比賽的正確比分/總進球/半場全場/第一球進球最後一球>
                    'lim5' => 'lim5',  // <其他運動HDP / OU / OE的每場比賽匹配>
                    'comtype' => 'comtype',  // <HDP / OU / OE的A，B，C，D，E，F，G，H，I，J的選擇>
                    'com1' => 'com1',  // <早盤 / 今日 / 滾球 佣金>
                    'com2' => 'com2',  // <1X2 / 雙重機會的佣金>
                    'com3' => 'com3',  // <混合過關佣金>
                    'com4' => 'com4',  // <其他佣金>
                    'suspend' => 'suspend'  // <0：沒有暫停，1：暫停>
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'maya' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'dream_game' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'super_lottery' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'real_time_gaming' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'royal_game' => [
            'method' => 'post',
            'action' => 'VPSMemberLimit',
            'form_params' => [
                'require' => [
                    'RequestID' => 'RequestID',
                    'Member' => 'Member',
                    'Control' => 'Control',
                    'List' => 'List',
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ren_ni_ying' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'wm_casino' => [
            'method' => 'POST',
            'action' => 'EditLimit',
            'form_params' => [
                'require' => [
                    'user' => 'user',
                    'limitType' => 'limitType',
                    'syslang' => 'syslang'
                ],
                'optional' => [
                    // 最大可赢
                    'maxwin' => 'maxwin',
                    // 最大可輸
                    'maxlose' => 'maxlose',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'forever_eight' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo_bull' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'vs_lottery' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'habanero' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'incorrect_score' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'mg_poker' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
    ],

    /**
     * 取得所有賽事列表
     *
     */
    'getGameMoreList' => [
        'all_bet' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'holdem' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'sa_gaming' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'so_power' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'super_sport' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ufa_sport' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'maya' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'dream_game' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'super_lottery' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'real_time_gaming' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'royal_game' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ren_ni_ying' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'wm_casino' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [
                ],
                'optional' => [
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'forever_eight' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo_bull' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'vs_lottery' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'habanero' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'incorrect_score' => [
            'method' => "POST",
            'action' => "GetGameMoreList",
            'form_params' => [
                'require' => [
                    'stype' => 'stype',
                ],
                'optional' => [
                    'sdate' => 'sdate',
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'mg_poker' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
    ],

    /**
     * 取得賽事結果
     *
     */
    'getGameResults' => [
        'all_bet' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'holdem' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'sa_gaming' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'so_power' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'super_sport' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ufa_sport' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'maya' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'dream_game' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'super_lottery' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'real_time_gaming' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'royal_game' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [
                ],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'ren_ni_ying' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'wm_casino' => [
            'method' => '',
            'action' => '',
            'form_params' => [
                'require' => [
                ],
                'optional' => [
                ],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'forever_eight' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'bingo_bull' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'vs_lottery' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'habanero' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'incorrect_score' => [
            'method' => "POST",
            'action' => "GetGameResults",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
        'mg_poker' => [
            'method' => "",
            'action' => "",
            'form_params' => [
                'require' => [],
                'optional' => [],
            ],
            'route_params' => [
                'require' => [],
                'optional' => [],
            ],
        ],
    ],
];
