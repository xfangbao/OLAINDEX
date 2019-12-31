<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Account;
use App\Service\AuthorizeService;
use Illuminate\Http\Request;

/**
 * 账号授权
 * Class AccountController
 * @package App\Http\Controllers\Api
 */
class AccountController extends BaseController
{
    /**
     * SettingController constructor.
     */
    public function __construct()
    {
        $this->middleware('token.refresh', ['except' => ['callback']]);
        $this->middleware('jwt.auth', ['except' => ['callback']]);
    }

    /**
     * 跳转申请
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apply(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'redirect_uri' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }
        $redirect_uri = $request->get('redirect_uri');
        $ru = 'https://developer.microsoft.com/en-us/graph/quick-start?appID=_appId_&appName=_appName_&redirectUrl='
            . $redirect_uri . '&platform=option-php';
        $deepLink = '/quickstart/graphIO?publicClientSupport=false&appName=OLAINDEX&redirectUrl='
            . $redirect_uri . '&allowImplicitFlow=false&ru='
            . urlencode($ru);
        $redirect = 'https://apps.dev.microsoft.com/?deepLink=' . urlencode($deepLink);
        return $this->returnData([
            'redirect' => $redirect
        ]);
    }

    /**
     * 跳转绑定
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bind(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'account_type' => 'required',
            'client_id' => 'required',
            'client_secret' => 'required',
            'redirect_uri' => 'required',
            'redirect' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }
        $data = [
            'account_type' => $request->get('account_type'),
            'client_id' => $request->get('client_id'),
            'client_secret' => $request->get('client_secret'),
            'redirect_uri' => $request->get('redirect_uri')
        ];

        $account = Account::query()->create($data);
        if (!$account) {
            return $this->returnError([], 500, '绑定账号失败，请稍后重试');
        }
        setting_set('account_id', $account->id);
        $slug = str_random();
        $accountCache = $account->toArray();
        $accountCache = array_merge($accountCache, ['redirect' => $request->get('redirect')]);
        \Cache::add($slug, $accountCache, 15 * 60); //15分钟内需完成绑定否则失效
        $authorizeUrl = AuthorizeService::init()->bind($accountCache)->getAuthorizeUrl($slug);

        return $this->returnData([
            'redirect' => $authorizeUrl
        ]);
    }

    /**
     * 解绑
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function unbind(Request $request)
    {
        $account_id = $request->get('account_id', 0);
        setting_set('account_id', 0);
        $account = Account::query()->find($account_id);
        if (!$account->delete()) {
            return $this->returnError([], 500, '解绑账号失败，请稍后重试');
        }

        return $this->returnData([]);
    }

    /**
     * 回调
     * @param Request $request
     * @return mixed
     * @throws \ErrorException
     */
    public function callback(Request $request)
    {
        $state = $request->get('state', '');
        $code = $request->get('code', '');

        if (!$state || !\Cache::has($state)) {
            \Cache::forget($state);
            return $this->returnError([], 400, 'Invalid state');
        }
        $accountCache = \Cache::get($state);
        $token = AuthorizeService::init()->bind($accountCache)->getAccessToken($code);
        $token = $token->toArray();
        \Log::info('access_token', $token);
        $access_token = array_get($token, 'access_token');
        $refresh_token = array_get($token, 'refresh_token');
        $expires = array_get($token, 'expires_in') !== 0 ? time() + array_get($token, 'expires_in') : 0;
        $account_id = $state['id'] ?? 0;
        $data = [
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
            'access_token_expires' => date('Y-m-d H:i:s', $expires),
        ];
        $account = Account::query()->find($account_id);
        $account->update($data);
        // todo:加入后台刷新
        refresh_account($account);
        $redirect = array_get($accountCache, 'redirect', '/');
        return redirect()->away($redirect);
    }

    /**
     * 账户详情
     * @param Request $request
     * @return mixed
     */
    public function info(Request $request)
    {

    }
}
