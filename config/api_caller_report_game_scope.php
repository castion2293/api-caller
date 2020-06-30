<?php
//這裡是提供給報表的過濾器使用
return [
    // 沙龍
    'sa_gaming' => [
        'game_scopes' => [
            // 百家樂
            'bac',
            // 龍虎
            'dtx',
            // 骰寶
            'sicbo',
            // 翻攤
            'ftan',
            // 輪盤
            'rot' ,
            // 電子遊藝
            'slot',
            // 小遊戲
            'minigame',
            // 小遊戲
            'multiplayer',
            // 幸運輪盤
            'moneywheel',
        ],
    ],
    // 歐博
    'all_bet' => [
        'game_scopes' => [
            // 普通百家樂
            'baccarat_ordinary',
            // VIP 百家樂
            'baccarat_vip',
            // 急速百家樂
            'baccarat_fast' ,
            // 競咪百家樂
            'baccarat_compete',
            // 骰寶
            'dice',
            // 龍虎
            'dragon_tiger',
            // 輪盤
            'roulette',
            // 歐洲廳百家樂
            'baccarat_europe',
            // 歐洲廳輪盤
            'roulette_europe',
            // 歐洲廳 21 點
            'blackjack_europe',
            // 聚寶百家樂
            'baccarat',
            // 牛牛
            'bull_bull',
            // 炸金花
            'win_three_card',
            // 空戰世紀
            'air_force',
        ],
    ],
    // BINGO
    'bingo' => [
        'game_scopes' => [
            // 賓果星
            'bingo_star',
        ],
    ],
    // SUPER 體彩
    'super_sport' => [
        'game_scopes' => [
            // 美棒
            'baseball_us',
            // 日棒
            'baseball_jp',
            // 台棒
            'baseball_tw',
            // 韓棒
            'baseball_kr',
            // 冰球
            'ice_hockey',
            // 籃球
            'basketball',
            // 美足（美式足球）
            'american_football',
            // 網球
            'tennis',
            // 足球（英式足球）
            'soccer',
            // 指數
            'stock_market',
            // 賽馬
            'horse_racing',
            // 電競
            'e_sports',
            // 其他
            'others',
            // 世足
            'fifa_world_cup',
            // 彩票
            'lottery',
        ],
    ],
    // MAYA 瑪雅
    'maya' => [
        'game_scopes' => [
            // 百家樂
            'Baccarat',
            // 輪盤
            'Roulette',
            // 龍虎
            'LongHu',
            // 競咪百家樂
            'BIDBaccarat',
            // 百家樂包桌
            'VIPBaccarat',
            // 骰子
            'Dice',
            // 保險百家樂
            'INSBaccarat',
            // 牛牛
            'NiuNiu',
            // 三王牌
            'ThreeCardPoker',
            // 色碟
            'SeDie',
        ],
    ],
    // NIHTAN 泥炭
//    'nihtan' => [
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
//    ],
    // DG 夢幻
    'dream_game' => [
        'game_scopes' => [
            // 百家樂 1 1
            'baccarat',
            // 現場百家樂 1 1
            'live_baccarat',
            // 波貝百家樂 1 1
            'bobe_baccarat',
            // 波貝 VIP 百家樂 1 10
            'bobe_vip_baccarat',
            // 波貝保險百家樂 1 2
            'bobe_insurance_baccarat',
            // 競咪百家樂 1 8
            'compete_baccarat',
            // 龍虎 1 3
            'dragon_tiger',
            // 現場龍虎 1 3
            'live_dragon_tiger',
            // 輪盤 1 4
            'roulette',
            // 現場輪盤 1 4
            'live_roulette',
            // 骰寶 1 5
            'dice',
            // 極速骰寶 1 12
            'fast_dice',
            // 波貝骰寶 1 5
            'bobe_dice',
            // 鬥牛 1 7
            'bull_fighting',
            // 波貝鬥牛 1 7
            'bobe_bull_fighting',
            // 炸金花 1 11
            'fried_golden_flower',
            // 現場賭場撲克 1 9
            'show_hand',
            // 現場牛牛
            "live_niuniu",
            // 牛牛
            "NiuNiu",
            // 翻攤
            "FanTan",
            // 保險百家樂
            "insurance_baccara",
            // 色碟
            "disc",
            // 魚蝦蟹
            'fishPrawnCrab',
        ],
    ],
    // 手中寶 keno
    'so_power' => [
        'game_scopes' => [
            // 北京 PK10 (官方 300)
            'PK',
            // 競速 PK10 (自開 90)
            'P1',
            // 飛速 PK10 (自開 60)
            'P2',
            // 超級 PK10 (自開 30)
            'P3',
            // 紅火牛
            'RC',
            // 重慶時時彩 (官方 600)
            'CT',
            // 競速時時彩 (自開 90)
            'C1',
            // 飛速時時彩 (自開 60)
            'C2',
            // 超級時時彩 (自開 45)
            'C3',
        ],
    ],

    // LOTTERY 101 彩球
    'super_lottery' => [
        'game_scopes' => [
            // 六合
            'liu_he',
            // 大樂
            'da_le',
            // 539
            '539',
            // 天天樂
            'tian_tian_le',
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
    ],
    // HC 皇朝電競
    'hong_chow' => [
        'game_scopes' => [
            // 所有遊戲 0
            'all',
            // 英雄聯盟 League of Legends 1
            'lol',
            // 王者榮耀 Kings of Glory 2
            'kog',
            // DOTA2 3
            'dota2',
            // CS:GO 4
            'csgo',
            // 絕地求生 PlayerUnknown's Battlegrounds 5
            'pubg',
            // 守望先鋒 OverWatch 6
            'ow',
            // 星際爭霸2 StarCraft II 7
            'sc2',
            // 魔獸爭霸3 WarCraft III 8
            'wc3',
            // 爐石傳說 HearthStone 9
            'hs',
            // 風暴英雄 Heroes of the Storm 11
            'hots',
            // 堡壘之夜 Fortnite 12
            'fortnite',
            // FIFA Online 13
            'fifa_online',
            // 穿越火線 CrossFire 14
            'cf',
            // 彩虹6號 Tom Clancy's Rainbow Six: Siege 15
            'rainbow6',
            // 傳說對決 Arena of Valor 16
            'aov',
            // Artifact 17
            'artifact',
        ],
    ],
    // Ameba Entertainment
    'ameba' => [
        'game_scopes' => [
            'slot',
        ],
    ],
    // RTG
    'real_time_gaming' => [
        'game_scopes' => [
            'slot',
        ],
    ],
    // Royal game
    'royal_game' => [
        'game_scopes' => [
            // 百家樂
            'Bacc',
            // 輪盤
            'LunPan',
            // 龍虎
            'LongHu',
            // 骰子
            'ShaiZi',
            // 保險百家樂
            'InsuBacc',
            // 翻攤
            'FanTan',
        ],
    ],
    // UFA 體育
    'ufa_sport' => [
        'game_scopes' => [
            'sport',
        ],
    ],
    // Ren Ni Ying 任你贏
    'ren_ni_ying' => [
        'game_scopes' => [
            // 北京賽車
            'bei_jing_sai_che',
            // 幸運飛艇
            'xing_yun_fei_ting',
            // 重慶時時彩
            'chong_qing_shi_cai',
            // 音速賽車 5分
            'yin_su_sai_che_5_min',
            // 音速賽車 75秒
            'yin_su_sai_che_75_sec',
            // 音速賽車 3分
            'yin_su_sai_che_3_min',
            // 江蘇骰寶(快3)
            'jiang_su_tou_bai',
        ],
    ],
    // CQ9 game
    'cq9_game' => [
        'game_scopes' => [
            'slot',
        ],
    ],
    // Winner Sport 贏家體育
    'winner_sport' => [
        'game_scopes' => [
            'AF' , // 美足
            'BK', // 美籃
            'BS', // 美棒
            'HO', // 冰球
            'JB', // 日棒
            'KB', // 韓棒
            'KT', // 籃球
            'LO', // 彩球
            'SC', // 足球
            'SE', // 棒球
            'SK', // 指數
            'TB', // 中職
            'RH', // 賽馬
            'RD', // 賽狗
            'PR', // 混和過關
        ],
    ],
    // 9K Lottery 9K彩球
    'nine_k_lottery' => [
        'game_scopes' => [
            'BingoBingo', // 賓果賓果
            'BJPK10', // 北京賽車 PK 拾
            'XYFT', // 幸運飛艇=
            'JSPK10', // 9K 極速 PK 拾 (75 秒/局)
            'KPPK10', // 9K 高頻 PK 拾 (180 秒/局)
            'BJKENO8', // 北京快樂 8
            'SLFK', // 斯洛伐克
            'CQSSC', // 重慶時時彩
            'TJSSC', // 天津時時彩
            'XJSSC', // 新彊時時彩
            'TXSSC', // 騰訊分分彩
            'QQSSC', // QQ 分分彩
            'TKKENO', // 東京 1.5 分
            'COVID19', // COVID19疫情預測
            'other', // 自開彩
        ],
    ],
    // 9K Lottery 9K彩球(自開彩)
    'nine_k_lottery_2' => [
        'game_scopes' => [
            'BingoBingo', // 賓果賓果
            'BJPK10', // 北京賽車 PK 拾
            'XYFT', // 幸運飛艇=
            'JSPK10', // 9K 極速 PK 拾 (75 秒/局)
            'KPPK10', // 9K 高頻 PK 拾 (180 秒/局)
            'BJKENO8', // 北京快樂 8
            'SLFK', // 斯洛伐克
            'CQSSC', // 重慶時時彩
            'TJSSC', // 天津時時彩
            'XJSSC', // 新彊時時彩
            'TXSSC', // 騰訊分分彩
            'QQSSC', // QQ 分分彩
            'TKKENO', // 東京 1.5 分
            'COVID19', // COVID19疫情預測
            'other', // 自開彩
        ],
    ],
    // QTech 電子
    'q_tech' => [
        'game_scopes' => [
            'slot'
        ],
    ],
    // WM 真人
    'wm_casino' => [
        'game_scopes' => [
            '101', // 百家樂
            '102', // 龍虎
            '103', // 輪盤
            '104', // 骰寶
            '105', // 牛牛
            '106', // 三公
            '107', // 番摊
            '108', // 色碟
            '110', // 魚蝦蟹
            '111', // 炸金花
            '112', // 溫州牌九
            '113', // 二八杠
        ]
    ],
    // bobo_poker 人人棋牌
    'bobo_poker' => [
        'game_scopes' => [
            // 北京賽車
            'racing',
            // 幸運飛艇
            'rowing',
            // 歡樂生肖
            'timetime',
            // 廣東11選五
            'gd11x5',
            // 江蘇11選五
            'js11x5',
            // 江西11選五
            'jx11x5',
            // 山東11選五
            'sd11x5',
            // 北京快三
            'bjk3',
            // 甘肅快三
            'gsk3',
            // 廣西快三
            'gxk3',
            // 河北快三
            'hebk3',
            // 湖北快三
            'hubk3',
            // 江蘇快三
            'jsk3',
            // 越南彩
            'vietnam-lottery',
            // 60秒急速賽車
            'self-racing',
            // 60秒急速飛艇
            'self-rowing',
            // 60秒急速11選五
            'ffc11x5',
            // 60秒急速快三
            'ffck3',
            // 台灣60秒賓果
            'self-bingobingo',
            // 發大財賽馬
            'horserace',
            // 60秒越南彩
            'self-vietnam',
            // 龍虎
            'dragon-tiger',
            // 骰寶
            'dice-bao',
            // 反圍骰
            'opposite-dice-bao',
            // 瘋狂輪盤
            'crazy-roulette',
            // 美式輪盤
            'american-roulette',
            // 21點
            'blackjack',
            // 人人牛牛
            'cowcow',
            // 二八槓
            'this-bar',
            // 女王的新衣'
            'roulette',
            // 百家樂
            'baccarat',
            // 色碟
            'shaking-disc',
            // 魚蝦蟹
            'fish-prawn-crab',
            // 越南三牌
            'ba-cay',
            // 越南五分彩
            'self-vietnam-five',
            // 櫻花三分彩
            'self-ball-three',
            // 快樂骰寶
            'happy-dice-bao',
            // 快樂色碟
            'happy-shaking-disc',
            // 歡樂魚蝦蟹
            'happy-fish-prawn-crab',
            // 歡樂龍虎
            'happy-dragon-tiger',
            // 歡樂輪盤
            'happy-roulette',
            // 越南炸金花
            'vietnam-golden-flower',
            // 百人牛牛
            'happy-cowcow',
            // 星河輪盤
            'new-roulette',
            // 歡樂百家樂
            'happy-baccarat',
            // 番攤
            'fan-tan',
            // 二八槓
            'happy-this-bar',
        ],
    ],
    // FOREVER 8 (AV電子)
    'forever_eight' => [
        'game_scopes' => [
            'slot'
        ],
    ],
    // slot_factory SF電子
    'slot_factory' => [
        'game_scopes' => [
            'slot'
        ],
    ],
    // S128 COCK FIGHT (S128鬥雞)
    'cock_fight' => [
        'game_scopes' => [
            'fight'
        ],
    ],
    // 賓果牛牛
    'bingo_bull' => [
        'game_scopes' => [
            // 賓果牛牛 (官方)
            'bingoBull',
        ],
    ],
    // CMD 體育
    'cmd_sport' => [
        'game_scopes' => [
            'sport',
        ],
    ],
    // 越南彩
    'vs_lottery' => [
        'game_scopes' => [
            'keno',
        ],
    ],
    // awc_sexy 性感百家樂
    'awc_sexy' => [
        'game_scopes' => [
            // 經典百家樂
            'MX-LIVE-001',
            // 保險百家樂
            'MX-LIVE-003',
            // 龍虎
            'MX-LIVE-006',
            // 骰寶
            'MX-LIVE-007',
            // 輪盤
            'MX-LIVE-009',
            // 紅藍對決 (只支援手機版)
            'MX-LIVE-010',
        ],
    ],
    'habanero' => [
        'game_scopes' => [
            'slot'
        ],
    ],
    // KK彩票
    'kk_lottery' => [
        'game_scopes' => [
            'keno',
        ],
    ],
    // 反波膽
    'incorrect_score' => [
        'game_scopes' => [
            // 英式足球
            '1',
        ],
    ],
    // MG 棋牌
    'mg_poker' => [
        'game_scopes' => [
            '101', // 搶莊牛牛
            '102', // 搶莊牌九
            '103', // 百家樂
            '104', // 二十一點
            '105', // 看三張搶莊牛牛
            '106', // 三公
            '107', // 炸金花
            '108', // 搶莊二八槓
            '109', // 百人牛牛      
            '110', // 龍虎鬥
            '111', // 德州撲克
            '112', // 紅黑大戰
            '113', // 看四張搶莊牛牛
            '114', // 通比妞妞
            '115', // 看四張搶莊牛牛
            '116', // 癩子牛牛
            '117', // 黑紅梅方
            '118', // 萬人推筒子
            '119', // 通比三公
            '120', // 鬥地主
            '201', // 台灣麻將
        ],
    ],
];
