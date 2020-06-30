<?php

namespace SuperPlatform\ApiCaller\Callers;

use GuzzleHttp\Client as GuzzleClient;
use SuperPlatform\ApiCaller\Exceptions\ApiCallerException;
use SuperPlatform\ApiCaller\Exceptions\BridgeActionException;
use SuperPlatform\ApiCaller\Exceptions\BridgeActionParamsException;
use Symfony\Component\Console\Output\ConsoleOutput;

abstract class Caller implements CallerInterface
{
    /**
     * HTTP 請求器
     *
     * @var GuzzleClient
     */
    protected $guzzleClient;

    /**
     * 遊戲站呼叫器的相關設定
     *
     * @var array
     */
    protected $config = [];

    /**
     * API 請求方式
     *
     * @var string
     */
    protected $method = '';

    /**
     * API 執行動作
     *
     * @var string
     */
    protected $action = '';

    /**
     * API 傳送參數
     *
     * @var array
     */
    protected $formParams = [];

    /**
     * 終端器輸出器
     *
     * @var ConsoleOutput
     */
    protected $console;

    /**
     * 遊戲站呼叫器有效的方法動作
     *
     * @var array
     */
    protected $enabledMethodActions;

    /**
     * @var array
     */
    protected $defaultEnabledMethodActions = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
        'PATCH' => [],
    ];

    /**
     * Caller constructor
     */
    public function __construct()
    {
        $this->guzzleClient = new GuzzleClient();
        $this->console = new ConsoleOutput();

        $this->enabledMethodActions = array_merge($this->defaultEnabledMethodActions, $this->enabledMethodActions);
        $this->enabledMethodActions = array_change_key_case($this->enabledMethodActions, CASE_UPPER);
        $this->enabledMethodActions = $this->arrayTrimRecursive($this->enabledMethodActions);
    }

    /**
     * 遞迴去除陣列資料頭尾空白
     *
     * @param $array
     * @return array|string
     */
    private function arrayTrimRecursive($array)
    {
        if (!is_array($array)) {
            return trim($array);
        }
        return array_map([$this, 'arrayTrimRecursive'], $array);
    }

    /**
     * 設定 API 請求模式與動作
     *
     * @param string ("GET", "POST", "PUT", "DELETE") $method
     * @param string $action
     * @param array $routeParams
     * @return static
     * @throws \Exception
     */
    public function methodAction(string $method, string $action, array $routeParams = [])
    {
        $method = strtoupper($method);

        if (!isset($this->enabledMethodActions[$method])) {
            throw new \Exception('Invalid method:' . $method);
        }
        if (!in_array($action, $this->enabledMethodActions[$method])) {
            throw new \Exception('Invalid action:' . $action);
        }
        $this->method = $method;
        $this->action = (!empty($routeParams))
            ? $this->bindRouteParams($action, $routeParams)
            : $action;

        return $this;
    }

    /**
     * 綁定路由 URL 中定義的變數
     *
     * 範例：
     *   $url = 'players/{playerId}/foo/{bar}';
     *   $params = [
     *       'playerId' => 123,
     *       'bar' => 'abc',
     *   ];
     *   echo bindRouteParams($url, $params);
     *   // 輸出 'players/123/foo/abc'
     *
     * @param string $url
     * @param array $params
     * @return string
     */
    private function bindRouteParams(string $url, $params)
    {
        preg_match_all('/{\K[^}]*(?=})/m', $url, $matches);
        $search = [];
        $replace = [];
        foreach ($matches[0] as $match) {
            $search[] = "/{{$match}}/";
            $replace[] = isset($params[$match]) ? $params[$match] : "{{$match}}";
        }

        return preg_replace($search, $replace, $url);
    }

    /**
     * 約定不足加密區塊長度填充字元
     *
     * @param string $text
     * @param string $chrPad
     * @param int $blockSize
     * @return string
     */
    protected function pkCs5Pad(string $text, string $chrPad = '', int $blockSize = 8)
    {
        $pad = $blockSize - (strlen($text) % $blockSize);
        $chrPad = strlen($chrPad) ? $chrPad : chr($pad);
        return $text . str_repeat($chrPad, $pad);
    }

    /**
     * 各遊戲館方法橋接器
     *
     * @param string $station
     * @param string $bridgeAction
     * @param array $params
     * @return array
     * @throws BridgeActionException
     * @throws BridgeActionParamsException
     * @throws \Exception
     */
    public function bridge(string $station, string $bridgeAction, array $params = [])
    {
        $bridgeActionMapper = self::bridgeActionMapper($station, $bridgeAction);
        try {
            return $this->methodAction(
                array_get($bridgeActionMapper, 'method'),
                array_get($bridgeActionMapper, 'action'),
                self::replaceBridgeParams(
                    array_get($params, 'route_params', []),
                    array_get($bridgeActionMapper, 'route_params')
                )
            )->params(
                self::replaceBridgeParams(
                    array_get($params, 'form_params', []),
                    array_get($bridgeActionMapper, 'form_params')
                )
            )->submit();
        } catch (ApiCallerException $exception) {
            throw new ApiCallerException($exception->response(), $station);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * 橋接方法名稱的對應方法動作查詢器
     *
     * @param string $station
     * @param string $bridgeAction
     * @return \Illuminate\Config\Repository|mixed
     * @throws BridgeActionException
     */
    public static function bridgeActionMapper(string $station, string $bridgeAction)
    {
        $methodAction = config(
            'api_caller_bridge.' .
            $bridgeAction .
            '.' .
            $station
        );

        if (!empty($methodAction)) {
            return $methodAction;
        }

        throw new BridgeActionException();
    }

    /**
     * 橋接方法的傳入參數名稱，轉換成對應遊戲館的參數名稱
     *
     * @param array $params
     * @param array $mapper
     * @return array
     * @throws BridgeActionParamsException
     */
    public static function replaceBridgeParams(array $params, array $mapper)
    {
        // 檢查對象
        if (empty($params)) {
            return [];
        }
        $keys = array_keys($params);

        // 檢查 require，若傳入參數不完全包含這陣列中所有元素，噴例外
        $requireParams = array_get($mapper, 'require');
        $diffRequireParams = array_diff(array_keys($requireParams), array_keys($params));
        if (!empty($diffRequireParams)) {
            throw new BridgeActionParamsException([
                    'message' => 'The bridge action have to be passed required params. ' .
                        'There are short of require parameters: ' .
                        join($diffRequireParams, ', ')
                ]
            );
        }

        // 傳入不存在的其他參數，噴例外
        $optionalParams = array_get($mapper, 'optional');
        $diffParams = array_diff($keys, array_keys(array_merge($requireParams, $optionalParams)));
        if (!empty($diffParams)) {
            throw new BridgeActionParamsException([
                    'message' => 'Invalid parameters passed. There are not allowed parameters: ' .
                        join($diffParams, ', ')
                ]
            );
        }

        // 轉換 require key
        $newParams = [];
        foreach ($keys as $key) {
            if (array_key_exists($key, $requireParams)) {
                $newParams[$requireParams[$key]] = $params[$key];
            }
            if (array_key_exists($key, $optionalParams)) {
                if (is_null($params[$key])) continue;
                $newParams[$optionalParams[$key]] = $params[$key];
            }
        }

        return $newParams;
    }

    /**
     * 將 GuzzleHttp curl 的 response 結果轉換成統一回應格式
     *
     * @param \GuzzleHttp\Psr7\Response $response
     * @param array $array
     * @return array
     */
    public function responseFormatter(\GuzzleHttp\Psr7\Response $response, array $array = [])
    {
        return [
            'http_code' => $response->getStatusCode(),
            'http_contents' => $response->getBody()->getContents(),
            'http_headers' => $response->getHeaders(),
            'response' => $array,
        ];
    }
}