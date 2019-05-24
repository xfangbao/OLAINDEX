<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


$api = app(Dingo\Api\Routing\Router::class);
$api->version(
    ['v1'],
    function ($api) {
        /* @var $api Dingo\Api\Routing\Router */

        $api->group(
            [
                'namespace' => 'App\Http\Controllers\v1\frontend',
                /*'middleware' => 'api.throttle',
                'limit' => 200, 'expires' => 1*/
            ],
            // 前台
            /* @var $api Dingo\Api\Routing\Router */
            function ($api) {
                // 登陆
                $api->post('auth/login', 'AuthController@login');
                $api->post('auth/logout', 'AuthController@logout');
                $api->post('auth/refresh', 'AuthController@refresh');
                $api->get('auth/user', 'AuthController@user');

                // 授权
                $api->get('onedrive/authorize/{account_type}', 'OneDriveController@authorizeLogin');
                $api->post('onedrive/callback', 'OneDriveController@callback');
                $api->post('onedrive/unbind', 'OneDriveController@unbind');
            }
        );
    }
);
