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
                $api->get('drive/authorize/{account_type}', 'OneDriveController@authorizeLogin');
                $api->post('drive/callback', 'OneDriveController@callback');
                $api->post('drive/unbind', 'OneDriveController@unbind');
            }
        );
        // 后台
        $api->group(
            [
                'namespace' => 'App\Http\Controllers\v1\backend',
//            'middleware' => 'api.throttle',
//            'limit' => 200, 'expires' => 1
            ],
            function ($api) {
                /* @var $api Dingo\Api\Routing\Router */
                // 资源
                $resource = ['user'];
                foreach ($resource as $value) {
                    $content = Str::plural($value);
                    $controller = Str::studly($value) . 'Controller';
                    $api->get($content, "{$controller}@index");
                    $api->post($content, "{$controller}@create");
                    $api->get("{$content}/{id}", "{$controller}@view");
                    $api->put("{$content}/{id}", "{$controller}@update");
                    $api->delete("{$content}/{id}", "{$controller}@delete");
                }
                $api->get('settings', 'SettingController@index');
                $api->put('settings', 'SettingController@update');
            }
        );
    }
);
