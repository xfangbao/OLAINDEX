<?php

namespace App\Utils;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class Tool
{
    /**
     * Transfer File Size
     *
     * @param string $size origin
     *
     * @return string
     */
    public static function convertSize($size)
    {
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) {
            $size /= 1024;
        }
        return @round($size, 2) . $units[$i];
    }


    /**
     * markdownToHtml
     *
     * @param string $markdown
     * @param bool $line
     *
     * @return string
     */
    public static function markdownToHtml(string $markdown, $line = false)
    {
        $parser = new \Parsedown();
        if (!$line) {
            $html = $parser->text($markdown);
        } else {
            $html = $parser->line($markdown);
        }

        return $html;
    }


    /**
     * 数组分页
     *
     * @param $items
     * @param $perPage
     * @return  \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function paginate($items, $perPage)
    {
        $pageStart = request()->get('page', 1);
        // Start displaying items from this number;
        $offSet = ($pageStart * $perPage) - $perPage;
        // Get only the items you need using array_slice
        $itemsForCurrentPage = array_slice($items, $offSet, $perPage, true);
        $paginator = new LengthAwarePaginator(
            $itemsForCurrentPage,
            count($items),
            $perPage,
            Paginator::resolveCurrentPage(),
            ['path' => Paginator::resolveCurrentPath()]
        );
        $paginator->appends(['limit' => request()->get('limit'), 'sort' => request()->get('sort')]);
        return $paginator;
    }

    /**
     * 解析路径
     *
     * @param $path
     *
     * @return string
     */
    public static function encodeUrl($path)
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
     * 处理请求路径
     *
     * @param $path
     * @param bool $query
     * @param bool $isFile
     * @return string
     */
    public static function getRequestPath($path, $query = true, $isFile = false)
    {
        $originPath = self::getAbsolutePath($path);
        $queryPath = trim($originPath, '/');
        $queryPath = self::encodeUrl(rawurldecode($queryPath));
        if (!$query) {
            return $queryPath;
        }
        $requestPath = empty($queryPath) ? '/' : ":/{$queryPath}:/";
        if ($isFile) {
            return rtrim($requestPath, ':/');
        }

        return $requestPath;
    }

    /**
     * 获取绝对路径
     *
     * @param $path
     *
     * @return mixed
     */
    public static function getAbsolutePath($path)
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
     * 分块读取文件内容
     *
     * @param $file
     * @param $offset
     * @param $length
     *
     * @return bool|string
     */
    public static function readFileContent($file, $offset, $length)
    {
        $handler = fopen($file, 'rb') ?? die('Failed Get Content');
        fseek($handler, $offset);

        return fread($handler, $length);
    }

    /**
     * 读取文件大小
     *
     * @param $path
     *
     * @return bool|int|string
     */
    public static function readFileSize($path)
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
     * 时间可读化转换
     *
     * @param $sTime
     * @param int $format
     * @return false|string
     */
    public static function transTimeFriendly($sTime, $format = 0)
    {
        //sTime=源时间，cTime=当前时间，dTime=时间差
        $cTime = time();
        $dTime = $cTime - $sTime;
        // 计算两个时间之间的日期差
        $date1 = date_create(date('Y-m-d', $cTime));
        $date2 = date_create(date('Y-m-d', $sTime));
        $diff = date_diff($date1, $date2);
        $dDay = $diff->days;

        if ($dTime == 0) {
            return '1秒前';
        } elseif ($dTime < 60 && $dTime > 0) {
            return $dTime . '秒前';
        } elseif ($dTime < 3600 && $dTime > 0) {
            return (int)($dTime / 60) . '分钟前';
        } elseif ($dTime >= 3600 && $dDay == 0) {
            return (int)($dTime / 3600) . '小时前';
        } elseif ($dDay == 1) {
            return date('昨天 H:i', $sTime);
        } elseif ($dDay == 2) {
            return date('前天 H:i', $sTime);
        } elseif ($format == 1) {
            return date('m-d H:i', $sTime);
        } else {
            // 不是今年
            if (date('Y', $cTime) != date('Y', $sTime)) {
                return date('Y-n-j', $sTime);
            }
            return date('n-j', $sTime);
        }
    }

}
