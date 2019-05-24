<?php


namespace App\Http\Middleware;


use App\Http\Controllers\v1\frontend\OneDriveController;
use App\models\Account;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RefreshOneDriveToken
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws \ErrorException
     */
    public function handle(Request $request, Closure $next)
    {
        $account_id = $request->get('account_id');

        $account = Account::find($account_id);
        if (!$account) {
            throw new BadRequestHttpException('未绑定账号');
        }
        $expires = $account->access_token_expires;
        $expired_at = Carbon::parse($expires);
        $now = Carbon::now();
        $hasExpired = $expired_at->lt($now);
        if ($hasExpired) {
            $oauth = new OneDriveController();
            $oauth->refresh($account_id);
        }

        return $next($request);
    }
}
