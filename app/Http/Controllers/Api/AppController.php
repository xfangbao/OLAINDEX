<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Setting;

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
        $setting = Setting::query()
            ->select(['name', 'value'])
            ->get();
        $settingData = [];
        foreach ($setting->toArray() as $detail) {
            $settingData[$detail['name']] = $detail['value'];
        }
        return $this->success($settingData);
    }
}
