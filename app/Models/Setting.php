<?php

namespace App\Models;

use App\Models\Traits\HelperModel;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HelperModel;

    /**
     * @var array
     */
    protected $fillable = ['name', 'value'];

    /**
     * @var array
     */
    protected $casts = [
        'id' => 'int',
    ];

    /**
     * 如果 value 是 json 则转成数组
     *
     * @param $value
     * @return mixed
     */
    public function getValueAttribute($value)
    {
        return is_json($value) ? json_decode($value, true) : $value;
    }

    /**
     * 如果 value 是数组 则转成 json
     *
     * @param $value
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = is_array($value) ? json_encode($value) : $value;
    }
}
