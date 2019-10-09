<?php


namespace App\Http\Middleware;

use App\Models\Authorization;
use Closure;
use Illuminate\Auth\Events\Login;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\RefreshToken;

class RefreshJWTToken extends RefreshToken
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        // 检查此次请求中是否带有 token，如果没有则抛出异常。
        $this->checkForToken($request);
        // 捕捉 token 过期所抛出 TokenExpiredException  异常
        try {
            // 检测用户的登录状态，如果正常则通过
            if ($this->auth->parseToken()->authenticate()) {
                return $next($request);
            }
            throw new UnauthorizedHttpException('jwt-auth', '未登录');
        } catch (TokenExpiredException $exception) {
            // 此处捕获到了 token 过期所抛出的 TokenExpiredException 异常，刷新该用户的 token 添加到响应头
            try {
                // 刷新用户 token
                $token = $this->auth->refresh();
                // 登录保证此次请求的成功
                \Auth::guard('api')->onceUsingId(
                    $this->auth->manager()
                        ->getPayloadFactory()
                        ->buildClaimsCollection()
                        ->toPlainArray()['sub']
                );
                $authorization = new Authorization($token);
                \Event::dispatch(new Login('api', $authorization->user(), false));
            } catch (JWTException $exception) {
                // 捕获 refresh 过期异常，无法刷新令牌，需要重新登录。
                throw new UnauthorizedHttpException(
                    'jwt-auth',
                    $exception->getMessage()
                );
            }
        }
        // 在响应头中返回新的 token
        return $this->setAuthenticationHeader($next($request), $token);
    }

}
