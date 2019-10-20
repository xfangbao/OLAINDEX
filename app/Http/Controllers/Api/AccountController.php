<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Service\AuthorizeService;
use Illuminate\Http\Request;

class AccountController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bind(Request $request)
    {
        $account = [
            'account_type' => $request->get('account_type'),
            'client_id' => $request->get('client_id'),
            'client_secret' => $request->get('client_secret'),
            'redirect_uri' => $request->get('redirect_uri')
        ];
        $authorizeUrl = AuthorizeService::init()->bind($account)->getAuthorizeUrl();

        return $this->returnData([
            'redirect_url' => $authorizeUrl
        ]);

    }

    public function callback()
    {
    }

}
