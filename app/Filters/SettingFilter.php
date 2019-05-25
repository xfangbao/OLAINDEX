<?php

namespace App\Filters;

use EloquentFilter\ModelFilter;

class SettingFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    /**
     * @param $name
     */
    public function name($name)
    {
        $this->where(['name' => $name]);
    }
}
