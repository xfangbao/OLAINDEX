<?php
/**
 * This file is part of the wangningkai/OLAINDEX.
 * (c) wangningkai <i@ningkai.wang>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Models;

use App\Service\Core\Constants;

class Client
{
    /**
     * @var string
     */
    public $client_id;

    /**
     * @var string
     */
    public $client_secret;

    /**
     * @var string
     */
    public $redirect_uri;

    /**
     * @var string
     */
    public $authorize_url;

    /**
     * @var string
     */
    public $authorize_endpoint;

    /**
     * @var string
     */
    public $token_endpoint;

    /**
     * @var string
     */
    public $graph_endpoint;

    /**
     * @var string
     */
    public $api_version;

    /**
     * @var string
     */
    public $scopes;

    /**
     * Client constructor.
     * @param array $array
     */
    public function __construct($array = [])
    {
        foreach ($array as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }

    /**
     * 获取客户端配置
     * @param $account
     * @return mixed
     */
    public static function setClientConfig($account)
    {
        $config = [
            Constants::ACCOUNT_COM => [
                'client_id' => array_get($account, 'client_id'),
                'client_secret' => array_get($account, 'client_secret'),
                'redirect_uri' => array_get($account, 'redirect_uri'),
                'authorize_url' => Constants::AUTHORITY_URL,
                'authorize_endpoint' => Constants::AUTHORIZE_ENDPOINT,
                'token_endpoint' => Constants::TOKEN_ENDPOINT,
                'graph_endpoint' => Constants::REST_ENDPOINT,
                'api_version' => Constants::API_VERSION,
                'scopes' => Constants::SCOPES
            ],
            Constants::ACCOUNT_CN => [
                'client_id' => array_get($account, 'client_id'),
                'client_secret' => array_get($account, 'client_secret'),
                'redirect_uri' => array_get($account, 'redirect_uri'),
                'authorize_url' => Constants::AUTHORITY_URL_21V,
                'authorize_endpoint' => Constants::AUTHORIZE_ENDPOINT_21V,
                'token_endpoint' => Constants::TOKEN_ENDPOINT_21V,
                'graph_endpoint' => Constants::REST_ENDPOINT_21V,
                'api_version' => Constants::API_VERSION,
                'scopes' => Constants::SCOPES
            ]
        ];
        return $config[array_get($account, 'account_type', 1)];
    }
}
