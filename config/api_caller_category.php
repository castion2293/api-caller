<?php
/*
|---------------------------------------------------------------------------
| 各遊戲館 - 遊戲別 - 產品類別的對應表
|
|---------------------------------------------------------------------------
*/
return [
    // 所有類別名稱
    'category' => [
        'sport', // 體育賽事
        'live', // 真人娛樂
        'e-game', // 電子遊藝
        'keno', // 彩票遊戲
        'e-battle', // 棋牌遊戲
        'e-battle-2', // 電子競技
        'animal-battle', // 動物競技
        'vietnam-lottery', // 越南
        'fishing', // 捕魚機
    ],

    // 沙龍
    'sa_gaming' => [
        // 百家樂
        'bac' => 'live',
        // 龍虎
        'dtx' => 'live',
        // 骰寶
        'sicbo' => 'live',
        // 翻攤
        'ftan' => 'live',
        // 輪盤
        'rot' => 'live',
        // 電子遊藝
        'slot' => 'e-game',
        // 小遊戲
        'minigame' => 'live',
        // 小遊戲
        'multiplayer' => 'e-game',
        // 幸運輪盤
        'moneywheel' => 'live',
    ],
    // 歐博
    'all_bet' => [
        // 普通百家樂
        'baccarat_ordinary' => 'live',
        // VIP 百家樂
        'baccarat_vip' => 'live',
        // 急速百家樂
        'baccarat_fast' => 'live',
        // 競咪百家樂
        'baccarat_compete' => 'live',
        // 骰寶
        'dice' => 'live',
        // 龍虎
        'dragon_tiger' => 'live',
        // 輪盤
        'roulette' => 'live',
        // 歐洲廳百家樂
        'baccarat_europe' => 'live',
        // 歐洲廳輪盤
        'roulette_europe' => 'live',
        // 歐洲廳 21 點
        'blackjack_europe' => 'live',
        // 聚寶百家樂
        'baccarat' => 'live',
        // 牛牛
        'bull_bull' => 'live',
        // 炸金花
        'win_three_card' => 'live',
        // 空戰世紀
        'air_force' => 'live',
    ],
    // BINGO
    'bingo' => [
        // 賓果星
        'bingo_star' => 'keno',
    ],
    // 德州
    'holdem' => [
        // 德州撲克
        'holdem_poker' => 'e-battle',
    ],
    // SUPER 體彩
    'super_sport' => [
        // 美棒
        'baseball_us' => 'sport',
        // 日棒
        'baseball_jp' => 'sport',
        // 台棒
        'baseball_tw' => 'sport',
        // 韓棒
        'baseball_kr' => 'sport',
        // 冰球
        'ice_hockey' => 'sport',
        // 籃球
        'basketball' => 'sport',
        // 美足（美式足球）
        'american_football' => 'sport',
        // 網球
        'tennis' => 'sport',
        // 足球（英式足球）
        'soccer' => 'sport',
        // 指數
        'stock_market' => 'sport',
        // 賽馬
        'horse_racing' => 'sport',
        // 電競
        'e_sports' => 'sport',
        // 其他
        'others' => 'sport',
        // 世足
        'fifa_world_cup' => 'sport',
        // 彩票
        'lottery' => 'sport',
    ],
    // UFA 體育
    'ufa_sport' => [
        'sport' => 'sport',
    ],
    // MAYA 瑪雅
    'maya' => [
        // 百家樂
        'Baccarat' => 'live',
        // 輪盤
        'Roulette' => 'live',
        // 龍虎
        'LongHu' => 'live',
        // 競咪百家樂
        'BIDBaccarat' => 'live',
        // 百家樂包桌
        'VIPBaccarat' => 'live',
        // 骰子
        'Dice' => 'live',
        // 保險百家樂
        'INSBaccarat' => 'live',
        // 牛牛
        'NiuNiu' => 'live',
        // 三王牌
        'ThreeCardPoker' => 'live',
        // 色碟
        'SeDie' => 'live',
    ],
    // NIHTAN 泥炭
//    'nihtan' => [
//        // 百家樂
//        'Baccarat' => 'live',
//        // 龍虎
//        'Dragon-Tiger' => 'live',
//        // 德州撲克
//        'Poker' => 'live',
//        // 骰寶
//        'Sicbo' => 'live',
//    ]
    // DREAM GAME DG
    'dream_game' => [
        // 百家樂 1 1
        'baccarat' => 'live',
        // 現場百家樂 1 1
        'live_baccarat' => 'live',
        // 波貝百家樂 1 1
        'bobe_baccarat' => 'live',
        // 波貝 VIP 百家樂 1 10
        'bobe_vip_baccarat' => 'live',
        // 波貝保險百家樂 1 2
        'bobe_insurance_baccarat' => 'live',
        // 競咪百家樂 1 8
        'compete_baccarat' => 'live',
        // 龍虎 1 3
        'dragon_tiger' => 'live',
        // 現場龍虎 1 3
        'live_dragon_tiger' => 'live',
        // 輪盤 1 4
        'roulette' => 'live',
        // 現場輪盤 1 4
        'live_roulette' => 'live',
        // 骰寶 1 5
        'dice' => 'live',
        // 極速骰寶 1 12
        'fast_dice' => 'live',
        // 波貝骰寶 1 5
        'bobe_dice' => 'live',
        // 鬥牛 1 7
        'bull_fighting' => 'live',
        // 波貝鬥牛 1 7
        'bobe_bull_fighting' => 'live',
        // 炸金花 1 11
        'fried_golden_flower' => 'live',
        // 現場賭場撲克 1 9
        'show_hand' => 'live',
        // 現場牛牛
        "live_niuniu" => "live",
        // 牛牛
        "NiuNiu" => "live",
        // 翻攤
        "FanTan" => "live",
        // 保險百家樂
        "insurance_baccara" => "live",
        // 色碟
        "disc" => "live",
        // 魚蝦蟹
        'fishPrawnCrab' => "live",
    ],
    // SO POWER 手中寶
    'so_power' => [
        // 北京 PK10 (官方 300)
        'PK' => 'keno',
        // 競速 PK10 (自開 90)
        'P1' => 'keno',
        // 飛速 PK10 (自開 60)
        'P2' => 'keno',
        // 超級 PK10 (自開 30)
        'P3' => 'keno',
        // 紅火牛
        'RC' => 'keno',
        // 重慶時時彩 (官方 600)
        'CT' => 'keno',
        // 競速時時彩 (自開 90)
        'C1' => 'keno',
        //飛速時時彩 (自開 60)
        'C2' => 'keno',
        //超級時時彩 (自開 45)
        'C3' => 'keno',
    ],
    // LOTTERY 101 彩球
    'super_lottery' => [
        'liu_he' => 'keno',
        // 大樂
        'da_le' => 'keno',
        // 539
        '539' => 'keno',
        // 天天樂
        'tian_tian_le' => 'keno',
        // 威力
        'wei_li' => 'keno',
        // 七星彩
        'qi_xing_cai' => 'keno',
        // 四星彩
        'si_xing_cai' => 'keno',
        // 三星彩
        'san_xing_cai' => 'keno',
        // 排列5
        'pai_lie_5' => 'keno'
    ],
    // HC 皇朝電競
//    'hong_chow' => [
//        // 所有遊戲 0
//        'all' => 'e-battle',
//        // 英雄聯盟 League of Legends 1
//        'lol' => 'e-battle',
//        // 王者榮耀 Kings of Glory 2
//        'kog' => 'e-battle',
//        // DOTA2 3
//        'dota2' => 'e-battle',
//        // CS:GO 4
//        'csgo' => 'e-battle',
//        // 絕地求生 PlayerUnknown's Battlegrounds 5
//        'pubg' => 'e-battle',
//        // 守望先鋒 OverWatch 6
//        'ow' => 'e-battle',
//        // 星際爭霸2 StarCraft II 7
//        'sc2' => 'e-battle',
//        // 魔獸爭霸3 WarCraft III 8
//        'wc3' => 'e-battle',
//        // 爐石傳說 HearthStone 9
//        'hs' => 'e-battle',
//        // 風暴英雄 Heroes of the Storm 11
//        'hots' => 'e-battle',
//        // 堡壘之夜 Fortnite 12
//        'fortnite' => 'e-battle',
//        // FIFA Online 13
//        'fifa_online' => 'e-battle',
//        // 穿越火線 CrossFire 14
//        'cf' => 'e-battle',
//        // 彩虹6號 Tom Clancy's Rainbow Six: Siege 15
//        'rainbow6' => 'e-battle',
//        // 傳說對決 Arena of Valor 16
//        'aov' => 'e-battle',
//        // Artifact 17
//        'artifact' => 'e-battle',
//    ],
    // Ameba Entertainment
    'ameba' => [
        'slot' => 'e-game',
    ],
    // RTG
    'real_time_gaming' => [
        'slot' => 'e-game',
    ],
    // Royal Game
    'royal_game' => [
        // 百家樂
        'Bacc' => 'live',
        // 輪盤
        'LunPan' => 'live',
        // 龍虎
        'LongHu' => 'live',
        // 骰子
        'ShaiZi' => 'live',
        // 保險百家樂
        'InsuBacc' => 'live',
        // 翻攤
        'FanTan' => 'live',
    ],
    // Ren Ni Ying 任你贏
    'ren_ni_ying' => [
        // 北京賽車
        'bei_jing_sai_che' => 'keno',
        // 幸運飛艇
        'xing_yun_fei_ting' => 'keno',
        // 重慶時時彩
        'chong_qing_shi_cai' => 'keno',
        // 音速賽車 5分
        'yin_su_sai_che_5_min' => 'keno',
        // 音速賽車 75秒
        'yin_su_sai_che_75_sec' => 'keno',
        // 音速賽車 3分
        'yin_su_sai_che_3_min' => 'keno',
        // 江蘇骰寶(快3)
        'jiang_su_tou_bai' => 'keno'
    ],
    // Cq9
    'cq9_game' => [
        'slot' => 'e-game',
    ],
    // 9K Lottery 9K彩球
    'nine_k_lottery' => [
        // 賓果賓果
        'BingoBingo' => 'keno',
        // 北京賽車 PK 拾
        'BJPK10' => 'keno',
        // 幸運飛艇
        'XYFT' => 'keno',
        // 9K 極速 PK 拾 (75 秒/局)
        'JSPK10' => 'keno',
        // 9K 高頻 PK 拾 (180 秒/局)
        'KPPK10' => 'keno',
        // 北京快樂 8
        'BJKENO8' => 'keno',
        // 斯洛伐克
        'SLFK' => 'keno',
        // 重慶時時彩
        'CQSSC' => 'keno',
        // 天津時時彩
        'TJSSC' => 'keno',
        // 新彊時時彩
        'XJSSC' => 'keno',
        // 騰訊分分彩
        'TXSSC' => 'keno',
        // QQ 分分彩
        'QQSSC' => 'keno',
        // 東京 1.5 分
        'TKKENO' => 'keno',
        // COVID19疫情預測
        'COVID19' => 'keno',
        // 自開彩
        'other' => 'keno',
    ],
    // 9K Lottery 9K彩球(自開彩)
    'nine_k_lottery_2' => [
        // 賓果賓果
        'BingoBingo' => 'keno',
        // 北京賽車 PK 拾
        'BJPK10' => 'keno',
        // 幸運飛艇
        'XYFT' => 'keno',
        // 9K 極速 PK 拾 (75 秒/局)
        'JSPK10' => 'keno',
        // 9K 高頻 PK 拾 (180 秒/局)
        'KPPK10' => 'keno',
        // 北京快樂 8
        'BJKENO8' => 'keno',
        // 斯洛伐克
        'SLFK' => 'keno',
        // 重慶時時彩
        'CQSSC' => 'keno',
        // 天津時時彩
        'TJSSC' => 'keno',
        // 新彊時時彩
        'XJSSC' => 'keno',
        // 騰訊分分彩
        'TXSSC' => 'keno',
        // QQ 分分彩
        'QQSSC' => 'keno',
        // 東京 1.5 分
        'TKKENO' => 'keno',
        // COVID19疫情預測
        'COVID19' => 'keno',
        // 自開彩
        'other' => 'keno',
    ],
    // Winner Sport 贏家體育
    'winner_sport' => [
        'AF' => 'sport', // 美足
        'BK' => 'sport', // 美籃
        'BS' => 'sport', // 美棒
        'HO' => 'sport', // 冰球
        'JB' => 'sport', // 日棒
        'KB' => 'sport', // 韓棒
        'KT' => 'sport', // 籃球
        'LO' => 'sport', // 彩球
        'SC' => 'sport', // 足球
        'SE' => 'sport', // 棒球
        'SK' => 'sport', // 指數
        'TB' => 'sport', // 中職
        'RH' => 'sport', // 賽馬
        'RD' => 'sport', // 賽狗
        'PR' => 'sport', // 混和過關
    ],
    // QTech 電子
    'q_tech' => [
        'slot' => 'e-game',
    ],
    // WM 真人
    'wm_casino' => [
        // 百家樂
        '101' => 'live',
        // 龍虎
        '102' => 'live',
        // 輪盤
        '103' => 'live',
        // 骰寶
        '104' => 'live',
        // 牛牛
        '105' => 'live',
        // 三公
        '106' => 'live',
        // 番摊
        '107' => 'live',
        // 色碟
        '108' => 'live',
        // 魚蝦蟹
        '110' => 'live',
        // 炸金花
        '111' => 'live',
        // 溫州牌九
        '112' => 'live',
        // 二八杠
        '113' => 'live',
    ],
    // bobo_poker 人人棋牌
    'bobo_poker' => [
        // 北京賽車
        'racing' => 'e-battle',
        // 幸運飛艇
        'rowing' => 'e-battle',
        // 歡樂生肖
        'timetime' => 'e-battle',
        // 廣東11選五
        'gd11x5' => 'e-battle',
        // 江蘇11選五
        'js11x5' => 'e-battle',
        // 江西11選五
        'jx11x5' => 'e-battle',
        // 山東11選五
        'sd11x5' => 'e-battle',
        // 北京快三
        'bjk3' => 'e-battle',
        // 甘肅快三
        'gsk3' => 'e-battle',
        // 廣西快三
        'gxk3' => 'e-battle',
        // 河北快三
        'hebk3' => 'e-battle',
        // 湖北快三
        'hubk3' => 'e-battle',
        // 江蘇快三
        'jsk3' => 'e-battle',
        // 越南彩
        'vietnam-lottery' => 'e-battle',
        // 60秒急速賽車
        'self-racing' => 'e-battle',
        // 60秒急速飛艇
        'self-rowing' => 'e-battle',
        // 60秒急速11選五
        'ffc11x5' => 'e-battle',
        // 60秒急速快三
        'ffck3' => 'e-battle',
        // 台灣60秒賓果
        'self-bingobingo' => 'e-battle',
        // 發大財賽馬
        'horserace' => 'e-battle',
        // 60秒越南彩
        'self-vietnam' => 'e-battle',
        // 龍虎
        'dragon-tiger' => 'e-battle',
        // 骰寶
        'dice-bao' => 'e-battle',
        // 反圍骰
        'opposite-dice-bao' => 'e-battle',
        // 瘋狂輪盤
        'crazy-roulette' => 'e-battle',
        // 美式輪盤
        'american-roulette' => 'e-battle',
        // 21點
        'blackjack' => 'e-battle',
        // 人人牛牛
        'cowcow' => 'e-battle',
        // 二八槓
        'this-bar' => 'e-battle',
        // 女王的新衣'
        'roulette' => 'e-battle',
        // 百家樂
        'baccarat' => 'e-battle',
        // 色碟
        'shaking-disc' => 'e-battle',
        // 魚蝦蟹
        'fish-prawn-crab' => 'e-battle',
        // 越南三牌
        'ba-cay' => 'e-battle',
        // 越南五分彩
        'self-vietnam-five' => 'e-battle',
        // 櫻花三分彩
        'self-ball-three' => 'e-battle',
        // 快樂骰寶
        'happy-dice-bao' => 'e-battle',
        // 快樂色碟
        'happy-shaking-disc' => 'e-battle',
        // 歡樂魚蝦蟹
        'happy-fish-prawn-crab' => 'e-battle',
        // 歡樂龍虎
        'happy-dragon-tiger' => 'e-battle',
        // 歡樂輪盤
        'happy-roulette' => 'e-battle',
        // 越南炸金花
        'vietnam-golden-flower' => 'e-battle',
        // 百人牛牛
        'happy-cowcow' => 'e-battle',
        // 星河輪盤
        'new-roulette' => 'e-battle',
        // 歡樂百家樂
        'happy-baccarat' => 'e-battle',
        // 番攤
        'fan-tan' => 'e-battle',
        // 二八槓
        'happy-this-bar' => 'e-battle',
    ],
    // FOREVER 8 (AV電子)
    'forever_eight' => [
        'slot' => 'e-game',
    ],
    // slot_factory SF電子
    'slot_factory' => [
        'slot' => 'e-game',
    ],
    // S128 COCK FIGHT (S128鬥雞)
    'cock_fight' => [
        'fight' => 'animal-battle',
    ],
    // 賓果牛牛
    'bingo_bull' => [
        // 賓果牛牛
        'bingoBull' => 'keno',
    ],
    // CMD 體育
    'cmd_sport' => [
        'sport' => 'sport',
    ],
    // 越南彩
    'vs_lottery' => [
        'keno' => 'vietnam-lottery',
    ],
    // awc_sexy 性感百家樂
    'awc_sexy' => [
        // 經典百家樂
        'MX-LIVE-001' => 'live',
        // 保險百家樂
        'MX-LIVE-003' => 'live',
        // 龍虎
        'MX-LIVE-006' => 'live',
        // 骰寶
        'MX-LIVE-007' => 'live',
        // 輪盤
        'MX-LIVE-009' => 'live',
        // 紅藍對決 (只支援手機版)
        'MX-LIVE-010' => 'live',
    ],
    // Habanero
    'habanero' => [
        'slot' => 'e-game',
    ],
    // KK彩票
    'kk_lottery' => [
        'keno' => 'keno',
    ],
    // 反波膽
    'incorrect_score' => [
        // 英式足球
        '1' => 'sport',
    ],
    // MG 棋牌
    'mg_poker' => [
        '101' => 'e-battle', // 搶莊牛牛
        '102' => 'e-battle', // 搶莊牌九
        '103' => 'e-battle', // 百家樂
        '104' => 'e-battle', // 二十一點
        '105' => 'e-battle', // 看三張搶莊牛牛
        '106' => 'e-battle', // 三公
        '107' => 'e-battle', // 炸金花
        '108' => 'e-battle', // 搶莊二八槓
        '109' => 'e-battle', // 百人牛牛      
        '110' => 'e-battle', // 龍虎鬥
        '111' => 'e-battle', // 德州撲克
        '112' => 'e-battle', // 紅黑大戰
        '113' => 'e-battle', // 看四張搶莊牛牛
        '114' => 'e-battle', // 通比妞妞
        '115' => 'e-battle', // 看四張搶莊牛牛
        '116' => 'e-battle', // 癩子牛牛
        '117' => 'e-battle', // 黑紅梅方
        '118' => 'e-battle', // 萬人推筒子
        '119' => 'e-battle', // 通比三公
        '120' => 'e-battle', // 鬥地主
        '201' => 'e-battle', // 台灣麻將
    ],
];
