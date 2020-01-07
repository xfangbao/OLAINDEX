<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;

/**
 * 应用基础
 * Class AppController
 * @package App\Http\Controllers\Api
 */
class AppController extends BaseController
{
    /**
     * 获取基础应用配置
     * @return \Illuminate\Http\JsonResponse
     */
    public function config()
    {
        return $this->success(setting());
    }
}
