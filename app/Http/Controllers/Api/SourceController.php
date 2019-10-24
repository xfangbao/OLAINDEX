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
    /**
     * 列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        return $this->returnData([]);
    }

    /**
     * 详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(Request $request)
    {
        return $this->returnData([]);
    }

    /**
     * 下载
     * @param Request $request
     */
    public function download(Request $request)
    {
    }

}
