<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Authorization;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

/**
 * 登录授权
 * Class AuthController
 * @package App\Http\Controllers\Api
 */
class AuthController extends BaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('token.refresh', ['except' => ['login', 'refresh', 'logout']]);
        $this->middleware('jwt.auth', ['except' => ['login', 'refresh']]);
    }

    /**
     * 登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|exists:users',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }
        $credentials = $request->only('name', 'password');
        if (!$token = \Auth::guard('api')->attempt($credentials)) {
            return $this->returnData([], 400, trans('auth.failed'));
        }
        $authorization = new Authorization($token);
        \Event::dispatch(new Login('api', $authorization->user(), false));

        $token = $authorization->toArray();

        return $this->returnData($token);
    }

    /**
     * 登出
     * @return mixed
     */
    public function logout()
    {
        \Auth::guard('api')->logout();

        return $this->returnData(['success']);
    }

    /**
     * 获取用户信息
     * Get User Profile
     */
    public function user()
    {
        /* @var $user User */
        $user = \Auth::guard('api')->user();

        return $this->returnData($user->toArray());
    }

    /**
     * 刷新令牌
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function refresh()
    {
        $authorization = new Authorization(\Auth::refresh());
        $token = $authorization->toArray();

        return $this->returnData($token);
    }
}
