<?php

namespace SuperPlatform\ApiCaller\Exceptions;

use Exception;

class ApiCallerException extends Exception
{
    /**
     * @var array
     */
    protected $response;
    public $station;

    /**
     * 瑪雅 ErrorCode 對照表，幫助測試階段排除問題
     *
     * @var array
     */
    private $mayaErrorCodeMapping = [
        '0' => '成功',
        '-1' => '系統繁忙',
        '11001' => '參數無效',
        '11002' => '系統維護中',
        '11003' => '無效的代理商編碼',
        '11004' => '非法 IP',
        '11005' => '不合法的數據簽名',
        '11006' => 'DES 驗證失敗',
        '11007' => '必填參數有空值',
        '11008' => '傳入參數超過約定長度',
        '11009' => '傳入參數類型有誤',
        '11010' => 'Request Time Out',
        '11018' => '不合法的日期格式',
        '11019' => '開始時間大於結束時間',
        '11020' => '參數值不在規定範圍內',
        '11021' => '試玩用戶過多',
        '11022' => '獲取試玩帳號失敗',
        '11023' => '帳號驗證回調接口錯誤',
        '11024' => 'Token 無效',
        '11025' => '回調請求失敗 HttpCode 錯誤',
        '11026' => '入庫失敗',
        '11028' => '同步账号已存在',
        '11029' => 'MD5 驗證失敗',
        '11030' => '無效的貸幣類型',
        '11031' => '未知道系統異常',
        '11032' => 'DES 解密異常',
        '11033' => '缺少數據簽名',
        '11034' => '獲取手機帳號註冊地址異常',
        '11035' => '找不到指定用戶',
        '11036' => 'BaseCdoe 加密異常',
        '11037' => '進入遊戲回調信息缺失',
        '11038' => '進入遊戲回調參數缺失',
        '11039' => '代理商回調接口編碼缺失',
        '11040' => '代理商回調接口編碼錯誤',
        '11041' => '重置試玩餘額失敗',
        '11045' => 'VenderMemberID 不存在',
        '11047' => '操作過於頻繁稍等片刻在試',
    ];

    /**
     * DG ErrorCode 對照表，幫助測試階段排除問題
     *
     * @var array
     */
    private $dgErrorCodeMapping = [
        '0' => '操作成功',
        '1' => '參數錯誤',
        '2' => 'Token 驗證失敗',
        '4' => '非法操作',
        '10' => '日期格式錯誤',
        '11' => '數據格式錯誤',
        '97' => '沒有權限',
        '98' => '操作失敗',
        '99' => '未知錯誤',
        '100' => '賬號被鎖定',
        '101' => '賬號格式錯誤',
        '102' => '賬號不存在',
        '103' => '此賬號被占用',
        '104' => '密碼格式錯誤',
        '105' => '密碼錯誤',
        '106' => '新舊密碼相同',
        '107' => '會員賬號不可用',
        '108' => '登入失敗',
        '109' => '註冊失敗',
        '113' => '傳入的代理賬號不是代理',
        '114' => '找不到會員',
        '116' => '賬號已占用',
        '117' => '找不到會員所屬的分公司',
        '118' => '找不到指定的代理',
        '119' => '存取款操作時代理點數不足',
        '120' => '餘額不足',
        '121' => '盈利限制必須大於或等於0',
        '150' => '免費試玩賬號用完',
        '300' => '系統維護',
        '320' => 'API Key 錯誤',
        '321' => '找不到相應的限紅組',
        '322' => '找不到指定的貨幣類型',
        '323' => '轉賬流水號占用',
        '324' => '轉賬失敗',
        '325' => '代理狀態不可用',
        '326' => '會員代理沒有視頻組',
        '328' => 'API 類型找不到',
        '329' => '會員代理信息不完整',
        '400' => '客戶端 IP 受限',
        '401' => '網路延遲',
        '402' => '連接關閉',
        '403' => '客戶端來源受限',
        '404' => '請求的資源不存在',
        '405' => '請求太頻繁',
        '406' => '請求超時',
        '407' => '找不到游戲地址',
        '500' => '空指針異常',
        '501' => '系統異常',
        '502' => '系統忙',
        '503' => '數據操作異',
    ];

