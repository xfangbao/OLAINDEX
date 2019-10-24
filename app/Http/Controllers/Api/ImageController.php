<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

/**
 * 图床接口
 * Class ImageController
 * @package App\Http\Controllers\Api
 */
class ImageController extends BaseController
{

    public function upload(Request $request)
    {
        $field = 'olaindex_img';
        if (!$request->hasFile($field)) {
            return $this->returnError([], 400, '上传文件为空');
        }
        $file = $request->file($field);
        $rule = [$field => 'required|max:4096|image'];
        $validator = \Validator::make(
            request()->all(),
            $rule
        );

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }
        //todo:上传
    }
}
