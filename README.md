# 遊戲站 API 呼叫器模組
    
### 安裝
因為是私有版控庫，安裝此 package 的專案必需在自己的 composer.json 先定義版控庫來源

    "repositories": [
        {
            "type": "git",
            "url": "git@git.sp168.cc:super-platform/api-caller.git"
        },
    ],

接著就可以透過下列指令進行安裝

    composer require super-platform/api-caller

如果 Laravel 版本在 5.4 以下，你必需手動追加 ServerProvider

    // config/app.php
    'providers' => [
        ...
        SuperPlatform\ApiCaller\ApiCallerServiceProvider::class,
    ],

如果不確定，就老實的使用手動追加最保險的方式

### API 呼叫器使用方法

一般 API 呼叫器範例

    $response = ApiCaller::make('sa_gaming')
        ->methodAction('POST', 'VerifyUsername')
        ->params([
            // 表單參數或 GET 的 query 參數
            'Username' => str_random()
        ])
        ->submit();
        
Rest API 呼叫器範例(部分參數是在路由中)

    $response = ApiCaller::make('bingo')
        ->methodAction('POST', 'players/{playerId}', [
            // 路由參數
            'playerId' => 'hero'
        ])
        ->params([
            // json 參數
        ])
        ->submit();
   
### API 橋接器使用方法

#### 前置作業

1. 先行在 ```ApiCaller``` 中實作對應館別的 ```StationCaller```
2. 先行在 ```ApiCaller``` 中實作對應館別的 ```StationCaller``` 的測試，確保能動
3. 根據文件提供的 Api 訪問方式，實作訪問時需要的加解密邏輯（若有加解密過程），確保 submit() 會返回成功
4. 根據文件提供的各功能名稱、參數，建立參照表 ```api_caller_bridge.php``` 內容
5. 實作呼叫參照表方法，並測試其動作
6. 完成，可嘗試使用本套件的 ApiPoke::poke() 方式訪問橋接後的動作名稱，與使用橋接後的參數

舉個例子，呼叫「取得餘額」方法

```
$response = ApiPoke::poke(
	// station name
    'stationName',
	// bridge action name
    'getBlanace', 
    // bridge parameters
    [
    	'form_params' => [
      		// your form parameters, if your have.
    	],
    	'route_params' => [
      		// your form parameters, if your have.
    	],
  	]
);
```

若需要取得所有參照名稱，請使用指令（尚未實作）

```
php artisan apipoke:document
```

查詢其他可用參數請使用指令（尚未實作）

```
php artisan apipoke --help
```

#### API 橋接器 Bridge Action 名稱參照表結構說明

/config/api_caller_bridge.php

```
  // 橋接 bridge action name
  'example_bridge_action' => [
      'bingo'       => [
      	'method' => 'POST', 
        'action' => 'real_bingo_action_name',     
        'route_params' => [...], 
        'form_params' => [...]
      ],
      'new_station' => [
      	'method' => 'POST', 
        'action' => 'real_new_station_action_name',     
        'route_params' => [...], 
        'form_params' => [...]
      ],
      ...
  ],
```

1. example_bridge_action: 根據 API 功能，統一 API action 名稱，並使其格式為 camel case
2. method: 根據 API 文件，定義應該使用的 http method 對應當前訪問的 API Action
3. action: 實際對應的 API Action（需根據各遊戲館文件自行補到 StationCaller::$enabledMethodActions 中）
4. params: 根據 API 文件，轉換傳入的橋接參數，到對應文件中實際參數的名稱，例如傳入 account 對應 all_bet 建立帳號需要的帳號參數名稱為 client

#### 路由參數：統一名稱一律使用 snake_case
```
  'route_params' => [
      // 必傳參數
      'require' => [
          // 統一名稱 => 對應名稱
          'account' => 'userAccount',
          'password' => 'userPassword',
          ...
      ],
      // 選填參數
      'optional' => [
          // 統一名稱 => 對應名稱
          'expires_in' => 'expiresDateTime'
          ...
      ],
  ]
```

#### 表單參數：統一名稱一律使用 snake_case
```
  'form_params' => [
      // 必傳參數
      'require' => [
          // 統一名稱 => 對應名稱
          'account' => 'client'
          ...
      ],
      // 選填參數
      'optional' => [
          // 統一名稱 => 對應名稱
          'name' => 'user_name'
          ...
      ],
  ]
```

#### 新增遊戲館參照資料到 ApiCaller

新增遊戲館，需到每個橋接 action 中，新增對應遊戲館的橋接名稱，否則會拋出例外；若有遊戲館不支援該方法，method 與 action 留空。

p. s. 請根據遊戲館提供的 API 文件，把對應功能建立到參照表中，否則請使用 ```ApiCaller``` 方法呼叫對應功能。

```
'example_bridge_action_1' => [
	'new_station' => [
      	'method' => 'GET', 
        'action' => 'real_new_station_action_name',     
        'route_params' => [
      		// 必傳參數
      		'require' => [
          		// 統一名稱 => 對應名稱
          		...
      		],
      		// 選填參數
      		'optional' => [
          		// 統一名稱 => 對應名稱
          		...
      		],
        ], 
        'form_params' => [
      		// 必傳參數
      		'require' => [
          		// 統一名稱 => 對應名稱
          		...
      		],
      		// 選填參數
      		'optional' => [
          		// 統一名稱 => 對應名稱
          		...
      		],
         ]
      ],
      ...
  ],
// 假設不存在對應 example_bridge_action_2 功能的 api，method 與 action 留空
'example_bridge_action_2' => [
	'new_station' => [
      	'method' => '', 
        'action' => '',     
        'route_params' => [
      		// 必傳參數
      		'require' => [
          		// 統一名稱 => 對應名稱
          		...
      		],
      		// 選填參數
      		'optional' => [
          		// 統一名稱 => 對應名稱
          		...
      		],
        ], 
        'form_params' => [
      		// 必傳參數
      		'require' => [
          		// 統一名稱 => 對應名稱
          		...
      		],
      		// 選填參數
      		'optional' => [
          		// 統一名稱 => 對應名稱
          		...
      		],
         ]
      ],
      ...
  ],
  ...
```