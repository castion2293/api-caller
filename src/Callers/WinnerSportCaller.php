<?php

namespace SuperPlatform\ApiCaller\Callers;

use GuzzleHttp\Exception\GuzzleException;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;

class WinnerSportCaller extends Caller
{
    public const STATION_NAME = 'winner_sport';

    protected $enabledMethodActions = [
        'POST' => [
            'Create_Member', // 新增會員帳號
            'Member_Login', // 帳號登入
            'Member_Edit', // 修改會員帳號
            'Member_Money', // 檢查點數
            'Transfer_Money', // 存/提款
            'Transfer_Check', // 檢查存/提款動作
            'Minus_Money', // 抓取負額度會員
            'Zero_Money', // 負額度會員的額度歸零
            'Get_Tix', // 抓取注單 如果沒有提供maxModId則用最近7天的紀錄 一次最多200筆
            'Find_Tix1', // 查詢注單1 用歸帳日查詢注單 只能查詢10天內的注單 用id排序最小到最大 一次最多200筆
            'Find_Tix2', // 查詢注單2 用下注時間查詢注單 只能查詢10天內的注單 用id排序最小到最大 一次最多200筆
            'Get_Msg', // 最新消息
        ],
    ];

    public function __construct()
    {
        parent::__construct();

        $aApiConfig = config('api_caller.' . self::STATION_NAME . '.config');

        foreach ($aApiConfig as $k => $v) {
            switch ($k) {
                case 'api_url':
                case 'api_key':
                case 'token':
                    $this->config[$k] = $v;
            }
        }
    }

    public function __destruct()
    {
        unset($this->formParams);
        unset($this->config);
    }

    /**
     * 設定 API 參數
     *
     * @param array $aRequestParams
     * @return self
     */
    public function params(array $aRequestParams = []): self
    {
        if ($this->action === 'Find_Tix2') {
            $aRequestParams['agent'] = config('api_caller.' . self::STATION_NAME . '.config.top_account');
        }

        $this->formParams = array_merge([
            // 每次 request 都需要的參數
            'sign_key' => $this->encrypt($aRequestParams),
        ], $aRequestParams);

        return $this;
    }

    /**
     * 送出 API 請求
     *
     * @return array
     * @throws GuzzleException
     * @throws ApiCallerException
     * @throws \Exception
     */
    public function submit(): array
    {
        try {
            $sApiUrl = "{$this->config['api_url']}{$this->action}.php";
            $sResponseRawData = $this->guzzleClient->request(
                $this->method,
                $sApiUrl,
                [
                    'headers' => [
                        'api_key' => $this->config['api_key'],
                    ],
                    'form_params' => $this->formParams, // 一般 POST 參數傳遞方法
                    'timeout' => '30',
                ]
            );

            $aResponseContentsData = json_decode($sResponseRawData->getBody()->getContents(), true);

            if ($this->isSuccessResponse($aResponseContentsData['code'])) {
                return $this->responseFormatter($sResponseRawData, $aResponseContentsData);
            } else {
                throw new ApiCallerException($aResponseContentsData, self::STATION_NAME);
            }
        } catch (\Exception $exception){
            throw $exception;
        }
    }

    /**
     * @param array $aRequestParams
     * @return string
     */
    private function encrypt(array $aRequestParams): string
    {
        $aRequestParams['token'] = $this->config['token'];
        $str = '';

        foreach ($aRequestParams as $k => $v) {
            $str .= "$k=$v&";
        }

        return md5(substr($str, 0, -1));
    }

    /**
     * @param string $sResponseCode
     * @return bool
     */
    private function isSuccessResponse(string $sResponseCode): bool
    {
        switch ($this->action) {
            case 'Create_Member':
                if (in_array($sResponseCode, ['001', '002'])) { // 001 成功 002 帳號重複
                    return true;
                } else {
                    return false;
                }

                break;
            case 'Get_Tix':
                if (in_array($sResponseCode, ['001', '002'])) { // 001 成功 002 無任何紀錄
                    return true;
                } else {
                    return false;
                }

                break;
            case 'Find_Tix2':
                if (in_array($sResponseCode, ['001', '002'])) { // 001 成功 002 無任何紀錄
                    return true;
                } else {
                    return false;
                }

                break;
            default:
                if ($sResponseCode === '001') {
                    return true;
                } else {
                    return false;
                }

                break;
        }
    }
}