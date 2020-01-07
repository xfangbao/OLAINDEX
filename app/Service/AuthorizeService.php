<?php
/**
 * This file is part of the wangningkai/OLAINDEX.
 * (c) wangningkai <i@ningkai.wang>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Service;

use Curl\Curl;
use App\Models\Client;
use App\Service\Core\Constants;
use Illuminate\Support\Collection;
use ErrorException;
use Log;

class AuthorizeService
{
    /**
     * @var AuthorizeService
     */
    private static $instance;

    /**
     * @var $account
     */
    private $account;

    /**
     * @return AuthorizeService
     */
    public static function init(): AuthorizeService
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
     * @param $account
     * @return $this
     */
    public function bind($account): AuthorizeService
    {
        $this->account = $account;
        return $this;
    }

    /**
     * OneDrive 授权请求
     * @param $form_params
     * @return Collection
     * @throws ErrorException
     */
    private function request($form_params): Collection
    {
        $client = new Client(client_config($this->account));
        $form_params = array_merge([
            'client_id' => $client->client_id,
            'client_secret' => $client->client_secret,
            'redirect_uri' => $client->redirect_uri,
        ], $form_params);
        if (array_get($this->account, 'account_type', 0) === Constants::ACCOUNT_CN) {
            $form_params = array_add(
                $form_params,
                'resource',
                $client->graph_endpoint
            );
        }
        $curl = new Curl();
        $curl->setHeader('Content-Type', 'application/x-www-form-urlencoded');
        $curl->setRetry(Constants::DEFAULT_RETRY);
        $curl->setConnectTimeout(Constants::DEFAULT_CONNECT_TIMEOUT);
        $curl->setTimeout(Constants::DEFAULT_TIMEOUT);
        $curl->post($client->authorize_url . $client->token_endpoint, $form_params);
        if ($curl->error) {
            $error = [
                'errno' => $curl->errorCode,
                'message' => $curl->errorMessage,
            ];
            Log::error('OneDrive Authorize Request Error.', $error);
            $message = $curl->errorCode . ': ' . $curl->errorMessage . "\n";
            throw new ErrorException($message);
        }
        return collect($curl->response);
    }

    /**
     * 获取授权登录地址
     *
     * @param $state
     * @return string
     */
    public function getAuthorizeUrl($state = ''): string
    {
        $client = new Client(client_config($this->account));
        $values = [
            'client_id' => $client->client_id,
            'redirect_uri' => $client->redirect_uri,
            'scope' => $client->scopes,
            'response_type' => 'code',
        ];
        if ($state) {
            $values = array_add($values, 'state', $state);
        }
        $query = http_build_query($values, '', '&', PHP_QUERY_RFC3986);
        $authorization_url = $client->authorize_url . $client->authorize_endpoint . "?{$query}";
        return $authorization_url;
    }

    /**
     * 请求获取access_token
     * @param $code
     * @return Collection
     * @throws ErrorException
     */
    public function getAccessToken($code): Collection
    {
        $form_params = [
            'code' => $code,
            'grant_type' => 'authorization_code',
        ];
        return $this->request($form_params);
    }

    /**
     * 请求刷新access_token
     * @param $existingRefreshToken
     * @return Collection
     * @throws ErrorException
     */
    public function refreshAccessToken($existingRefreshToken): Collection
    {
        $form_params = [
            'refresh_token' => $existingRefreshToken,
            'grant_type' => 'refresh_token',
        ];
        return $this->request($form_params);
    }

    /**
     * 防止实例被克隆（这会创建实例的副本）
     */
    private function __clone()
    {
    }
}
