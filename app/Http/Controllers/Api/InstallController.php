<?php
/**
 * This file is part of the wangningkai/OLAINDEX.
 * (c) wangningkai <i@ningkai.wang>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\BaseController;

/**
 * 安装流程
 * Class InstallController
 * @package App\Http\Controllers\Api
 * @todo:自定义安装
 */
class InstallController extends BaseController
{
    public function checkInstall()
    {
        $canWritable = is_writable(storage_path());
        if (!$canWritable) {
            $this->fail('请确保 [storage] 目录有读写权限');
        }
        $lockFile = install_path('install.lock');
        if (file_exists($lockFile)) {
            $this->fail('已安装完毕');
        }
        return $this->success();

    }

}
