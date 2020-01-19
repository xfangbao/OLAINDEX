<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

/**
 * 资源接口
 * Class SourceController
 * @package App\Http\Controllers\Api
 */
class SourceController extends BaseController
{
    private $account;

    /**
     * 请求预处理
     * @throws \ErrorException
     */
    public function preVerify()
    {
    }

    /**
     * 列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $path = $request->get('path');
        return $this->success([]);
    }

    /**
     * 详情/预览
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(Request $request)
    {
        return $this->success([]);
    }

    /**
     * 下载
     * @param Request $request
     */
    public function download(Request $request)
    {
    }
}
