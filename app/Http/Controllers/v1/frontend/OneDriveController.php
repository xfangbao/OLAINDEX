<?php

namespace App\Http\Controllers\v1\frontend;

use App\Http\Controllers\BaseController;
use App\Models\Account;
use App\Service\Authorize;
use App\Service\OneDrive;
use App\Utils\AuthCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use ErrorException;
use Illuminate\Support\Facades\Log;

class OneDriveController extends BaseController
{
    /**
     * OneDriveController constructor.
     */
    public function __construct()
    {
        $this->middleware('jwt.auth')->except(['callback']);
        $this->middleware('token.refresh')->except(['callback']);
    }

    /**
     * 授权登录
     * @param Request $request
     * @param $account_type
     * @return mixed
     */
    public function authorizeLogin(Request $request, $account_type)
    {
        $redirect = $request->get('redirect', '');
        $account_id = $request->get('account_id', 0);
        $state_data = [
            'account_id' => $account_id,
            'account_type' => $account_type,
            'redirect' => $redirect
        ];
        $state_data = json_encode($state_data);

        $state = AuthCode::encrypt($state_data, config('app.key'), AuthCode::EXPIRE);
        $authorization_url = Authorize::getInstance($account_type)->getAuthorizeUrl($state);

        return $this->returnData([
            'authorization_url' => $authorization_url
        ]);
    }

    /**
     * @param Request $request
     * @return mixed|void
     * @throws ErrorException
     */
    public function callback(Request $request)
    {
        $code = $request->get('code');
        $state = $request->get('state');
        if (!$state || !$code) {
            return $this->response->errorBadRequest('非法请求');
        }
        if (!(strpos($state, '%2') === false)) {
            $state = urldecode($state);
        }
        $decrypt_str = AuthCode::decrypt($state, config('app.key'), AuthCode::EXPIRE);
        if (!$decrypt_str) {
            return $this->response->errorBadRequest('非法 state');
        }
        $data = json_decode($decrypt_str, true);
        $account_type = Arr::get($data, 'account_type');
        $account_id = Arr::get($data, 'account_id');
        $account = Account::query()->where(['id' => $account_id])->first();
        if ($account && $account->account_email) {
            return $this->response->errorBadRequest('已经绑定过帐号啦~');
        }
        $token = Authorize::getInstance($account_type)->getAccessToken($code);

        $token = $token->toArray();
        Log::info('access_token', $token);
        $access_token = Arr::get($token, 'access_token');
        $refresh_token = Arr::get($token, 'refresh_token');
        $expires = Arr::get($token, 'expires_in') !== 0 ? time() + Arr::get($token, 'expires_in') : 0;

        $data = [
            'id' => $account_id,
            'account_type' => $account_type,
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
            'access_token_expires' => date('Y-m-d H:i:s', $expires),
        ];

        if (!$account) {
            $account = Account::create($data);
            if (!$account) {
                return $this->response->errorInternal('关联失败，请稍后重试~');
            }
        } else {
            $saved = $account->update($data);
            if (!$saved) {
                return $this->response->errorInternal('关联失败，请稍后重试~');
            }
        }

        $response = OneDrive::getInstance($account)->getDriveInfo();
        $extend = $response['errno'] === 0 ? Arr::get($response, 'data') : '';
        $account->account_email = Arr::get($extend, 'owner.user.email');
        $account->extend = $extend;
        $account->save();
        return $this->returnData([
            'state' => 'success'
        ]);
    }

    /**
     * 刷新token
     * @param $account_id
     * @return mixed|void
     * @throws ErrorException
     */
    public function refresh($account_id)
    {
        $account = Account::query()->where(['id' => $account_id])->first();
        $existingRefreshToken = $account->refresh_token;
        $account_type = $account->account_type;
        $expires = strtotime($account->access_token_expires);
        $hasExpired = $expires - time() <= 0;
        if (!$hasExpired) {
            return $this->response->errorBadRequest('无需刷新令牌~');
        }
        $token = Authorize::getInstance($account_type)->refreshAccessToken($existingRefreshToken);
        $token = $token->toArray();
        $access_token = Arr::get($token, 'access_token');
        $refresh_token = Arr::get($token, 'refresh_token');
        $expires = Arr::get($token, 'expires_in') !== 0 ? time() + Arr::get($token, 'expires_in') : 0;
        $data = [
            'id' => $account_id,
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
            'access_token_expires' => date('Y-m-d H:i:s', $expires),
        ];
        Log::info('refresh_token', $data);
        $saved = $account->update($data);
        if (!$saved) {
            return $this->response->errorInternal('刷新失败，请稍后重试~');
        }
        $response = OneDrive::getInstance($account)->getDriveInfo();
        $extend = $response['errno'] === 0 ? Arr::get($response, 'data') : '';
        $account->account_email = Arr::get($extend, 'owner.user.email');
        $account->extend = $extend;
        $account->save();
        return $this->returnData([
            'state' => 'success'
        ]);
    }

    /**
     * 解绑账号
     * @param Request $request
     * @return mixed|void
     */
    public function unbind(Request $request)
    {
        $account_id = $request->get('account_id', 0);
        $account = Account::query()->where(['id' => $account_id])->first();
        $data = [
            'id' => $account_id,
            'access_token' => '',
            'refresh_token' => '',
            'access_token_expires' => Carbon::now(),
            'account_type' => '',
            'account_email' => '',
        ];
        $account = $account->update($data);
        if (!$account) {
            return $this->response->errorInternal('解绑失败，请稍后重试~');
        }
        return $this->returnData([
            'state' => 'success'
        ]);
    }

}
