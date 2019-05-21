<?php


namespace App\Service;


use App\Entities\ClientConfigEntity;
use App\Utils\CoreConstants;
use Curl\Curl;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class Authorize
{
    /**
     * @var $instance
     */
    private static $instance;


    private $account_type;

    /**
     * @param $account_type
     * @return Authorize
     */
    public static function getInstance($account_type)
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self($account_type);
        }
        return self::$instance;
    }

    /**
     * Authorize constructor.
     * @param $account_type
     */
    private function __construct($account_type)
    {
        $this->account_type = $account_type;
    }

    /**
     *  OneDrive 授权请求
     *
     * @param $form_params
     * @return \Illuminate\Support\Collection
     * @throws \ErrorException
     */
    private function request($form_params)
    {
        $client_config = new ClientConfigEntity(CoreConstants::getClientConfig($this->account_type));
        $form_params = array_merge([
            'client_id' => $client_config->client_id,
            'client_secret' => $client_config->client_secret,
            'redirect_uri' => $client_config->redirect_uri,

        ], $form_params);
        if ($this->account_type === CoreConstants::ACCOUNT_CN) {
            $form_params = Arr::add(
                $form_params,
                'resource',
                $client_config->graph_endpoint
            );
        }
        $curl = new Curl();
        $curl->setHeader('Content-Type', 'application/x-www-form-urlencoded');
        $curl->post($client_config->authorize_url . $client_config->token_endpoint, $form_params);
        if ($curl->error) {
            $error = [
                'errno' => $curl->errorCode,
                'message' => $curl->errorMessage,
            ];
            Log::error('OneDrive Authorize Request Error.', $error);
            $message = $curl->errorCode . ': ' . $curl->errorMessage . "\n";
            throw new \ErrorException($message);

        }
        $token = collect($curl->response);
        return $token;

    }

    /**
     * 获取授权登录地址
     *
     * @param $state
     * @return string
     */
    public function getAuthorizeUrl($state = '')
    {
        $client_config = new ClientConfigEntity(CoreConstants::getClientConfig($this->account_type));

        $values = [
            'client_id' => $client_config->client_id,
            'redirect_uri' => $client_config->redirect_uri,
            'scope' => $client_config->scopes,
            'response_type' => 'code',
        ];
        if ($state) {
            $values = Arr::add($values, 'state', $state);
        }
        $query = http_build_query($values, '', '&', PHP_QUERY_RFC3986);
        $authorization_url = $client_config->authorize_url . $client_config->authorize_endpoint . "?{$query}";
        return $authorization_url;
    }

    /**
     * 请求获取access_token
     * @param $code
     * @return \Illuminate\Support\Collection
     * @throws \ErrorException
     */
    public function getAccessToken($code)
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
     * @return \Illuminate\Support\Collection
     * @throws \ErrorException
     */
    public function refreshAccessToken($existingRefreshToken)
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

    /**
     * 防止反序列化（这将创建它的副本）
     */
    private function __wakeup()
    {
    }

}
