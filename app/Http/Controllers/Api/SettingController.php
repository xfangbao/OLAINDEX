<?php


namespace App\Http\Controllers\Api;

use App\Events\SettingEvent;
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
        $setting = Setting::query()->get();
        $settingData = [];
        foreach ($setting->toArray() as $detail) {
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
        $originData = [];
        foreach ($data as $k => $v) {
            $originData[] = [
                'name' => $k,
                'value' => is_array($v) ? json_encode($v) : $v
            ];
        }
        // 查询数据库中是否有配置
        $saved = Setting::query()->pluck('name')->all();
        $newData = collect($originData)->lazy()->filter(static function ($value) use ($saved) {
            return !in_array($value['name'], $saved, false);
        })->toArray();
        $editData = collect($originData)->lazy()->reject(static function ($value) use ($saved) {
            return !in_array($value['name'], $saved, false);
        })->toArray();
        if ($newData) {
            Setting::query()->insert($newData);
        }
        (new Setting)->updateBatch($editData);

        event(new SettingEvent($originData));

        return $this->returnData($data);
    }
}
