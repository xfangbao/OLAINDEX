<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\BaseController;
use App\Models\Setting;

class AppController extends BaseController
{
    /**
     * 获取基础应用配置
     * @return \Illuminate\Http\JsonResponse
     */
    public function config()
    {
        $setting = Setting::query()
            ->select(['name','value'])
            ->get()->toArray();
        $settingData = [];
        foreach ($setting as $detail) {
            $settingData[$detail['name']] = $detail['value'];
        }
        return $this->returnData($settingData);
    }

}
