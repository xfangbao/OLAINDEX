<?php

namespace App\Service;

use App\Entities\ClientConfigEntity;
use App\models\OauthAccount;
use App\Utils\CoreConstants;
use Illuminate\Support\Arr;

class OneDrive
{
    /**
     * @var $instance
     */
    private static $instance;

    /**
     * @var OauthAccount $account
     */
    private $account;

    /**
     * @var GraphRequest $graph
     */
    private $graph;

    /**
     * @param OauthAccount $account
     * @return OneDrive
     */
    public static function getInstance(OauthAccount $account)
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self($account);
        }
        return self::$instance;
    }

    /**
     * OneDrive constructor.
     * @param OauthAccount $account
     */
    private function __construct($account)
    {
        $this->account = $account;
        $this->initRequest($account);
    }

    /**
     * @param OauthAccount $account
     */
    private function initRequest($account)
    {
        $accountType = $account->account_type;
        $clientConfig = new ClientConfigEntity(CoreConstants::getClientConfig($accountType));
        $baseUrl = $clientConfig->graph_endpoint;
        $apiVersion = $clientConfig->api_version;
        $accessToken = $account->access_token;
        $this->graph = (new GraphRequest())
            ->setAccessToken($accessToken)
            ->setBaseUrl($baseUrl)
            ->setApiVersion($apiVersion);
    }

    /**
     * @param      $method
     * @param      $param
     * @param null $token
     *
     * @return mixed
     * @throws \ErrorException
     */
    public function request($method, $param, $token = null)
    {
        $response = $this->graph->request($method, $param, $token);
        if (is_null($response->getResponseError())) {
            $headers = json_decode($response->getResponseHeaders(), true);
            $response = json_decode($response->getResponse(), true);
            return [
                'errno' => 0,
                'message' => 'OK',
                'headers' => $headers,
                'data' => $response,
            ];
        } else {
            return json_decode($response->getResponseError(), true);
        }
    }

    /**
     * 获取账户信息
     *
     * @throws \ErrorException
     */
    public function getAccountInfo()
    {
        $endpoint = '/me';
        $response = $this->request('get', $endpoint);
        return $response;
    }

    /**
     * 获取网盘信息
     *
     * @return mixed
     * @throws \ErrorException
     */
    public function getDriveInfo()
    {
        $endpoint = '/me/drive';
        $response = $this->request('get', $endpoint);
        return $response;
    }

    /**
     * 获取网盘资源目录列表
     *
     * @param string $itemId
     * @param string $query
     *
     * @return array|mixed
     * @throws \ErrorException
     */
    public function getItemList($itemId = '', $query = '')
    {
        $endpoint = $itemId ? "/me/drive/items/{$itemId}/children{$query}"
            : "/me/drive/root/children{$query}";
        $response = $this->request('get', $endpoint);
        if ($response['errno'] === 0) {
            $response_data = Arr::get($response, 'data');
            $data = $this->getNextLinkList($response_data);
            $format = $this->formatItem($data);
            return $this->response($format);
        } else {
            return $response;
        }
    }

    /**
     * 通过路径获取网盘资源目录列表
     *
     * @param string $path
     * @param string $query
     *
     * @return array|mixed
     * @throws \ErrorException
     */
    public function getItemListByPath($path = '/', $query = '')
    {
        $requestPath = $this->getRequestPath($path);
        $endpoint = $requestPath === '/' ? "/me/drive/root/children{$query}"
            : "/me/drive/root{$requestPath}children{$query}";
        $response = $this->request('get', $endpoint);
        if ($response['errno'] === 0) {
            $response_data = Arr::get($response, 'data');
            $data = $this->getNextLinkList($response_data);
            $format = $this->formatItem($data);
            return $this->response($format);
        } else {
            return $response;
        }
    }

    /**
     * 获取下一页全部网盘资源目录
     *
     * @param       $list
     * @param array $result
     *
     * @return array
     * @throws \ErrorException
     */
    public function getNextLinkList($list, &$result = [])
    {
        if (Arr::has($list, '@odata.nextLink')) {
            $baseLength = strlen($this->graph->getBaseUrl()) + strlen($this->graph->getApiVersion());
            $endpoint = substr($list['@odata.nextLink'], $baseLength);
            $response = $this->request('get', $endpoint);
            if ($response['errno'] === 0) {
                $data = $response['data'];
            } else {
                $data = [];
            }
            $result = array_merge(
                $list['value'],
                $this->getNextLinkList($data, $result)
            );
        } else {
            $result = array_merge($list['value'], $result);
        }
        return $result;
    }

    /**
     * 根据资源id获取网盘资源
     *
     * @param        $itemId
     * @param string $query
     *
     * @return array|mixed
     * @throws \ErrorException
     */
    public function getItem($itemId, $query = '')
    {
        $endpoint = "/me/drive/items/{$itemId}{$query}";
        $response = $this->request('get', $endpoint);
        if ($response['errno'] === 0) {
            $data = Arr::get($response, 'data');
            $format = $this->formatItem($data, false);
            return $this->response($format);
        } else {
            return $response;
        }
    }

    /**
     * 通过路径获取网盘资源
     *
     * @param        $path
     * @param string $query
     *
     * @return array|mixed
     * @throws \ErrorException
     */
    public function getItemByPath($path, $query = '')
    {
        $requestPath = $this->getRequestPath($path);
        $endpoint = "/me/drive/root{$requestPath}{$query}";
        $response = $this->request('get', $endpoint);
        if ($response['errno'] === 0) {
            $data = Arr::get($response, 'data');
            $format = $this->formatItem($data, false);
            return $this->response($format);
        } else {
            return $response;
        }
    }

    /**
     * 处理url
     *
     * @param $path
     *
     * @return string
     */
    public function encodeUrl($path)
    {
        $url = [];
        foreach (explode('/', $path) as $key => $value) {
            if (empty(!$value)) {
                $url[] = rawurlencode($value);
            }
        }
        return @implode('/', $url);
    }

    /**
     * 获取格式化请求路径
     *
     * @param      $path
     * @param bool $isFile
     *
     * @return string
     */
    public function getRequestPath($path, $isFile = false)
    {
        $originPath = $this->getAbsolutePath($path);
        $queryPath = trim($originPath, '/');
        $queryPath = $this->encodeUrl(rawurldecode($queryPath));
        $requestPath = empty($queryPath) ? '/' : ":/{$queryPath}:/";
        if ($isFile) {
            return rtrim($requestPath, ':/');
        }
        return $requestPath;
    }

    /**
     * 转换为绝对路径
     *
     * @param $path
     *
     * @return mixed
     */
    public function getAbsolutePath($path)
    {
        $path = str_replace(['/', '\\', '//'], '/', $path);
        $parts = array_filter(explode('/', $path), 'strlen');
        $absolutes = [];
        foreach ($parts as $part) {
            if ('.' == $part) {
                continue;
            }
            if ('..' == $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }
        return str_replace('//', '/', '/' . implode('/', $absolutes) . '/');
    }

    /**
     * 格式化目录数据
     *
     * @param      $response
     * @param bool $isList
     *
     * @return array
     */
    public function formatItem($response, $isList = true)
    {
        if ($isList) {
            $items = [];
            foreach ($response as $item) {
                if (Arr::has($item, 'file')) {
                    $item['ext'] = strtolower(
                        pathinfo(
                            $item['name'],
                            PATHINFO_EXTENSION
                        )
                    );
                }
                $items[$item['name']] = $item;
            }
            return $items;
        } else {
            $response['ext'] = strtolower(
                pathinfo(
                    $response['name'],
                    PATHINFO_EXTENSION
                )
            );
            return $response;
        }
    }

    public function response($data, $errno = 0, $msg = 'ok')
    {
        return [
            'errno' => $errno,
            'msg' => $msg,
            'data' => $data,
        ];
    }

    /**
     * 防止实例被克隆（这会创建实例的副本）
     */
    private function __clone()
    {
    }

    /**
     * 防止反序列化（这将创建它的副本）
     */
    private function __wakeup()
    {
    }
}
