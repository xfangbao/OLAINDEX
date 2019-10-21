<?php

namespace App\Models;

use App\Models\Traits\HelperModel;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Account
 *
 * @property int $id
 * @property int $account_type
 * @property string $account_email
 * @property string $client_id
 * @property string $client_secret
 * @property string $redirect_uri
 * @property string|null $access_token
 * @property string|null $refresh_token
 * @property string|null $access_token_expires
 * @property int $status
 * @property mixed $extend
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereAccessTokenExpires($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereAccountEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereAccountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereClientSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereMap($map)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereRedirectUri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereExtend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Account extends Model
{
    use  HelperModel;

    public const STATUS_ON = 1;
    public const STATUS_OFF = 0;

    protected $fillable = [
        'account_type',
        'client_id',
        'client_secret',
        'redirect_uri',
        'account_email',
        'access_token',
        'refresh_token',
        'access_token_expires',
        'status',
        'extend'
    ];

    /**
     * 如果 ext 是 json 则转成数组
     *
     * @param $value
     * @return mixed
     */
    public function getExtendAttribute($value)
    {
        return is_json($value) ? json_decode($value, true) : $value;
    }

    /**
     * 如果 ext 是数组 则转成 json
     *
     * @param $value
     */
    public function setExtendAttribute($value)
    {
        $this->attributes['extend'] = is_array($value) ? json_encode($value) : $value;
    }
}
