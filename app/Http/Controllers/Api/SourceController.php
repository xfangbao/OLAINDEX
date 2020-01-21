<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Service\Disk;
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
        $query = $request->get('query', '/');
        $limit = $request->get('limit', 10);
        $graphPath = trans_request_path($query);
        /*$originPath = rawurldecode(trim(trans_absolute_path($query), '/'));
        $pathArray = $originPath ? explode('/', $originPath) : [];*/
        $key = sprintf('one:list:%s', $graphPath);
        $list = \Cache::remember($key, setting('expires'), static function () use ($graphPath) {
            $resp = Disk::connect()->getItemListByPath($graphPath, '?select=id,eTag,name,size,lastModifiedDateTime,file,image,folder,'
                . 'parentReference,@microsoft.graph.downloadUrl&expand=thumbnails');
            if ($resp['errno'] === 0) {
                return $resp['data'];
            }
            return [];
        });
        $data = $this->paginate($list, $limit);
        return $this->success($data);
    }

    /**
     * 详情/预览
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(Request $request)
    {
        return $this->success();
    }

    /**
     * 下载
     * @param Request $request
     */
    public function download(Request $request)
    {
    }
}
