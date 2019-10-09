<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\BaseController;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends BaseController
{
    /**
     * SettingController constructor.
     */
    public function __construct()
    {
        $this->middleware(['token.refresh', 'jwt.auth']);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $setting = Setting::query()->get()->toArray();
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
        $saved = Setting::query()->pluck('name')->all();
        $newData = collect($editData)->lazy()->filter(static function ($value) use ($saved) {
            return !in_array($value['name'], $saved, false);
        })->toArray();
        $editData = collect($editData)->lazy()->reject(static function ($value) use ($saved) {
            return !in_array($value['name'], $saved, false);
        })->toArray();
        if ($newData) {
            Setting::query()->insert($newData);
        }
        (new Setting)->updateBatch($editData);
        return $this->returnData($data);
    }
}