    private $nineKCodeMapping = [
        '0' => '成功',
        '-1' => 'Api 系統錯誤，請重試或連絡系統商',
        '-2' => 'ApiToKen 認證失敗',
        '-3' => 'BossID 不存在',
        '-1001' => 'MemberAccount 是無效格式',
        '-1002' => 'MemberPassword 是無效格式',
        '-1003' => 'MemberAccount 已存在',
        '-1004' => 'MemberAccount 不存在',
        '-1005' => 'Member 帳號被鎖定',
        '-1006' => 'Member 登入失敗',
        '-2001' => '轉帳失敗',
        '-2002' => 'BossID 代理額度不足',
        '-2003' => 'Member 會員額度不足',
        '-2004' => '轉帳額度為 0',
        '-2005' => '轉帳交易記錄不存在',
        '-2006' => '使用方交易單號已存在',
        '-3001' => '日期格式錯誤',
        '-9999' => '系統維護中, 不開放',
    ];

    private $bingoBullCodeMapping = [
        '1' => '執行成功',
        '10002' => '取不到 token',
        '10003' => '接收到不完整的參數',
        '10004' => '逾期的 token',
        '10005' => '錯誤的 apikey',
        '10006' => '輸入的帳號已存在',
        '10007' => '帳號須為英文開頭的英數組合',
        '10008' => '帳號須為 25 個字元之內',
        '10009' => '帳號輸入錯誤',
        '10010' => '此帳號非下層會員',
        '10011' => '商戶點數餘額不足',
        '10012' => '會員點數餘額不足',
        '10013' => '非白名單 ip',
        '10014' => '搜尋時間限制不得超過 1 日',
        '10015' => '輸入的頁數只能是數字格式',
        '10016' => '前綴代碼錯誤',
    ];

    /**
     * @param array $response
     * @param string $station
     */
    public function __construct($response = [], $station)
    {
        parent::__construct('Api caller receive failure response, use `$exception->response()` get more details.');

        $this->response = $response ?: [];
        $this->station = $station;
    }

