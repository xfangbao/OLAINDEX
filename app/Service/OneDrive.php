<?php
/**
 * This file is part of the wangningkai/OLAINDEX.
 * (c) wangningkai <i@ningkai.wang>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Service;

use App\Models\Client;
use ErrorException;

class OneDrive
{
    /**
     * @var OneDrive
     */
    private static $instance;

    /**
     * @var $account
     */
    private $account;

    /**
     * @var RequestService $service
     */
    private $service;

    /**
     * @return OneDrive
     */
    public static function init(): OneDrive
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * AuthorizeService constructor.
     */
    private function __construct()
    {
    }

    /**
     * 初始化请求参数
     * @param $account
     * @return $this
     */
    public function bind($account): OneDrive
    {
        $this->account = $account;
        $client = new Client(Client::setClientConfig($this->account));
        $base_url = $client->graph_endpoint;
        $api_version = $client->api_version;
        $access_token = array_get($account, 'access_token');
        $this->service = (new RequestService())
            ->setAccessToken($access_token)
            ->setBaseUrl($base_url)
            ->setApiVersion($api_version);
        return $this;
    }

    /**
     * 发送请求
     * @param $method
     * @param $param
     * @param bool $needToken
     * @param string $attachBody
     * @return array|mixed
     * @throws ErrorException
     */
    public function request($method, $param, $needToken = true, $attachBody = '')
    {
        $response = $this->service->request($method, $param, $needToken, $attachBody);
        if ($response->error) {
            return json_decode($response->getResponseError(), true);
        }
        $headers = json_decode($response->getResponseHeaders(), true);
        $response = json_decode($response->getResponse(), true);

        return [
            'errno' => 0,
            'message' => 'OK',
            'headers' => $headers,
            'data' => $response,
        ];
    }

    /**
     * 获取账户信息
     * @return array|mixed
     * @throws ErrorException
     */
    public function getAccountInfo()
    {
        $endpoint = '/me';
        return $this->request('get', $endpoint);
    }

    /**
     * 获取网盘信息
     * @return array|mixed
     * @throws ErrorException
     */
    public function getDriveInfo()
    {
        $endpoint = '/me/drive';
        return $this->request('get', $endpoint);
    }

    /**
     * 获取网盘资源目录列表
     * @param string $itemId
     * @param string $query
     * @return array|mixed
     * @throws ErrorException
     */
    public function getItemList($itemId = '', $query = '')
    {
        $endpoint = $itemId ? "/me/drive/items/{$itemId}/children{$query}"
            : "/me/drive/root/children{$query}";
        $response = $this->request('get', $endpoint);
        if ($response['errno'] === 0) {
            $response_data = array_get($response, 'data');
            $data = $this->getNextLinkList($response_data);
            $format = $this->formatItem($data);
            return $this->response($format);
        }
        return $response;
    }

    /**
     * 通过路径获取网盘资源目录列表
     * @param string $path
     * @param string $query
     * @return array|mixed
     * @throws ErrorException
     */
    public function getItemListByPath($path = '/', $query = '')
    {
        $requestPath = $this->getRequestPath($path);
        $endpoint = $requestPath === '/' ? "/me/drive/root/children{$query}"
            : "/me/drive/root{$requestPath}children{$query}";
        $response = $this->request('get', $endpoint);
        if ($response['errno'] === 0) {
            $response_data = array_get($response, 'data');
            $data = $this->getNextLinkList($response_data);
            $format = $this->formatItem($data);
            return $this->response($format);
        }
        return $response;
    }

    /**
     * 获取下一页全部网盘资源目录
     * @param $list
     * @param array $result
     * @return array
     * @throws ErrorException
     */
    public function getNextLinkList($list, &$result = []): array
    {
        if (array_has($list, '@odata.nextLink')) {
            $baseLength = strlen($this->service->getBaseUrl()) + strlen($this->service->getApiVersion());
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
     * @param $itemId
     * @param string $query
     * @return array|mixed
     * @throws ErrorException
     */
    public function getItem($itemId, $query = '')
    {
        $endpoint = "/me/drive/items/{$itemId}{$query}";
        $response = $this->request('get', $endpoint);
        if ($response['errno'] === 0) {
            $data = array_get($response, 'data');
            $format = $this->formatItem($data, false);
            return $this->response($format);
        }
        return $response;
    }

    /**
     * 通过路径获取网盘资源
     * @param $path
     * @param string $query
     * @return array|mixed
     * @throws ErrorException
     */
    public function getItemByPath($path, $query = '')
    {
        $requestPath = $this->getRequestPath($path);
        $endpoint = "/me/drive/root{$requestPath}{$query}";
        $response = $this->request('get', $endpoint);
        if ($response['errno'] === 0) {
            $data = array_get($response, 'data');
            $format = $this->formatItem($data, false);
            return $this->response($format);
        }
        return $response;
    }

    /**
     * 上传文件
     * @param $id
     * @param $body
     * @return array|mixed
     * @throws ErrorException
     */
    public function upload($id, $body)
    {
        $endpoint = "/me/drive/items/{$id}/content";
        return $this->request('put', $endpoint, true, $body);
    }

    /**
     * 上传文件到指定路径
     * @param $path
     * @param $body
     * @return array|mixed
     * @throws ErrorException
     */
    public function uploadByPath($path, $body)
    {
        $requestPath = $this->getRequestPath($path);
        $endpoint = "/me/drive/root{$requestPath}content";
        return $this->request('put', $endpoint, true, $body);
    }

    /**
     * 创建分片上传
     * @param $remote
     * @return array|mixed
     * @throws ErrorException
     */
    public function createUploadSession($remote)
    {
        $requestPath = $this->getRequestPath($remote);
        $endpoint = "/me/drive/root{$requestPath}createUploadSession";
        $body = json_encode([
            'item' => [
                '@microsoft.request.conflictBehavior' => 'fail',
            ],
        ]);
        return $this->request('post', $endpoint, true, $body);
    }

    /**
     * 通过资源id创建分片上传
     * @param $sid
     * @return array|mixed
     * @throws ErrorException
     */
    public function createUploadSessionBySid($sid)
    {
        $endpoint = "/me/drive/items/{$sid}/createUploadSession";
        $body = json_encode([
            'item' => [
                '@microsoft.request.conflictBehavior' => 'fail',
            ],
        ]);
        return $this->request('post', $endpoint, true, $body);
    }

    /**
     * 上传分片
     * @param $url
     * @param $file
     * @param $offset
     * @param int $length
     * @return array|mixed
     * @throws ErrorException
     */
    public function uploadToSession(
        $url,
        $file,
        $offset,
        $length = 5242880
    ) {
        $file_size = $this->readFileSize($file);
        $content_length = (($offset + $length) > $file_size) ? ($file_size
            - $offset) : $length;
        $end = (($offset + $length) > $file_size) ? ($file_size - 1)
            : $offset + $content_length - 1;
        $content = $this->readFileContent($file, $offset, $length);
        $headers = [
            'Content-Length' => $content_length,
            'Content-Range' => "bytes {$offset}-{$end}/{$file_size}",
        ];
        $requestBody = $content;
        $response = $this->request(
            'put',
            [$url, $headers, 360],
            false,
            $requestBody
        );
        return $response;
    }

    /**
     * 获取分片上传状态
     * @param $url
     * @return array|mixed
     * @throws ErrorException
     */
    public function uploadSessionStatus($url)
    {
        return $this->request('get', $url, false);
    }

    /**
     * 删除分片上传session
     * @param $url
     * @return array|mixed
     * @throws ErrorException
     */
    public function deleteUploadSession($url)
    {
        return $this->request('delete', $url, false);
    }

    /**
     * 处理url
     *
     * @param $path
     *
     * @return string
     */
    public function encodeUrl($path): string
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
    public function getRequestPath($path, $isFile = false): string
    {
        $origin_path = $this->getAbsolutePath($path);
        $query_path = trim($origin_path, '/');
        $query_path = $this->encodeUrl(rawurldecode($query_path));
        $request_path = empty($query_path) ? '/' : ":/{$query_path}:/";
        if ($isFile) {
            return rtrim($request_path, ':/');
        }
        return $request_path;
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
    public function formatItem($response, $isList = true): array
    {
        if ($isList) {
            $items = [];
            foreach ($response as $item) {
                if (array_has($item, 'file')) {
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
        }
        $response['ext'] = strtolower(
            pathinfo(
                $response['name'],
                PATHINFO_EXTENSION
            )
        );
        return $response;
    }

    public function response($data, $errno = 0, $msg = 'ok'): array
    {
        return [
            'errno' => $errno,
            'message' => $msg,
            'data' => $data,
        ];
    }

    /**
     * Read File Size
     *
     * @param $path
     *
     * @return bool|int|string
     */
    public function readFileSize($path)
    {
        if (!file_exists($path)) {
            return false;
        }
        $size = filesize($path);
        if (!($file = fopen($path, 'rb'))) {
            return false;
        }
        if ($size >= 0) { //Check if it really is a small file (< 2 GB)
            if (fseek($file, 0, SEEK_END) === 0) { //It really is a small file
                fclose($file);
                return $size;
            }
        }
        //Quickly jump the first 2 GB with fseek. After that fseek is not working on 32 bit php (it uses int internally)
        $size = PHP_INT_MAX - 1;
        if (fseek($file, PHP_INT_MAX - 1) !== 0) {
            fclose($file);
            return false;
        }
        $length = 1024 * 1024;
        $read = '';
        while (!feof($file)) { //Read the file until end
            $read = fread($file, $length);
            $size = bcadd($size, $length);
        }
        $size = bcsub($size, $length);
        $size = bcadd($size, strlen($read));
        fclose($file);
        return $size;
    }

    /**
     * Read File Content
     * @param $file
     * @param $offset
     * @param $length
     * @return bool|string
     */
    public function readFileContent($file, $offset, $length)
    {
        $handler = fopen($file, 'rb') ?? die('Failed Get Content');
        fseek($handler, $offset);
        return fread($handler, $length);
    }

    /**
     * 防止实例被克隆（这会创建实例的副本）
     */
    private function __clone()
    {
    }
}
