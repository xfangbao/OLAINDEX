<?php


namespace App\Http\Controllers\v1\frontend;


use App\Http\Controllers\BaseController;
use App\Models\Authorization;
use App\Transformers\AuthorizationTransformer;
use App\Transformers\UserTransformer;
use App\Utils\ResponseSerializer;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Exception;

class AuthController extends BaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login', 'refresh']]);
        $this->middleware('token.refresh', ['except' => ['login', 'refresh', 'logout']]);
    }

    /**
     * 登录
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response|mixed
     * @throws Exception
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return $this->response->errorBadRequest(trans('auth.failed'));
        }

        $authorization = new Authorization($token);
        Event::dispatch(new Login('api', $authorization->user(), false));

        return $this->response->item($authorization, new AuthorizationTransformer(), function ($resource, $fractal) {
            $fractal->setSerializer(new ResponseSerializer());
        })->setStatusCode(201);
    }


    /**
     * 登出
     * @return mixed
     */
    public function logout()
    {
        Auth::guard('api')->logout();

        return $this->returnData(['state' => 'success']);
    }

    /**
     * 获取用户信息
     * Get User Profile
     *
     * @return \Dingo\Api\Http\Response|mixed
     */
    public function user()
    {
        $user = Auth::guard('api')->user();

        return $this->response()->item($user, new UserTransformer(), function ($resource, $fractal) {
            /* @var $fractal */
            $fractal->setSerializer(new ResponseSerializer());
        });
    }


    /**
     *  刷新令牌
     * @return \Dingo\Api\Http\Response
     */
    public function refresh()
    {
        $authorization = new Authorization(Auth::refresh());
        return $this->response->item($authorization, new AuthorizationTransformer(), function ($resource, $fractal) {
            $fractal->setSerializer(new ResponseSerializer());
        });
    }
}