    public function response()
    {
        $response = $this->response;

        switch ($this->station) {
            case 'all_bet':
                $response['errorCode'] = $this->response['error_code'];
                $response['errorMsg'] = $this->response['message'];
                break;
            case 'bingo':
                // 使用 http_code 回應錯誤
                $response['errorCode'] = $response['code'];
                $response['errorMsg'] = $response['message'];
                break;
            case 'holdem':
                $response['errorCode'] = $this->response['code'];
                $response['errorMsg'] = $this->response['msg'];
                break;
            case 'maya':
                $response['errorCode'] = $this->response['ErrorCode'];
                $response['errorMsg'] = array_has($this->mayaErrorCodeMapping, $response['errorCode'])
                    ? array_get($this->mayaErrorCodeMapping, $response['errorCode'])
                    : '';
                break;
            case 'so_power':
                $response['errorCode'] = $this->response['message'];
                $response['errorMsg'] = $this->response['message'];
                break;
            case 'nihtan':
                // 問題很多
                break;
            case 'sa_gaming':
                $response['errorCode'] = $this->response['ErrorMsgId'];
                $response['errorMsg'] = $this->response['ErrorMsg'];
                break;
            case 'super_sport':
                $response['errorCode'] = $this->response['code'];
                $response['errorMsg'] = $this->response['msg'];
                break;
            case 'dream_game':
                $response['errorCode'] = $this->response['codeId'];
                $response['errorMsg'] = array_has($this->dgErrorCodeMapping, $this->response['codeId'])
                    ? array_get($this->dgErrorCodeMapping, $response['errorCode'])
                    : $this->getMessage();
                break;
            case 'super_lottery':
                $response['errorCode'] = $this->response['code'];
                $response['errorMsg'] = $this->response['msg'];
                break;
            case 'hong_chow':
                $response['errorCode'] = $this->response['code'];
                $response['errorMsg'] = $this->response['msg'];
                break;
            case 'ameba':
                $response['errorCode'] = $this->response['error_code'];
                $response['errorMsg'] = $this->response['error_code'];
                break;
            case 'real_time_gaming':
                $response['errorCode'] = $this->response['error_code'];
                $response['errorMsg'] = $this->response['error_code'];
                break;
            case 'royal_game':
                $response['errorCode'] = $this->response['error_code'];
                $response['errorMsg'] = $this->response['error_msg'];
                break;
            case 'ren_ni_ying':
                $response['errorCode'] = $this->response['errorCode'];
                $response['errorMsg'] = $this->response['errorMsg'];
                break;
            case 'cq9_game':
                $response['errorCode'] = $this->response['code'];
                $response['errorMsg'] = $this->response['message'];
                break;
            case 'nine_k_lottery':
                $response['errorCode'] = $this->response['errorCode'];
                $response['errorMsg'] = array_has($this->nineKCodeMapping, $this->response['errorCode'])
                    ? array_get($this->nineKCodeMapping, $response['errorCode'])
                    : $this->getMessage();
                break;
            case 'nine_k_lottery_2':
                $response['errorCode'] = $this->response['errorCode'];
                $response['errorMsg'] = array_has($this->nineKCodeMapping, $this->response['errorCode'])
                    ? array_get($this->nineKCodeMapping, $response['errorCode'])
                    : $this->getMessage();
                break;
            case 'ufa_sport':
                $response['errorCode'] = $this->response['errcode'];
                $response['errorMsg'] = $this->response['errtext'];
                break;
            case 'q_tech':
                $response['errorCode'] = $this->response['errorCode'];
                $response['errorMsg'] = $this->response['errorMsg'];
                break;
            case 'wm_casino':
                $response['errorCode'] = $this->response['errorCode'];
                $response['errorMsg'] = $this->response['errorMessage'];
                break;
            case 'bobo_poker':
                $response['errorCode'] = $this->response['errorCode'];
                $response['errorMsg'] = $this->response['errorMsg'];
                break;
            case 'forever_eight':
                $response['errorCode'] = $this->response['ErrorCode'];
                $response['errorMsg'] = $this->response['ErrorMsg'];
                break;
            case 'slot_factory':
                $response['errorCode'] = $this->response['errorCode'];
                $response['errorMsg'] = $this->response['errorMsg'];
                break;
            case 'bingo_bull':
                $response['errorCode'] = $this->response['errorCode'];
                $response['errorMsg'] = array_has($this->bingoBullCodeMapping, $this->response['errorCode'])
                    ? array_get($this->bingoBullCodeMapping, $response['errorCode'])
                    : $this->getMessage();
                break;
            case 'cock_fight':
                $response['errorCode'] = $this->response['errorCode'];
                $response['errorMsg'] = $this->response['errorMsg'];
                break;
            case 'cmd_sport':
                $response['errorCode'] = $this->response['errorCode'];
                $response['errorMsg'] = $this->response['errorMsg'];
                break;
            case 'vs_lottery':
//                $response['errorCode'] = $this->response['errorCode'];
//                $response['errorMsg'] = $this->response['errorMsg'];
                break;
            case 'awc_sexy':
                $response['errorCode'] = $this->response['errorCode'];
                $response['errorMsg'] = $this->response['errorMsg'];
                break;
            case 'habanero':
                $response['errorCode'] = $this->response['Success'];
                $response['errorMsg'] = $this->response['Message'];
                break;
            case 'kk_lottery':
                $response['errorCode'] = $this->response['errorCode'];
                $response['errorMsg'] = $this->response['errorMsg'];
                break;
            case 'incorrect_score':
                $response['errorCode'] = $this->response['errorCode'];
                $response['errorMsg'] = $this->response['errorMsg'];
                break;
            case 'mg_poker':
                $response['errorCode'] = $this->response['code'];
                $response['errorMsg'] = $this->response['msg'];
                break;
            default:
                $response['errorCode'] = null;
                $response['errorMsg'] = null;
                break;
        }

        $this->response = $response;

        return $this->response;
    }
}