<?php
/*
|--------------------------------------------------------------------------
| 各遊戲館 - 遊戲別 - 玩法配置檔，與其金鑰
|--------------------------------------------------------------------------
| 遊戲館如：
|   sa_gaming
|   all_bet
|   bingo
|   super_sport
|
| 遊戲別（視各遊戲館文件定義）如：
|   sa_gaming -> bac (百家樂)
|   sa_gaming -> dtx (龍虎)
|   ...
|   super_sport -> baseball_tw (台棒)
|   super_sport -> baseball_jp (日棒)
|   super_sport -> baseball_us (美棒)
|   ...
|
| 玩法（視各遊戲館文件定義）如：
|   general 通用，指返水、限額設定，無論是什麼玩法都統一使用此配置值
|   first_round 第一局
|   second_round 第二局
|   ...
|   first_half 上半場
|   second_half 下半場
|   ...
|   big_small 大小
|   edd_even 單雙
|   ...
*/
return [
    // 沙龍
    'sa_gaming' => [
        'config' => [
            'api_url' => env('SA_GAMING_API_URL'),
            'md5_key' => env('SA_GAMING_MD5_KEY'),
            'secret_key' => env('SA_GAMING_SECRET_KEY'),
            'encrypt_key' => env('SA_GAMING_ENCRYPT_KEY'),
            'lobby_code' => env('SA_GAMING_LOBBY_CODE'),
            'play_url' => env('SA_GAMING_PLAY_URL'),
            'currency' => env('SA_GAMING_DEFAULT_CURRENCY'),
            'test_account' => env('SA_GAMING_TEST_MEMBER_ACCOUNT'),
            'test_password' => env('SA_GAMING_TEST_MEMBER_PASSWORD'),
            'maxWinning' => env('SA_GAMING_MAX_WINNING'),
        ],
        'rate' => [
            // 各幣別對應遊戲內點數之比例
            // 系統1000VD = 遊戲1VD
            'VND' => 1000, //越南盾
        ],
        'deposit_limit' => [
            // 各幣別轉入遊戲館時的最小單位限制
            'VND' => 10,  //越南盾
        ],
        'game_scopes' => [
            /** 索引使用「文件定義」的玩法名稱 */
            // 百家樂
            'bac' => [
                'general', // 通用
            ],
            // 龍虎
            'dtx' => [
                'general', // 通用
            ],
            // 骰寶
            'sicbo' => [
                'general', // 通用
            ],
            // 翻攤
            'ftan' => [
                'general', // 通用
            ],
            // 輪盤
            'rot' => [
                'general', // 通用
            ],
            // 電子遊藝
            'slot' => [
                'general', // 通用
            ],
            // 小遊戲
            'minigame' => [
                'general', // 通用
            ],
            // 小遊戲
            'multiplayer' => [
                'general', // 通用
            ],
            // 幸運輪盤
            'moneywheel' => [
                'general',
            ],
        ],
        'period_maintain' => [
            // 北京时间 2018年11月05日早上11时至下午1时30分进行系统维护。
            // day~hh:mm-hh:mm
            '1~11:00-13:30',
        ],
    ],
    // 歐博
    'all_bet' => [
        'config' => [
            'api_url' => env('ALL_BET_API_URL'),
            'property_id' => env('ALL_BET_PROPERTY_ID'),
            'des_key' => env('ALL_BET_DES_KEY'),
            'des_iv' => env('ALL_BET_DES_IV'),
            'md5_key' => env('ALL_BET_MD5_KEY'),
            'agent' => env('ALL_BET_AGENT_ACCOUNT'),
            'test_account' => env('ALL_BET_TEST_MEMBER_ACCOUNT'),
            'test_password' => env('ALL_BET_TEST_MEMBER_PASSWORD'),
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的玩法名稱 */
            // 普通百家樂
            'baccarat_ordinary' => [
                'general', // 通用
            ],
            // VIP 百家樂
            'baccarat_vip' => [
                'general', // 通用
            ],
            // 急速百家樂
            'baccarat_fast' => [
                'general', // 通用
            ],
            // 競咪百家樂
            'baccarat_compete' => [
                'general', // 通用
            ],
            // 骰寶
            'dice' => [
                'general', // 通用
            ],
            // 龍虎
            'dragon_tiger' => [
                'general', // 通用
            ],
            // 輪盤
            'roulette' => [
                'general', // 通用
            ],
            // 歐洲廳百家樂
            'baccarat_europe' => [
                'general', // 通用
            ],
            // 歐洲廳輪盤
            'roulette_europe' => [
                'general', // 通用
            ],
            // 歐洲廳 21 點
            'blackjack_europe' => [
                'general', // 通用
            ],
            // 聚寶百家樂
            'baccarat' => [
                'general', // 通用
            ],
            // 牛牛
            'bull_bull' => [
                'general', // 通用
            ],
            // 炸金花
            'win_three_card' => [
                'general', // 通用
            ],
            // 空戰世紀
            'air_force' => [
                'general', // 通用
            ],
        ],
        'period_maintain' => [
            // 日期：2018/11/07 （星期三）
            // 時間：08:00 a.m. - 12:00 p.m. (GMT+8)
            // day~hh:mm-hh:mm
            '3~08:00-12:00',
        ],
    ],
    // BINGO
//    'bingo' => [
//        'config' => [
//            'api_url' => env('BINGO_API_URL'),
//            'api_key' => env('BINGO_API_KEY'),
//            'test_account' => env('BINGO_TEST_MEMBER_ACCOUNT'),
//            'test_password' => env('BINGO_TEST_MEMBER_PASSWORD'),
//        ],
//        'game_scopes' => [
//            /** 索引使用「文件定義」的玩法名稱 */
//            // 賓果星
//            'bingo_star' => [
//                // 押星號
//                'star',
//                // 超級玩法(特別號)：單、雙
//                'super_odd_even',
//                // 超級玩法(特別號)：大、小
//                'super_big_small',
//                // 超級玩法(特別號)：獨猜
//                'super_guess',
//                // 一般玩法：單、雙、平
//                'normal_odd_even_draw',
//                // 一般玩法：大、小、合
//                'normal_big_small_tie',
//                // 五行
//                'elements',
//                // 四季
//                'seasons',
//                // 猜不出
//                'other_fanbodan',
//            ],
//        ],
//        'period_maintain' => [
//            // 週一 中午 pm:12:00 ~ pm: 4:00
//            // day~hh:mm-hh:mm
//            '1~12:00-16:30',
//        ],
//    ],
    // SUPER 體彩
    'super_sport' => [
        'config' => [
            'api_url' => env('SUPER_SPORT_API_URL'),
            'api_route' => env('SUPER_SPORT_API_ROUTE'),
            'api_key' => env('SUPER_SPORT_API_KEY'),
            'api_iv' => env('SUPER_SPORT_API_IV'),
            'up_account' => env('SUPER_SPORT_AGENT_ACCOUNT'),
            'up_password' => env('SUPER_SPORT_AGENT_PASSWORD'),
            'test_account' => env('SUPER_SPORT_TEST_MEMBER_ACCOUNT'),
            'test_password' => env('SUPER_SPORT_TEST_MEMBER_PASSWORD'),
            'currency' => env('SUPER_SPORT_CURRENCY', 'TWD'),
        ],
        'rate' => [
            // 各幣別對應遊戲內點數之比例
            // 系統1000VD = 遊戲1VD
            'VND' => 1000, //越南盾
        ],
        'deposit_limit' => [
            // 各幣別轉入遊戲館時的最小單位限制
            'VND' => 10,  //越南盾
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的玩法名稱 */
            // 美棒
            'baseball_us' => [
                'general', // 通用
            ],
            // 日棒
            'baseball_jp' => [
                'general', // 通用
            ],
            // 台棒
            'baseball_tw' => [
                'general', // 通用
            ],
            // 韓棒
            'baseball_kr' => [
                'general', // 通用
            ],
            // 冰球
            'ice_hockey' => [
                'general', // 通用
            ],
            // 籃球
            'basketball' => [
                'general', // 通用
            ],
            // 美足（美式足球）
            'american_football' => [
                'general', // 通用
            ],
            // 網球
            'tennis' => [
                'general', // 通用
            ],
            // 足球（英式足球）
            'soccer' => [
                'general', // 通用
            ],
            // 指數
            'stock_market' => [
                'general', // 通用
            ],
            // 賽馬
            'horse_racing' => [
                'general', // 通用
            ],
            // 電競
            'e_sports' => [
                'general', // 通用
            ],
            // 其他
            'others' => [
                'general', // 通用
            ],
            // 世足
            'fifa_world_cup' => [
                'general', // 通用
            ],
            // 彩票
            'lottery' => [
                'general' // 通用
            ],
        ],
        'period_maintain' => [
            // 週一 中午 pm:12:00 ~ pm: 4:00
            // day~hh:mm-hh:mm
            '1~12:00-16:30',
        ],
    ],
    // MAYA 瑪雅
//    'maya' => [
//        'config' => [
//            'api_url' => env('MAYA_API_URL'),
//            'property_id' => env('MAYA_API_PROPERTY_ID'),
//            'md5_key' => env('MAYA_MD5_KEY'),
//            'des_key' => env('MAYA_DES_KEY'),
//            'site_no' => env('MAYA_SITE_NO'),
//            'test_site_no' => env('MAYA_TEST_SITE_NO', env('MAYA_TEST_SITE_NO', 'test')),
//            'test_account' => env('MAYA_TEST_MEMBER_ACCOUNT'),
//            'test_password' => env('MAYA_TEST_MEMBER_PASSWORD'),
//            'test_game_config_id' => env('MAYA_TEST_MEMBER_GMAE_CONFIG_ID'),
//        ],
//        'game_scopes' => [
//            /** 索引使用「自定義」的玩法名稱 */
//            // 百家樂
//            'Baccarat' => [
//                'general', // 通用
//            ],
//            // 輪盤
//            'Roulette' => [
//                'general', // 通用
//            ],
//            // 龍虎
//            'LongHu' => [
//                'general', // 通用
//            ],
//            // 競咪百家樂
//            'BIDBaccarat' => [
//                'general', // 通用
//            ],
//            // 百家樂包桌
//            'VIPBaccarat' => [
//                'general', // 通用
//            ],
//            // 骰子
//            'Dice' => [
//                'general', // 通用
//            ],
//            // 保險百家樂
//            'INSBaccarat' => [
//                'general', // 通用
//            ],
//            // 牛牛
//            'NiuNiu' => [
//                'general', // 通用
//            ],
//            // 三王牌
//            'ThreeCardPoker' => [
//                'general', // 通用
//            ],
//            // 色碟
//            'SeDie' => [
//                'general', // 通用
//            ],
//        ],
//        'period_maintain' => [
//            // 玛雅视讯维护 周二 北京时间 7：30am —-9: 30am
//            // day~hh:mm-hh:mm
//            '2~07:30-09:30',
//        ],
//    ],
    // NIHTAN 泥炭
//    'nihtan' => [
//        'config' => [
//            'vendor_name' => env('NIHTAN_VENDOR_NAME'),
//            'secret_key' => env('NIHTAN_SECRET_KEY'),
//            'api_url' => env('NIHTAN_API_URL'),
//        ],
//        'game_scopes' => [
//            /** 索引使用「自定義」的玩法名稱 */
//            // 百家樂
//            'Baccarat' => [
//                'general', // 通用
//            ],
//            // 龍虎
//            'Dragon-Tiger' => [
//                'general', // 通用
//            ],
//            // 德州撲克
//            'Poker' => [
//                'general', // 通用
//            ],
//            // 骰寶
//            'Sicbo' => [
//                'general', // 通用
//            ]
//        ],
//        'period_maintain' => [
//            // xx
//            // day~hh:mm-hh:mm
//            '1~00:00-23:59',
//            '2~00:00-23:59',
//            '3~00:00-23:59',
//            '4~00:00-23:59',
//            '5~00:00-23:59',
//            '6~00:00-23:59',
//            '7~00:00-23:59',
//        ],
//    ],
    // DG 電子
    'dream_game' => [
        'config' => [
            'api_url' => env('DREAM_GAME_API_URL'),
            'report_url' => env('DREAM_GAME_REPORT_URL'),
            'api_key' => env('DREAM_GAME_API_KEY'),
            'api_agent' => env('DREAM_GAME_API_AGENT'),
            'api_mobile_suffix' => env('DREAM_GAME_MOBILE_SUFFIX'),
            'api_token' => env('DREAM_GAME_API_TOKEN'),
            'api_member_betting_limit' => env('DREAM_GAME_MEMBER_BETTING_LIMIT'),
            'api_member_winning_limit' => env('DREAM_GAME_MEMBER_WINNING_LIMIT'),
            'api_language' => [
                0 => 'en', // 英文
                1 => 'cn', // 中文简体
                2 => 'tw', // 中文繁体
                3 => 'kr', // 韩语
                4 => 'my', // 缅甸语
                5 => 'th', // 泰语
            ],
            'api_currency' => [
                '1' => 'CNY',  // 人民币
                '2' => 'USD',  // 美元
                '3' => 'MYR',  // 马来西亚币
                '4' => 'HKD',  // 港币
                '5' => 'THB',  // 泰珠
                '6' => 'SGD',  // 新加坡元
                '7' => 'PHP',  // 菲律宾比索
                '8' => 'TWD',  // 台币
                '9' => 'VND',  // 越南盾
                '10' => 'IDR',  // 印尼(盾)
                '11' => 'JPY',  // 日元
                '12' => 'KHR',  // 柬埔寨币
                '13' => 'KRW',  // 韩元
                '16' => 'AUD',  // 澳大利亚元
                '19' => 'INR',  // 印度卢比
                '20' => 'EUR',  // 欧元
                '21' => 'GBP',  // 英镑
                '22' => 'CAD',  // 加拿大
                '23' => 'KRW2', // 韩元       已去除3个0，游戏中1块，等同于实际1000块
                '24' => 'MMK',  // 缅甸币
                '25' => 'MMK2', // 缅甸币     已去除3个0，游戏中1块，等同于实际1000块
                '29' => 'VND2', // 越南盾     已去除3个0，游戏中1块，等同于实际1000块
                '30' => 'IDR2', // 印尼(盾)   已去除3个0，游戏中1块，等同于实际1000块
            ],
            'currency' => env('DREAM_GAME_DEFAULT_CURRENCY', 'TWD'),
            'test_account' => env('DREAM_GAME_TEST_MEMBER_ACCOUNT'),
            'test_password' => env('DREAM_GAME_TEST_MEMBER_PASSWORD'),
        ],
        'rate' => [
            // 各幣別對應遊戲內點數之比例
            // 系統1000VD = 遊戲1VD
            'VND2' => 1000, //越南盾
        ],
        'deposit_limit' => [
            // 各幣別轉入遊戲館時的最小單位限制
            'VND2' => 10,  //越南盾
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的玩法名稱 GameType + GameId */
            // 百家樂 1 1
            'baccarat' => [
                'general', // 通用
            ],
            // 現場百家樂 1 1
            'live_baccarat' => [
                'general', // 通用
            ],
            // 波貝百家樂 1 1
            'bobe_baccarat' => [
                'general', // 通用
            ],
            // 波貝 VIP 百家樂 1 10
            'bobe_vip_baccarat' => [
                'general', // 通用
            ],
            // 波貝保險百家樂 1 2
            'bobe_insurance_baccarat' => [
                'general', // 通用
            ],
            // 競咪百家樂 1 8
            'compete_baccarat' => [
                'general', // 通用
            ],
            // 龍虎 1 3
            'dragon_tiger' => [
                'general', // 通用
            ],
            // 現場龍虎 1 3
            'live_dragon_tiger' => [
                'general', // 通用
            ],
            // 輪盤 1 4
            'roulette' => [
                'general', // 通用
            ],
            // 現場輪盤 1 4
            'live_roulette' => [
                'general', // 通用
            ],
            // 骰寶 1 5
            'dice' => [
                'general', // 通用
            ],
            // 極速骰寶 1 12
            'fast_dice' => [
                'general', // 通用
            ],
            // 波貝骰寶 1 5
            'bobe_dice' => [
                'general', // 通用
            ],
            // 鬥牛 1 7
            'bull_fighting' => [
                'general', // 通用
            ],
            // 波貝鬥牛 1 7
            'bobe_bull_fighting' => [
                'general', // 通用
            ],
            // 炸金花 1 11
            'fried_golden_flower' => [
                'general', // 通用
            ],
            // 現場賭場撲克 1 9
            'show_hand' => [
                'general', // 通用
            ],
            // 現場牛牛
            "live_niuniu" => [
                'general', // 通用
            ],
            // 牛牛
            "NiuNiu" => [
                'general', // 通用
            ],
            // 翻攤
            "FanTan" => [
                'general', // 通用
            ],
            "insurance_baccara" => [
                'general', // 通用
            ],
            "disc" => [
                'general', // 通用
            ],
            // 魚蝦蟹
            'fishPrawnCrab' => [
                'general', // 通用
            ],
        ],
        'period_maintain' => [
            // 日期：2018/11/07 （星期三）
            // 時間：08:00 a.m. - 12:00 p.m. (GMT+8)
            // day~hh:mm-hh:mm
            '2~00:00-00:00',
        ],
    ],
    // 手中寶 keno
//    'so_power' => [
//        'config' => [
//            'api_url' => env('SO_POWER_API_URL'),
//            'api_lobby_url' => env('SO_POWER_LOBBY_URL'),
//            'api_client_id' => env('SO_POWER_API_CLIENT_ID'),
//            'api_client_secret' => env('SO_POWER_API_CLIENT_SECRET'),
//        ],
//        'game_scopes' => [
//            /**  索引使用「自定義」的玩法名稱  GameType 遊戲代碼 + RTYPE 玩法說明  */
//            // 北京 PK10 (官方 300)
//            // 1 = 定位 2 = 雙面 3 = 區間
//            'PK' => [
//                'general', // 通用
//            ],
//            // 競速 PK10 (自開 90)
//            // 1 = 定位 2 = 雙面 3 = 區間
//            'P1' => [
//                'general', // 通用
//            ],
//            // 飛速 PK10 (自開 60)
//            // 1 = 定位 2 = 雙面 3 = 區間
//            'P2' => [
//                'general', // 通用
//            ],
//            // 超級 PK10 (自開 30)
//            // 1 = 定位 2 = 雙面 3 = 區間
//            'P3' => [
//                'general', // 通用
//            ],
//            // 紅火牛
//            // 1 = 三星 2 = 前二 3 = 後二 4 = 龍虎
//            'RC' => [
//                'general', // 通用
//            ],
//            // 重慶時時彩 (官方 600)
//            // 1 = 一星 2 = 雙面 3 = 前三總和 4 = 五球總和 5 = 龍虎萬千 6 = 千三順子 7 = 中三總和 8 = 後三總和 9 = 龍虎萬百 10 = 龍虎萬十
//            // 11 = 龍虎萬個 12 = 龍虎千百 13 = 龍虎千十 14 = 龍虎千個 15 = 龍虎百十 16 = 龍虎百個 17 = 龍虎十個 18 = 中三順子 19 = 後三順子 20 = 前三雙面
//            // 21 = 中三雙面 22 = 後三雙面 23 = 五球雙面
//            'CT' => [
//                'general', // 通用
//            ],
//            // 競速時時彩 (自開 90)
//            // 1 = 一星 2 = 雙面 3 = 前三總和 4 = 五球總和 5 = 龍虎萬千 6 = 千三順子 7 = 中三總和 8 = 後三總和 9 = 龍虎萬百 10 = 龍虎萬十
//            // 11 = 龍虎萬個 12 = 龍虎千百 13 = 龍虎千十 14 = 龍虎千個 15 = 龍虎百十 16 = 龍虎百個 17 = 龍虎十個 18 = 中三順子 19 = 後三順子 20 = 前三雙面
//            // 21 = 中三雙面 22 = 後三雙面 23 = 五球雙面
//            'C1' => [
//                'general', // 通用
//            ],
//            // 飛速時時彩 (自開 60)
//            // 1 = 一星 2 = 雙面 3 = 前三總和 4 = 五球總和 5 = 龍虎萬千 6 = 千三順子 7 = 中三總和 8 = 後三總和 9 = 龍虎萬百 10 = 龍虎萬十
//            // 11 = 龍虎萬個 12 = 龍虎千百 13 = 龍虎千十 14 = 龍虎千個 15 = 龍虎百十 16 = 龍虎百個 17 = 龍虎十個 18 = 中三順子 19 = 後三順子 20 = 前三雙面
//            // 21 = 中三雙面 22 = 後三雙面 23 = 五球雙面
//            'C2' => [
//                'general', // 通用
//            ],
//            // 超級時時彩 (自開 45)
//            // 1 = 一星 2 = 雙面 3 = 前三總和 4 = 五球總和 5 = 龍虎萬千 6 = 千三順子 7 = 中三總和 8 = 後三總和 9 = 龍虎萬百 10 = 龍虎萬十
//            // 11 = 龍虎萬個 12 = 龍虎千百 13 = 龍虎千十 14 = 龍虎千個 15 = 龍虎百十 16 = 龍虎百個 17 = 龍虎十個 18 = 中三順子 19 = 後三順子 20 = 前三雙面
//            // 21 = 中三雙面 22 = 後三雙面 23 = 五球雙面
//            'C3' => [
//                'general', // 通用
//            ],
//        ],
//        'period_maintain' => [
//            // 每週三 時間：05:00 a.m. - 09:00 p.m. (GMT+8)
//            // day~hh:mm-hh:mm
//            '3~05:00-09:00',
//        ],
//    ],
    // LOTTERY 101 彩球
    'super_lottery' => [
        'config' => [
            'api_url' => env('SUPER_LOTTERY_URL'),
            'api_route' => env('SUPER_LOTTERY_ROUTE'),
            'api_key' => env('SUPER_LOTTERY_KEY'),
            'api_iv' => env('SUPER_LOTTERY_IV'),
            'leader_account' => env('SUPER_LOTTERY_LEAD_AGENT_ACCOUNT'),
            'leader_password' => env('SUPER_LOTTERY_LEAD_AGENT_PASSWORD'),
            'up_account' => env('SUPER_LOTTERY_AGENT_ACCOUNT'),
            'up_password' => env('SUPER_LOTTERY_AGENT_PASSWORD'),
            'test_account' => env('SUPER_LOTTERY_TEST_MEMBER_ACCOUNT'),
            'test_password' => env('SUPER_LOTTERY_TEST_MEMBER_PASSWORD'),
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的玩法名稱 */
            // 六合
            'liu_he' => [
                'general', // 通用
            ],
            // 大樂
            'da_le' => [
                'general', // 通用
            ],
            // 539
            '539' => [
                'general', // 通用
            ],
            // 天天樂
            'tian_tian_le' => [
                'general', // 通用
            ],
            // 威力
//            'wei_li' => [
//                'general', // 通用
//            ],
//            // 七星彩
//            'qi_xing_cai' => [
//                'general', // 通用
//            ],
//            // 四星彩
//            'si_xing_cai' => [
//                'general', // 通用
//            ],
//            // 三星彩
//            'san_xing_cai' => [
//                'general', // 通用
//            ],
//            // 排列5
//            'pai_lie_5' => [
//                'general', // 通用
//            ]
        ],
        'period_maintain' => [
            // 週一 中午 pm:12:00 ~ pm: 4:00
            // day~hh:mm-hh:mm
            '1~12:00-16:30',
        ],
        'rebate' => true,
    ],
    // HC 皇朝電競
//    'hong_chow' => [
//        'config' => [
//            'api_url' => env('HONG_CHOW_API_URL'),
//            'agent_id' => env('HONG_CHOW_AGENT_ID'),
//            'secret_key' => env('HONG_CHOW_SECRET_KEY'),
//            'backend_url' => env('HONG_CHOW_BACKEND_URL'),
//            'backend_account' => env('HONG_CHOW_BACKEND_ACCOUNT'),
//            'backend_password' => env('HONG_CHOW_BACKEND_PASSWORD'),
//            'frontend_pc_url' => env('HONG_CHOW_FRONTEND_PC'),
//            'frontend_mobile_url' => env('HONG_CHOW_FRONTEND_MOBILE'),
//        ],
//        'game_scopes' => [
//            /** 索引使用「自定義」的遊戲名稱 遊戲代號*/
//            // 所有遊戲 0
//            'all' => [
//                'general',
//            ],
//            // 英雄聯盟 League of Legends 1
//            'lol' => [
//                'general',
//            ],
//            // 王者榮耀 Kings of Glory 2
//            'kog' => [
//                'general',
//            ],
//            // DOTA2 3
//            'dota2' => [
//                'general',
//            ],
//            // CS:GO 4
//            'csgo' => [
//                'general',
//            ],
//            // 絕地求生 PlayerUnknown's Battlegrounds 5
//            'pubg' => [
//                'general',
//            ],
//            // 守望先鋒 OverWatch 6
//            'ow' => [
//                'general',
//            ],
//            // 星際爭霸2 StarCraft II 7
//            'sc2' => [
//                'general',
//            ],
//            // 魔獸爭霸3 WarCraft III 8
//            'wc3' => [
//                'general',
//            ],
//            // 爐石傳說 HearthStone 9
//            'hs' => [
//                'general',
//            ],
//            // 風暴英雄 Heroes of the Storm 11
//            'hots' => [
//                'general',
//            ],
//            // 堡壘之夜 Fortnite 12
//            'fortnite' => [
//                'general',
//            ],
//            // FIFA Online 13
//            'fifa_online' => [
//                'general',
//            ],
//            // 穿越火線 CrossFire 14
//            'cf' => [
//                'general',
//            ],
//            // 彩虹6號 Tom Clancy's Rainbow Six: Siege 15
//            'rainbow6' => [
//                'general',
//            ],
//            // 傳說對決 Arena of Valor 16
//            'aov' => [
//                'general',
//            ],
//            // Artifact 17
//            'artifact' => [
//                'general',
//            ],
//        ],
//        'period_maintain' => [
//            // 無固定維護時間
//            '2~00:00-00:00',
//        ],
//    ],
    // Ameba Entertainment
    'ameba' => [
        'config' => [
            'site_id' => env('AMEBA_SITE_ID'),
            'secret_key' => env('AMEBA_SECRET_KEY'),
            'api_url' => env('AMEBA_API_URL'),
            'backend_url' => env('AMEBA_BACKEND_URL'),
            'backend_account' => env('AMEBA_BACKEND_ACCOUNT'),
            'backend_password' => env('AMEBA_BACKEND_PASSWORD'),
            'test_account' => env('AMEBA_TEST_ACCOUNT'),
            'test_password' => env('AMEBA_TEST_PASSWORD'),
            'api_language' => [
                'enUS',
                'zhTW',
                'zhCN',
                'jaJP',
                'koKR',
                'thTH',
                'viVN',
            ],
            'api_currency' => [
                'CNY',
                'HKD',
                'JPY',
                'KRW',
                'THB',
                'MYR',
                'EUR',
                'GBP',
                'USD',
                'IDR',
                'IDR_1000',
                'VND',
                'VND_1000',
                'TWD',
                'SGD',
                'INR',
                'PHP',
                'MMK',
                'NZD',
            ],
        ],
        'game_scopes' => [
            'slot' => ['general',],
        ],
        'period_maintain' => [
            // 無固定維護時間
            '2~00:00-00:00',
        ],
    ],
    // RTG
    'real_time_gaming' => [
        'config' => [
            'api_url' => env('RTG_API_URL'),
            'api_username' => env('RTG_USERNAME'),
            'api_password' => env('RTG_PASSWORD'),
            'test_account' => env('RTG_TEST_MEMBER_ACCOUNT'),
            'currency' => env('RTG_DEFAULT_CURRENCY', 'TWD'),
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的遊戲名稱 遊戲代號*/
            'slot' => ['general',],
        ],
        'rate' => [
            // 各幣別對應遊戲內點數之比例
            // 系統1000VND = 遊戲1VND
            'VND' => 1000, //越南盾
        ],
        'deposit_limit' => [
            // 各幣別轉入遊戲館時的最小單位限制
            'VND' => 10,  //越南盾
        ],
        'period_maintain' => [
            // 無固定維護時間
            '2~00:00-00:00',
        ],
    ],
    // Royal game
    'royal_game' => [
        'config' => [
            'api_url' => env('ROYAL_GAME_API_URL'),
            'token_key' => env('ROYAL_GAME_TOKEN_KEY'),
            'bucket_id' => env('ROYAL_GAME_BUCKET_ID'),
            'game_url' => env('ROYAL_GAME_GAME_URL'),
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的玩法名稱 */
            // 百家樂
            'Bacc' => [
                'general', // 通用
            ],
            // 輪盤
            'LunPan' => [
                'general', // 通用
            ],
            // 龍虎
            'LongHu' => [
                'general', // 通用
            ],
            // 骰子
            'ShaiZi' => [
                'general', // 通用
            ],
            // 保險百家樂
            'InsuBacc' => [
                'general', // 通用
            ],
            // 翻攤
            'FanTan' => [
                'general', // 通用
            ],
        ],
        'period_maintain' => [
            // 無固定維護時間
            '1~11:30-16:00',
        ],
    ],
    // UFA 體育
    'ufa_sport' => [
        'config' => [
            'api_url' => env('UFA_SPORT_API_URL'),
            'currency' => env('UFA_SPORT_CURRENCY'),
            'secret_code' => env('UFA_SPORT_SECRET_CODE'),
            'host_url' => env('UFA_SPORT_GAME_HOST_URL'),
            'agent' => env('UFA_SPORT_ACCOUNT_ID'),
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的玩法名稱 */
            'sport' => ['general',],
        ],
        'period_maintain' => [
            // 無固定維護時間
            '4~14:00-17:00',
        ],
    ],
    // Ren Ni Ying 任你贏
//    'ren_ni_ying' => [
//        'config' => [
//            'api_url' => env('REN_NI_YING_API_URL'),
//            'agent_id' => env('REN_NI_YING_AGENT_ID'),
//            'balk' => env('REN_NI_YING_BALK'),
//            'max_profit' => env('REN_NI_YING_MAXPROFIT'),
//            'proportion' => env('REN_NI_YING_PROPORTION'),
//            'keep_rebate_rate' => env('REN_NI_YING_KEEPREBATETATE'),
//            'ticket_time_range' => env('REN_NI_YING_TICKET_TIME_RANGE'),
//            'test_member_account' => env('REN_NI_YING_TEST_MEMBER_ACCOUNT'),
//        ],
//        'game_scopes' => [
//            /** 索引使用「自定義」的玩法名稱 */
//            // 北京賽車
//            'bei_jing_sai_che' => [
//                'general', // 通用
//            ],
//            // 幸運飛艇
//            'xing_yun_fei_ting' => [
//                'general', // 通用
//            ],
//            // 重慶時時彩
//            'chong_qing_shi_cai' => [
//                'general', // 通用
//            ],
//            // 音速賽車 5分
//            'yin_su_sai_che_5_min' => [
//                'general', // 通用
//            ],
//            // 音速賽車 75秒
//            'yin_su_sai_che_75_sec' => [
//                'general', // 通用
//            ],
//            // 音速賽車 3分
//            'yin_su_sai_che_3_min' => [
//                'general', // 通用
//            ],
//            // 江蘇骰寶(快3)
//            'jiang_su_tou_bai' => [
//                'general', // 通用
//            ],
//        ],
//        'period_maintain' => [
//            // 無固定維護時間
//            '2~00:00-00:00',
//        ],
//    ],
    // CQ9 game
    'cq9_game' => [
        'config' => [
            'api_url' => env('CQ9_GAME_API_URL'),
            'api_token' => env('CQ9_GAME_API_TOKEN'),
            'test_account' => env('CQ9_GAME_TEST_ACCOUNT'),
            'test_password' => env('CQ9_GAME_TEST_PASSWORD'),
            'test_member_account' => env('CQ9_GAME_TEST_MEMBER_ACCOUNT'),
            'test_member_password' => env('CQ9_GAME_TEST_MEMBER_PASSWORD'),
            'currency' => env('CQ9_GAME_CURRENCY'),
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的遊戲名稱 遊戲代號*/
            'slot' => ['general',],
        ],
        'period_maintain' => [
            // 無固定維護時間
            '2~00:00-00:00',
        ],
    ],
    // Winner Sport 贏家體育
    'winner_sport' => [
        'config' => [
            'api_url' => env('WINNER_SPORT_API_URL'),
            'api_key' => env('WINNER_SPORT_APY_KEY'),
            'token' => env('WINNER_SPORT_TOKEN'),
            'top_account' => env('WINNER_SPORT_TOP_ACCOUNT'),
            'login_path' => env('WINNER_SPORT_LOGIN_PATH'),
        ],
        'game_scopes' => [
            'AF' => ['general'], // 美足
            'BK' => ['general'], // 美籃
            'BS' => ['general'], // 美棒
            'HO' => ['general'], // 冰球
            'JB' => ['general'], // 日棒
            'KB' => ['general'], // 韓棒
            'KT' => ['general'], // 籃球
            'LO' => ['general'], // 彩球
            'SC' => ['general'], // 足球
            'SE' => ['general'], // 棒球
            'SK' => ['general'], // 指數
            'TB' => ['general'], // 中職
            'RH' => ['general'], // 賽馬
            'RD' => ['general'], // 賽狗
            'PR' => ['general'], // 混和過關
        ],
        'period_maintain' => [
            // 無固定維護時間
            '2~00:00-00:00',
        ],
    ],
    // 9K Lottery 9K彩球
    'nine_k_lottery' => [
        'config' => [
            'api_url' => env('NINE_K_LOTTERY_API_URL'),
            'api_token' => env('NINE_K_LOTTERY_API_TOKEN'),
            'api_demo_token' => env('NINE_K_LOTTERY_API_DEMO_TOKEN'),
            'agent_id' => env('NINE_K_LOTTERY_AGENT_ID'),
            'agent_demo_id' => env('NINE_K_LOTTERY_AGENT_DEMO_ID'),
            'demo_member_accounts' => env('NINE_K_LOTTERY_DEMO_MEMBER_ACCOUNTs'),
            'test_member_account' => env('NINE_K_LOTTERY_TEST_MEMBER_ACCOUNT'),
            'test_member_password' => env('NINE_K_LOTTERY_TEST_MEMBER_PASSWORD'),
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的遊戲名稱 遊戲代號*/
            // 賓果賓果
            'BingoBingo' => [
                'general', // 通用
            ],
            // 北京賽車 PK 拾
            'BJPK10' => [
                'general', // 通用
            ],
            // 幸運飛艇
            'XYFT' => [
                'general', // 通用
            ],
            // 9K 極速 PK 拾 (75 秒/局)
            'JSPK10' => [
                'general', // 通用
            ],
            // 9K 高頻 PK 拾 (180 秒/局)
            'KPPK10' => [
                'general', // 通用
            ],
            // 北京快樂 8
            'BJKENO8' => [
                'general', // 通用
            ],
            // 斯洛伐克
            'SLFK' => [
                'general', // 通用
            ],
            // 重慶時時彩
            'CQSSC' => [
                'general', // 通用
            ],
            // 天津時時彩
            'TJSSC' => [
                'general', // 通用
            ],
            // 新彊時時彩
            'XJSSC' => [
                'general', // 通用
            ],
            // 騰訊分分彩
            'TXSSC' => [
                'general', // 通用
            ],
            // QQ 分分彩
            'QQSSC' => [
                'general', // 通用
            ],
            // 東京 1.5 分
            'TKKENO' => [
                'general', // 通用
            ],
            // COVID19疫情預測
            'COVID19' => [
                'general', // 通用
            ],
            // 自開彩
            'other' => [
                'general', // 通用
            ],
        ],
        'period_maintain' => [
            // 無固定維護時間
            '2~00:00-00:00',
        ],
    ],
    // 9K Lottery 9K彩球
    'nine_k_lottery_2' => [
        'config' => [
            'api_url' => env('NINE_K_LOTTERY_2_API_URL'),
            'api_token' => env('NINE_K_LOTTERY_2_API_TOKEN'),
            'api_demo_token' => env('NINE_K_LOTTERY_2_API_DEMO_TOKEN'),
            'agent_id' => env('NINE_K_LOTTERY_2_AGENT_ID'),
            'prefix' => env('NINE_K_LOTTERY_2_PREFIX'),
            'agent_demo_id' => env('NINE_K_LOTTERY_2_AGENT_DEMO_ID'),
            'demo_member_accounts' => env('NINE_K_LOTTERY_2_DEMO_MEMBER_ACCOUNTs'),
            'test_member_account' => env('NINE_K_LOTTERY_2_TEST_MEMBER_ACCOUNT'),
            'test_member_password' => env('NINE_K_LOTTERY_2_TEST_MEMBER_PASSWORD'),
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的遊戲名稱 遊戲代號*/
            // 賓果賓果
            'BingoBingo' => [
                'general', // 通用
            ],
            // 北京賽車 PK 拾
            'BJPK10' => [
                'general', // 通用
            ],
            // 幸運飛艇
            'XYFT' => [
                'general', // 通用
            ],
            // 9K 極速 PK 拾 (75 秒/局)
            'JSPK10' => [
                'general', // 通用
            ],
            // 9K 高頻 PK 拾 (180 秒/局)
            'KPPK10' => [
                'general', // 通用
            ],
            // 北京快樂 8
            'BJKENO8' => [
                'general', // 通用
            ],
            // 斯洛伐克
            'SLFK' => [
                'general', // 通用
            ],
            // 重慶時時彩
            'CQSSC' => [
                'general', // 通用
            ],
            // 天津時時彩
            'TJSSC' => [
                'general', // 通用
            ],
            // 新彊時時彩
            'XJSSC' => [
                'general', // 通用
            ],
            // 騰訊分分彩
            'TXSSC' => [
                'general', // 通用
            ],
            // QQ 分分彩
            'QQSSC' => [
                'general', // 通用
            ],
            // 東京 1.5 分
            'TKKENO' => [
                'general', // 通用
            ],
            // COVID19疫情預測
            'COVID19' => [
                'general', // 通用
            ],
            // 自開彩
            'other' => [
                'general', // 通用
            ],
        ],
        'period_maintain' => [
            // 無固定維護時間
            '2~00:00-00:00',
        ],
    ],
    // QTech 電子
    'q_tech' => [
        'config' => [
            'api_url' => env('QTECH_API_URL'),
            'agent_username' => env('QTECH_AGENT_USERNAME'),
            'agent_password' => env('QTECH_AGENT_PASSWORD'),
            'wallet_session_id' => env('QTECH_WALLET_SESSION_ID'),
            'passkey' => env('QTECH_PASSKEY'),
            'currency' => env('QTECH_CURRENCY'),
            'country' => env('QTECH_COUNTRY'),
            'language' => env('QTECH_LANGUAGE'),
            'time_zone' => env('QTECH_TIME_ZONE'),
            'mode' => env('QTECH_MODE'),
            'bet_limit_code' => env('QTECH_BET_LIMIT_CODE'),
            'test_member_account' => env('QTECH_TEST_MEMBER_ACCOUNT'),
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的遊戲名稱 遊戲代號*/
            'slot' => ['general',],
        ],
        'period_maintain' => [
            // 無固定維護時間
            '2~00:00-00:00',
        ],
    ],
    // WM 真人
    'wm_casino' => [
        'config' => [
            'api_url' => env('WM_CASINO_API_URL'),
            'vendor_id' => env('WM_CASINO_VENDOR_ID'),
            'signature_key' => env('WM_CASINO_SIGNATURE_KEY'),
            // 系統中英文設定    0:中文, 1:英文
            'syslang' => env('WM_CASINO_MEMBER_SYSTEM_LANG', '0'),
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的遊戲名稱 遊戲代號*/
            // 百家樂
            '101' => [
                'general', // 通用
            ],
            // 龍虎
            '102' => [
                'general', // 通用
            ],
            // 輪盤
            '103' => [
                'general', // 通用
            ],
            // 骰寶
            '104' => [
                'general', // 通用
            ],
            // 牛牛
            '105' => [
                'general', // 通用
            ],
            // 三公
            '106' => [
                'general', // 通用
            ],
            // 番攤
            '107' => [
                'general', // 通用
            ],
            // 色碟
            '108' => [
                'general', // 通用
            ],
            // 魚蝦蟹
            '110' => [
                'general', // 通用
            ],
            // 炸金花
            '111' => [
                'general', // 通用
            ],
            // 溫州牌九
            '112' => [
                'general', // 通用
            ],
            // 二八杠
            '113' => [
                'general', // 通用
            ],
        ],
        'period_maintain' => [
            // 無固定維護時間
            '1~12:00-14:30',
        ],
    ],
    // bobo_poker 人人棋牌
    'bobo_poker' => [
        'config' => [
            'api_url' => env('BOBO_POKER_API_URL'),
            'agent_id' => env('BOBO_POKER_AGENT_ID'),
            'md5_key' => env('BOBO_POKER_MD5_KEY'),
            'test_member_account' => env('BOBO_POKER_TEST_MEMBER_ACCOUNT'),
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的遊戲名稱 遊戲代號*/
            // 北京賽車
            'racing' => [
                'general', // 通用
            ],
            // 幸運飛艇
            'rowing' => [
                'general', // 通用
            ],
            // 歡樂生肖
            'timetime' => [
                'general', // 通用
            ],
            // 廣東11選五
            'gd11x5' => [
                'general', // 通用
            ],
            // 江蘇11選五
            'js11x5' => [
                'general', // 通用
            ],
            // 江西11選五
            'jx11x5' => [
                'general', // 通用
            ],
            // 山東11選五
            'sd11x5' => [
                'general', // 通用
            ],
            // 北京快三
            'bjk3' => [
                'general', // 通用
            ],
            // 甘肅快三
            'gsk3' => [
                'general', // 通用
            ],
            // 廣西快三
            'gxk3' => [
                'general', // 通用
            ],
            // 河北快三
            'hebk3' => [
                'general', // 通用
            ],
            // 湖北快三
            'hubk3' => [
                'general', // 通用
            ],
            // 江蘇快三
            'jsk3' => [
                'general', // 通用
            ],
            // 越南彩
            'vietnam-lottery' => [
                'general', // 通用
            ],
            // 60秒急速賽車
            'self-racing' => [
                'general', // 通用
            ],
            // 60秒急速飛艇
            'self-rowing' => [
                'general', // 通用
            ],
            // 60秒急速11選五
            'ffc11x5' => [
                'general', // 通用
            ],
            // 60秒急速快三
            'ffck3' => [
                'general', // 通用
            ],
            // 台灣60秒賓果
            'self-bingobingo' => [
                'general', // 通用
            ],
            // 發大財賽馬
            'horserace' => [
                'general', // 通用
            ],
            // 60秒越南彩
            'self-vietnam' => [
                'general', // 通用
            ],
            // 櫻花三分彩
            'self-ball-three' => [
                'general', // 通用
            ],
            // 龍虎
            'dragon-tiger' => [
                'general', // 通用
            ],
            // 歡樂龍虎
            'happy-dragon-tiger' => [
                'general', // 通用
            ],
            // 骰寶
            'dice-bao' => [
                'general', // 通用
            ],
            // 歡樂骰寶
            'happy-dice-bao' => [
                'general', // 通用
            ],
            // 反圍骰
            'opposite-dice-bao' => [
                'general', // 通用
            ],
            // 瘋狂輪盤
            'crazy-roulette' => [
                'general', // 通用
            ],
            // 美式輪盤
            'american-roulette' => [
                'general', // 通用
            ],
            // 歡樂輪盤
            'happy-roulette' => [
                'general', // 通用
            ],
            // 21點
            'blackjack' => [
                'general', // 通用
            ],
            // 人人牛牛
            'cowcow' => [
                'general', // 通用
            ],
            // 二八槓
            'this-bar' => [
                'general', // 通用
            ],
            // 女王的新衣
            'roulette' => [
                'general', // 通用
            ],
            // 百家樂
            'baccarat' => [
                'general', // 通用
            ],
            // 色碟
            'shaking-disc' => [
                'general', // 通用
            ],
            // 歡樂色碟
            'happy-shaking-disc' => [
                'general', // 通用
            ],
            // 魚蝦蟹
            'fish-prawn-crab' => [
                'general', // 通用
            ],
            // 歡樂魚蝦蟹
            'happy-fish-prawn-crab' => [
                'general', // 通用
            ],
            // 越南三牌
            'ba-cay' => [
                'general', // 通用
            ],
            // 越南五分彩
            'self-vietnam-five' => [
                'general', // 通用
            ],
            // 越南炸金花
            'vietnam-golden-flower' => [
                'general', // 通用
            ],
            // 百人牛牛
            'happy-cowcow' => [
                'general', // 通用
            ],
            // 星河輪盤
            'new-roulette' => [
                'general', // 通用
            ],
            // 歡樂百家樂
            'happy-baccarat' => [
                'general', // 通用
            ],
            // 番攤
            'fan-tan' => [
                'general', // 通用
            ],
            'happy-this-bar' => [
                'general', // 通用
            ],
        ],
        'period_maintain' => [
            // 無固定維護時間
            '3~10:30-11:30',
        ],
    ],
    // FOREVER 8 (AV電子)
    'forever_eight' => [
        'config' => [
            'api_url' => env('FOREVER_EIGHT_API_URL'),
            'client_ID' => env('FOREVER_EIGHT_CLIENT_ID'),
            'md5_key' => env('FOREVER_EIGHT_MD5_KEY'),
            'aes_key' => env('FOREVER_EIGHT_AES_KEY'),
            'initial_aes_key' => env('FOREVER_EIGHT_INITIAL_AES_KEY'),
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的遊戲名稱 遊戲代號*/
            'slot' => ['general',],
        ],
        'period_maintain' => [
            // 無固定維護時間
            '2~00:00-00:00',
        ],
    ],
    // slot_factory SF電子
    'slot_factory' => [
        'config' => [
            'api_url' => env('SLOT_FACTORY_API_URL'),
            'api_backend_url' => env('SLOT_FACTORY_API_BACKEND_URL'),
            'secret_key' => env('SLOT_FACTORY_SECRET_KEY'),
            'customer_name' => env('SLOT_FACTORY_CUSTOMER_NAME'),
            'language' => env('SLOT_FACTORY_LANG'),
            'country' => env('SLOT_FACTORY_COUNTRY'),
            'currency' => env('SLOT_FACTORY_CURRENCY'),
            'test_member_account' => env('SLOT_FACTORY_TEST_MEMBER_ACCOUNT'),
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的遊戲名稱 遊戲代號*/
            'slot' => ['general',],
        ],
        'period_maintain' => [
            // 無固定維護時間
            '2~00:00-00:00',
        ],
    ],
    // CMD體育
    'cmd_sport' => [
        'config' => [
            'api_url' => env('CMD_SPORT_API_URL'),
            'web_url' => env('CMD_SPORT_WEB_URL'),
            'mobile_url' => env('CMD_SPORT_MOBILE_URL'),
            'partner_key' => env('CMD_SPORT_PARTNER_KEY'),
            'test_member_account' => env('CMD_SPORT_TEST_MEMBER_ACCOUNT'),
            'currency' => env('CMD_SPORT_CURRENCY'),
            'lang' => env('CMD_SPORT_LANGUAGE'),
            'template_name' => env('CMD_SPORT_TEMPLATE_NAME'),
            'view' => env('CMD_SPORT_VIEW'),
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的遊戲名稱 遊戲代號*/
            'sport' => ['general'],
        ],
        'period_maintain' => [
            // 無固定維護時間
            '5~00:00-00:00',
        ],
        'rate' => [
            // 各幣別對應遊戲內點數之比例
            // 系統1000VD = 遊戲1VD
            'VD' => 1000, //越南盾
            'IDR' => 1000 //印尼幣
        ],
        'deposit_limit' => [
            // 各幣別轉入遊戲館時的最小單位限制
            'VD' => 10,  //越南盾
            'IDR' => 10, //印尼幣
        ],
    ],
    // S128 COCK FIGHT (S128鬥雞)
    'cock_fight' => [
        'config' => [
            'api_url' => env('COCK_FIGHT_API_URL'),
            'game_url' => env('COCK_FIGHT_GAME_URL'),
            'api_key' => env('COCK_FIGHT_API_KEY'),
            'agent_code' => env('COCK_FIGHT_AGENT_CODE'),
            'language' => env('COCK_FIGHT_LANGUAGE'),
            'currency' => env('COCK_FIGHT_CURRENCY'),
            'test_member_account' => env('COCK_FIGHT_TEST_ACCOUNT'),
            // 限紅
            'meron_wala_min_bet' => env('COCK_FIGHT_MERON_WALA_MIN_BET'),
            'meron_wala_max_bet' => env('COCK_FIGHT_MERON_WALA_MAX_BET'),
            'meron_wala_max_match_bet' => env('COCK_FIGHT_MERON_WALA_MAX_MATCH_BET'),
            'bdd_min_bet' => env('COCK_FIGHT_BDD_MIN_BET'),
            'bdd_max_bet' => env('COCK_FIGHT_BDD_MAX_BET'),
            'bdd_max_match_bet' => env('COCK_FIGHT_BDD_MAX_MATCH_BET'),
            'ftd_min_bet' => env('COCK_FIGHT_FTD_MIN_BET'),
            'ftd_max_bet' => env('COCK_FIGHT_FTD_MAX_BET'),
            'ftd_max_match_bet' => env('COCK_FIGHT_FTD_MAX_MATCH_BET'),
        ],
        'rate' => [
            // 各幣別對應遊戲內點數之比例
            // 系統1000VND = 遊戲1VND
            'VND' => 1000, //越南盾
        ],
        'deposit_limit' => [
            // 各幣別轉入遊戲館時的最小單位限制
            'VND' => 10,  //越南盾
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的遊戲名稱 遊戲代號*/
            'fight' => ['general',],
        ],
        'period_maintain' => [
            // 無固定維護時間
            '2~00:00-00:00',
        ],
    ],
    // bingo bull 賓果牛牛
    'bingo_bull' => [
        'config' => [
            'api_url' => env('BINGO_BULL_API_URL'),
            'api_key' => env('BINGO_BULL_API_KEY'),
            'prefix_code' => env('BINGO_BULL_PREFIX_CODE'),
            'agent_account' => env('BINGO_BULL_AGENT_ACCOUNT'),
            'agent_password' => env('BINGO_BULL_AGENT_PASSWORD'),
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的遊戲名稱 遊戲代號*/
            'bingoBull' => ['general',],
        ],
        'period_maintain' => [
            // 無固定維護時間
            '2~00:00-00:00',
        ],
        'rebate' => true,
    ],

    //Vs_Lottery(越南彩)
    'vs_lottery' => [
        'config' => [
            'api_url' => env('VS_LOTTERY_API_URL'),
            'partner_id' => env('VS_LOTTERY_PARTNER_ID'),
            'partner_password' => env('VS_LOTTERY_PARTNER_PASSWORD'),
            'member_account_prefix' => env('VS_LOTTERY_MEMBER_ACCOUNT_PREFIX'),
            'currency' => env('VS_LOTTERY_CURRENCY'),
            'lang' => env('VS_LOTTERY_LANG'),
        ],
        'rate' => [
            // 各幣別對應遊戲內點數之比例
            // 系統1000VND = 遊戲1VND
            'VND' => 1000, //越南盾
        ],
        'deposit_limit' => [
            // 各幣別轉入遊戲館時的最小單位限制
            'VND' => 10,  //越南盾
        ],
        'game_scopes' => [
            'keno' => [
                'general',
            ],
        ],
        'period_maintain' => [
            // 無固定維護時間
            '2~00:00-00:00',
        ],
    ],

    // awc_sexy 性感百家樂
    'awc_sexy' => [
        'config' => [
            'api_url' => env('AWC_SEXY_API_URL'),
            'api_key' => env('AWC_SEXY_API_KEY'),
            'agent_id' => env('AWC_SEXY_AGENT_ID'),
            'currency' => env('AWC_SEXY_CURRENCY'),
            'bet_limit' => env('AWC_SEXY_BET_LIMIT'),
            'language' => env('AWC_SEXY_LANGUAGE'),
            'fetch_url' => env('AWC_SEXY_FETCH_URL'),
            'test_member_account' => env('AWC_SECY_TEST_MEMNER_ACCOUNR'),
        ],
        'game_scopes' => [
            // 經典百家樂
            'MX-LIVE-001' => [
                'general',
            ],
            // 保險百家樂
            'MX-LIVE-003' => [
                'general',
            ],
            // 龍虎
            'MX-LIVE-006' => [
                'general',
            ],
            // 骰寶
            'MX-LIVE-007' => [
                'general',
            ],
            // 輪盤
            'MX-LIVE-009' => [
                'general',
            ],
            // 紅藍對決 (只支援手機版)
            'MX-LIVE-010' => [
                'general',
            ],
        ],
        'period_maintain' => [
            // 無固定維護時間
            '2~00:00-00:00',
        ],
        'rate' => [
            // 各幣別對應遊戲內點數之比例
            // 系統1000VND = 遊戲1VND
            'VND' => 1000, //越南盾
        ],
        'deposit_limit' => [
            // 各幣別轉入遊戲館時的最小單位限制
            'VND' => 10,  //越南盾
        ],
    ],
    'habanero' => [
        'config' => [
            'api_url' => env('HABANERO_API_URL'),
            'api_lobby' => env('HABANERO_LOBBY_URL'),
            'brand_ID' => env('HABANERO_BRAND_ID'),
            'api_key' => env('HABANERO_API_KEY'),
            'currency' => env('HABANERO_CURRENCY', 'TWD'),
            'language' => env('HABANERO_LANGUAGE', 'zh-CN'),
            'agent_account' => env('HABANERO_AGENT_ACCOUNT'),
            'agent_password' => env('HABANERO_AGENT_PASSWORD'),
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的遊戲名稱 遊戲代號*/
            'slot' => ['general',],
        ],
        'period_maintain' => [
            // 無固定維護時間
            '2~00:00-00:00',
        ],
        'rate' => [
            // 各幣別對應遊戲內點數之比例
            // 系統1000VND = 遊戲1VND
            'VND2' => 1000, //越南盾
        ],
        'deposit_limit' => [
            // 各幣別轉入遊戲館時的最小單位限制
            'VND2' => 10,  //越南盾
        ],
    ],
    // kk_lottery KK彩票
    'kk_lottery' => [
        'config' => [
            'api_url' => env('KK_LOTTERY_API_URL'),
            'api_key' => env('KK_LOTTERY_API_KEY'),
            'api_version' => env('KK_LOTTERY_API_VERSION'),
            'agent_id' => env('KK_LOTTERY_AGENT_ID'),
            'platform_id' => env('KK_LOTTERY_PLATFORM_ID'),
            'platform_name' => env('KK_LOTTERY_PLATFORM_NAME'),
            'country' => env('KK_LOTTERY_COUNTRY'),
            'currency' => env('KK_LOTTERY_CURRENCY'),
            'user_type' => env('KK_LOTTERY_USER_TYPE'),
            'odds' => env('KK_LOTTERY_ODDS'),
            'test_member_account' => env('KK_LOTTERY_TEST_MEMBER_ACCOUNT'),
        ],
        'game_scopes' => [
            'keno' => [
                'general',
            ],
        ],
        'period_maintain' => [
            // 無固定維護時間
            '2~00:00-00:00',
        ],
//        'rate' => [
//            // 各幣別對應遊戲內點數之比例
//            // 系統1000VND = 遊戲1VND
//            'VND' => 1000, //越南盾
//        ],
//        'deposit_limit' => [
//            // 各幣別轉入遊戲館時的最小單位限制
//            'VND' => 10,  //越南盾
//        ],
    ],
    // 反波膽
    'incorrect_score' => [
        'config' => [
            'api_url' => env('INCORRECT_SCORE_API_URL'),
            'vendor_id' => env('INCORRECT_SCORE_VENDOR_ID'),
            'agentid' => env('INCORRECT_SCORE_AGENT_ID'),
            'signature_key' => env('INCORRECT_SCORE_SIGNATURE_KEY'),
            'lang' => env('INCORRECT_SCORE_LANG'),
            'ver' => env('INCORRECT_SCORE_VER'),
            'test_line_vendor_id' => env('INCORRECT_SCORE_TEST_LINE_VENDOR_ID'),
            'test_line_agentid' => env('INCORRECT_SCORE_TEST_LINE_AGENT_ID'),
            'test_line_signature_key' => env('INCORRECT_SCORE_TEST_LINE_SIGNATURE_KEY'),
            'test_line_api_url' => env('INCORRECT_SCORE_TEST_LINE_API_URL'),
        ],
        'game_scopes' => [
            // 英式足球
            '1' => [
                'general', // 通用
            ],
        ],
        'period_maintain' => [
            // 無固定維護時間
            '1~13:00-15:00'
        ],
        'rebate' => true,
    ],
    // MG棋牌
    'mg_poker' => [
        'config' => [
            'api_url' => env('MG_POKER_API_URL'),
            'vendor_id' => env('MG_POKER_VENDOR_ID'),
            'signature_key' => env('MG_POKER_SIGNATURE_KEY'),
            // 系統中英文設定    0:中文, 1:英文
            'syslang' => env('MG_POKER_MEMBER_SYSTEM_LANG', '0'),
        ],
        'game_scopes' => [
            /** 索引使用「自定義」的遊戲名稱 遊戲代號*/
            // 搶莊牛牛
            '101' => [
                'general', // 通用
            ],
            // 搶莊牌九
            '102' => [
                'general', // 通用
            ],
            // 百家樂
            '103' => [
                'general', // 通用
            ],
            // 二十一點
            '104' => [
                'general', // 通用
            ],
            // 看三張搶莊牛牛
            '105' => [
                'general', // 通用
            ],
            // 三公
            '106' => [
                'general', // 通用
            ],
            // 炸金花
            '107' => [
                'general', // 通用
            ],
            // 搶莊二八槓
            '108' => [
                'general', // 通用
            ],
            // 百人牛牛
            '109' => [
                'general', // 通用
            ],            
            // 龍虎鬥
            '110' => [
                'general', // 通用
            ],
            // 德州撲克
            '111' => [
                'general', // 通用
            ],
            // 紅黑大戰
            '112' => [
                'general', // 通用
            ],
            // 看四張搶莊牛牛
            '113' => [
                'general', // 通用
            ],
            // 通比妞妞
            '114' => [
                'general', // 通用
            ],
            // 看四張搶莊牛牛
            '115' => [
                'general', // 通用
            ],
            // 癩子牛牛
            '116' => [
                'general', // 通用
            ],
            // 黑紅梅方
            '117' => [
                'general', // 通用
            ],
            // 萬人推筒子
            '118' => [
                'general', // 通用
            ],
            // 通比三公
            '119' => [
                'general', // 通用
            ],
            // 鬥地主
            '120' => [
                'general', // 通用
            ],
            // 台灣麻將
            '201' => [
                'general', // 通用
            ],

        ],
        'period_maintain' => [
            // 無固定維護時間
            '2~00:00-00:00',
        ],
    ],

];
