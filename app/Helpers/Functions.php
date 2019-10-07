<?php
/**
 * This file is part of the wangningkai/OLAINDEX.
 * (c) wangningkai <i@ningkai.wang>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
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
