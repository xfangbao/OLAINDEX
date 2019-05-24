<?php


namespace App\Http\Controllers\v1\backend;


use App\Http\Controllers\BaseController;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends BaseController
{
    /**
     * SettingController constructor.
     * @param Setting $modelClass
     */
    public function __construct(Setting $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $setting = $this->modelClass->filter($request->all())->get()->toArray();
        $settingData = [];
        foreach ($setting as $detail) {
            $settingData[$detail['name']] = $detail['value'];
        }
        return $this->returnData($settingData);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        $data = $request->all();
        $editData = [];
        foreach ($data as $k => $v) {
            $editData[] = [
                'name' => $k,
                'value' => is_array($v) ? json_encode($v) : $v
            ];
        }
        // 查询数据库中是否有配置
        $saved = $this->modelClass::query()->pluck('name')->all();
        $newData = collect($editData)->filter(function ($value) use ($saved) {
            return !in_array($value['name'], $saved, false);
        })->toArray();
        $editData = collect($editData)->reject(function ($value) use ($saved) {
            return !in_array($value['name'], $saved, false);
        })->toArray();
        if ($newData) {
            $this->modelClass::query()->insert($newData);
        }
        $this->modelClass->updateBatch($editData);
        return $this->returnData($data);
    }

}
