<?php
/**
 * This file is part of the wangningkai/OLAINDEX.
 * (c) wangningkai <i@ningkai.wang>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use App\Service\Core\Constants;

if (!function_exists('is_json')) {
    /**
     * 判断字符串是否是json
     *
     * @param $json
     * @return bool
     */
    function is_json($json)
    {
        json_decode($json, true);
        return (json_last_error() === JSON_ERROR_NONE);
    }
}
if (!function_exists('url_encode')) {
    /**
     * 解析路径
     *
     * @param $path
     *
     * @return string
     */
    function url_encode($path): string
    {
        $url = [];
        foreach (explode('/', $path) as $key => $value) {
            if (empty(!$value)) {
                $url[] = rawurlencode($value);
            }
        }
        return @implode('/', $url);
    }
}
if (!function_exists('trans_request_path')) {
    /**
     * 处理请求路径
     *
     * @param $path
     * @param bool $query
     * @param bool $isFile
     * @return string
     */
    function trans_request_path($path, $query = true, $isFile = false): string
    {
        $originPath = trans_absolute_path($path);
        $queryPath = trim($originPath, '/');
        $queryPath = url_encode(rawurldecode($queryPath));
        if (!$query) {
            return $queryPath;
        }
        $requestPath = empty($queryPath) ? '/' : ":/{$queryPath}:/";
        if ($isFile) {
            return rtrim($requestPath, ':/');
        }
        return $requestPath;
    }
}
if (!function_exists('trans_absolute_path')) {
    /**
     * 获取绝对路径
     *
     * @param $path
     *
     * @return mixed
     */
    function trans_absolute_path($path)
    {
        $path = str_replace(['/', '\\', '//'], '/', $path);
        $parts = array_filter(explode('/', $path), 'strlen');
        $absolutes = [];
        foreach ($parts as $part) {
            if ('.' === $part) {
                continue;
            }
            if ('..' === $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }
        return str_replace('//', '/', '/' . implode('/', $absolutes) . '/');
    }
}
if (!function_exists('flash_message')) {
    /**
     * 操作成功或者失败的提示
     *
     * @param string $message
     * @param bool $success
     */
    function flash_message($message = '成功', $success = true): void
    {
        $alertType = $success ? 'success' : 'danger';
        \Session::put('alertMessage', $message);
        \Session::put('alertType', $alertType);
    }
}
if (!function_exists('setting')) {
    /**
     * 获取设置
     * @param string $key
     * @param string $default
     * @return mixed
     */
    function setting($key = '', $default = '')
    {
        $setting = \Cache::remember('settings', 60 * 60 * 2, static function () {
            try {
                $setting = \App\Models\Setting::all();
            } catch (Exception $e) {
                return [];
            }
            $settingData = [];
            foreach ($setting as $detail) {
                $settingData = array_add($settingData, $detail->name, $detail->value);
            }
            return $settingData;
        });
        $setting = collect($setting)->all();
        return $key ? array_get($setting, $key, $default) : $setting;
    }
}
if (!function_exists('setting_set')) {
    /**
     * 更新设置
     * @param mixed $key
     * @param mixed $value
     * @return mixed
     */
    function setting_set($key = '', $value = '')
    {
        if (!is_array($key)) {
            $value = is_array($value) ? json_encode($value) : $value;
            \App\Models\Setting::query()->updateOrCreate(['name' => $key], ['value' => $value]);
        } else {
            foreach ($key as $k => $v) {
                $v = is_array($v) ? json_encode($v) : $v;
                \App\Models\Setting::query()->updateOrCreate(['name' => $k], ['value' => $v]);
            }
        }

        return refresh_setting();
    }
}
if (!function_exists('refresh_setting')) {
    /**
     * 刷新设置缓存
     * @return array
     */
    function refresh_setting()
    {
        $settingData = [];
        try {
            $settingModel = \App\Models\Setting::all();
        } catch (Exception $e) {
            $settingModel = [];
        }
        foreach ($settingModel->toArray() as $detail) {
            $settingData[$detail['name']] = $detail['value'];
        }

        \Cache::forever('settings', $settingData);

        return collect($settingData)->toArray();
    }
}
if (!function_exists('install_path')) {
    /**
     * 安装路径
     * @param string $path
     * @return string
     */
    function install_path($path = '')
    {
        return storage_path('install' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
    }
}
if (!function_exists('refresh_token')) {
    /**
     * 刷新账户token
     * @param bool $force
     * @return bool
     * @throws \ErrorException
     */
    function refresh_token($force = false)
    {
        if (!$force) {
            $expires = strtotime(setting('account.access_token_expires'));
            $hasExpired = $expires - time() <= 30 * 10; // 半小时刷新token
            if (!$hasExpired) {
                return false;
            }
        }
        $token = \App\Service\Disk::authorize()->refreshAccessToken(setting('account.refresh_token'));
        $token = $token->toArray();
        $access_token = array_get($token, 'access_token');
        $refresh_token = array_get($token, 'refresh_token');
        $expires = array_has($token, 'expires_in') ? time() + array_get($token, 'expires_in') : 0;
        $access_token_expires = date('Y-m-d H:i:s', $expires);
        $account = array_merge(setting('account'), [
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
            'access_token_expires' => $access_token_expires,
        ]);
        setting_set('account', $account);
        setting_set('refresh_at', \Carbon\Carbon::now());
        return true;
    }
}
if (!function_exists('refresh_account')) {
    /**
     * 刷新账户信息
     * @return bool
     * @throws ErrorException
     */
    function refresh_account()
    {
        refresh_token();
        $response = \App\Service\Disk::connect()->getDriveInfo();
        if ($response['errno'] === 0) {
            $extend = array_get($response, 'data');
            $account = array_merge(setting('account'), [
                'account_email' => array_get($extend, 'owner.user.email', ''),
                'extend' => $extend,
            ]);
        } else {
            $response = \App\Service\Disk::connect()->getAccountInfo();
            $extend = array_get($response, 'data');
            $account = array_merge(setting('account'), [
                'account_email' => $response['errno'] === 0 ? array_get($extend, 'userPrincipalName') : '',
                'extend' => $extend,
            ]);
        }
        setting_set('account', $account);
        return true;
    }
}
if (!function_exists('client_config')) {
    /**
     * client配置
     * @param array $params
     * @return mixed
     */
    function client_config($params)
    {
        $config = [
            Constants::ACCOUNT_COM => [
                'client_id' => array_get($params, 'client_id'),
                'client_secret' => array_get($params, 'client_secret'),
                'redirect_uri' => array_get($params, 'redirect_uri'),
                'authorize_url' => Constants::AUTHORITY_URL,
                'authorize_endpoint' => Constants::AUTHORIZE_ENDPOINT,
                'token_endpoint' => Constants::TOKEN_ENDPOINT,
                'graph_endpoint' => Constants::REST_ENDPOINT,
                'api_version' => Constants::API_VERSION,
                'scopes' => Constants::SCOPES
            ],
            Constants::ACCOUNT_CN => [
                'client_id' => array_get($params, 'client_id'),
                'client_secret' => array_get($params, 'client_secret'),
                'redirect_uri' => array_get($params, 'redirect_uri'),
                'authorize_url' => Constants::AUTHORITY_URL_21V,
                'authorize_endpoint' => Constants::AUTHORIZE_ENDPOINT_21V,
                'token_endpoint' => Constants::TOKEN_ENDPOINT_21V,
                'graph_endpoint' => Constants::REST_ENDPOINT_21V,
                'api_version' => Constants::API_VERSION,
                'scopes' => Constants::SCOPES
            ]
        ];
        return $config[array_get($params, 'account_type', 1)];

    }
}
